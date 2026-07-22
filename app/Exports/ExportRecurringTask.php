<?php

namespace App\Exports;

use App\Models\Task;
use App\Models\TaskDetails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ExportRecurringTask implements FromView
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
        $this->task_id = $data['task_id'];
    }

    public function view(): View
    {
        // dd($data);
        return view('recurring_task', [
            'invoices' => TaskDetails::where('task_id', $this->task_id)->get()
        ]);
    }

}
