<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CaseOrdersExport implements FromView
{
    protected $caseOrders;

    public function __construct($caseOrders)
    {
        $this->caseOrders = $caseOrders;
    }

    public function view(): View
    {
        return view('reports.caseorders_export', [
            'caseOrders' => $this->caseOrders
        ]);
    }
}
