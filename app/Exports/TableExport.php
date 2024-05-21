<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class TableExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $html;

    public function __construct($html)
    {
        $this->html = $html;
    }

    public function view(): View
    {
        return view('exports.table', ['html' => $this->html]);
    }

    public function collection()
    {

    }
}
