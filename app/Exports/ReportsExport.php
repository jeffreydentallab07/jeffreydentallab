<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection
{
    protected $reports;

    public function __construct($reports)
    {
        $this->reports = $reports;
    }

    public function collection()
    {
        return new Collection($this->reports);
    }
     public function headings(): array
    {
        return ["ID", "Details", "Date"];
    }

}
