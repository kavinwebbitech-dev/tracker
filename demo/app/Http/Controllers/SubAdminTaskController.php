<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\TaskDetails;
use App\Models\TaskComment;
use App\Models\TaskStaff;
use App\Models\Notification;
use App\Exports\ExportTask;
use App\Models\TaskFollowComment;
use App\Models\PaymentComment;
use App\Exports\ExportRecurringTask;
use Carbon\Carbon;
use Mail;
use Auth;
use PDF;
use File;
use Excel;
use DB;

class SubAdminTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function TaskCreate()
    {
        $sub_admin1 = User::where('user_type', '!=', 'super_admin')->get();
        $sub_admin = User::where('sub_admin_id', Auth::user()->id)->where('user_type', 'staff')->get();
        $projects = Project::latest()->get();
        return view('sub_admin.task.add', compact('sub_admin', 'projects', 'sub_admin1'));
    }

    public function TaskView()
    {   
        $sort_by = '';
        $sort_task_status = '';
        $project_id = '';
        $start_date = '';

        if(request('task_type') && request('task_type')!=''){
          $sort_by = request('task_type');
        }
        if(request('start_date') && request('start_date')!=''){
          $start_date = request('start_date');
        }
        if(request('task_status') && request('task_status')!=''){
          $sort_task_status = request('task_status');
        }
        if(request('project_id') && request('project_id')!=''){
          $project_id = request('project_id');
        }

        if ($sort_by)
        {
            $sub_admin = TaskStaff::with(['task_comments'])->where('staff_id', $sort_by)->latest();
        }
        else
        {
            $sub_admin = TaskStaff::latest();
        }

        if ($sort_task_status == "inprogress" || $sort_task_status == "user_completed" || $sort_task_status == "recommend_to_admin" || $sort_task_status == "completed" || $sort_task_status == "rejected" || $sort_task_status == "over_due") {
            $sub_admin = $sub_admin->where('status', $sort_task_status);
        }

        $project_id = explode(',', $project_id);

        if ($project_id[0])
        {
            $sub_admin = $sub_admin->whereIn('project_id', $project_id);
        }

        if ($start_date) {
            // Ensure start_date is formatted correctly if it contains a time component
            $start_date = \Carbon\Carbon::parse($start_date)->format('Y-m-d');
            
            $sub_admin = $sub_admin->whereHas('task_comments', function ($query) use ($start_date) {
                $query->where('start_date', 'like', '%'.$start_date.'%');  // Compare only the date part of start_date
            });
        }

        $sub_admin = $sub_admin->paginate(100);


        // print_r($rawQuery); exit();

        $projects = Project::orderBy('date_created', 'desc')->get();
        if(Auth::user()->user_type == "sub_admin"){
            $user_details = User::where('user_type','staff')->where('status', 'active')->get();
        }else{
            $user_details = User::where('sub_admin_id', Auth::user()->id)->get();
        }
        // dd($user_details);

        return view('sub_admin.task.view', compact('sub_admin', 'sort_by', 'sort_task_status', 'user_details', 'projects', 'project_id', 'start_date'));
    }

    public function TaskRecurringView()
    {

        $sub_admin = Task::where('sub_admin_id', Auth::user()->id)->where('task_type', 'recurring')->get();
        return view('sub_admin.task.recurring', compact('sub_admin'));
    }

    public function TaskEdit($id)
    {
        $task = Task::where('id', $id)->first();
        $staff = User::where('sub_admin_id', $task->sub_admin_id)->get();
        $TaskStaff = TaskStaff::where('task_id', $task->id)->select('staff_id')->get();
        $sub_admin = User::where('sub_admin_id', Auth::user()->id)->where('user_type', 'staff')->get();
        $projects = Project::latest()->get();
        $sub_admin1 = User::where('user_type', '!=', 'super_admin')->get();
        return view('sub_admin.task.edit', compact('sub_admin', 'staff', 'task', 'TaskStaff', 'projects', 'sub_admin1'));
    }

    public function TaskDelete($id)
    {
        if ($id) {
            $user  = Task::find($id);
            if ($user->delete()) {
                return redirect()->back()->with('error', 'Task Deleted Successfully');
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function TaskSingleDelete($id)
    {
        if ($id) {
            $user  = TaskStaff::find($id);
            if ($user->delete()) {
                return redirect()->back()->with('error', 'Task Deleted Successfully');
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function fetchStaff(Request $request)
    {
        $data['staff'] = User::where("admin_id", $request->country_id)
                                ->get(["name", "id"]);
  
        return response()->json($data);
    }

    public function TaskStore(Request $request)
    {
        // dd($request->all());

        $request->validate([
            // 'staff_id'     => 'required|max:255',
            'task_name'    => 'required|max:255'
        ]);

        if ($request->task_type == "custom") 
        {

            $selected_user = $request->addmore;
            $form_date = (new \Carbon\Carbon($request->start_date))->format('d-m-Y h:i:a') ?? '';
            $to_date   = (new \Carbon\Carbon($request->end_date))->format('d-m-Y') ?? '';

            $user_details_check = User::where('id', Auth::user()->id)->first();
            $task                   = new Task();
            $task->admin_id         = $user_details_check->admin_id;
            $task->sub_admin_id     = Auth::user()->id;
            $task->staff_id         = json_encode($request->multiple_staff);
            $task->name             = $request->task_name;
            $task->description      = $request->editor1;
            $task->project_id       = $request->project_id;
            $task->task_type        = $request->task_type;
            $task->assign_by        = Auth::user()->id;
            $task->start_date       = $request->start_date;
            $task->end_date         = $request->end_date;
            $task->project_follow_up  = $request->project_follow_up;
            $task->payment_follow_up  = $request->payment_follow_up;
            $task->created_at       = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
            $task->status           = "inprogress";

            if ($task->save()) {

                $key_number     = 'T'.str_pad($task->id, 6, "0", STR_PAD_LEFT);
                $update_task    = Task::find($task->id);
                $update_task->task_no  = $key_number;
                $update_task->save();

                if ($selected_user) {
                    foreach ($selected_user as $key => $value) {
                        $task_staff             = new TaskStaff();
                        $task_staff->task_id    = $task->id;
                        $task_staff->staff_id   = $value['id'];
                        $task_staff->project_id = $request->project_id;
                        $task_staff->staff_name = $value['name'];
                        $task_staff->start_date = $value['start_date'];
                        $task_staff->end_date   = $value['end_date'];
                        $task_staff->priority   = $value['priority'];
                        $task_staff->created_id = Auth::user()->id;
                        $task_staff->status     = "inprogress";
                        $task_staff->save();

                        $message_box = Auth::user()->name . ' Assign New Task';
                        $notification                = new Notification();
                        $notification->task_id       = $task->id;
                        $notification->receiver_id   = $task_staff->staff_id;
                        $notification->receiver_type = "staff";
                        $notification->message       = $message_box;
                        $notification->status        = 0;
                        $notification->save();

                        $task_details          = Task::where('id', $task->id)->first();
                        $staff_details         = User::where('id', $value['id'])->first();
                        $data['name']          = Auth::user()->name;
                        $data['email']         = Auth::user()->email;
                        $data['logo']          = url('public/admin_assets/images/logo.png');;
                        $data['task_details']  = $task_details;
                        $data['staff_details'] = $staff_details;
                        $data['start_date']    = $value['start_date'];
                        $data['end_date']      = $value['end_date'];

                        try{
                            Mail::send('mail.staff_task', $data, function($message)use($data) {
                                $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                                    ->from("noreplywebbitech@gmail.com", 'no-reply')
                                ->subject(Auth::user()->name. '- Assign New Task');
                            });
                        }
                        catch(\Exception $e){
                            // dd($e);
                        }

                    }
                }

                
                return redirect()->back()->with('success', 'Task Created Successfully');
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
        else
        {   
            $form_date = (new \Carbon\Carbon($request->recurring_start_date))->format('d-m-Y') ?? '';
            $date = new \Carbon\Carbon($request->recurring_start_date);
            $date->addDays($request->date_type);
            $to_date = $date->format('d-m-Y');

            $user_details_check = User::where('id', $request->staff_id)->first();
            $task                   = new Task();
            $task->admin_id         = $user_details_check->admin_id;
            $task->sub_admin_id     = $user_details_check->sub_admin_id;
            $task->staff_id         = $request->staff_id;
            $task->name             = $request->task_name;
            $task->description      = $request->editor1;
            $task->task_type        = $request->task_type;
            $task->date_type        = $request->date_type;
            $task->project_id       = $request->project_id;
            $task->project_follow_up  = $request->project_follow_up;
            $task->payment_follow_up  = $request->payment_follow_up;
            $task->assign_by        = Auth::user()->id;
            $task->start_date       = $form_date;
            $task->end_date         = $to_date;
            $task->created_at       = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
            $task->status           = "inprogress";

            if ($task->save()) {

                $key_number     = 'T'.$task->id;
                $update_task    = Task::find($task->id);
                $update_task->task_no  = $key_number;
                $update_task->save();

                $task_details               = new TaskDetails();
                $task_details->task_id      = $task->id;
                $task_details->staff_id     = $request->staff_id;
                $task_details->start_date   = $form_date;
                $task_details->end_data     = $to_date;
                $task_details->date_type    = $request->date_type;
                $task_details->status       = "inprogress";
                $task_details->points       = "0";
                $task_details->save();

                $message_box = Auth::user()->name . ' - Assign New Task';
                $notification                = new Notification();
                $notification->task_id       = $task->id;
                $notification->receiver_id   = $request->staff_id;
                $notification->receiver_type = "staff";
                $notification->message       = $message_box;
                $notification->status        = 0;
                $notification->save();

                $task_details          = Task::where('id', $task->id)->first();
                $staff_details         = User::where('id', $request->staff_id)->first();
                $data['name']          = Auth::user()->name;
                $data['email']         = Auth::user()->email;
                $data['logo']          = url('public/admin_assets/images/logo.png');
                $data['task_details']  = $task_details;
                $data['staff_details'] = $staff_details;

                try{
                    Mail::send('mail.task_details', $data, function($message)use($data) {
                        $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                            ->from("noreplywebbitech@gmail.com", 'no-reply')
                        ->subject('Admin Assign New Task');
                    });
                }
                catch(\Exception $e){
                    // dd($e);
                }

                return redirect()->back()->with('success', 'Task Created Successfully');

                
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }

    }

    public function TaskUpdate(Request $request, $id)
    {
        // dd($request->all());
        
        // dd($request->all());

        $request->validate([
            // 'staff_id'     => 'required|max:255',
            'task_name'    => 'required|max:255'
        ]);

        if ($request->task_type == "custom") 
        {

            $selected_user = $request->addmore;
            $form_date = (new \Carbon\Carbon($request->start_date))->format('d-m-Y h:i:a') ?? '';
            $to_date   = (new \Carbon\Carbon($request->end_date))->format('d-m-Y') ?? '';

            $user_details_check = User::where('id', Auth::user()->id)->first();
            $task                   = Task::find($id);
            $task->admin_id         = $user_details_check->admin_id;
            $task->sub_admin_id     = Auth::user()->id;
            $task->staff_id         = json_encode($request->multiple_staff);
            $task->name             = $request->task_name;
            $task->description      = $request->editor1;
            $task->task_amount      = $request->task_amount;
            $task->task_type        = $request->task_type;
            $task->project_id       = $request->project_id;
            // $task->assign_by        = Auth::user()->id;
            $task->start_date       = $request->start_date;
            $task->end_date         = $request->end_date;
            $task->project_follow_up  = $request->project_follow_up;
            $task->payment_follow_up  = $request->payment_follow_up;
            $task->created_at       = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
            $task->status           = "inprogress";

            if ($task->save()) {

                if ($selected_user) {
                    foreach ($selected_user as $key => $value) {

                        $TaskStaffCheck = TaskStaff::where('task_id', $task->id)->where('staff_id', $value['id'])->first();

                        if (isset($TaskStaffCheck)) {
                            $task_staff             = TaskStaff::find($TaskStaffCheck->id);
                            $task_staff->task_id    = $task->id;
                            $task_staff->staff_id   = $value['id'];
                            $task_staff->staff_name = $value['name'];
                            $task_staff->project_id       = $request->project_id;
                            $task_staff->start_date = $value['start_date'];
                            $task_staff->end_date   = $value['end_date'];
                            $task_staff->priority   = $value['priority'];
                            $task_staff->status     = "inprogress";
                            $task_staff->save();
                        }
                        else
                        {
                            $task_staff             = new TaskStaff();
                            $task_staff->task_id    = $task->id;
                            $task_staff->staff_id   = $value['id'];
                            $task_staff->staff_name = $value['name'];
                            $task_staff->project_id       = $request->project_id;
                            $task_staff->start_date = $value['start_date'];
                            $task_staff->end_date   = $value['end_date'];
                            $task_staff->priority   = $value['priority'];
                            $task_staff->status     = "progress";
                            $task_staff->save();

                            $task_details          = Task::where('id', $task->id)->first();
                            $staff_details         = User::where('id', $value['id'])->first();
                            $data['name']          = Auth::user()->name;
                            $data['email']         = Auth::user()->email;
                            $data['logo']          = url('public/admin_assets/images/logo.png');;
                            $data['task_details']  = $task_details;
                            $data['staff_details'] = $staff_details;
                            $data['start_date']    = $value['start_date'];
                            $data['end_date']      = $value['end_date'];

                            try{
                                Mail::send('mail.staff_task', $data, function($message)use($data) {
                                    $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                                    ->subject(Auth::user()->name. '- Assign New Task');
                                });
                            }
                            catch(\Exception $e){
                                // dd($e);
                            }
                        }
                    }
                }

                
                return redirect()->back()->with('success', 'Task Created Successfully');
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
        else
        {   
            $form_date = (new \Carbon\Carbon($request->recurring_start_date))->format('d-m-Y H:i:s') ?? '';
            $date = new \Carbon\Carbon($request->recurring_start_date);
            $date->addDays($request->date_type);
            $to_date = $date->format('d-m-Y H:i:s');

            $user_details_check = User::where('id', $request->staff_id)->first();
            $task                   = Task::find($id);
            $task->admin_id         = $user_details_check->admin_id;
            $task->sub_admin_id     = $user_details_check->sub_admin_id;
            $task->staff_id         = $request->staff_id;
            $task->name             = $request->task_name;
            $task->description      = $request->editor1;
            $task->project_id       = $request->project_id;
            $task->task_type        = $request->task_type;
            $task->date_type        = $request->date_type;
            $task->project_follow_up  = $request->project_follow_up;
            $task->payment_follow_up  = $request->payment_follow_up;
            // $task->assign_by        = Auth::user()->id;
            $task->created_at       = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
            $task->status           = "inprogress";

            if ($task->save()) {

                $task_details               = new TaskDetails();
                $task_details->task_id      = $task->id;
                $task_details->staff_id     = $request->staff_id;
                $task_details->start_date   = $form_date;
                $task_details->end_data     = $to_date;
                $task_details->date_type    = $request->date_type;
                $task_details->status       = "inprogress";
                $task_details->points       = "0";
                if ($task_details->save()) {

                    $task_details          = Task::where('id', $task->id)->first();
                    $staff_details         = User::where('id', $request->staff_id)->first();
                    $data['name']          = Auth::user()->name;
                    $data['email']         = Auth::user()->email;
                    $data['logo']          = url('public/admin_assets/images/logo.png');
                    $data['task_details']  = $task_details;
                    $data['staff_details'] = $staff_details;

                    try{
                        Mail::send('mail.task_details', $data, function($message)use($data) {
                            $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                                ->from("noreplywebbitech@gmail.com", 'no-reply')
                            ->subject('Admin Assign New Task');
                        });
                    }
                    catch(\Exception $e){
                        // dd($e);
                    }

                    return redirect()->back()->with('success', 'Task Created Successfully');

                }
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
    }

    public function TaskStatusUpdate($id)
    {
        $task = Task::where('id', $id)->first();
        return view('sub_admin.task.status', compact('task'));
    }


    public function TaskStatus(Request $request)
    {
        dd($request->all());

        $today_date = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();

        $task_comments                = TaskStaff::find($request->staff_task_id);
        $task_comments->status        = $request->staff_status;
        $task_comments->admin_comment = $request->admin_comment_model;

        if ($task_comments->save()) {

            if ($request->staff_status == "completed") {
                $status1 = "Completed";

                $date1 = date('Y-m-d');
                $date2 = date('Y-m-d', strtotime($task_comments->end_date));
                $points = 1;
                $user = User::find($task_comments->staff_id);
                if ($date2 >= $date1) {
                    if ($user->points == null) {
                        $user->points = $points;
                    }
                    else
                    {
                        $user->points += $points;
                    }
                    
                    $task_comments->points = "+1";
                }
                else
                {
                    $user->points -= $points;
                    $task_comments->points = "-1";
                }
                $user->save();
                $task_comments->completed_date = $today_date;

            }
            elseif ($request->staff_status == "rejected") {
                $status1 = "Rejected";
            }

            $task_details                = Task::where('id', $task_comments->task_id)->first();
            $message_cmd                 = Auth::user()->name .' Updated by Task Status - '. $task_details->task_no;

            $notification                = new Notification();
            $notification->task_id       = $task_details->id;
            $notification->receiver_id   = $request->staff_task_id;
            $notification->receiver_type = "staff";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();
            
            $staff_details           = User::where('id', $task_comments->staff_id)->first();
            $data['name']            = Auth::user()->name;
            $data['email']           = Auth::user()->email;
            $data['logo']            = url('public/admin_assets/images/logo.png');
            $data['task_details']    = $task_details;
            $data['staff_details']   = $staff_details;
            $data['status1']         = $status1;
            // $message->to($data['staff_details']['email'], $data['staff_details']['name'])
            try{
                Mail::send('mail.custom_task_status', $data, function($message)use($data) {
                    $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                    ->subject(Auth::user()->name. ' has been Completed the Recurring Task');
                });
            }
            catch(\Exception $e){
                // dd($e);
            }

            return redirect()->back()->with('success', 'Staff Task Status Update Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    

    public function Reopen(Request $request)
    {
        
        $today_date = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
        $message_box = 'Admin - Reopen this task';
        $points = 1;
        $status = $request->reopen_status;
        $task_id = $request->reopen_task;
        $user_comment1 = $request->user_comment;
        $task = Task::find($task_id);
        $task->status = $status;
        $task->admin_comments = $user_comment1;
        $task->completed_date = $today_date;
        $task->points = 0;
        if ($task->save()) {

            $user = User::find($task->staff_id);
            $user->points -= $points;
            $user->save();
            $notification                = new Notification();
            $notification->task_id       = $task->id;
            $notification->receiver_id   = $task->staff_id;
            $notification->receiver_type = "staff";
            $notification->message       = $message_box;
            $notification->status        = 0;
            $notification->save();

            $notification1                = new Notification();
            $notification1->task_id       = $task->id;
            $notification1->receiver_id   = $task->admin_id;
            $notification1->receiver_type = "sub_admin";
            $notification1->message       = $message_box;
            $notification1->status        = 0;
            $notification1->save();

            
            $task_details          = Task::where('id', $task->id)->first();
            $staff_details         = User::where('id', $task->staff_id)->first();
            $data['name']          = Auth::user()->name;
            $data['email']         = Auth::user()->email;
            $data['logo']          = url('public/admin_assets/images/logo.png');
            $data['task_details']  = $task_details;
            $data['staff_details'] = $staff_details;

            try{
                Mail::send('mail.reopen', $data, function($message)use($data) {
                    $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                    ->subject('Admin - Reopen this task');
                });
            }
            catch(\Exception $e){
                // dd($e);
            }
            
            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
        }
        else
        {
            return redirect()->back()->with('erro', 'Something Wrong');
        }


    }

    public function TaskDetailsGet($id)
    {

        $task = TaskDetails::where('id', $id)->first();
        return view('sub_admin.task.task_details', compact('task'));
    }

    public function TaskDetailsUpdate(Request $request)
    {
        // dd($request->all());

        $today_date = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
        
        $status             = $request->status;
        $task_details_id    = $request->task_details_id;
        $user_comment1      = $request->user_comment;

        $task_details       = TaskDetails::find($task_details_id);
        $task_details->status = $status;
        $task_details->sub_admin_comment = $user_comment1;
        
        if ($task_details->save()) {


            if($request->status == "rejected")
            {
                $message_cmd = $task_details->task_main->task_no. ' has been Rejected by Sub Admin';
                $notification                = new Notification();
                $notification->task_id       = $task_details->task_main->id;
                $notification->receiver_id   = $task_details->task_main->staff_id;
                $notification->receiver_type = "staff";
                $notification->message       = $message_cmd;
                $notification->status        = 0;
                $notification->save();
            }
            elseif($request->status == "recommend_to_admin")
            {
                $message_cmd = $task_details->task_main->task_no. ' has been Completed. Recomment by Sub Admin';

                $notification1                = new Notification();
                $notification1->task_id       = $task_details->task_main->id;
                $notification1->receiver_id   = $task_details->task_main->admin_id;
                $notification1->receiver_type = "admin";
                $notification1->message       = $message_cmd;
                $notification1->status        = 0;
                $notification1->save();

            }

            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function SuperAdminDownload (Request $request)
    {
        // dd($request->all());

        $data['sort_task_type']     = $request->sort_task_type;
        $data['sort_task_status']   = $request->sort_task_status;
        $data['txt_search']         = $request->txt_search;
        $data['user_id']            = Auth::user()->id;
        $data['user_type']          = "sub_admin_id";

        $sort_task_type     = $request->sort_task_type;
        $sort_task_status   = $request->sort_task_status;
        $user_name          = $request->txt_search;
        $user_id            = Auth::user()->id;
        $user_type          = "sub_admin_id";
        
        $book_details = Task::with(['staff_details'])->where('sub_admin_id', $user_id);
        if ($sort_task_type) {
            $book_details = $book_details->where('task_type', $sort_task_type);
        }
        if ($sort_task_status) {
            $book_details = $book_details->where('status', $sort_task_status);
        }
        if ($user_name) {
            $book_details = $book_details->whereHas('staff_details', function ($query) use ($user_name){
                    $query->where('name', 'like', '%'.$user_name.'%');
                });
        }
        $book_details = $book_details->get();

        if (count($book_details)) {
            return Excel::download(new ExportTask($data), 'Recurring.xlsx');
        }
        else
        {   
            return redirect()->back()->with('error', 'No Data Available');
        }
    }

    public function YourTaskView()
    {
        // dd(Auth::user()->id);
        $sub_admin = Task::where('staff_id', Auth::user()->id)->latest()->get();
        // $sub_admin = Task::where('staff_id', Auth::user()->id)->get();
        return view('sub_admin.your_task.your_task', compact('sub_admin'));
    }

    public function YourAdminModel(Request $request)
    {
        $task = TaskDetails::where('id', $request->product_id)->first();
        $view =  view('sub_admin.your_task.rendermodel', compact('task'))->render();
        return response()->json(['html'=>$view]);
    }

    public function AdminTaskStatus(Request $request)
    {
        // dd($request->all());

        $today_date = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
        
        $status = $request->status;
        $task_id = $request->task_id;
        $user_comment1 = $request->user_comment;
        $task = Task::find($task_id);
        $task->status = $status;
        $task->admin_comments = $user_comment1;
        
        if ($task->save()) {

            if ($request->status == "user_completed") {
                $message_box = $task->task_no.'this task has been Completed by Sub Admin';
            }

            $notification1                = new Notification();
            $notification1->task_id       = $task->id;
            $notification1->receiver_id   = $task->admin_id;
            $notification1->receiver_type = "sub_admin";
            $notification1->message       = $message_box;
            $notification1->status        = 0;
            $notification1->save();

            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function YourTaskDetailsView($id)
    {
        $task = Task::where('id', $id)->first();
        $task_staff = TaskStaff::where('task_id', $id)->where('staff_id', Auth::user()->id)->first();
        $task_comments = TaskComment::where('task_id', $id)->where('staff_id', Auth::user()->id)->get();
        return view('sub_admin.your_task.task_details', compact('task', 'task_staff', 'task_comments'));
        // $view =  view('sub_admin.your_task.rendermodel', compact('task'))->render();
        // return response()->json(['html'=>$view]);
    }

    public function AdminTaskDetailStatus(Request $request)
    {
        // dd($request->all());

        $today_date = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
        
        $status             = $request->status;
        $task_details_id    = $request->task_details_id;
        $user_comment1      = $request->user_comment;

        $task_details       = Task::find($task_details_id);
        $task_details->status = $status;
        $task_details->sub_admin_comment = $user_comment1;
        
        if ($task_details->save()) {


            if($request->status == "user_completed")
            {
                $message_cmd = Auth::user()->name . ' Updated by Task Status - '. $task_details->task_no;
                $notification                = new Notification();
                $notification->task_id       = $task_details->id;
                $notification->receiver_id   = $task_details->admin_id;
                $notification->url           = route('admin.task.status', $task_details->id);
                $notification->receiver_type = "admin";
                $notification->message       = $message_cmd;
                $notification->status        = 0;
                $notification->save();

                $task_details            = Task::where('id', $task_details->id)->first();
                $staff_details           = User::where('id', 1)->first();
                $recurreing_task         = Task::where('id', $task_details_id)->first();
                $data['name']            = Auth::user()->name;
                $data['email']           = Auth::user()->email;
                $data['logo']            = url('public/admin_assets/images/logo.png');
                $data['task_details']    = $task_details;
                $data['staff_details']   = $staff_details;
                $data['recurreing_task'] = $recurreing_task;
                // $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                try{
                    Mail::send('mail.recurring_task_status', $data, function($message)use($data) {
                        $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                            ->from("noreplywebbitech@gmail.com", 'no-reply')
                        ->subject(Auth::user()->name. ' has been Completed the Recurring Task');
                    });
                }
                catch(\Exception $e){
                    // dd($e);
                }

            }

            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function TaskUpdateComment(Request $request)
    {
        // dd($request->all());

        $today_date = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
        $points_admin = $request->previous;
        $task_comments                = TaskStaff::find($request->staff_task_id);
        $task_comments->status        = $request->staff_status1;
        $task_comments->admin_comment = $request->admin_comment_model;

            if ($request->staff_status1 == "recommend_to_admin") {
                $status1 = "Recommend to Admin";

            }
            elseif ($request->staff_status1 == "completed") {
                $status1 = "Completed";

                $date1 = strtotime($today_date);
                $date2 = strtotime($task_comments->end_date);
                $points = 1;
                $user = User::find($task_comments->staff_id);
                if ($points_admin == "on") {
                    if ($user->points == null) {
                        $user->points = $points;
                    }
                    else
                    {
                        $user->points += $points;
                    }
                    
                    $task_comments->points = "+1";
                }
                elseif ($date2 >= $date1) {
                    if ($user->points == null) {
                        $user->points = $points;
                    }
                    else
                    {
                        $user->points += $points;
                    }
                    
                    $task_comments->points = "+1";
                }
                else
                {
                    if ($user->points == null) {
                        // $user->points = $points;
                    }
                    else
                    {
                        $user->points -= $points;
                    }
                    
                    // $user->points -= $points;
                    $task_comments->points = "-1";
                }
                $user->save();
                $task_comments->completed_date = $today_date;

            }
            elseif ($request->staff_status1 == "canceled") {
                $status1 = "Canceled";
            }
            elseif ($request->staff_status1 == "rejected") {
                $status1 = "Rejected";
            }
            if ($task_comments->save()) {

                $task_details                = Task::where('id', $task_comments->task_id)->first();

                if($request->staff_status1 == "completed")
                {
                    $message_cmd = $task_details->task_no .' - has been Completed';

                    $notification                = new Notification();
                    $notification->task_id       = $task_comments->task_id;
                    $notification->receiver_id   = $task_comments->staff_id;
                    $notification->receiver_type = "staff";
                    $notification->message       = $message_cmd;
                    $notification->status        = 0;
                    $notification->save();

                    $staff_details           = User::where('id', $task_comments->staff_id)->first();
                    $data['name']            = Auth::user()->name;
                    $data['email']           = Auth::user()->email;
                    $data['logo']            = url('public/admin_assets/images/logo.png');
                    $data['task_details']    = $task_details;
                    $data['staff_details']   = $staff_details;
                    $data['status1']         = $status1;
                    
                    try{
                        Mail::send('mail.custom_task_status', $data, function($message)use($data) {
                            $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                                ->from("noreplywebbitech@gmail.com", 'no-reply')
                            ->subject(Auth::user()->name . 'Rejected by this task - '. $data['task_details']['task_no']);
                        });
                    }
                    catch(\Exception $e){
                        // dd($e);
                    }

                }
                elseif($request->staff_status1 == "canceled")
                {
                    $message_cmd = Auth::user()->name . 'Canceled by this task - '. $task_details->task_no;

                    $notification                = new Notification();
                    $notification->task_id       = $task_comments->task_id;
                    $notification->receiver_id   = $task_comments->staff_id;
                    $notification->receiver_type = "staff";
                    $notification->message       = $message_cmd;
                    $notification->status        = 0;
                    $notification->save();

                    $staff_details           = User::where('id', $task_comments->staff_id)->first();
                    $data['name']            = Auth::user()->name;
                    $data['email']           = Auth::user()->email;
                    $data['logo']            = url('public/admin_assets/images/logo.png');
                    $data['task_details']    = $task_details;
                    $data['staff_details']   = $staff_details;
                    $data['status1']         = $status1;
                    
                    try{
                        Mail::send('mail.custom_task_status', $data, function($message)use($data) {
                            $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                                ->from("noreplywebbitech@gmail.com", 'no-reply')
                            ->subject(Auth::user()->name . 'Rejected by this task - '. $data['task_details']['task_no']);
                        });
                    }
                    catch(\Exception $e){
                        // dd($e);
                    }

                }
                elseif($request->staff_status1 == "rejected")
                {
                    $message_cmd = Auth::user()->name . 'Rejected by this task - '. $task_details->task_no;

                    $notification                = new Notification();
                    $notification->task_id       = $task_comments->task_id;
                    $notification->receiver_id   = $task_comments->staff_id;
                    $notification->receiver_type = "staff";
                    $notification->message       = $message_cmd;
                    $notification->status        = 0;
                    $notification->save();

                    $staff_details           = User::where('id', $task_comments->staff_id)->first();
                    $data['name']            = Auth::user()->name;
                    $data['email']           = Auth::user()->email;
                    $data['logo']            = url('public/admin_assets/images/logo.png');
                    $data['task_details']    = $task_details;
                    $data['staff_details']   = $staff_details;
                    $data['status1']         = $status1;
                    
                    try{
                        Mail::send('mail.custom_task_status', $data, function($message)use($data) {
                            $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                                ->from("noreplywebbitech@gmail.com", 'no-reply')
                            ->subject(Auth::user()->name . 'Rejected by this task - '. $data['task_details']['task_no']);
                        });
                    }
                    catch(\Exception $e){
                        // dd($e);
                    }

                }
                elseif($request->staff_status1 == "recommend_to_admin")
                {
                    // dd($task_details);
                    $message_cmd = Auth::user()->name . 'Recomment to Completed this task - '. $task_details->task_no;

                    $notification1                = new Notification();
                    $notification1->task_id       = $task_comments->task_id;
                    $notification1->receiver_id   = 1;
                    $notification1->receiver_type = "admin";
                    $notification1->message       = $message_cmd;
                    $notification1->status        = 0;
                    $notification1->save();

                    $notification                = new Notification();
                    $notification->task_id       = $task_comments->task_id;
                    $notification->receiver_id   = $task_comments->staff_id;
                    $notification->receiver_type = "staff";
                    $notification->message       = $message_cmd;
                    $notification->status        = 0;
                    $notification->save();

                    $staff_details           = User::where('id', 1)->first();
                    $data['name']            = Auth::user()->name;
                    $data['email']           = Auth::user()->email;
                    $data['logo']            = url('public/admin_assets/images/logo.png');
                    $data['task_details']    = $task_details;
                    $data['staff_details']   = $staff_details;
                    $data['status1']         = $status1;
                    
                    try{
                        Mail::send('mail.custom_task_status', $data, function($message)use($data) {
                            $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                                ->from("noreplywebbitech@gmail.com", 'no-reply')
                            ->subject(Auth::user()->name . ' Recomment to Completed by this task - '. $data['task_details']['task_no']);
                        });
                    }
                    catch(\Exception $e){
                        // dd($e);
                    }

                }


            return redirect()->back()->with('success', 'Staff Task Status Update Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function ClosedStatusTask($id)
    {
        // dd($id);
        $task_staff     = Task::find($id);
        $task_staff->status = "completed";
        if ($task_staff->save()) {
            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
        }
        return redirect()->back()->with('error', 'Something Wrong');
    }

    public function TaskDetailsTimeUpdate(Request $request)
    {
        // dd($request->all());
        $task_comments                      = TaskComment::find($request->task_comment_id);
        $task_comments->working_hours       = number_format($request->working_hours_1, 2);
        $task_comments->add_working_hours   = number_format($request->working_hours_2, 2);
        $task_comments->admin_comment       = $request->comment_model;
        if ($task_comments->save()) {
            return redirect()->back()->with('success', 'Task Comments Updated Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function AddTaskStatus(Request $request)
    {
        // dd($request->all());
        $task_comments                = new TaskComment();
        $task_comments->task_id       = $request->task_id;
        $task_comments->staff_id      = Auth::user()->id;
        $task_comments->start_date    = $request->start_date;
        $task_comments->end_date      = $request->end_date;
        $task_comments->working_hours = $request->working_hours;
        $task_comments->user_comment  = $request->user_comment;
        if ($task_comments->save()) {
            return redirect()->back()->with('success', 'Task Comments Add Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function TaskDetailsTimeUpdate1(Request $request)
    {
        // dd($request->all());
        $task_comments                = TaskComment::find($request->task_comment_id);
        $task_comments->working_hours = $request->working_hours_1;
        $task_comments->user_comment  = $request->comment_model;
        if ($task_comments->save()) {
            return redirect()->back()->with('success', 'Task Comments Updated Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function TaskCommentDelete($id)
    {
        if ($id) {
            $user  = TaskComment::find($id);
            $user->is_delete = 0;
            if ($user->save()) {
                return redirect()->back()->with('error', 'Task Comment Deleted Successfully');
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function ChangeStatusTask($id)
    {
        // dd($id);
        $task_staff     = TaskStaff::find($id);
        $task_staff->status = "user_completed";
        $status1 = "Request to Completed";
        if ($task_staff->save()) {

            $task_details                = Task::where('id', $task_staff->task_id)->first();
            $message_cmd                 = Auth::user()->name. ' Updated by Task - '. $task_details->task_no;
            $notification                = new Notification();
            $notification->task_id       = $task_details->id;
            $notification->receiver_id   = $task_details->admin_id;
            $notification->receiver_type = "admin";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();

            $staff_details           = User::where('id', $task_details->admin_id)->first();
            $data['name']            = Auth::user()->name;
            $data['email']           = Auth::user()->email;
            $data['logo']            = url('public/admin_assets/images/logo.png');
            $data['task_details']    = $task_details;
            $data['staff_details']   = $staff_details;
            $data['status1']         = $status1;
            // $message->to($data['staff_details']['email'], $data['staff_details']['name'])
            try{
                Mail::send('mail.staff_closed', $data, function($message)use($data) {
                    $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                    ->subject(Auth::user()->name. ' has been Request to Completed '. $data['task_details']['name']);
                });
            }
            catch(\Exception $e){
                // dd($e);
            }

            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
        }
        return redirect()->back()->with('error', 'Something Wrong');
    }

    public function AdminTaskCheck(Request $request)
    {
        $user_id = $request->id;
        $task_id = $request->task_id;
        $text = $request->text;

        $TaskStaff = TaskStaff::where('task_id', $task_id)->where('staff_id', $user_id)->first();
        // dd($TaskStaff);
        $html = "";
        $selected = '';
        $selected1 = '';
        $selected2 = '';
        if ($TaskStaff) {
            if ($TaskStaff->priority == "Low") {
                $selected = 'selected="selected"';
            }
            elseif ($TaskStaff->priority == "Medium") {
                $selected1 = 'selected="selected"';
            }
            elseif ($TaskStaff->priority == "High") {
                $selected2 = 'selected="selected"';
            }
            $html .= '<div class="form-group row">
                <div class="col-md-3">
                    <label class="form-label">Name</label>
                    <input type="hidden" name="addmore['.$user_id.'][id]" value="'.$user_id.'">
                    <input type="text" class="form-control" name="addmore['.$user_id.'][name]" value="'.$text.'">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="addmore['.$user_id.'][start_date]" value="'.$TaskStaff->start_date.'" placeholder="test">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" name="addmore['.$user_id.'][end_date]" value="'.$TaskStaff->end_date.'" placeholder="test1">
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Task Priority </label>
                        <div class="input-group mb-3">
                            <select class="form-control" name="addmore['.$user_id.'][priority]">
                                <option value="">Select Priority</option>
                                <option value="High" '.$selected2.'>High</option>\
                                <option value="Medium" '.$selected1.'>Medium</option>
                                <option value="Low" '.$selected.'>Low</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>';
        }
        else
        {
            $html .= '<div class="form-group row">
                <div class="col-md-3">
                    <label class="form-label">Name</label>
                    <input type="hidden" name="addmore['.$user_id.'][id]" value="'.$user_id.'">
                    <input type="text" class="form-control" name="addmore['.$user_id.'][name]" value="'.$text.'">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="addmore['.$user_id.'][start_date]" placeholder="test">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" name="addmore['.$user_id.'][end_date]" placeholder="test1">
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Task Priority </label>
                        <div class="input-group mb-3">
                            <select class="form-control" name="addmore['.$user_id.'][priority]">
                                <option value="">Select Priority</option>
                                <option value="High">High</option>\
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>';
        }

        return $html;
        // dd($TaskStaff);
    }

    public function FollowUpProject()
    {
        $sub_admin = Task::where('project_follow_up', Auth::user()->id)
                        ->orWhere('payment_follow_up', Auth::user()->id)
                        ->get();
        return view('sub_admin.task.follow_up', compact('sub_admin'));
    }

    public function FollowUpProjectDetails($id)
    {

        $task = Task::where('id', $id)->first();
        return view('sub_admin.task.project-status', compact('task'));
    }

    public function FollowUpProjectComments(Request $request)
    {
        // dd($request->all());

        $follow_comment                 = new TaskFollowComment();
        $follow_comment->follower_id    = Auth::user()->id;
        $follow_comment->task_id        = $request->task_id;
        $follow_comment->project_id     = $request->project_id;
        $follow_comment->staff_id       = $request->staff_status;
        $follow_comment->comments       = $request->admin_comment_model;
        $follow_comment->status         = $request->previous;
        $follow_comment->save();

        if ($follow_comment) {
            return redirect()->back()->with('success', 'Follow Status Updated Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function ChangeCoordinarTask($id)
    {
        // dd($id);
        $task_details1                = Task::where('id', $id)->first();
        $task_details1->status        = "payment_process";
        $task_details1->save();

        $status1 = "Client follow up compelted";

        if ($task_details1) {

            $task_details                = Task::where('id', $id)->first();
            $message_cmd                 = 'Follow to Payment Process by Task no - '. $task_details->task_no;

            $notification                = new Notification();
            $notification->task_id       = $task_details->id;
            $notification->receiver_id   = $task_details->payment_follow_up;
            $notification->receiver_type = "";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();

            $task_staff_details  = TaskStaff::where('task_id', $id)->get();

            foreach ($task_staff_details as $key => $value) {
                $task_staff_update          = TaskStaff::find($value->id);
                $task_staff_update->status  = "user_completed";
                $task_staff_update->save();
            }

            $staff_details           = User::where('id', $task_details->payment_follow_up)->first();
            $data['name']            = Auth::user()->name;
            $data['email']           = Auth::user()->email;
            $data['logo']            = url('public/admin_assets/images/logo.png');
            $data['task_details']    = $task_details;
            $data['staff_details']   = $staff_details;
            $data['status1']         = $status1;
            
            try{
                Mail::send('mail.staff_coordinar', $data, function($message)use($data) {
                    $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                    ->subject($task_details->task_no. ' Client follow up compelted.');
                });
            }
            catch(\Exception $e){
                // dd($e);
            }

            return redirect()->back()->with('success', 'Task Status Updated Successfully!');


        }
        return redirect()->back()->with('error', 'Something Wrong');

    }

    public function FollowUpProjectPayment(Request $request)
    {
        // dd($request->all());

        $follow_comment                 = new PaymentComment();
        $follow_comment->follower_id    = Auth::user()->id;
        $follow_comment->task_id        = $request->task_id;
        $follow_comment->project_id     = $request->project_id;
        $follow_comment->task_date      = $request->task_date;
        $follow_comment->amount         = $request->amount;
        $follow_comment->comments       = $request->admin_comment_model;
        $follow_comment->save();

        if ($follow_comment) {
            return redirect()->back()->with('success', 'Payment Status Updated Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function ChangePaymentTask($id)
    {
        // dd($id);
        $task_details1                = Task::where('id', $id)->first();
        $task_details1->status        = "recommend_to_admin";
        $task_details1->save();

        $status1 = "Payment follow up compelted";

        if ($task_details1) {

            $task_details                = Task::where('id', $id)->first();
            $message_cmd                 = 'Payment follow up compelted by Task no - '. $task_details->task_no;

            $notification                = new Notification();
            $notification->task_id       = $task_details->id;
            $notification->receiver_id   = $task_details->admin_id;
            $notification->receiver_type = "";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();

            $task_staff_details  = TaskStaff::where('task_id', $id)->get();

            foreach ($task_staff_details as $key => $value) {
                $task_staff_update          = TaskStaff::find($value->id);
                $task_staff_update->status  = "recommend_to_admin";
                $task_staff_update->save();
            }

            $staff_details           = User::where('id', $task_details->admin_id)->first();
            $data['name']            = Auth::user()->name;
            $data['email']           = Auth::user()->email;
            $data['logo']            = url('public/admin_assets/images/logo.png');
            $data['task_details']    = $task_details;
            $data['staff_details']   = $staff_details;
            $data['status1']         = $status1;
            
            try{
                Mail::send('mail.payment_comeplete', $data, function($message)use($data) {
                    $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                    ->subject($task_details->task_no. ' Payment follow up compelted.');
                });
            }
            catch(\Exception $e){
                // dd($e);
            }

            return redirect()->back()->with('success', 'Task Status Updated Successfully!');


        }
        return redirect()->back()->with('error', 'Something Wrong');

    }

    public function SubAdminModel(Request $request)
    {
        $task = TaskDetails::where('id', $request->product_id)->first();
        $view =  view('sub_admin.task.rendermodel', compact('task'))->render();
        return response()->json(['html'=>$view]);
    }

    public function RecurTaskStatus(Request $request)
    {
        // dd($request->all());

        $today_date = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();

        $task_comments                = TaskDetails::find($request->task_id);
        $task_comments->status        = $request->status;
        $task_comments->admin_comments = $request->user_comment;

        if ($task_comments->save()) {

            if ($request->status == "completed") {
                $status1 = "Completed";

                $date1 = date('Y-m-d');
                $date2 = date('Y-m-d', strtotime($task_comments->end_date));
                $points = 1;
                $user = User::find($task_comments->staff_id);
                if ($date2 >= $date1) {
                    if ($user->points == null) {
                        $user->points = $points;
                    }
                    else
                    {
                        $user->points += $points;
                    }
                    
                    $task_comments->points = "+1";
                }
                else
                {
                    $user->points -= $points;
                    $task_comments->points = "-1";
                }
                $user->save();
                $task_comments->completed_date = $today_date;

            }
            elseif ($request->staff_status == "rejected") {
                $status1 = "Rejected";
            }

            $task_details                = Task::where('id', $task_comments->id)->first();
            $message_cmd                 = Auth::user()->name .' Updated by Task Status - '. $task_details->task_no;

            $notification                = new Notification();
            $notification->task_id       = $task_details->id;
            $notification->receiver_id   = $task_details->staff_id;
            $notification->receiver_type = "staff";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();
            
            $staff_details           = User::where('id', $task_comments->staff_id)->first();
            $data['name']            = Auth::user()->name;
            $data['email']           = Auth::user()->email;
            $data['logo']            = url('public/admin_assets/images/logo.png');
            $data['task_details']    = $task_details;
            $data['staff_details']   = $staff_details;
            $data['status1']         = $status1;
            // $message->to($data['staff_details']['email'], $data['staff_details']['name'])
            try{
                Mail::send('mail.custom_task_status', $data, function($message)use($data) {
                    $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                    ->subject(Auth::user()->name. ' has been Completed the Recurring Task');
                });
            }
            catch(\Exception $e){
                // dd($e);
            }

            return redirect()->back()->with('success', 'Staff Task Status Update Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function AdminSearchTask(Request $request)
    {

        $text_value_search = $request->text_value_search;
        $start_date = $request->start_date;
        $sort_task_status = $request->sort_task_status;
        $sort_task_type = $request->sort_task_type;
        $project_id = $request->project_id;

        $users = TaskStaff::orderBy('created_at', 'desc')->latest();

        if ($project_id) {
            $users = $users->where('project_id', $project_id);
        }

        if ($sort_task_status == "inprogress" || $sort_task_status == "user_completed" || $sort_task_status == "recommend_to_admin" || $sort_task_status == "completed" || $sort_task_status == "rejected" || $sort_task_status == "over_due") {
            $users = $users->where('status', $sort_task_status);
        }

        if ($sort_task_type) {
            $users = $users->where('staff_id', $sort_task_type);
        }

        if ($text_value_search) {
            $users = $users->where(function($q) use ($text_value_search) { 
                       $q->where('staff_name', 'LIKE', "%" . $text_value_search . "%")
                            ->orWhereHas('project_details', function($q) use ($text_value_search) {
                                $q->where('name', 'LIKE', "%" . $text_value_search . "%");
                            })
                            ->orWhereHas('task_details', function($q) use ($text_value_search) {
                                $q->where('task_no', 'LIKE', "%" . $text_value_search . "%")
                                  ->orWhere('description', 'LIKE', "%" . $text_value_search . "%")
                                  ->orWhere('name', 'LIKE', "%" . $text_value_search . "%");
                            });
                        });
        }

        $sub_admin = $users->get();

        $view =  view('sub_admin.task.search_task', compact('sub_admin', 'start_date'))->render();

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

    public function AdminTaskUpdateTime(Request $request)
    {
        // dd($request->all());

        $total_admin_time = $request->admin_time;

        if ($request->submit == "Same User to Admin") {
            if ($total_admin_time) {
                foreach ($total_admin_time as $key => $value) {
                    
                    if ($value['id'] && isset($value['checkbox']) && $value['checkbox'] == "on") {
                        
                        $comments_details = TaskComment::where('id', $value['id'])->first();

                        $task_comments                      = TaskComment::find($value['id']);
                        $task_comments->add_working_hours   = $comments_details->working_hours;
                        $task_comments->save();

                    }

                }
            }
        }

        if ($request->submit == "Upload Admin Hours") {
            if ($total_admin_time) {
                foreach ($total_admin_time as $key => $value) {
                    
                    if ($value['id'] && $value['add_work_time']) {
                        
                        $task_comments                      = TaskComment::find($value['id']);
                        $task_comments->add_working_hours   = number_format($value['add_work_time'], 2);
                        $task_comments->save();

                    }

                }
            }
        }        

        return redirect()->back()->with('success', 'Admin Working Time Updated Successfully!');
    }

}
