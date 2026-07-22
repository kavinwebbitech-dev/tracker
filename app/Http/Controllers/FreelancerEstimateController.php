<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\SalesPerson;
use App\Models\RenewProject;
use App\Models\ProjectBitAmount;
use App\Models\Customers;
use App\Models\Service;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\IncomeAmount;
use App\Models\Notification;
use App\Models\ClientTask;
use App\Models\ClientTaskDetails;
use App\Models\ClientTaskComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use PDF;
use Dompdf\Dompdf;

class FreelancerEstimateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function create()
    {
        return view('freelancer.projects.add');
    }

    public function store(Request $request)
    {

        $create_task                = new ClientTask();
        $create_task->user_id       = Auth::user()->id;
        $create_task->user_type     = "freelancer";
        $create_task->name          = $request->name;
        // $create_task->amount        = $request->amount;
        $create_task->total_hours   = $request->hour;
        $create_task->date          = $request->confirm_date;
        $create_task->description   = $request->editor1;
        $create_task->status        = "In Progress";
        
        if ($create_task->save()) {
            
            $key_number              = 'WT'.str_pad($create_task->id, 6, "0", STR_PAD_LEFT);
            $update_task             = ClientTask::find($create_task->id);
            $update_task->ticket_id  = $key_number;
            $update_task->save();
            $total_hours = null;
            if ($request->addmore) {
                
                foreach ($request->addmore as $key => $value) {
                    $total_hours += $value['hour']; 
                    $create_task_details                 = new ClientTaskDetails();
                    $create_task_details->client_task_id = $create_task->id;
                    $create_task_details->user_id        = Auth::user()->id;
                    $create_task_details->alert_user     = 1;
                    $create_task_details->alert_Status   = 1;
                    $create_task_details->name           = $value['points'];
                    $create_task_details->status         = "In Progress";
                    $create_task_details->save();

                }

            }
            $update_task->total_hours  = $total_hours;
            $update_task->save();

            return redirect()->route('freelancer.projects.task.view')->with('success', 'New Task Estimate Create SuccessFully');

        }

        return redirect()->back()->with('error', 'Something Wrong');

    }

    public function taskview()
    {
        $client_task = ClientTask::where('user_id', Auth::user()->id)->get();

        if ($client_task) {
            foreach ($client_task as $key => $value) {
                $find_value = 0;
                $details = $value->client_task_details;

                foreach ($details as $key1 => $value1) {
                    
                    if ($value1->status != "Approved") {
                        $find_value = 1;
                    }

                }

                if ($find_value == 0) {
                    $client_update = ClientTask::find($value->id);
                    $client_update->status = "Approved";
                    $client_update->save();
                }
                else
                {
                    $client_update = ClientTask::find($value->id);
                    $client_update->status = "In Progress";
                    $client_update->save();
                }

            }
        }

        return view('freelancer.projects.taskview', compact('client_task'));
    }

    public function ViewTask($id)
    {
        $client_task = ClientTask::where('id', $id)->first();
        
        // dd($client_task->client_task_details);
        return view('freelancer.projects.task_details', compact('client_task'));
    }

    public function TaskRequestUpdate(Request $request)
    {


        $amount_details     = ClientTaskDetails::where('id', $request->id)->first();
        $background = false;
        // dd($amount_details);
        if ($amount_details->alert_user != 1 && $amount_details->alert_Status == 1) {
            $client_task_details                = ClientTaskDetails::find($amount_details->id);
            $client_task_details->alert_user    = Auth::user()->id;
            $client_task_details->alert_Status  = 0;
            $client_task_details->save();

            $background = true;
        }
        $request_view = "request_view_".''.$request->id;

        // dd();
            
        $view =  view('freelancer.projects.modelrender1', compact('amount_details'))->render();
        return response()->json(['html'=>$view, 'background' => $background, 'request_view' => $request_view]);
    }

    public function TaskRequestCommentUpdate(Request $request)
    {
        $client = ClientTask::find($request->client_task_id);
        
        $client_comment                         = new ClientTaskComment();
        $client_comment->client_task_id         = $request->client_task_id;
        $client_comment->client_task_details_id = $request->client_id;
        $client_comment->hours                  = $client->total_hours + $request->hour;
        $client_comment->user_id                = Auth::user()->id;
        $client_comment->comments               = $request->editor1;
        if($client_comment->save())
        {
            $client->total_hours  = $client_comment->hours;
            if($client->cost_type == "hourly"){
                $client->total_amount = $client->amount * $client_comment->hours;
                $client->save();
                // $client->total_hours  = $client_comment->hours;
            }
            if($client->cost_type == "fixed"){
                $client->total_amount = $client->amount;
                $client->save();
            }
            $client_task_details                = ClientTaskDetails::find($request->client_id);
            $client_task_details->category      = $request->category;
            $client_task_details->amount        = $request->amount;
            $client_task_details->status        = $request->status;
            $client_task_details->estimate_time = number_format($request->estimate_time, 2);
            $client_task_details->estimate_type = $request->estimate_type;
            $client_task_details->alert_user    = 1;
            $client_task_details->alert_Status  = 1;
            $client_task_details->save();

            $message_cmd                 = Auth::user()->name. ' Update by Status in Task Estimate - '. $client_task_details->name;
            $notification                = new Notification();
            $notification->task_id       = "";
            $notification->receiver_id   = 1;
            $notification->url           = route('admin.freelancer.task.details', $client_task_details->client_task_id).'/#key-'.$client_task_details->id;
            $notification->receiver_type = "admin";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();

            return redirect()->back()->with('success', 'Estimate Time Details Updated Successfully');

        }

    }

    public function DeleteDetailsTask($id)
    {

        $delete_details = ClientTaskDetails::find($id);
        if ($delete_details->delete()) {
            return redirect()->back()->with('error', 'Deleted Successfully');
        }

    }

    public function PointStore(Request $request)
    {
        // dd($request->all());

        $create_task = $request->client_task_id;

        if ($request->addmore) {
                
            foreach ($request->addmore as $key => $value) {

                $create_task_details                 = new ClientTaskDetails();
                $create_task_details->client_task_id = $create_task;
                $create_task_details->user_id        = Auth::user()->id;
                $create_task_details->alert_user     = 1;
                $create_task_details->alert_Status   = 1;
                $create_task_details->name           = $value['points'];
                $create_task_details->status         = "In Progress";
                $create_task_details->save();

            }

            return redirect()->back()->with('success', 'New Task Estimate Create SuccessFully');

        }

    }
}
