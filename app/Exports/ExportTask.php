<?php

namespace App\Exports;

use App\Models\Task;
use App\Models\TaskStaff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ExportTask implements FromView
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
        $this->txt_search       = $data['txt_search'];
        $this->sort_task_type   = $data['sort_task_type'];
        $this->sort_task_status = $data['sort_task_status'];
        $this->user_id          = $data['user_id'];
        $this->user_type        = $data['user_type'];
        $this->project_id       = $data['project_id'];
    }

    public function view(): View
    {   

        $sort_task_type     = $this->sort_task_type;
        $sort_task_status   = $this->sort_task_status;
        $user_name          = $this->txt_search;
        $user_id            = $this->user_id;
        $user_type          = $this->user_type;
        $project_id         = $this->project_id;
        
        $book_details = TaskStaff::with(['task_details']);

        if ($sort_task_type) {
            $book_details = $book_details->where('staff_id', $sort_task_type);
        }
        if ($sort_task_status) {
            $book_details = $book_details->where('status', $sort_task_status);
        }

        if ($project_id) {
            $book_details = $book_details->whereIn('project_id', $project_id);
        }        
        // $book_details = $book_details->get();

        // dd($data);
        return view('custom_task', [
            'invoices' => $book_details->get()
        ]);
    }
}
