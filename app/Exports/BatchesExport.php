<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BatchesExport implements WithColumnFormatting, FromCollection, WithEvents, WithStrictNullComparison, ShouldAutoSize
{

    protected $data; //数据体
    protected $columns;


    public function __construct(array $data, array $columns)
    {
        $this->data = $data;
        $this->columns = $columns;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_TEXT,
            'L' => NumberFormat::FORMAT_TEXT,
            'M' => NumberFormat::FORMAT_TEXT,
            'N' => NumberFormat::FORMAT_TEXT,
            'O' => NumberFormat::FORMAT_TEXT,
            'P' => NumberFormat::FORMAT_TEXT,
            'Q' => NumberFormat::FORMAT_TEXT,
            'R' => NumberFormat::FORMAT_TEXT,
            'S' => NumberFormat::FORMAT_TEXT,
            'T' => NumberFormat::FORMAT_TEXT,
            'U' => NumberFormat::FORMAT_TEXT,
            'V' => NumberFormat::FORMAT_TEXT,
            'W' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function registerEvents(): array
    {
        $count = count($this->data) > 3 ? count($this->data) + 2 : 4;

        return [
            AfterSheet::class => function (AfterSheet $event) use ($count) {
//                $event->sheet->getDelegate()->setMergeCells(['A1:S1']); //合并单元格
//                $event->sheet->getDelegate()->getStyle('A1:S1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);   //设置水平居中
                $event->sheet->getDelegate()->getStyle('A1:W1')->getFont()->setBold(true)->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1:W' . $count)
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);
            },
        ];
    }

//    public function headings(): array
//    {
//        return ["ASD发货清单"];
//    }

    public function collection()
    {
        $res = [
            'Voltage PO',
            'PO# Client',
            'Customer',
            'Project name',
            'Customer PO',
            'Delivery address',
            'Shipment No',
            'Vessel',
            'Destination Port',
            'H/BL',
            'EPC',
        ];
        if (in_array('APC', $this->columns)) {
            $res[] = 'APC';
        }
        $res[] = 'ETD';
        if (in_array('ATD', $this->columns)) {
            $res[] = 'ATD';
        }
        $res[] = 'ETA port';

        if (in_array('ATA port', $this->columns)) {
            $res[] = 'ATA port';
        }
        $res[] = 'ETA site';
        if (in_array('ATA site', $this->columns)) {
            $res[] = 'ATA site';
        }
        if (in_array('Shipping method', $this->columns)) {
            $res[] = 'Shipping method';
        }
        if (in_array('Carrier', $this->columns)) {
            $res[] = 'Carrier';
        }

        $res[] = 'Container No.';
        $res[] = 'Type';
        if (in_array('Remarks', $this->columns)) {
            $res[] = 'Remarks';
        }

        $title = $res;
        array_unshift($this->data, $title);

        return collect($this->data);
    }
}
