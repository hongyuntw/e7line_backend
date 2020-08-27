<?php

namespace App\Exports;

use App\BusinessConcatPerson;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class SenaoItemsExport implements FromArray, WithEvents,ShouldAutoSize , WithHeadings
{

    public $order_items;
    public $order_array;
    public $isbn_qty;
    public $isbn_name;


    public function __construct(Collection $order_items)
    {
        $order_array = [];
        $isbn_qty = [];
        $isbn_name = [];

        foreach($order_items as $order_item){
            $order = $order_item->order;
            $senao_order = $order->senao_order;
            $order_array[$order->no] = $senao_order->seq_id;


            $isbn = $order_item->product_relation->ISBN;
            if(array_key_exists($isbn , $isbn_qty)){
                $isbn_qty[$isbn] += $order_item->quantity;
            }
            else{
                $isbn_qty[$isbn] = $order_item->quantity;
            }

            if(! array_key_exists($isbn , $isbn_name)){
                $isbn_name[$isbn]  = $order_item->product_relation->product->name . ' ' . $order_item->product_relation->product_detail->name;
            }
        }


        $this->order_items = $order_items;
        $this->order_array = $order_array;
        $this->isbn_qty = $isbn_qty;
        $this->isbn_name = $isbn_name;

//        dump($order_items);
//        dump($order_array);
//        dump($isbn_qty);
//        dd($isbn_name);

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:Z99')->getFont()->setSize(14);

                $cols = ['A', 'B', 'C', 'D', 'E', 'F'];
                foreach ($cols as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(false);
                    $event->sheet->getColumnDimension($col)->setWidth(20);
                }
                $row = 2;
                foreach($this->isbn_qty as $isbn => $qty){
                    $event->sheet->getDelegate()->setCellValue('A'.$row, $isbn);
                    $event->sheet->getDelegate()->setCellValue('B'.$row, $this->isbn_name[$isbn]);
                    $event->sheet->getDelegate()->setCellValue('C'.$row, $qty);

                    $event->sheet->getDelegate()->setCellValue('D'.$row, '=C'.$row.'-E'.$row);
                    $event->sheet->getDelegate()->setCellValue('E'.$row, '=C'.$row.'-D'.$row);

                    $row += 1;
                }
                $row+=1;
                $event->sheet->getDelegate()->setCellValue('A'.$row, '訂單編號');
                $event->sheet->getDelegate()->setCellValue('B'.$row, '神腦訂單序號(seq_id)');
                $row+=1;
                foreach($this->order_array as $order_no => $seq_id){
                    $event->sheet->getDelegate()->setCellValue('A'.$row, $order_no);
                    $event->sheet->getDelegate()->setCellValue('B'.$row, $seq_id);
                    $row+=1;
                }

            },
        ];
    }

//    public function map($order_item): array
//    {
//        return [
//            $order_item->product_relation->ISBN,
//            $order_item->product_relation->product->name . ' ' . $order_item->product_relation->product_detail->name,
//            $order_item->quantity,
//        ];
//    }
//
//
//    public function collection()
//    {
//        return $this->order_items;
//    }

    public function array() :array
    {
        return array();
    }

    public function headings(): array
    {
        return [
            'ISBN',
            '品名',
            '數量',
            '庫存',
            '叫貨數量',
        ];
    }

}
