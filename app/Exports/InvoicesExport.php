<?php


namespace App\Exports;

use App\BusinessConcatPerson;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;



class InvoicesExport implements FromArray
{

    protected $concat_persons;

    public function __construct(Collection $concat_persons)
    {
        $concat_persons->transform(function($item) {
            return $item->only(['name','customer_name','email','city','area']);
        });
        $col_names = ([
            "name" => "福委姓名",
            "customer_name" => "客戶姓名",
            "email" => "信箱",
            "city" => "縣市",
            "area" => "地區",
        ]);
        $concat_persons = $concat_persons->toArray();
        array_unshift($concat_persons,$col_names);

        $this->concat_persons = $concat_persons;

    }

    public function array():array
    {
        return $this->concat_persons;
    }

}
