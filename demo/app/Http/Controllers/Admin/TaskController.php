<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskDetails;
use App\Models\Notification;
use App\Exports\ExportTask;
use App\Exports\ExportRecurringTask;
use Carbon\Carbon;
use Mail;
use Auth;
use PDF;
use File;
use Excel;
use DB;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function TaskCreate()
    {
        $sub_admin1 = User::where('user_type', '!=', 'super_admin')->get();
        $sub_admin = User::where('user_type', 'staff')->get();
        return view('second_admin.task.add', compact('sub_admin', 'sub_admin1'));
    }

    public function TaskView()
    {

        $sort_by = '';
        $sort_task_status = '';
        if(request('task_type') && request('task_type')!=''){
          $sort_by = request('task_type');
        }
        if(request('task_status') && request('task_status')!=''){
          $sort_task_status = request('task_status');
        }
        // dd($sort_by);

        if ($sort_by == "custom" || $sort_by == "recurring")
        {
            $sub_admin = Task::where('admin_id', Auth::user()->id)->where('task_type', $sort_by)->get();
        }
        else
        {
            $sub_admin = Task::where('admin_id', Auth::user()->id)->get();
        }

        if ($sort_task_status == "inprogress" || $sort_task_status == "user_completed" || $sort_task_status == "recommend_to_admin" || $sort_task_status == "completed" || $sort_task_status == "rejected" || $sort_task_status == "over_due") {
            $sub_admin = $sub_admin->where('status', $sort_task_status);
        }
        else 
        {
            $sub_admin = $sub_admin;
        }
        
        return view('second_admin.task.view', compact('sub_admin', 'sort_by', 'sort_task_status'));
    }

    public function YourTaskView()
    {

        $sort_by = '';
        $sort_task_status = '';
        if(request('task_type') && request('task_type')!=''){
          $sort_by = request('task_type');
        }
        if(request('task_status') && request('task_status')!=''){
          $sort_task_status = request('task_status');
        }
        // dd($sort_by);

        if ($sort_by == "custom" || $sort_by == "recurring")
        {
            $sub_admin = Task::where('staff_id', Auth::user()->id)->where('task_type', $sort_by)->get();
        }
        else
        {
            $sub_admin = Task::where('staff_id', Auth::user()->id)->get();
        }

        if ($sort_task_status == "inprogress" || $sort_task_status == "user_completed" || $sort_task_status == "recommend_to_admin" || $sort_task_status == "completed" || $sort_task_status == "rejected") {
            $sub_admin = $sub_admin->where('status', $sort_task_status);
        }
        else 
        {
            $sub_admin = $sub_admin;
        }

        // $sub_admin = Task::where('staff_id', Auth::user()->id)->get();
        return view('second_admin.your_task.your_task', compact('sub_admin', 'sort_by', 'sort_task_status'));
    }

    public function YourAdminModel(Request $request)
    {
        $task = Task::where('id', $request->product_id)->first();
        $view =  view('second_admin.your_task.rendermodel', compact('task'))->render();
        return response()->json(['html'=>$view]);
    }

    public function TaskRecurringView()
    {
        $sub_admin = Task::where('task_type', 'recurring')->get();
        return view('second_admin.task.recurring', compact('sub_admin'));
    }

    public function TaskEdit($id)
    {
        $task = Task::where('id', $id)->first();
        $staff = User::where('admin_id', $task->admin_id)->get();
        $sub_admin = User::where('user_type', 'sub_admin')->get();
        return view('second_admin.task.edit', compact('sub_admin', 'staff', 'task'));
    }



    public function TaskDelete($id)
    {
        if ($id) {
            $user  = Task::find($id);
            if ($user->delete()) {
                return redirect()->route('second.admin.task.view')->with('error', 'Task Deleted Successfully');
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
        $data['staff'] = User::where('admin_id', Auth::user()->id)->where('user_type', $request->country_id)
                                ->get(["name", "id"]);
  
        return response()->json($data);
    }

    public function TaskUpdate(Request $request, $id)
    {
        dd($request->all());
        
        $request->validate([
            'staff_id'     => 'required|max:255',
            'task_name'    => 'required|max:255',
            'task_type'    => 'required|max:255'
        ]);
        
        if ($request->task_type == "custom") 
        {

            $selected_user = $request->addmore;
            $form_date = (new \Carbon\Carbon($request->start_date))->format('d-m-Y h:i:a') ?? '';
            $to_date   = (new \Carbon\Carbon($request->end_date))->format('d-m-Y') ?? '';

            if ($request->user_type == "sub_admin") {
                $user_details_check = User::where('id', $request->task_sub_admin)->first();
                $task                   = Task::find($id);
                $task->admin_id         = $request->admin_id;
                $task->sub_admin_id     = $request->sub_admin_id;
                $task->staff_id         = $request->task_sub_admin;
                $task->name             = $request->task_name;
                $task->description      = $request->editor1;
                $task->task_amount      = $request->task_amount;
                $task->task_type        = $request->task_type;
                $task->project_id       = $request->project_id;
                $task->start_date       = $request->start_date;
                $task->end_date         = $request->end_date;
                $task->payment_follow_up  = $request->payment_follow_up;
                $task->project_follow_up  = $request->project_follow_up;
                $task->created_at       = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
                $task->status           = "inprogress";

                if ($task->save()) {
                    
                    $key_number     = 'T'.str_pad($task->id, 6, "0", STR_PAD_LEFT);
                    $update_task    = Task::find($task->id);
                    $update_task->task_no  = $key_number;
                    $update_task->save();

                    if ($selected_user) {
                        foreach ($selected_user as $key => $value) {

                            $TaskStaffCheck = TaskStaff::where('task_id', $task->id)->where('staff_id', $value['id'])->first();
                            
                            if (isset($TaskStaffCheck)) {

                                $task_staff             = TaskStaff::find($TaskStaffCheck->id);
                                $task_staff->task_id    = $task->id;
                                $task_staff->staff_id   = $value['id'];
                                $task_staff->staff_name = $value['name'];
                                $task_staff->project_id = $request->project_id;
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
                                $task_staff->project_id = $request->project_id;
                                $task_staff->staff_name = $value['name'];
                                $task_staff->start_date = $value['start_date'];
                                $task_staff->end_date   = $value['end_date'];
                                $task_staff->priority   = $value['priority'];
                                $task_staff->status     = "inprogress";
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

                    
                    return redirect()->back()->with('warning', 'Task Edit Successfully');
                }
                else{
                    return redirect()->back()->with('error', 'Something Wrong');
                }
            }
            else
            {
                $user_details_check     = User::where('id', $request->staff_id)->first();
                $task                   = Task::find($id);
                $task->admin_id         = $request->staff_id;
                $task->sub_admin_id     = $request->sub_admin_id;
                $task->staff_id         = json_encode($request->multiple_staff);
                $task->name             = $request->task_name;
                $task->description      = $request->editor1;
                $task->project_id       = $request->project_id;
                $task->task_amount      = $request->task_amount;
                $task->task_type        = $request->task_type;
                $task->assign_by        = Auth::user()->id;
                $task->start_date       = $request->start_date;
                $task->end_date         = $request->end_date;
                $task->payment_follow_up  = $request->payment_follow_up;
                $task->project_follow_up  = $request->project_follow_up;
                $task->created_at       = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
                $task->status           = "inprogress";

                if ($task->save()) {
                    
                    $key_number     = 'T'.str_pad($task->id, 6, "0", STR_PAD_LEFT);
                    $update_task    = Task::find($task->id);
                    $update_task->task_no  = $key_number;
                    $update_task->save();
                    
                    if ($selected_user) {
                        foreach ($selected_user as $key => $value) {

                            $TaskStaffCheck = TaskStaff::where('task_id', $task->id)->where('staff_id', $value['id'])->first();

                            if (isset($TaskStaffCheck)) {

                                $task_staff             = TaskStaff::find($TaskStaffCheck->id);
                                $task_staff->task_id    = $task->id;
                                $task_staff->staff_id   = $value['id'];
                                $task_staff->project_id = $request->project_id;
                                $task_staff->staff_name = $value['name'];
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
                                $task_staff->project_id = $request->project_id;
                                $task_staff->start_date = $value['start_date'];
                                $task_staff->end_date   = $value['end_date'];
                                $task_staff->priority   = $value['priority'];
                                $task_staff->status     = "inprogress";
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

                    
                    return redirect()->back()->with('warning', 'Task Edit Successfully');
                }
                else{
                    return redirect()->back()->with('error', 'Something Wrong');
                }
            }
        }
        else
        {   
            $form_date = (new \Carbon\Carbon($request->recurring_start_date))->format('d-m-Y') ?? '';
            $date = new \Carbon\Carbon($request->recurring_start_date);
            $date->addDays($request->date_type);
            $to_date = $date->format('d-m-Y');
            // dd($to_date);
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
            $task->payment_follow_up  = $request->payment_follow_up;
            $task->project_follow_up  = $request->project_follow_up;
            $task->assign_by        = Auth::user()->id;
            $task->created_at       = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
            $task->status           = "inprogress";

            if ($task->save()) {
                
                $key_number     = 'T'.str_pad($task->id, 6, "0", STR_PAD_LEFT);
                $update_task    = Task::find($task->id);
                $update_task->task_no  = $key_number;
                $update_task->save();

                // $task_details               = new TaskDetails();
                // $task_details->task_id      = $task->id;
                // $task_details->project_id   = $request->project_id;
                // $task_details->staff_id     = $request->staff_id;
                // $task_details->start_date   = $form_date;
                // $task_details->end_data     = $to_date;
                // $task_details->date_type    = $request->date_type;
                // $task_details->status       = "inprogress";
                // $task_details->points       = "0";
                // if ($task_details->save()) {

                    

                // }

                $task_details          = Task::where('id', $task->id)->first();
                $staff_details         = User::where('id', $request->staff_id)->first();
                $data['name']          = Auth::user()->name;
                $data['email']         = Auth::user()->email;
                $data['logo']          = url('public/admin_assets/images/logo.png');;
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

                return redirect()->back()->with('warning', 'Task Edit Successfully');
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }

    }

    public function TaskStore(Request $request)
    {
        
        
        $request->validate([
            'user_type'     => 'required|max:255',
            'staff_id'     => 'required|max:255',
            'task_name'    => 'required|max:255',
            'task_type'    => 'required|max:255'
        ]);
        // dd($request->all());
        if ($request->task_type == "custom") 
        {

            // $form_date = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
            $form_date = (new \Carbon\Carbon($request->start_date))->format('d-m-Y h:i:a') ?? '';
            $to_date   = (new \Carbon\Carbon($request->end_date))->format('d-m-Y') ?? '';


            $user_details_check = User::where('id', $request->staff_id)->first();
            $task                   = new Task();
            $task->admin_id         = $user_details_check->admin_id;
            $task->sub_admin_id     = $user_details_check->sub_admin_id;
            $task->staff_id         = $request->staff_id;
            $task->project_id       = $request->project_id;
            $task->name             = $request->task_name;
            $task->description      = $request->editor1;
            $task->task_type        = $request->task_type;
            $task->assign_by        = Auth::user()->id;
            $task->start_date       = $form_date;
            $task->end_date         = $to_date;
            $task->payment_follow_up  = $request->payment_follow_up;
            $task->project_follow_up  = $request->project_follow_up;
            $task->created_at       = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
            $task->status           = "inprogress";

            if ($task->save()) {
                
                $key_number     = 'T'.str_pad($task->id, 6, "0", STR_PAD_LEFT);
                $update_task    = Task::find($task->id);
                $update_task->task_no  = $key_number;
                $update_task->save();

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
        else
        {   
            $form_date = (new \Carbon\Carbon($request->recurring_start_date))->format('d-m-Y H:i:s') ?? '';
            $date = new \Carbon\Carbon($request->recurring_start_date);
            $date->addDays($request->date_type);
            $to_date = $date->format('d-m-Y H:i:s');

            // $form_date = (new \Carbon\Carbon($request->recurring_start_date))->format('d-m-Y H:i:s') ?? '';
            // $form_date = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
            // $date = \Carbon\Carbon::now('Asia/Kolkata');
            // $date->addDays($request->date_type);
            // $to_date = $date->format('d-m-Y H:i:s');
            
            $user_details_check = User::where('id', $request->staff_id)->first();
            $task                   = new Task();
            $task->admin_id         = $user_details_check->admin_id;
            $task->sub_admin_id     = $user_details_check->sub_admin_id;
            $task->staff_id         = $request->staff_id;
            $task->project_id       = $request->project_id;
            $task->name             = $request->task_name;
            $task->description      = $request->editor1;
            $task->task_type        = $request->task_type;
            $task->date_type        = $request->date_type;
            $task->assign_by        = Auth::user()->id;
            $task->payment_follow_up  = $request->payment_follow_up;
            $task->project_follow_up  = $request->project_follow_up;
            $task->created_at       = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
            $task->status           = "inprogress";

            if ($task->save()) {

                $key_number     = 'T'.str_pad($task->id, 6, "0", STR_PAD_LEFT);
                $update_task    = Task::find($task->id);
                $update_task->task_no  = $key_number;
                $update_task->save();

                $task_details               = new TaskDetails();
                $task_details->task_id      = $task->id;
                $task_details->staff_id     = $request->staff_id;
                $task_details->project_id   = $request->project_id;
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

                    return redirect()->route('admin.task.view')->with('success', 'Task Created Successfully');

                }
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
        
    }

    public function AdminModel(Request $request)
    {
        $task = Task::where('id', $request->product_id)->first();
        $view =  view('second_admin.task.rendermodel', compact('task'))->render();
        return response()->json(['html'=>$view]);
    }

    public function TaskStatusUpdate($id)
    {

        $task = Task::where('id', $id)->first();
        return view('second_admin.task.status', compact('task'));
    }

    public function TaskStatus(Request $request)
    {
        // dd($request->all());

        $today_date = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
        
        $status = $request->status;
        $task_id = $request->task_id;
        $user_comment1 = $request->user_comment;
        $task = Task::find($task_id);
        if ($request->status == "user_completed") {
            $message_box = $task->task_no.'this task has been Canceled by Admin';
        }
        elseif($request->status == "rejected")
        {
            $message_box = $task->task_no.'this task has been Rejected by Admin';
            $task->user_comments = "";
        }
        elseif($request->status == "completed")
        {
            $message_box = $task->task_no.'this task has been Completed by Admin';
            $date1 = strtotime($today_date);
            $date2 = strtotime($task->end_date);
            $points = 1;
            $user = User::find($task->staff_id);
            if ($date2 >= $date1) {
                $user->points -= $points;
                $task->points = "-1";
            }
            else
            {
                $user->points += $points;
                $task->points = "+1";
            }
            $user->save();
            $task->completed_date = $today_date;
        }
        
        $task->status = $status;
        $task->admin_comments = $user_comment1;
        
        if ($task->save()) {

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

            if ($request->status == "completed") {

                $notification1                = new Notification();
                $notification1->task_id       = $task->id;
                $notification1->receiver_id   = $task->assign_by;
                $notification1->receiver_type = "assign_by";
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
                    Mail::send('mail.task_details', $data, function($message)use($data) {
                        $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                            ->from("noreplywebbitech@gmail.com", 'no-reply')
                        ->subject($data['task_details']['name'].' - task has been Completed');
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
                $message_box = $task->task_no.'this task has been Completed by Admin';
            }

            $notification1                = new Notification();
            $notification1->task_id       = $task->id;
            $notification1->receiver_id   = $task->admin_id;
            $notification1->receiver_type = "admin";
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
            $data['logo']          = "https://manchesters.in/assets/images/mainHome/logo.png";
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
            return redirect()->back()->with('error', 'Something Wrong');
        }


    }

    public function TaskDetailsGet($id)
    {

        $task = TaskDetails::where('id', $id)->first();
        return view('second_admin.task.task_details', compact('task'));
    }

    public function TaskDetailsUpdate(Request $request)
    {
        // dd($request->all());

        $today_date = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
        
        $status             = $request->status;
        $task_details_id    = $request->task_details_id;
        $user_comment1      = $request->user_comment;
        $task_details       = TaskDetails::find($task_details_id);
        // dd($today_date);

        if ($request->status == "canceled") {
            $message_box = $task_details->task_main->task_no.'this task has been Canceled by Admin';
        }
        elseif($request->status == "rejected")
        {
            $message_box = $task_details->task_main->task_no.'this task has been Rejected by Admin';
            $task_details->user_comments = "";
        }
        elseif($request->status == "completed")
        {
            $message_box = $task_details->task_no.'this task has been Closed by Admin';
            $date1 = strtotime($today_date);
            $date2 = strtotime($task_details->end_date);
            $points = 1;
            $user = User::find($task_details->task_main->staff_id);
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
            $task_details->completed_date = $today_date;
        }
        
        $task_details->status = $status;
        $task_details->admin_comments = $user_comment1;
        
        if ($task_details->save()) {

            $notification                = new Notification();
            $notification->task_id       = $task_details->task_main->id;
            $notification->receiver_id   = $task_details->task_main->staff_id;
            $notification->receiver_type = "staff";
            $notification->message       = $message_box;
            $notification->status        = 0;
            $notification->save();

            if ($task_details->task_main->sub_admin_id) {
                $notification1                = new Notification();
                $notification1->task_id       = $task_details->id;
                $notification1->receiver_id   = $task_details->task_main->sub_admin_id;
                $notification1->receiver_type = "sub_admin";
                $notification1->message       = $message_box;
                $notification1->status        = 0;
                $notification1->save();
            }
            

            if($request->status == "completed")
            {
                $notification1                = new Notification();
                $notification1->task_id       = $task_details->id;
                $notification1->receiver_id   = $task_details->task_main->assign_by;
                $notification1->receiver_type = "assign_by";
                $notification1->message       = $message_box;
                $notification1->status        = 0;
                $notification1->save();
            }
            

            if ($request->status == "completed") {
                $task_details          = TaskDetails::where('id', $task_details->id)->first();
                $staff_details         = User::where('id', $task_details->task_main->staff_id)->first();
                $data['name']          = Auth::user()->name;
                $data['email']         = Auth::user()->email;
                $data['logo']          = "https://manchesters.in/assets/images/mainHome/logo.png";
                $data['task_details']  = $task_details;
                $data['staff_details'] = $staff_details;

                try{
                    Mail::send('mail.task_details', $data, function($message)use($data) {
                        $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                            ->from("noreplywebbitech@gmail.com", 'no-reply')
                        ->subject($data['task_details']['name'].' - task has been Completed');
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

    public function SuperAdminDownload (Request $request)
    {
        // dd($request->all());

        $data['sort_task_type']     = $request->sort_task_type;
        $data['sort_task_status']   = $request->sort_task_status;
        $data['txt_search']         = $request->txt_search;
        $data['user_id']            = Auth::user()->id;
        $data['user_type']          = "admin_id";

        $sort_task_type     = $request->sort_task_type;
        $sort_task_status   = $request->sort_task_status;
        $user_name          = $request->txt_search;
        $user_id            = Auth::user()->id;
        $user_type          = "admin_id";
        
        // if ($user_name) {
        $user = User::where('name', 'like', '%'.$user_name.'%')->first();

        $book_details = Task::with(['staff_details']);
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
        // dd($user);
        $book_details           = $book_details->get();
        $data['book_details']   = $book_details;
        $data['user']           = $user;
        $data['logo']           = "https://manchesters.in/assets/images/mainHome/logo.png";

        if ($request->report_type == "mail") {
            if ($user_name) {
                try{
                    Mail::send('mail.report', $data, function($message)use($data) {
                        $message->to("kesavanwebbitech@gmail.com", $data['user']['name'])
                            ->from("noreplywebbitech@gmail.com", 'no-reply')
                        ->subject("Report Sent By Super Admin");
                    });
                    return redirect()->back()->with('success', 'Report Sent Staff Email Address');
                }
                catch(\Exception $e){
                    // dd($e);
                }
            }
            else
            {   
                return redirect()->back()->with('error', 'Please type Staff Name');
            }
            
        }
        elseif($request->report_type == "whatsapp")
        {
            return redirect()->back()->with('error', 'No Data Available');
        }
        else
        {

            if (count($book_details)) {
                return Excel::download(new ExportTask($data), 'Single Task.xlsx');
            }
            else
            {   
                return redirect()->back()->with('error', 'No Data Available');
            }
        }
        // }
        // else
        // {   
        //     return redirect()->back()->with('error', 'Please type Staff Name');
        // }

    }

    public function YourTaskDetailsView($id)
    {
        $task = TaskDetails::where('id', $id)->first();
        return view('second_admin.your_task.task_details', compact('task'));
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

        $task_details       = TaskDetails::find($task_details_id);
        $task_details->status = $status;
        $task_details->sub_admin_comment = $user_comment1;
        
        if ($task_details->save()) {


            if($request->status == "user_completed")
            {
                $message_cmd = $task_details->task_main->task_no. ' has been Completed by Sub Admin';
                $notification                = new Notification();
                $notification->task_id       = $task_details->task_main->id;
                $notification->receiver_id   = $task_details->task_main->admin_id;
                $notification->receiver_type = "super_admin";
                $notification->message       = $message_cmd;
                $notification->status        = 0;
                $notification->save();
            }

            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function AdminTaskCheck(Request $request)
    {
        
    }

}
