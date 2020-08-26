<?php

namespace App\Exports;

use App\BusinessConcatPerson;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class SenaoOrdersExport implements FromArray, WithEvents,WithColumnFormatting
{

    public $senao_orders;

    public function __construct(Collection $senao_orders)
    {
        $senao_orders->transform(function ($item) {
            return $item->only([
                'seq_id', 'no', 'create_date', 'pay_date', 'senao_isbn', 'supplier_isbn', 'product_name', 'color',
                'attribute_name', 'attribute_vlaue', 'quantity', 'price', 'receiver', 'phone1',
                'phone2', 'cellphone', 'address', 'status', 'shipment_code', 'shipment_company',
                'reason'
            ]);
        });
        $col_names = ([
            'seq_id' => '訂單序號(請勿修改)',
            'no' => '訂單編號(請勿修改)',
            'create_date' => '訂單日期',
            'pay_date' => '付款日期',
            'senao_isbn' => '神腦料號(請勿修改)',
            'supplier_isbn' => '供應商料號',
            'product_name' => '商品名稱',
            'color' => '顏色',
            'attribute_name' => '自訂屬性名稱',
            'attribute_value' => '自訂屬性值',
            'quantity' => '數量',
            'price' => '商品成本(未稅)',
            'receiver' => '收件者',
            'phone1' => '收件者電話1',
            'phone2' => '收件者電話2',
            'cellphone' => '收件者手機',
            'address' => '收件者地址',
            'status' => '*狀態',
            'shipment_code' => '*出貨單號(狀態為「完成出貨」時必填)',
            'shipment_company' => '*物流公司(狀態為「完成出貨」時必填)',
            'reason' => '*原因(狀態為「延遲出貨」或「無法出貨」時必填)'
        ]);
        $senao_orders = $senao_orders->toArray();
        array_unshift($senao_orders, $col_names);


        for ($i = 1; $i < count($senao_orders); $i++) {
            if ($senao_orders[$i]['create_date'] != '' and (!is_null($senao_orders[$i]['create_date']))) {
                $t = strtotime($senao_orders[$i]['create_date']);
                $format_date = date('Y-m-d', $t);
                $senao_orders[$i]['create_date'] = $format_date;
            }
            if ($senao_orders[$i]['pay_date'] != '' and (!is_null($senao_orders[$i]['pay_date']))) {
                $t = strtotime($senao_orders[$i]['pay_date']);
                $format_date = date('Y-m-d', $t);
                $senao_orders[$i]['pay_date'] = $format_date;
            }

        }


        $this->senao_orders = $senao_orders;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cols = ['A', 'B', 'C', 'D', 'E', 'G','P','Q','R','S','T','U','V'];
                foreach ($cols as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(false);
                    $event->sheet->getColumnDimension($col)->setWidth(20);
                    $event->sheet->formatColumn($col,'@');

                }


            },
        ];
    }
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function array(): array
    {
        return $this->senao_orders;
//        return array();
    }

}
