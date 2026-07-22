<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskDetails;
use App\Models\TaskComment;
use App\Models\TaskStaff;
use App\Models\Project;
use App\Models\TaskFollowComment;
use App\Models\PaymentComment;
use Auth;
use Mail;
use App\Models\Notification;

class StaffTaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function TaskCreate()
    {
        $projects = Project::latest()->get();
        return view('staff.task.add', compact('projects'));
    }

    public function TaskStore(Request $request)
    {
        // dd($request->all());

        $date_details = explode('-', $request->date_range_task);
        // dd($date_details);

        $start_date = $date_details[0];
        $end_date = $date_details[1];

        $form_date = (new \Carbon\Carbon($start_date))->format('Y-m-d') ?? '';
        $to_date   = (new \Carbon\Carbon($end_date))->format('Y-m-d') ?? '';
        
        $user_details_check = User::where('id', Auth::user()->id)->first();
        // dd($user_details_check);

        $task                   = new Task();
        $task->admin_id         = $user_details_check->admin_id;
        $task->sub_admin_id     = $user_details_check->sub_admin_id;
        $task->staff_id         = json_encode(Auth::user()->id);
        $task->name             = $request->task_name;
        $task->description      = $request->editor1;
        $task->project_id       = $request->project_id;
        $task->task_type        = "custom";
        $task->assign_by        = Auth::user()->id;
        $task->start_date       = $form_date;
        $task->end_date         = $to_date;
        $task->created_at       = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
        $task->status           = "inprogress";

        if ($task->save()) {

            $key_number     = 'T'.str_pad($task->id, 6, "0", STR_PAD_LEFT);
            $update_task    = Task::find($task->id);
            $update_task->task_no  = $key_number;
            $update_task->save();

            $task_staff             = new TaskStaff();
            $task_staff->task_id    = $task->id;
            $task_staff->staff_id   = Auth::user()->id;
            $task_staff->project_id = $request->project_id;
            $task_staff->staff_name = Auth::user()->name;
            $task_staff->start_date = $form_date;
            $task_staff->end_date   = $to_date;
            $task_staff->priority   = $request->priority;
            $task_staff->created_id = Auth::user()->id;
            $task_staff->status     = "inprogress";
            $task_staff->save();

            $message_box = Auth::user()->name . ' Create a New Task - ' . $request->task_name;
            $notification                = new Notification();
            $notification->task_id       = $task->id;
            $notification->receiver_id   = $user_details_check->admin_id;
            $notification->receiver_type = "super_admin";
            $notification->message       = $message_box;
            $notification->status        = 0;
            $notification->save();

            $message_box = Auth::user()->name . ' Create a New Task - ' . $request->task_name;
            $notification                = new Notification();
            $notification->task_id       = $task->id;
            $notification->receiver_id   = $user_details_check->sub_admin_id;
            $notification->receiver_type = "sub_admin";
            $notification->message       = $message_box;
            $notification->status        = 0;
            $notification->save();

            return redirect()->route('staff.progress')->with('success', 'Task Created Successfully');

        }
    }

    public function Pending()
    {
        // 'bookconditions', 'like', '%'.$book_condition.'%'
        $sub_admin = TaskStaff::where('staff_id', Auth::user()->id)->where('status', 'pending')->get();
        return view('staff.task.view', compact('sub_admin'));
    }

    public function Progress()
    {
        $sub_admin = TaskStaff::where('staff_id', Auth::user()->id)->where('status', 'inprogress')->get();
        // dd($sub_admin);
        return view('staff.task.view', compact('sub_admin'));
    }

    public function Completed()
    {
        $sub_admin = TaskStaff::where('staff_id', Auth::user()->id)->where('status', 'user_completed')->get();
        return view('staff.task.view', compact('sub_admin'));
    }

    public function Closed()
    {
        $sub_admin = TaskStaff::where('staff_id', Auth::user()->id)->where('status', 'completed')->get();
        return view('staff.task.view', compact('sub_admin'));
    }

    public function Rejected()
    {
        $sub_admin = TaskStaff::where('staff_id', Auth::user()->id)->where('status', 'rejected')->get();
        return view('staff.task.view', compact('sub_admin'));
    }
    public function OverDue()
    {
        $sub_admin = TaskStaff::where('staff_id', Auth::user()->id)->where('status', 'over_due')->get();
        return view('staff.task.view', compact('sub_admin'));
    }
    public function Recurring()
    {
        $sub_admin = Task::where('staff_id', Auth::user()->id)->where('task_type', 'recurring')->get();
        return view('staff.task.recurring', compact('sub_admin'));
    }

    public function ViewTask($id)
    {
        $task = Task::where('id', $id)->first();
        $task_staff = TaskStaff::where('task_id', $id)->where('staff_id', Auth::user()->id)->first();
        $task_comments = TaskComment::where('task_id', $id)->where('staff_id', Auth::user()->id)->get();
        return view('staff.task.task_details', compact('task', 'task_comments', 'task_staff'));
    }

    public function ViewRecurTask($id)
    {
        $task = Task::where('id', $id)->first();
        $task_staff = TaskDetails::where('task_id', $id)->where('staff_id', Auth::user()->id)->get();
        return view('staff.task.recur_details', compact('task', 'task_staff'));
    }

    public function StaffModel(Request $request)
    {
        $task = TaskDetails::where('id', $request->product_id)->first();
        // dd($task);
        $view =  view('staff.task.rendermodel', compact('task'))->render();
        return response()->json(['html'=>$view]);
    }

    public function TaskStatusUpdate(Request $request)
    {
        // dd($request->all());

        if ($request->submit == "Submit") {

            $status = $request->status;
            $task_id = $request->task_id;
            $user_comment1 = $request->user_comment;
            $task = TaskDetails::find($task_id);
            $task->status = $status;
            $task->user_comments = $user_comment1;
            if ($task->save()) {

                if ($status == "user_completed") {
                    $message_cmd = Auth::user()->name. ' has been Completed by this '.$task->task_no.' task';
                }
                $notification                = new Notification();
                $notification->task_id       = $task->id;
                $notification->receiver_id   = $task->sub_admin_id;
                $notification->receiver_type = "sub_admin";
                $notification->message       = $message_cmd;
                $notification->status        = 0;
                $notification->save();

                if($task->sub_admin_id != $task->assign_by)
                {
                    $notification                = new Notification();
                    $notification->task_id       = $task->id;
                    $notification->receiver_id   = $task->assign_by;
                    $notification->receiver_type = "admin";
                    $notification->message       = $message_cmd;
                    $notification->status        = 0;
                    $notification->save();
                }
                
                
                return redirect()->back()->with('success', 'Task Status Updated Successfully!');
            }
            else
            {
                return redirect()->back()->with('success', 'Task Status Updated Successfully!');
            }
        }
        else
        {
            $status = $request->status_value;
            $task_id = $request->task_id;

            $task = TaskDetails::find($task_id);
            $task->status = $status;
            if ($task->save()) {
                session()->flash('success', 'Task Status Updated Successfully!');
                return true;
            }
            else
            {
                session()->flash('error', 'Something Wrong');
                return true;
            }
        }
    }

    public function TaskDetailsGet($id)
    {

        $task = TaskDetails::where('id', $id)->first();
        return view('staff.task.details', compact('task'));
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
        $task_details->user_comments = $user_comment1;

        if ($task_details->save()) {
            if($request->status == "user_completed")
            {
                $message_box = $task_details->task_main->task_no. ' has been Completed by Staff';
            }
            
            $notification                = new Notification();
            $notification->task_id       = $task_details->id;
            $notification->receiver_id   = $task_details->task_main->sub_admin_id;
            $notification->receiver_type = "sub_admin";
            $notification->message       = $message_box;
            $notification->status        = 0;
            $notification->save();
            
            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
        }
        else
        {
            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
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
        $task_comments->working_hours = number_format($request->working_hours, 2);
        $task_comments->user_comment  = $request->user_comment;
        if ($task_comments->save()) {
            return redirect()->back()->with('success', 'Task Comments Add Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function TaskDetailsTimeUpdate(Request $request)
    {
        // dd($request->all());
        $task_comments                = TaskComment::find($request->task_comment_id);
        $task_comments->working_hours = number_format($request->working_hours_1, 2);
        $task_comments->user_comment  = $request->user_comment;
        $task_comments->start_date  = $request->start_date;
        $task_comments->end_date  = $request->end_date;

        if ($task_comments->save()) {
            // dd($task_comments);
            return redirect()->back()->with('success', 'Task Comments Updated Successfully!');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function ChangeStatusTask($id)
    {
        // dd($id);
        $task_staff     = TaskStaff::find($id);
        $task_staff->status = "request_to_coordinar_completed";
        $status1 = "Request to Completed";
        if ($task_staff->save()) {

            $task_details                = Task::where('id', $task_staff->task_id)->first();
            $message_cmd                 = Auth::user()->name. ' Updated by Task no - '. $task_details->task_no;
            $notification                = new Notification();
            $notification->task_id       = $task_details->id;
            $notification->receiver_id   = $task_details->project_follow_up;
            $notification->receiver_type = "staff";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();

            $staff_details           = User::where('id', $task_details->project_follow_up)->first();
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

            // if ($task_details->assign_by != $task_details->sub_admin_id) {
            //     $notification                = new Notification();
            //     $notification->task_id       = $task_details->id;
            //     $notification->receiver_id   = $task_details->assign_by;
            //     $notification->receiver_type = "admin";
            //     $notification->message       = $message_cmd;
            //     $notification->status        = 0;
            //     $notification->save();

            //     $staff_details1           = User::where('id', $task_details->assign_by)->first();
            //     $data1['name']            = Auth::user()->name;
            //     $data1['email']           = Auth::user()->email;
            //     $data1['logo']            = url('public/admin_assets/images/logo.png');
            //     $data1['task_details']    = $task_details;
            //     $data1['staff_details']   = $staff_details1;
            //     $data1['status1']         = $status1;
            //     if ($data1) {
            //         try{
            //             Mail::send('mail.staff_closed', $data1, function($message)use($data1) {
            //                 $message->to($data1['staff_details']['email'], $data1['staff_details']['name'])
            //                     ->from("noreplywebbitech@gmail.com", 'no-reply')
            //                 ->subject(Auth::user()->name. ' has been Request to Completed '. $data1['task_details']['name']);
            //             });
            //         }
            //         catch(\Exception $e){
                        
            //         }
            //     }
                
            // }
            
            

            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
        }
        return redirect()->back()->with('error', 'Something Wrong');
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

    public function CommentDelete($id)
    {

        $task_delete = TaskComment::find($id);
        $task_delete->delete();
        if ($task_delete) {
            return redirect()->back()->with('error', 'Deleted');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function FollowUpProject()
    {
        $sub_admin = Task::where('project_follow_up', Auth::user()->id)
                        ->orWhere('payment_follow_up', Auth::user()->id)
                        ->get();
        return view('staff.task.follow_up', compact('sub_admin'));
    }

    public function FollowUpProjectDetails($id)
    {

        $task = Task::where('id', $id)->first();
        return view('staff.task.status', compact('task'));
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

}
