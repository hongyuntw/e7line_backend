<?php


namespace App\Exports;

use App\BusinessConcatPerson;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;



class InvoicesExport implements FromCollection
{

    protected $concat_persons;

    public function __construct(Collection $concat_persons)
    {
        $this->concat_persons = $concat_persons;
    }

    public function collection()
    {
        return $this->concat_persons;
    }

}
