<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;     // 自動註冊事件監聽器
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
//use PHPExcel_Cell_DataType;




class CustomerTemplateExport implements FromArray, WithEvents
{

    public function __construct()
    {

    }

    public function array(): array
    {
        return array();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:Z99')->getFont()->setSize(14)->setName("微軟正黑體");
                $event->sheet->getDelegate()->setCellValue('A1', '客戶名稱');
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                $event->sheet->getDelegate()->setCellValue('B1', '負責業務');
                $event->sheet->getDelegate()->getStyle('B1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                $event->sheet->getDelegate()->setCellValue('C1', '統一編號');
                $event->sheet->getDelegate()->setCellValue('D1', '資本額');
                $event->sheet->getDelegate()->setCellValue('E1', '規模');
                $event->sheet->getDelegate()->setCellValue('F1', '縣市');
                $event->sheet->getDelegate()->getStyle('F1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                $event->sheet->getDelegate()->getStyle('G1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                $event->sheet->getDelegate()->setCellValue('G1', '地區');
                $event->sheet->getDelegate()->setCellValue('H1', '地址');
                $event->sheet->getDelegate()->setCellValue('I1', '電話');
                $event->sheet->getDelegate()->setCellValue('J1', '傳真');
                $event->sheet->getDelegate()->setCellValue('K1', '註記');

                $event->sheet->getDelegate()->setMergeCells([
                    'L1:N1',
                ]);

                $event->sheet->getDelegate()->getStyle('L1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                $event->sheet->getDelegate()->getStyle('L1')->getFont()->setBold(true);

                $event->sheet->getDelegate()->setCellValue('L1', '紅色欄位為必填值（此欄為說明欄位，請勿填）');
                $event->sheet->getDelegate()->getStyle('L1')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(60);







            },
        ];


    }



}
