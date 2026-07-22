<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskDetails;
use App\Exports\ExportTask;
use App\Models\ClientTask;
use App\Models\ClientTaskDetails;
use App\Models\ClientTaskComment;
use App\Models\Notification;
use App\Exports\ExportRecurringTask;
use Auth;
use Mail;
use PDF;
use File;
use Excel;
use DB;
use Illuminate\Http\Request;

class FreeLancerController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $sub_admin = User::where('user_type', 'freelancer')->get();
        return view('admin.freelancer.add', compact('sub_admin'));
    }

    public function view()
    {
        $sub_admin = User::where('user_type', 'freelancer')->get();
        return view('admin.freelancer.view', compact('sub_admin'));
    }

    public function edit($id)
    {
        $staff = User::where('id', $id)->first();
        $sub_admin = User::where('user_type', 'freelancer')->get();
        return view('admin.freelancer.edit', compact('sub_admin', 'staff'));
    }

    public function delete($id)
    {
        if ($id) {
            $user  = User::find($id);
            if ($user->delete()) {
                return redirect()->route('admin.freelancer.view')->with('error', 'Staff Deleted Successfully');
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name'          => 'required|max:255',
            'email'         => 'required|max:255|unique:users',
            'password'      => 'required|confirmed'
        ]);
        
        $password            = Hash::make($request->password);
        $user                = new User();
        $user->name          = $request->name;
        $user->email         = $request->email;
        $user->phone         = $request->phone;
        $user->salary        = $request->salary;
        $user->user_type     = "freelancer";
        $user->status        = "active";
        $user->password      = $password;
        if ($user->save()) {

            $data['name']          = $request->name;
            $data['email']         = $request->email;
            $data['password']      = $request->password;
            $data['logo']          = url('public/admin_assets/images/logo.png');
            $data['link_url']      = url('/login');

            try{
                Mail::send('mail.user_details', $data, function($message)use($data) {
                    $message->to($data['email'], $data['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                    ->subject('Welcome to Webbitech Family');
                });
            }
            catch(\Exception $e){
                // dd($e);
            }

            return redirect()->route('admin.freelancer.view')->with('success', 'Freelancer Created Successfully');
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $user               = User::find($id);
        if ($request->password) {
            $request->validate([
                'name'          => 'required|max:255',
                'password'      => 'required|confirmed'
            ]);

            $password           = Hash::make($request->password);
            $user->password     = $password;
        }
        
        $user->name          = $request->name;
        $user->email         = $request->email;
        $user->phone         = $request->phone;
        $user->sub_admin_id  = $request->sub_admin;
        $user->user_type     = "freelancer";
        $user->status        = $request->status;
        
        if ($user->save()) {
            return redirect()->route('admin.freelancer.view')->with('warning', 'Freelancer Edited Successfully');
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function TaskRequest()
    {
        $client_task  = ClientTask::where('user_type', 'freelancer')->get();
        $client_task1 = ClientTask::where('user_type', 'freelancer')->whereNot('status', 'Closed')->get();

        if ($client_task1) {
            foreach ($client_task1 as $key => $value) {
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

        return view('admin.freelancer.task-request', compact('client_task'));
    }

    public function TaskRequestDetails($id)
    {


        $client_task = ClientTask::where('id', $id)->first();

        $amount_details     = ClientTaskDetails::where('client_task_id', $client_task->id)->get();

        
        if ($amount_details) {
            foreach ($amount_details as $key => $value) {
                
                $sum = strtotime('00:00:00');
                $total_time = 0;
                $total_amount = 0;
                $project_amount = 0;
                $toal_time = 0;
                $totaltime = 0;
                if ($value->estimate_time && $value->amount) {
                    $working_time_h = number_format($value->estimate_time, 2);
                    $timeinsec = strtotime($working_time_h) - $sum;
                    $totaltime += $timeinsec;

                    $toal_time += $totaltime;

                    $h = intval($totaltime / 3600);
                    $totaltime = $totaltime - ($h * 3600);
                    $m = intval($totaltime / 60);
                    $s = $totaltime - ($m * 60);

                    $total_time = $h.':'.$m;
                    $hour_rate = $value->amount;
                                                    
                    // dd($hour_convert[1]);
                    if (isset($m) && $m) {
                        $calculate = $h + ($m / 60);
                    }
                    else
                    {
                        $calculate = $h;
                    }
                    
                    $added_rate = round($calculate, 2);
                    $total_amount = $added_rate * $hour_rate;
                    $project_amount += $total_amount;

                    $update_details = ClientTaskDetails::find($value->id);
                    $update_details->total_amount = $project_amount;
                    $update_details->save();

                }
                
            }
        }

        return view('admin.freelancer.requestview', compact('client_task'));
    }

    public function TaskRequestUpdate(Request $request)
    {

        $amount_details     = ClientTaskDetails::where('id', $request->id)->first();
        $background = false;
        // dd($amount_details);
        if ($amount_details->alert_user == 1 && $amount_details->alert_Status == 1) {
            $client_task_details                = ClientTaskDetails::find($amount_details->id);
            $client_task_details->alert_user    = 1;
            $client_task_details->alert_Status  = 0;
            $client_task_details->save();

            $background = true;
        }
        $request_view = "request_view_".''.$request->id;
        
        $view =  view('admin.freelancer.modelrender1', compact('amount_details'))->render();
        return response()->json(['html'=>$view, 'background' => $background, 'request_view' => $request_view]);
    }

    public function DeleteDetailsTask($id)
    {

        ClientTaskDetails::findOrFail($id)->delete();

        return redirect()->back()->with('error', 'Deleted Successfully');

    }

    public function TaskRequestCommentUpdate(Request $request)
    {
        // dd($request->all());

        if($request->status == "Approved")
        {
            $client_task_details                = ClientTaskDetails::find($request->client_id);
            $client_task_details->alert_user    = $client_task_details->client_task_details->id;
            $client_task_details->alert_Status  = 1;
            $client_task_details->status        = $request->status;
            $client_task_details->appr_date     = date('Y-m-d H:i:s');
            $client_task_details->save();

            $message_cmd                 = Auth::user()->name. ' Update by Status in Task Estimate - '. $client_task_details->name;
            $notification                = new Notification();
            $notification->task_id       = "";
            $notification->receiver_id   = 1;
            $notification->url           = route('admin.freelancer.task.details', $client_task_details->client_task_id);
            $notification->receiver_type = "freelancer";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();

            return redirect()->back()->with('success', 'Estimate Time Details Updated Successfully');

        }
        else
        {
            $client_task_details                = ClientTaskDetails::find($request->client_id);
            $client_task_details->amount        = $request->amount;
            $client_task_details->estimate_time = number_format($request->estimate_time, 2);
            $client_task_details->estimate_type = $request->estimate_type;
            $client_task_details->alert_user    = $client_task_details->user_id;
            $client_task_details->alert_Status  = 1;
            $client_task_details->status        = "Admin Objection";
            $client_task_details->save();

            $message_cmd                 = Auth::user()->name. ' Update by Status in Task Estimate - '. $client_task_details->name;
            $notification                = new Notification();
            $notification->task_id       = "";
            $notification->receiver_id   = $client_task_details->user_id;
            $notification->url           = route('freelancer.projects.task.details', $client_task_details->client_task_id);
            $notification->receiver_type = "freelancer";
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
    
    public function DeleteEstimateTask($id)
    {
        ClientTask::findOrFail($id)->delete();
        return redirect()->back()->with('error', 'Estimate Deleted Successfully');

    }

    public function EstimateCreate()
    {
        $client_details = User::where('user_type', 'freelancer')->get();
        return view('admin.freelancer.estimate-add', compact('client_details'));
    }

    public function EstimateStore(Request $request)
    {
        // dd($request->all());

        $create_task                = new ClientTask();
        $create_task->user_id       = $request->client_id;
        $create_task->user_type     = "freelancer";
        $create_task->cost_type     = $request->cost_type;
        $create_task->name          = $request->name;
        $create_task->amount        = $request->amount;
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
                    $create_task_details->user_id        = $request->client_id;
                    $create_task_details->alert_user     = $request->client_id;
                    $create_task_details->alert_Status   = 1;
                    $create_task_details->name           = $value['points'];
                    $create_task_details->status         = "In Progress";
                    $create_task_details->save();

                }

            }

            return redirect()->route('admin.freelancer.request')->with('success', 'New Task Estimate Create SuccessFully');

        }

        return redirect()->back()->with('error', 'Something Wrong');

    }

    public function EstimateExtraStore(Request $request)
    {
        // dd($request->all());

        $create_task = $request->client_task_id;
        
        $delete_details = ClientTask::where('id', $create_task)->first();

        if ($request->addmore) {
                
            foreach ($request->addmore as $key => $value) {

                $create_task_details                 = new ClientTaskDetails();
                $create_task_details->client_task_id = $create_task;
                $create_task_details->alert_user     = $delete_details->user_id;
                $create_task_details->alert_Status   = 1;
                $create_task_details->user_id        = Auth::user()->id;
                $create_task_details->name           = $value['points'];
                $create_task_details->status         = "In Progress";
                $create_task_details->save();

            }

            return redirect()->back()->with('success', 'New Task Estimate Create SuccessFully');

        }

    }

    public function DeleteEstimateComment($id)
    {

        ClientTaskComment::findOrFail($id)->delete();
        return redirect()->back()->with('error', 'Comments Deleted Successfully');

    }

    public function TaskEstimateClose(Request $request)
    {

        // dd($request->all());

        if($request->estimate_id)
        {
            $update_task                = ClientTask::find($request->estimate_id);
            $update_task->closed_date   = $request->date;
            $update_task->total_amount  = $request->total_amount;
            $update_task->total_hours   = $request->total_hours;
            $update_task->status        = $request->status;
            if ($update_task->save()) {
                return redirect()->back()->with('success', 'Estimate Details SuccessFully');
            }
            else
            {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function TaskRequestEdit($id)
    {
        $client_details = User::where('user_type', 'freelancer')->get();
        $task_details = ClientTask::where('id', $id)->first();
        // dd($task_details);
        return view('admin.freelancer.estimate-edit', compact('client_details', 'task_details'));
    }

    public function EstimateEditStore(Request $request, $id)
    {

        $create_task                = ClientTask::find($id);
        $create_task->user_id       = $request->client_id;
        $create_task->user_type     = "freelancer";
        $create_task->name          = $request->name;
        $create_task->cost_type     = $request->cost_type;
        $create_task->date          = $request->confirm_date;
        $create_task->amount        = $request->amount;
        $create_task->description   = $request->editor1;
        $create_task->status        = "In Progress";
        
        if ($create_task->save()) {
            // $create_task->total_hours  = $create_task->hours;
            if($create_task->cost_type == "hourly"){
                $create_task->total_amount = $create_task->amount * $create_task->total_hours;
                $create_task->update();
                // $client->total_hours  = $client_comment->hours;
            }
            if($create_task->cost_type == "fixed"){
                $create_task->total_amount = $create_task->amount;
                $create_task->update();
            }
            if ($request->addmore) {
                
                foreach ($request->addmore as $key => $value) {
                    // dd($value);
                    if (isset($value['id']) && $value['id']) {
                        $create_task_details                 = ClientTaskDetails::find($value['id']);
                        $create_task_details->client_task_id = $create_task->id;
                        $create_task_details->user_id        = $request->client_id;
                        $create_task_details->alert_user     = $request->client_id;
                        $create_task_details->alert_Status   = 1;
                        $create_task_details->name           = $value['points'];
                        $create_task_details->status         = "In Progress";
                        $create_task_details->save();
                    }
                    else
                    {
                        $create_task_details                 = new ClientTaskDetails();
                        $create_task_details->client_task_id = $create_task->id;
                        $create_task_details->user_id        = $request->client_id;
                        $create_task_details->alert_user     = $request->client_id;
                        $create_task_details->alert_Status   = 1;
                        $create_task_details->name           = $value['points'];
                        $create_task_details->status         = "In Progress";
                        $create_task_details->save();
                    }

                }

            }

            return redirect()->route('admin.freelancer.request')->with('warning', 'Task Estimate Edited SuccessFully');

        }

        return redirect()->back()->with('error', 'Something Wrong');

    }
    
    public function EstimateDescription(Request $request)
    {
        // dd($request->all());
        
        $update_task = ClientTask::where('id', $request->id)->first();
        
        $view =  view('admin.freelancer.modelrender3', compact('update_task'))->render();
        return response()->json(['html'=>$view]);
        
    }

    public function EstimatePay(Request $request){
        $update_task = ClientTask::where('id', $request->id)->first();
        
        $view =  view('admin.freelancer.amount_pay', compact('update_task'))->render();
        return response()->json(['html'=>$view]);
    }

    public function TaskRequestPaymentUpdate(Request $request){
        $client = ClientTask::find($request->client_task_id);
        if($client){
            $client->paid_amount = $client->paid_amount + $request->amount;
            $client->save();
            return redirect()->route('admin.freelancer.request')->with('warning', 'Task Estimate Payment Updated SuccessFully');
        }
        return redirect()->route('admin.freelancer.request')->with('danger', 'Task Estimate Not Found');
    }

}
