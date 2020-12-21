<?php

namespace App\Admin\Actions\Post;

use App\Admin\Controllers\BatchController;
use App\Exports\BatchesExport;
use App\Project;
use Carbon\Carbon;
use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BatchReplicate extends BatchAction
{

    public $name = 'Export';

    protected $selector = '.report-posts';

    public function handle(Collection $collection, Request $request)
    {
        $columns = $request->input('column');

        $ids = $collection->pluck('id')->toArray();

        $projects = Project::with('client', 'poClients.poFactories.batches.containers')->find($ids);
        $data = [];
        foreach ($projects as $project) {
            foreach ($project->poClients as $poClient) {
                foreach ($poClient->poFactories as $poFactory) {
                    foreach ($poFactory->batches as $batch) {
                        $eta_job_site = "";
                        $ata_job_site = "";
                        $type = "";
                        $container_no = "";
                        $remarks = "";
                        foreach ($batch->containers as $key => $container) {
                            $container_no .= $container->no ? ($key + 1) . '、' . $container->no.' / '.$container->remarks . "\r\n" : ($key + 1) . '、' . "/ \r\n";
                            $eta_job_site .= $container->eta_job_site ? ($key + 1) . '、' . $container->eta_job_site . "\r\n" : ($key + 1) . '、' . "/ \r\n";
                            $ata_job_site .= $container->ata_job_site ? ($key + 1) . '、' . $container->ata_job_site . "\r\n" : ($key + 1) . '、' . "/ \r\n";
                            $type .= $container->type ? ($key + 1) . '、' . $container->type . "\r\n" : ($key + 1) . '、' . "/ \r\n";
                            $remarks .= $container->remarks ? ($key + 1) . '、' . $container->remarks . "\r\n" : ($key + 1) . '、' . "/ \r\n";
                        }


                        $res['voltage_no'] = $poClient->voltage_no;
                        $res['customer'] = $project->client->name;
                        $res['project_name'] = $project->name;
                        $res['customer_po'] = $poClient->no;
                        $res['address'] = $project->address;
                        $res['shipment_no'] = $batch->name ? getSequence($batch->sequence) . ' - ' . $batch->name : getSequence($batch->sequence);
                        $res['b_l'] = $batch->b_l;
                        $res['estimated_production_completion'] = optional($batch->estimated_production_completion)->toDateString();

                        if (in_array('APC', $columns)) {
                            $res['actual_production_completion'] = optional($batch->actual_production_completion)->toDateString();
                        }

                        $res['etd_port'] = optional($batch->etd_port)->toDateString();

                        if (in_array('ATD', $columns)) {
                            $res['atd_port'] = optional($batch->atd_port)->toDateString();
                        }

                        $res['eta_port'] = optional($batch->eta_port)->toDateString();
                        if (in_array('ATA port', $columns)) {
                            $res['ata_port'] = optional($batch->ata_port)->toDateString();
                        }
                        $res['eta_job_site'] = $eta_job_site;
                        if (in_array('ATA site', $columns)) {
                            $res['ata_job_site'] = $ata_job_site;
                        }
                        if (in_array('Shipping method', $columns)) {
                            $res['shipping_method'] = $batch->shipping_method;
                        }
                        if (in_array('Carrier', $columns)) {
                            $res['carrier'] = $batch->carrier;
                        }
                        $res['container_no'] = $container_no;
                        $res['type'] = $type;

                        if (in_array('Remarks', $columns)) {
                            $res['remarks'] = $remarks;
                        }


                        $data[] = $res;
                    }
                }
            }
        }

        $filename = "projects_" . Carbon::now()->toDateString() . "_" . time() . ".xlsx"; //导出的文件名
        $filepath = "export/" . $filename; //导出的文件名
        Excel::store(
            new BatchesExport($data, $columns),
            $filepath
        );

        return $this->response()->success('Exported！')->download(url('/storage/' . $filename));
    }

    public function form()
    {
        $this->checkbox('column', 'column')
            ->options([
                'APC'             => 'APC',
                'ATD'             => 'ATD',
                'ATA port'        => 'ATA port',
                'ATA site'        => 'ATA site',
                'Shipping method' => 'Shipping method',
                'Carrier'         => 'Carrier',
                'Remarks'         => 'Remarks',
            ])
            ->checked(['APC', 'ATD', 'ATA port', 'ATA site', 'Shipping method', 'Carrier']);
    }

    public function html()
    {
        return "<a class='report-posts btn btn-sm btn-success'><i class='fa fa-download'></i> Export</a>";
    }

}
