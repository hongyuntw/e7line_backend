<?php

namespace App\Imports;

use App\Customer;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

//class CustomersImport implements ToModel ,WithValidation, WithHeadingRow
class SenaoOrdersImport implements  ToCollection
{

    private $order_rows = null;
    public function collection(Collection $rows)
    {
        try{
            if (!($rows[0][0] == '宅急便(黑貓)' or $rows[0][0] =='完成出貨')){
                $this->order_rows = $rows;
            }
        }
        catch (\Exception $exception) {
            $this->order_rows = $rows;
        }

    }

    public function getRows()
    {
        return  $this->order_rows;
    }

}

