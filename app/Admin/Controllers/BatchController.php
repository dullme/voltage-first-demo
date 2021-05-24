<?php

namespace App\Admin\Controllers;

use App\Exports\BatchesExport;
use App\Forwarder;
use App\ForwarderContact;
use App\PoFactory;
use App\Project;
use Carbon\Carbon;
use Excel;
use App\Batch;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class BatchController extends AdminController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Batch';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Batch());

        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableActions();
        $grid->disableCreateButton();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();


            $batches = Batch::select('carrier', 'port_of_departure', 'destination_port')->get();

            $port_of_departure = $batches->where('port_of_departure', '!=', null)->pluck('port_of_departure', 'port_of_departure')->unique();
            $destination_port = $batches->where('destination_port', '!=', null)->pluck('destination_port', 'destination_port')->unique();
            $carrier = $batches->where('carrier', '!=', null)->pluck('carrier', 'carrier')->unique();

            $filter->column(1/2, function ($filter){
                $filter->like('containers.no', 'Container No');
                $filter->like('b_l', 'B/L');
                $filter->between('atd_port' ,'ATD Port')->date();
                $filter->between('etd_port' ,'ETD Port')->date();

                $filter->where(function ($query) {
                    $forwarder_contact = ForwarderContact::where('forwarder_id', $this->input)->pluck('id');
                    $query->whereIn('china_inland_forwarder', $forwarder_contact);
                }, 'CIF')->select(Forwarder::pluck('name', 'id'));


//                $filter->equal('china_inland_forwarder.id', __('CIF'))->select(Forwarder::pluck('name', 'id'));
            });

            $filter->column(1/2, function ($filter) use ($port_of_departure, $destination_port, $carrier) {
                $filter->equal('port_of_departure', 'Port Of Departure')->select($port_of_departure);
                $filter->equal('destination_port', 'Destination Port')->select($destination_port);
                $filter->equal('carrier', 'Carrier')->select($carrier);
                $filter->equal('project_id', 'Project')->select(Project::pluck('name', 'id'));
            });

        });

        $grid->project('Project Name')->display(function ($project){
            $url = '/admin/projects/'.$project['id'];
            return "<a style='display: block' href='{$url}'>{$project['name']}</a>";
        });

//        $grid->column('name', __('Name'))->display(function ($name) {
//            return $name ? getSequence($this->sequence) . ' - ' . $name : getSequence($this->sequence);
//        });

        $grid->containers(__('Container No'))->display(function ($containers) {
            $res = '';
            foreach ($containers as $container) {
                $url = url('admin/batch/show/' . $container['batch_id'] . '?container=' . $container['id']);
                $res .= "<a style='display: block' href='{$url}'>{$container['no']}</a>";
            }

            return $res;
        });

        $grid->column(__('TUE'))->display(function () {
            $tue = translateBoxToTue(collect($this->containers)->pluck('type')->toArray());
            return $tue == 0 ? '' : $tue.' TUE';
        })->label();

        $grid->column('port_of_departure', __('Port Of Departure'));
        $grid->column('destination_port', __('Destination Port'));
        $grid->column('carrier', __('Carrier'));
//        $grid->column('sequence', __('Sequence'));
//        $grid->column('ocean_forwarder', __('Ocean forwarder'));
//        $grid->column('inland_forwarder', __('Inland forwarder'));
//        $grid->column('china_inland_forwarder', __('China inland forwarder'));
        $grid->column('b_l', __('B/L'));
        $grid->column('vessel', __('Vessel'));
        $grid->column('etd_port', __('ETD Port'));
        $grid->column('atd_port', __('ATD Port'));
        $grid->column('CIF')->display(function (){
            return optional(optional($this->chinaInlandForwarder)->forwarder)->name;
        });
        $grid->column('foreign_currency', __('Foreign Currency'))->display(function ($curr){
            if($this->foreign_currency_type == 1){
                $type = '$';
            }elseif ($this->foreign_currency_type == 2){
                $type = 'A$';
            }else{
                $type = 'Unknown';
            }

            return $curr ? $type . ' ' . $curr : '-';
        });
//        $grid->column('remarks', __('Remarks'));

        $grid->header(function ($query) {
            $tue = translateBoxToTue($query->get()->pluck('containers')->flatten()->pluck('type')->toArray());
            return "<label class='label label-success'>当前共有 {$tue} TUE</label>";
        });

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

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Batch());
        $form->tools(function (Form\Tools $tools) {

        // 去掉`列表`按钮
        $tools->disableList();

        // 去掉`删除`按钮
        $tools->disableDelete();

        // 去掉`查看`按钮
        $tools->disableView();
        // 添加一个按钮, 参数可以是字符串, 或者实现了Renderable或Htmlable接口的对象实例
        $tools->add('<a class="btn btn-sm btn-default" href="'.url('/admin/batch/show/'.request()->route()->parameters()['batch']).'"><i class="fa fa-mail-reply"></i>&nbsp;&nbsp;Back</a>');
    });

        $form->display('po_factory_id', __('Po client no'))->with(function ($value) {
            return PoFactory::with('poClient')->findOrFail($value)->poClient->no;
        });
        $form->display('name', __('Name'));
//        $form->number('sequence', __('Sequence'));
//        $form->number('status', __('Status'));
//        $form->datetime('estimated_production_completion', __('Estimated production completion'))->default(date('Y-m-d H:i:s'));
//        $form->datetime('etd_port', __('Etd port'))->default(date('Y-m-d H:i:s'));
//        $form->datetime('eta_port', __('Eta port'))->default(date('Y-m-d H:i:s'));
//        $form->datetime('eta_job_site', __('Eta job site'))->default(date('Y-m-d H:i:s'));
//        $form->datetime('actual_production_completion', __('Actual production completion'))->default(date('Y-m-d H:i:s'));
//        $form->datetime('atd_port', __('Atd port'))->default(date('Y-m-d H:i:s'));
//        $form->datetime('ata_port', __('Ata port'))->default(date('Y-m-d H:i:s'));
//        $form->datetime('ata_job_site', __('Ata job site'))->default(date('Y-m-d H:i:s'));
//        $form->text('carrier', __('Carrier'));
//        $form->number('ocean_forwarder', __('Ocean forwarder'));
//        $form->number('inland_forwarder', __('Inland forwarder'));
//        $form->number('china_inland_forwarder', __('China inland forwarder'));
//        $form->text('b_l', __('B l'));
//        $form->text('vessel', __('Vessel'));
//        $form->text('remarks', __('Remarks'));
//        $form->text('shipping_method', __('Shipping method'));
//        $form->decimal('rmb', __('Rmb'));
//        $form->decimal('foreign_currency', __('Foreign currency'));
//        $form->number('foreign_currency_type', __('Foreign currency type'));
//        $form->text('port_of_departure', __('Port of departure'));
//        $form->text('destination_port', __('Destination port'));
//        $form->textarea('epc_history', __('Epc history'));
//        $form->textarea('etd_port_history', __('Etd port history'));
//        $form->textarea('eta_port_history', __('Eta port history'));
        $form->file('file')->move('/shipment');

        $form->saved(function (Form $form) {
            admin_toastr('SUCCESS', 'success');
            return redirect(url('/admin/batch/show/'.$form->model()->id));
        });

        return $form;
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
