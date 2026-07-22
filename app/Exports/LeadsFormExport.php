<?php

namespace App\Exports;

use App\Models\Task;
use App\Models\IncomeAmount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class LeadsFormExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Task::all();
    // }

    private $data;

    public function __construct($leads_from)
    {
        // dd($data);
        $this->leads_from       = $leads_from;
    }

    public function view(): View
    {   

        $leads_from    = $this->leads_from;

        // dd($IncomeAmount->get());
        return view('leads_from', [
            'leads_from' => $leads_from
        ]);
    }
}
