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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Auth;
use PDF;
use Dompdf\Dompdf;

class ClientProjectController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $total_amount = 0;
        $total_pening = 0;
        $user_title_name = "Webbitech";

        $project_get = Project::where('customer_id', Auth::user()->cus_id)->orderBy('date_created', 'desc')->get();
        foreach ($project_get as $key => $value) {
            $total_amount = 0;
            $bit_amounts = $value->bit_amounts;
            if (count($bit_amounts) > 0) {
                foreach ($bit_amounts as $key1 => $value1) {
                    $total_amount += $value1->fld_project_amount;
                }
            }

            if ($value->bid_amount == 0 && $total_amount == 0 || $value->bid_amount == $total_amount) {
                $project_update = Project::find($value->id);
                $project_update->payment_status = "completed";
                $project_update->save();
            }
            
        }

        $users = Project::where('customer_id', Auth::user()->cus_id)->orderBy('date_created', 'desc');
        

        $user_list = \App\Models\Project::groupBy('added_by')
              ->get(['added_by']);

        $salesperson = $user_list;

        $users = $users->paginate(50);
        // dd($users);

        $service_get = Service::get();
        $recordCount = 0;
        $page_title = "Total";
        return view('client.projects.view', compact('users', 'start_date', 'end_date', 'status','page_title', 'recordCount', 'salesperson','salesperson1','service_get1', 'service_get', 'user_title_name'));
    }

    public function status($id)
    {
        $sub_admin = Project::where('id', $id)->first();
        return view('client.projects.status', compact('sub_admin'));
    }

    
    public function ViewProject($id)
    {
        $project = Project::where('id', $id)->first();
        
        $user_list = \App\Models\TaskStaff::where('project_id', $id)
              ->groupBy('staff_id')
              ->get(['staff_id']);
              
        return view('client.projects.project_view', compact('project', 'user_list'));
    }

    public function SearchView(Request $request)
    {
        // dd($request->all());
        $total_amount = 0;
        $total_pening = 0;


        $start_date         = $request->start_date ?? '';
        $end_date           = $request->end_date ?? '';
        $status             = $request->status ?? '';
        $salesperson1       = $request->salesperson ?? '';
        $service_get1       = $request->service ?? '';
        $text_value_search  = $request->text_value_search ?? '';

        $users = Project::where('is_renewal', 0)->where('payment_status', null)->whereNot('status', 6)->orderBy('date_created', 'desc');

        if ($start_date) {
            $users = $users->whereDate('sales_user_date', '>=', $start_date)
                ->whereDate('sales_user_date', '<=', $end_date);
        }
        else
        {
            $users = $users;
        }

        if ($status == "all") {
            $users = $users;
        }
        elseif($status)
        {
            $users = $users->where('status', $status);
        }
        else
        {
            $users = $users;
        }
        if($salesperson1)
        {
            $users = $users->where('sales_user_id', $salesperson1);
        }
        else
        {
            $users = $users;
        }
        if($service_get1)
        {
            $users = $users->where('services_id', $service_get1);
        }
        else
        {
            $users = $users;
        }

        $users = $users->where(function($q) use ($text_value_search) { 
                        $q->where('name', 'LIKE', "%".$text_value_search."%")
                        ->orwhere('description', 'LIKE', "%".$text_value_search."%")
                        ->orWhereHas('sales_user_details', function($q) use ($text_value_search){
                            $q->where('firstname', 'LIKE', "%".$text_value_search."%");
                        });
                    });

        $users = $users->get();

        $view =  view('client.projects.search', compact('users', 'start_date', 'end_date', 'status','salesperson1','service_get1'))->render();
        
        if ($view) {
            $data['project'] = $view;
            $data['status'] = "success";
        }
        else
        {
            $data['status'] = "error";
        }

        return $data;

    }

    public function TaskStatusUpdate($id)
    {
        $task = Task::where('id', $id)->first();
        // dd($task);
        return view('client.projects.task_status', compact('task'));
    }

    public function TaskDetailsTimeUpdate(Request $request)
    {
        $task_comments_details = TaskComment::where('id', $request->task_comment_id)->first();
        // dd($task_comments_details);

        $task_comments                      = TaskComment::find($request->task_comment_id);
        $task_comments->client_comment       = $request->comment_model;
        if ($task_comments->save()) {

            $message_cmd                 = Auth::user()->name. ' Client Update their Comments in task no - '.$task_comments_details->task_details->task_no ;
            
            $notification                = new Notification();
            $notification->task_id       = $task_comments_details->task_details->id;
            $notification->receiver_id   = $task_comments_details->staff_id;
            $notification->url           = route('staff.view.task', $task_comments_details->task_details->id);
            $notification->receiver_type = "staff";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();

            $notification                = new Notification();
            $notification->task_id       = $task_comments_details->task_details->id;
            $notification->receiver_id   = 1;
            $notification->url           = "";
            $notification->receiver_type = "admin";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();

            return redirect()->back()->with('success', 'Task Comments Updated Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function create()
    {
        return view('client.projects.add');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $create_task                = new ClientTask();
        $create_task->user_id       = Auth::user()->id;
        $create_task->name          = $request->name;
        $create_task->date          = $request->confirm_date;
        $create_task->description   = $request->editor1;
        $create_task->status        = "In Progress";
        
        if ($create_task->save()) {
            
            $key_number              = 'WT'.str_pad($create_task->id, 6, "0", STR_PAD_LEFT);
            $update_task             = ClientTask::find($create_task->id);
            $update_task->ticket_id  = $key_number;
            $update_task->save();

            if ($request->addmore) {
                
                foreach ($request->addmore as $key => $value) {

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

            return redirect()->route('client.projects.task.view')->with('success', 'New Task Estimate Create SuccessFully');

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

        return view('client.projects.taskview', compact('client_task'));
    }

    public function ViewTask($id)
    {
        $client_task = ClientTask::where('id', $id)->first();
        
        // dd($client_task->client_task_details);
        return view('client.projects.task_details', compact('client_task'));
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
            
        $view =  view('client.projects.modelrender1', compact('amount_details'))->render();
        return response()->json(['html'=>$view, 'background' => $background, 'request_view' => $request_view]);
    }

    public function TaskRequestCommentUpdate(Request $request)
    {
        // dd($request->all());

        if($request->status == "Approved")
        {
            $client_task_details                = ClientTaskDetails::find($request->client_id);
            $client_task_details->alert_user    = 1;
            $client_task_details->alert_Status  = 1;
            $client_task_details->status        = $request->status;
            $client_task_details->appr_date     = date('Y-m-d H:i:s');
            $client_task_details->save();

            $message_cmd                 = Auth::user()->name. ' Update by Status in Task Estimate - '. $client_task_details->name;
            $notification                = new Notification();
            $notification->task_id       = "";
            $notification->receiver_id   = 1;
            $notification->url           = route('task.request.details', $client_task_details->client_task_id);
            $notification->receiver_type = "admin";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();

            return redirect()->back()->with('success', 'Estimate Time Details Updated Successfully');

        }
        else
        {
            $client_task_details                = ClientTaskDetails::find($request->client_id);
            $client_task_details->alert_user    = 1;
            $client_task_details->alert_Status  = 1;
            $client_task_details->status        = $request->status;
            $client_task_details->save();

            $message_cmd                 = Auth::user()->name. ' Update by Status in Task Estimate - '. $client_task_details->name;
            $notification                = new Notification();
            $notification->task_id       = "";
            $notification->receiver_id   = 1;
            $notification->url           = route('task.request.details', $client_task_details->client_task_id);
            $notification->receiver_type = "admin";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();

            $client_comment                         = new ClientTaskComment();
            $client_comment->client_task_id         = $request->client_task_id;
            $client_comment->client_task_details_id = $request->client_id;
            $client_comment->user_id                = Auth::user()->id;
            $client_comment->comments               = $request->editor1;
            if($client_comment->save())
            {
                return redirect()->back()->with('success', 'Estimate Time Details Updated Successfully');
            }
            
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
