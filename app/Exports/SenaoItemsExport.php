<?php

namespace App\Exports;

use App\BusinessConcatPerson;
use App\Order;
use App\SenaoOrder;
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
            $order = Order::where('id','=',$order_item->order_id)->first();
            try{
                $senao_order = SenaoOrder::where('order_id','=',$order->id)->first();
                $order_array[$order->no] = $senao_order->seq_id;
            }
            catch (\Exception $exception){
//                dump($exception);
//                dump($order_item);
//                dd($order);
                continue;
            }



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

                $merge_cell_info = [
                    'A1:E2'
                ];
                $styleArray = array(
                    'borders' => array(
                        'outline' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        ),
                    ),
                );
                $thinBorderStyleArray = array(
                    'borders' => array(
                        'allBorders' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ),
                    ),
                );

                $cols = ['A', 'B', 'C', 'D', 'E', 'F'];
                foreach ($cols as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(false);
                    $event->sheet->getColumnDimension($col)->setWidth(20);
                }




                $event->sheet->getDelegate()->setCellValue('A1','e7line叫貨表');
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);

                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $cols = ['A', 'B', 'C', 'D', 'E', 'F'];
                foreach ($cols as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(false);
                    if ($col = 'A' or $col = 'B'){
                        $event->sheet->getColumnDimension($col)->setWidth(20);
                    }
                }
                $event->sheet->getDelegate()->getStyle('A3:E3')->getFont()->setSize(16);

                $event->sheet->getDelegate()->setCellValue('A3', 'ISBN');
                $event->sheet->getDelegate()->setCellValue('B3', '品名');
                $event->sheet->getDelegate()->setCellValue('C3', '數量');
                $event->sheet->getDelegate()->setCellValue('D3', '庫存');
                $event->sheet->getDelegate()->setCellValue('E3', '叫貨數量');

                $event->sheet->getDelegate()->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->getStyle('B3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->getStyle('C3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->getStyle('D3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->getStyle('E3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);



                $row = 4;
                foreach($this->isbn_qty as $isbn => $qty){
                    $event->sheet->getDelegate()->setCellValue('A'.$row, $isbn);
                    $event->sheet->getDelegate()->setCellValue('B'.$row, $this->isbn_name[$isbn]);
                    $event->sheet->getDelegate()->setCellValue('C'.$row, $qty);
                    $event->sheet->getDelegate()->setCellValue('D'.$row, '=C'.$row.'-E'.$row);
                    $event->sheet->getDelegate()->setCellValue('E'.$row, '=C'.$row.'-D'.$row);

                    $event->sheet->getDelegate()->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $event->sheet->getDelegate()->getStyle('B'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $event->sheet->getDelegate()->getStyle('C'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $event->sheet->getDelegate()->getStyle('D'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                    $event->sheet->getDelegate()->getStyle('E'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);


                    $row += 1;
                }
                $row+=1;
                $event->sheet->getDelegate()->setCellValue('A'.$row, '訂單編號');
                $event->sheet->getDelegate()->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->getStyle('B'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getDelegate()->setCellValue('B'.$row, '神腦訂單序號');
                $event->sheet->getDelegate()->getStyle('A'.$row)->getFont()->setSize(16);
                $event->sheet->getDelegate()->getStyle('B'.$row)->getFont()->setSize(16);


                $row+=1;
                foreach($this->order_array as $order_no => $seq_id){
                    $event->sheet->getDelegate()->setCellValue('A'.$row, $order_no);
                    $event->sheet->getDelegate()->setCellValue('B'.$row, $seq_id);
                    $row+=1;
                }

                $row+=1;
                array_push($merge_cell_info, 'A' . ($row) . ':A' . ($row+1));
                array_push($merge_cell_info, 'B' . ($row) . ':B' . ($row+1));
                array_push($merge_cell_info, 'C' . ($row) . ':C' . ($row+1));

                array_push($merge_cell_info, 'D' . ($row) . ':E' . ($row+1));

                $event->sheet->getDelegate()->setCellValue('A'.$row, '經辦');
                $event->sheet->getDelegate()->getStyle('A'.$row)->getFont()->setSize(16);

                $event->sheet->getDelegate()->setCellValue('C'.$row, '採購主管');
                $event->sheet->getDelegate()->getStyle('C'.$row)->getFont()->setSize(16);

                $event->sheet->getDelegate()->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A'.$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('C'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('C'.$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


                $event->sheet->getDelegate()->getStyle('A1:E' . ($row+1))->applyFromArray($thinBorderStyleArray);



                $event->sheet->getDelegate()->setMergeCells($merge_cell_info);

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
