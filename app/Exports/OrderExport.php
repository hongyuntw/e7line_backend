<?php

namespace App\Exports;

use App\Order;
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


class OrderExport implements FromArray,WithEvents,WithDrawings
{

    protected  $order;
    public static $payment_method_names = ['匯款', '貨到付款', '薪資帳戶扣款', '信用卡刷卡機制', 'LINEPay'];
    public  $row_index;


    public function __construct(Order $order)
    {
//        dd($order);
        $this->order  = $order;
//        $this->order = [];
        $this->row_index = count($order->order_items) + 29;


    }
    public function array():array
    {
//        dd($this->order);
        return array();
//        return $this->order->toArray();
    }

    public function registerEvents():array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // 合併單元格
                $merge_cell_info = [
                    'A1:K2',
                    'A3:K3',
                    'A4:B4',
                    'C5:D6',
                    'E5:F6',
                    'A7:B7',
                    'C7:D7',
                    'E7:F7',
                    'A5:B6',
                    'H5:K5',
                    'H6:K6',
                    'A8:B9',
                    'C8:K9',
                    'A10:K11',
                    'A12:H13',
//                    金額等
                    'I12:I13',
                    'J12:J13',
                    'K12:K13',
                ];

//                logo and qrcode
                array_push($merge_cell_info, 'C'.($this->row_index) .':E'.($this->row_index+2));

                $event->sheet->getDelegate()->getStyle('C'.($this->row_index))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('C'.($this->row_index))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                array_push($merge_cell_info, 'A'.($this->row_index) .':B'.($this->row_index+2));

                $event->sheet->getDelegate()->getStyle('A'.($this->row_index))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A'.($this->row_index))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


                $styleArray = array(
                    'borders' => array(
                        'outline' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        ),
                    ),
                );
                $event->sheet->getDelegate()->getStyle('A1:Z99')->getFont()->setSize(14)->setName("微軟正黑體");
                $widths = range(1,100);
                foreach ($widths as $k => $v) {
                    // 設定列寬度
                    $event->sheet->getDelegate()->getRowDimension($k)->setRowHeight(20);
                }

//
                $event->sheet->getDelegate()->getStyle('A1:K1')->getFont()->setSize(20);
                $event->sheet->getDelegate()->setCellValue('A1','大宗禮券/禮品訂購單');
                $event->sheet->getDelegate()->setCellValue('A3','★為了保障客戶權益，姓名、電話、地址、收貨時間及付款時間請務必填寫完整★');
                $event->sheet->getDelegate()->setCellValue('A4','訂購日期');
                $event->sheet->getDelegate()->setCellValue('C4',(new Carbon($this->order->create_date))->year);

                $event->sheet->getDelegate()->setCellValue('D4','年');
                $event->sheet->getDelegate()->setCellValue('E4',(new Carbon($this->order->create_date))->month);

                $event->sheet->getDelegate()->setCellValue('F4','月');
                $event->sheet->getDelegate()->setCellValue('J4',(new Carbon($this->order->create_date))->day);

                $event->sheet->getDelegate()->setCellValue('K4','日');
                $event->sheet->getDelegate()->setCellValue('C5',$this->order->customer?$this->order->customer->name:$this->order->other_customer_name);
                $event->sheet->getDelegate()->setCellValue('A5','訂購單位');
                $event->sheet->getDelegate()->setCellValue('E5','聯絡方式');
                $event->sheet->getDelegate()->setCellValue('H5',$this->order->phone_number);

                $event->sheet->getDelegate()->setCellValue('G5','電話');
                $event->sheet->getDelegate()->setCellValue('H6',$this->order->email);
                $event->sheet->getDelegate()->setCellValue('G6','Mail');
                $event->sheet->getDelegate()->setCellValue('C7',$this->order->business_concat_person?$this->order->business_concat_person->name:$this->order->other_concat_person_name);
                $event->sheet->getDelegate()->setCellValue('A7','訂購窗口');
                $event->sheet->getDelegate()->setCellValue('E7','收貨時間');
                $event->sheet->getDelegate()->setCellValue('G7',(new Carbon($this->order->receive_date))->year.'年');
                $event->sheet->getDelegate()->setCellValue('H7',(new Carbon($this->order->receive_date))->month);
                $event->sheet->getDelegate()->setCellValue('J7',(new Carbon($this->order->receive_date))->day);
                $event->sheet->getDelegate()->setCellValue('I7','月');
                $event->sheet->getDelegate()->setCellValue('K7','日');
                $event->sheet->getDelegate()->setCellValue('A8','收貨地址');
                $event->sheet->getDelegate()->setCellValue('C8',$this->order->ship_to);
//                商品
                $event->sheet->getDelegate()->setCellValue('A10','商品訂購明細');
                $event->sheet->getDelegate()->setCellValue('A12','商品名稱');
                $event->sheet->getDelegate()->setCellValue('I12','金額');
                $event->sheet->getDelegate()->setCellValue('J12','數量');
                $event->sheet->getDelegate()->setCellValue('K12','小計');

                $row_index = 14;
                foreach ($this->order->order_items()->orderBy('product_relation_id')->get() as $order_item){
                    array_push($merge_cell_info, 'A'.$row_index.':H'.$row_index);
                    $event->sheet->getDelegate()->setCellValue('A'.$row_index,$order_item->product_relation->product->name.' '.$order_item->product_relation->product_detail->name);
                    $event->sheet->getDelegate()->setCellValue('I'.$row_index,$order_item->price);
                    $event->sheet->getDelegate()->setCellValue('J'.$row_index,$order_item->quantity);
                    $event->sheet->getDelegate()->setCellValue('K'.$row_index,$order_item->quantity*$order_item->price);
                    $row_index+=1;
                }
                $event->sheet->getDelegate()->getStyle('A3:K'.$row_index)->getFont()->setSize(12);
                $row_index++;
                array_push($merge_cell_info, 'A'.$row_index.':H'.$row_index);
                if($this->order->tax_id){
                    $event->sheet->getDelegate()->setCellValue('A'.$row_index,'  ▇ 報帳發票：   統編：'.$this->order->tax_id);

                }
                else{
                    $event->sheet->getDelegate()->setCellValue('A'.$row_index,'  ▇ 報帳發票：   統編： ');

                }
                $row_index ++;
                $row_index++;

                array_push($merge_cell_info, 'A'.$row_index.':I'.$row_index);
                $event->sheet->getDelegate()->setCellValue('A'.($row_index),'金額總計');
                $event->sheet->getDelegate()->getStyle('A'.($row_index))->getFont()->setBold(true);

                $event->sheet->getDelegate()->getStyle('A'.($row_index))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

//                total
                array_push($merge_cell_info, 'J'.($row_index) .':K'.$row_index);
                $event->sheet->getDelegate()->setCellValue('J'.($row_index),$this->order->amount);
                $event->sheet->getDelegate()->getStyle('J'.($row_index))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('J'.($row_index))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


                $row_index++;
                array_push($merge_cell_info, 'A'.($row_index) .':K'.$row_index);
                $event->sheet->getDelegate()->getStyle('A'.($row_index))->getFont()->setBold(true);

                $event->sheet->getDelegate()->setCellValue('A'.($row_index),'★作業流程');

                $row_index++;
                array_push($merge_cell_info, 'A'.($row_index) .':K'.$row_index);
                $event->sheet->getDelegate()->getRowDimension($row_index)->setRowHeight(40);
                $event->sheet->getDelegate()->getStyle('A'.($row_index))->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A'.($row_index))->getFont()->setSize(12);
                $event->sheet->getDelegate()->setCellValue('A'.($row_index),'1.訂單須雙方同意商品、價格、數量及交期。雙方窗口簽名後訂單始成立，訂單成立後依序進行商品叫貨及財務流程動作，訂單成立後視同已同意採購不得隨意取消及更改！');

                $row_index++;
                array_push($merge_cell_info, 'A'.($row_index) .':K'.$row_index);
                $event->sheet->getDelegate()->getRowDimension($row_index)->setRowHeight(40);
                $event->sheet->getDelegate()->getStyle('A'.($row_index))->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A'.($row_index))->getFont()->setSize(12);

                $event->sheet->getDelegate()->setCellValue('A'.($row_index),'2.商品訂購享有七天內商品瑕疵之退換貨服務，故不接受無故退貨，雲城股份有限公司保有最終退換貨與否之決定權及規則更改權利。若買受人為公司請開立退貨折讓單，並需加蓋公司代表章。');


                $row_index++;
                array_push($merge_cell_info, 'A'.($row_index) .':K'.$row_index);
                $event->sheet->getDelegate()->setCellValue('A'.($row_index),'3.雙方購買得另立買賣合約，保障雙方之權利。');
                $event->sheet->getDelegate()->getStyle('A'.($row_index-2).':K'.$row_index)->getFont()->setSize(10);



                $row_index++;
                array_push($merge_cell_info, 'A'.($row_index) .':K'.$row_index);
                $event->sheet->getDelegate()->getStyle('A'.($row_index))->getFont()->setBold(true);
                $event->sheet->getDelegate()->setCellValue('A'.($row_index),'★轉帳資訊 (匯款後請主動告知以利查帳!)');

                $row_index++;
                array_push($merge_cell_info, 'A'.($row_index) .':G'.$row_index);
                $event->sheet->getDelegate()->setCellValue('A'.($row_index),'付款方式:'.self::$payment_method_names[$this->order->payment_method]);

                array_push($merge_cell_info, 'H'.($row_index) .':I'.$row_index);

                $event->sheet->getDelegate()->getStyle('H'.($row_index))->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

                $event->sheet->getDelegate()->setCellValue('H'.($row_index),'(必填欄位匯款日期)');
                $event->sheet->getDelegate()->getStyle('H'.($row_index))->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle('H'.($row_index) .':I'.$row_index)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('J'.($row_index) .':K'.$row_index)->applyFromArray($styleArray);




                array_push($merge_cell_info, 'J'.($row_index) .':K'.$row_index);

                $event->sheet->getDelegate()->setCellValue('J'.($row_index),$newDate = date("Y-m-d", strtotime($this->order->payment_date)));

                $row_index++;
                if($this->order->payment_account=="公司帳戶"){
                    $event->sheet->getDelegate()->setCellValue('A'.($row_index),'代碼: 812(台新國際商業銀行)北新店分行');
                    $event->sheet->getDelegate()->setCellValue('F'.($row_index),'戶名: 陳建興');
                    $event->sheet->getDelegate()->setCellValue('I'.($row_index),'帳號: 2070-10-0007591-6');
                }
                else{
                    $event->sheet->getDelegate()->setCellValue('A'.($row_index),'代碼: 017(兆豐國際商業銀行)新店分行');
                    $event->sheet->getDelegate()->setCellValue('F'.($row_index),'戶名: 雲城股份有限公司');
                    $event->sheet->getDelegate()->setCellValue('I'.($row_index),'帳號: 046-09-02490-8');

                }
                array_push($merge_cell_info, 'I'.($row_index) .':K'.$row_index);


                $event->sheet->getDelegate()->getStyle('A'.($row_index-1).':K'.$row_index)->getFont()->setSize(10);



                $row_index++;
                array_push($merge_cell_info, 'A'.($row_index) .':K'.$row_index);
                $event->sheet->getDelegate()->getStyle('A'.($row_index))->getFont()->setBold(true);
                $event->sheet->getDelegate()->setCellValue('A'.($row_index),'★訂購專線');



                $row_index++;
                array_push($merge_cell_info, 'A'.($row_index) .':E'.$row_index);
                $event->sheet->getDelegate()->setCellValue('A'.($row_index),'聯絡人: '.$this->order->user->name);

                array_push($merge_cell_info, 'F'.($row_index) .':K'.$row_index);
                $event->sheet->getDelegate()->setCellValue('F'.($row_index),'電話: (02)89124000#'.$this->order->user->extension_number);


                $row_index++;
                array_push($merge_cell_info, 'A'.($row_index) .':E'.$row_index);
                $event->sheet->getDelegate()->setCellValue('A'.($row_index),'傳真號碼: 02-89124250 ');

                array_push($merge_cell_info, 'F'.($row_index) .':K'.$row_index);
                $event->sheet->getDelegate()->setCellValue('F'.($row_index),'Mail: '.$this->order->user->email);




                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


                $event->sheet->getDelegate()->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('C5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('C5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


                $event->sheet->getDelegate()->getStyle('E5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('E5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('C7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('E7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('C8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A10')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A10')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('A12')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('I12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('I12')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('J12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('J12')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getStyle('K12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getStyle('K12')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $row_index = $this->row_index + 3;
                array_push($merge_cell_info, 'C'.($row_index) .':E'.$row_index);
                $event->sheet->getDelegate()->setCellValue('C'.($row_index),'企業員工福利平台');
                $event->sheet->getDelegate()->getStyle('C'.$row_index)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


                array_push($merge_cell_info, 'F'.($row_index-1) .':H'.($row_index-1));
                $event->sheet->getDelegate()->setCellValue('F'.($row_index-1),'______________________');
                $event->sheet->getDelegate()->getStyle('F'.($row_index-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


                array_push($merge_cell_info, 'F'.($row_index) .':H'.$row_index);
                $event->sheet->getDelegate()->setCellValue('F'.($row_index),'業務窗口');
                $event->sheet->getDelegate()->getStyle('F'.$row_index)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                array_push($merge_cell_info, 'I'.($row_index) .':K'.$row_index);
                $event->sheet->getDelegate()->setCellValue('I'.($row_index),'客戶簽收');
                $event->sheet->getDelegate()->getStyle('I'.$row_index)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                array_push($merge_cell_info, 'I'.($row_index-1) .':K'.($row_index-1));
                $event->sheet->getDelegate()->setCellValue('I'.($row_index-1),'______________________');
                $event->sheet->getDelegate()->getStyle('I'.($row_index-1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);




                $event->sheet->getDelegate()->getStyle('I'.($row_index-3).':K'.($row_index-1))->applyFromArray($styleArray);

                $event->sheet->getDelegate()->getStyle('I'.($row_index-3).':K'.($row_index))->applyFromArray($styleArray);


                $row_index += 3;
                array_push($merge_cell_info, 'A'.($row_index) .':K'.($row_index));
                $event->sheet->getDelegate()->setCellValue('A'.($row_index),'---------------------------------------------------------內部簽核流程----------------------------------------------------------');
                $event->sheet->getDelegate()->getStyle('A'.($row_index))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $row_index++;

                array_push($merge_cell_info, 'A'.($row_index) .':B'.($row_index));
                $event->sheet->getDelegate()->setCellValue('A'.($row_index),'1.訂單審核');
                $event->sheet->getDelegate()->getStyle('A'.($row_index))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                array_push($merge_cell_info, 'C'.($row_index) .':D'.($row_index));
                $event->sheet->getDelegate()->setCellValue('C'.($row_index),'2.採購審核');
                $event->sheet->getDelegate()->getStyle('C'.($row_index))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


                array_push($merge_cell_info, 'E'.($row_index) .':F'.($row_index));
                $event->sheet->getDelegate()->setCellValue('E'.($row_index),'3.出貨核準');
                $event->sheet->getDelegate()->getStyle('E'.($row_index))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                array_push($merge_cell_info, 'G'.($row_index) .':H'.($row_index));
                $event->sheet->getDelegate()->setCellValue('G'.($row_index),'4. 領貨人員');
                $event->sheet->getDelegate()->getStyle('G'.($row_index))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                array_push($merge_cell_info, 'I'.($row_index) .':J'.($row_index));
                $event->sheet->getDelegate()->setCellValue('I'.($row_index),'5.收款確認');
                $event->sheet->getDelegate()->getStyle('I'.($row_index))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->setCellValue('K'.($row_index),'6.印製發票');
                $event->sheet->getDelegate()->getStyle('K'.($row_index))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


                $event->sheet->getDelegate()->getStyle('A'.($row_index).':K'.$row_index)->getFont()->setSize(10);







                //                合併儲存格
                $event->sheet->getDelegate()->setMergeCells($merge_cell_info);



            },
        ];


    }

    public function drawings()
    {
        // TODO: Implement drawings() method.
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('/img/e7line/e7lineLogo.png'));
        $drawing->setHeight(50);
//        $drawing->setWidth(60);
//        $drawing->
        $drawing->setCoordinates('C1');

        $drawing2 = new Drawing();
        $drawing2->setName('Logo');
        $drawing2->setDescription('Logo');
        $drawing2->setPath(public_path('/img/e7line/e7lineLogo.png'));
        $drawing2->setHeight(80);
        $drawing2->setWidth(150);
        $drawing2->setOffsetX(-40);

//        $drawing->
        $drawing2->setCoordinates('D'.$this->row_index);

        $drawing3 = new Drawing();
        $drawing3->setName('QRCODE');
        $drawing3->setDescription('QRCODE');
        $drawing3->setPath(public_path('/img/e7line/e7lineQRcode.png'));
        $drawing3->setHeight(100);
        $drawing3->setWidth(100);
        $drawing3->setOffsetX(20);
        $drawing3->setCoordinates('A'.$this->row_index);


        return [$drawing,$drawing2,$drawing3];

    }


}
