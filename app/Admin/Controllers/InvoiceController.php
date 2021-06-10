<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\InvoicesExporter;
use App\Exports\BatchesExport;
use App\Forwarder;
use App\ForwarderContact;
use App\PoFactory;
use App\Project;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Excel;
use App\Batch;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class InvoiceController extends AdminController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Invoice';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Batch());
        $grid->exporter(new InvoicesExporter());
        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableCreateButton();
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->where(function ($query) {
                $date = Carbon::parse($this->input)->subMonth(3);
                $query->whereBetween('atd_port', [$date->startOfMonth()->toDateString(), $date->endOfMonth()->toDateString()]);
            }, 'Payment Date')->date();
            $filter->where(function ($query) {
                $forwarder_contact = ForwarderContact::where('forwarder_id', $this->input)->pluck('id');
                $query->whereIn('ocean_forwarder', $forwarder_contact);
            }, 'O/AF')->select(Admin::user()->language == 'cn'? Forwarder::pluck('cn_name', 'id') : Forwarder::pluck('name', 'id'));

        });

        $grid->column('Operator')->display(function (){
            return $this->project->author->username;
        });

        $grid->column('project_name', 'Project Name')->display(function (){
            $url = '/admin/projects/'.$this->project->id;
            return "<a style='display: block' href='{$url}'>{$this->project->name}</a>";
        })->width(150);


        $grid->column('voltage_no', 'PO NO.')->display(function () {
            return $this->poFactory->poClient->voltage_no;
        })->width(100);

        $grid->column('b_l', __('B/L'))->display(function ($b_l){
            $url = '/admin/batch/show/'.$this->id;
            return "<a style='display: block' href='{$url}'>{$b_l}</a>";
        });

        $grid->column('atd_port', __('Ship Date'))->width(85);
        $grid->column('O/AF', __('Shipping Firm'))->display(function (){

            return Admin::user()->language == 'cn'? optional(optional($this->oceanForwarder)->forwarder)->cn_name : optional(optional($this->oceanForwarder)->forwarder)->name;
        })->width(200);

        $grid->column('invoice_no', 'NO1');
        $grid->column('invoice_no2', 'NO2');
        $grid->column('freight_amount', __('Freight Amount(USD)'))->width(110);
        $grid->column('tariff_amount', __('Tariff Amount(USD)'))->width(110);
        $grid->column('payment_date', __('Payment Date'))->display(function (){
//            return substr($this->atd_port->startOfMonth()->addMonth(3)->toDateString(), 0, 8) . '15';
        });

        $grid->containers( __('Containers'))->display(function ($containers) {
            $containers = collect($containers)->where('type', '!=', '')->groupBy('type');
            $html = '';
            foreach ($containers as $key=>$container){

                $html .= "<p style='margin-bottom: 0px'>{$container->count()} * {$key}</p>";
            }

            return $html;
        });

        $grid->column('freight_file', __('Freight'))->display(function ($freight_file){
            return $freight_file ? "<a href='/admin/download?file={$freight_file}' target='_blank'><i class='fa fa-download'></i></a>" : '';
        });

        $grid->column('tariff_file', __('Tariff'))->display(function ($tariff_file){
            return $tariff_file ? "<a href='/admin/download?file={$tariff_file}' target='_blank'><i class='fa fa-download'></i></a>" : '';
        });

        $grid->column('invoice_remark', __('Remarks'));


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Batch::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('po_factory_id', __('Po factory id'));
        $show->field('name', __('Name'));
        $show->field('sequence', __('Sequence'));
        $show->field('status', __('Status'));
        $show->field('estimated_production_completion', __('Estimated production completion'));
        $show->field('etd_port', __('Etd port'));
        $show->field('eta_port', __('Eta port'));
        $show->field('eta_job_site', __('Eta job site'));
        $show->field('actual_production_completion', __('Actual production completion'));
        $show->field('atd_port', __('Atd port'));
        $show->field('ata_port', __('Ata port'));
        $show->field('ata_job_site', __('Ata job site'));
        $show->field('carrier', __('Carrier'));
        $show->field('ocean_forwarder', __('Ocean forwarder'));
        $show->field('inland_forwarder', __('Inland forwarder'));
        $show->field('china_inland_forwarder', __('China inland forwarder'));
        $show->field('b_l', __('B l'));
        $show->field('vessel', __('Vessel'));
        $show->field('remarks', __('Remarks'));
        $show->field('shipping_method', __('Shipping method'));
        $show->field('rmb', __('Rmb'));
        $show->field('foreign_currency', __('Foreign currency'));
        $show->field('foreign_currency_type', __('Foreign currency type'));
        $show->field('port_of_departure', __('Port of departure'));
        $show->field('destination_port', __('Destination port'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('epc_history', __('Epc history'));
        $show->field('etd_port_history', __('Etd port history'));
        $show->field('eta_port_history', __('Eta port history'));

        return $show;
    }


    public function export(Request $request)
    {

        dd($request->all());

        $projects = Project::with('client', 'poClients.poFactories.batches.containers')->find($ids);
        $data = [];
        foreach ($projects as $project) {
            foreach ($project->poClients as $poClient) {
                foreach ($poClient->poFactories as $poFactory) {
                    foreach ($poFactory->batches as $batch) {
                        $ata_job_site = "";
                        $type = "";
                        $container_no = "";
                        $remarks = "";
                        foreach ($batch->containers as $key => $container) {
                            $container_no .= $container->no ? ($key+1).'、'.$container->no . "\r\n" : ($key+1).'、'."/ \r\n";
                            $ata_job_site .= $container->ata_job_site ? ($key+1).'、'.$container->ata_job_site . "\r\n" : ($key+1).'、'."/ \r\n";
                            $type .= $container->type ? ($key+1).'、'.$container->type . "\r\n" : ($key+1).'、'."/ \r\n";
                            $remarks .= $container->remarks ? ($key+1).'、'.$container->remarks . "\r\n" : ($key+1).'、'."/ \r\n";
                        }


                        $data[] = [
                            'voltage_no'                      => $poClient->voltage_no,
                            'customer'                        => $project->client->name,
                            'customer_po'                     => $poClient->no,
                            'address'                         => $project->address,
                            'shipment_no'                     => $batch->name ? getSequence($batch->sequence) . ' - ' . $batch->name : getSequence($batch->sequence),
                            'estimated_production_completion' => optional($batch->estimated_production_completion)->toDateString(),
                            'etd_port'                        => optional($batch->etd_port)->toDateString(),
                            'eta_port'                        => optional($batch->eta_port)->toDateString(),
                            'eta_job_site'                    => optional($batch->eta_job_site)->toDateString(),

                            'actual_production_completion' => optional($batch->actual_production_completion)->toDateString(),
                            'atd_port'                     => optional($batch->atd_port)->toDateString(),
                            'ata_port'                     => optional($batch->ata_port)->toDateString(),
                            'ata_job_site'                 => $ata_job_site,

                            'shipping_method' => $batch->shipping_method,
                            'carrier'         => $batch->carrier,
                            'b_l'             => $batch->b_l,
                            'container_no'    => $container_no,
                            'type'            => $type,
                            'remarks'         => $remarks,
                        ];
                    }
                }
            }
        }

        return Excel::download(
            new BatchesExport($data),
            "projects_".time().".xlsx"   //导出的文件名
        );
    }
}
