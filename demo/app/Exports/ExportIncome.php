<?php

namespace App\Exports;

use App\Models\Task;
use App\Models\IncomeAmount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ExportIncome implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Task::all();
    // }

    private $data;

    public function __construct($data)
    {
        // dd($data);
        $this->start_date       = $data['start_date'];
        $this->end_date         = $data['end_date'];
    }

    public function view(): View
    {   

        $start_date    = $this->start_date;
        $end_date      = $this->end_date;
        
        if($start_date)
        {
            $IncomeAmount = IncomeAmount::whereDate('income_date', '>=', $start_date)
                ->whereDate('income_date', '<=', $end_date)->latest();
                
        }
        else
        {
            $IncomeAmount = IncomeAmount::latest();
        }
        

        // dd($IncomeAmount->get());
        return view('IncomeAmount', [
            'invoices' => $IncomeAmount->get()
        ]);
    }
}
