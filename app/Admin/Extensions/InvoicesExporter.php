<?php


namespace App\Admin\Extensions;


use App\Container;
use Carbon\Carbon;
use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesExporter extends ExcelExporter implements WithMapping
{

    protected $fileName = 'invoices.xlsx';

    protected $columns = [
        'project_id'    => 'Operator',
        'name'    => 'Project Name',
        'po_factory_id' => 'PO NO.',
        'b_l' => 'B/L',
        'atd_port' => 'Ship Date',
        'ocean_forwarder' => 'Shipping Firm',
        'invoice_no' => 'NO1',
        'invoice_no2' => 'NO2',
        'freight_amount' => 'Freight Amount(USD)',
        'tariff_amount' => 'Tariff Amount(USD)',
        'payment_date' => 'Payment Date',
        'id' => 'Containers',
        'invoice_remark' => 'Remarks',

    ];

    public function map($batches): array
    {
        return [
            data_get($batches, 'project.author.username'),
            data_get($batches, 'project.name'),
            data_get($batches, 'poFactory.poClient.voltage_no'),
            ' '.$batches->b_l,
            Carbon::parse($batches->atd_port)->toDateString(),
            data_get($batches, 'oceanForwarder.forwarder.name'),
            $batches->invoice_no,
            $batches->invoice_no2,
            $batches->freight_amount,
            $batches->tariff_amount,
            substr(Carbon::parse($batches->atd_port)->startOfMonth()->addMonth(3)->toDateString(), 0, 8) . '15',
            $this->getContainers($batches->id),
            $batches->invoice_remark,
        ];
    }

    public function getContainers($id)
    {
        $containers = Container::where('batch_id', $id)->where('type', '!=', '')->get();
        $containers = collect($containers)->groupBy('type');
        $text = '';
        $count = $containers->count();
        $i = 0;
        foreach ($containers as $key=>$container){
            $text .= $count > ++$i ? "{$container->count()} * {$key} \n" : "{$container->count()} * {$key}";
        }

        return $text;
    }
}
