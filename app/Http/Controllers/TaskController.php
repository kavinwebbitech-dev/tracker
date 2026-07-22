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
use App\Models\SimpleTask;
use App\Exports\ExportRecurringTask;
use Carbon\Carbon;
use App\Models\ClientTask;
use App\Models\ClientTaskDetails;
use App\Models\ClientTaskComment;
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

    public function TaskCreate(Request $request)
    {
        $sub_admin = User::where('user_type', 'sub_admin')->where('status', 'active')->get();
        $projects = Project::latest()->get();
        $sub_admin1 = User::where('user_type', '!=', 'super_admin')->where('status', 'active')->get();
        $staff_details = User::where('user_type', 'staff')
            ->where('status', 'active')
            ->get();
        $simple_task_id     = $request->query('simple_task_id');
        $prefill_task_name  = $request->query('task_name');
        $prefill_start_date = $request->query('start_date')
            ? \Carbon\Carbon::parse($request->query('start_date'))->format('Y-m-d')
            : null;
        $prefill_end_date   = $request->query('end_date')
            ? \Carbon\Carbon::parse($request->query('end_date'))->format('Y-m-d')
            : null;
        // $flow_up_user = User::where('user_type', 'staff')->get();
        return view('admin.task.add', compact(
            'sub_admin',
            'sub_admin1',
            'projects',
            'staff_details',
            'simple_task_id',
            'prefill_task_name',
            'prefill_start_date',
            'prefill_end_date'
        ));
    }

    public function TaskView()
    {
        $sort_by = '';
        $sort_task_status = '';
        $project_id = '';
        $start_date = '';
        $ex_user_detail = "";

        if (request('task_type') && request('task_type') != '') {
            $sort_by = request('task_type');
        }
        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
        }
        if (request('task_status') && request('task_status') != '') {
            $sort_task_status = request('task_status');
        }
        if (request('project_id') && request('project_id') != '') {
            $project_id = request('project_id');
        }
        if (request('ex_user_details') && request('ex_user_details') != '') {
            $ex_user_detail = request('ex_user_details');
        }

        if ($sort_by) {
            $sub_admin = TaskStaff::with(['task_comments'])->where('staff_id', $sort_by)->latest();
        } else {
            $sub_admin = TaskStaff::latest();
        }

        if ($ex_user_detail) {
            $sub_admin = $sub_admin->where('staff_id', $ex_user_detail);
        } else {
            $sub_admin = $sub_admin;
        }

        if ($sort_task_status == "inprogress" || $sort_task_status == "user_completed" || $sort_task_status == "recommend_to_admin" || $sort_task_status == "completed" || $sort_task_status == "rejected" || $sort_task_status == "over_due") {
            $sub_admin = $sub_admin->where('status', $sort_task_status);
        }

        $project_id = explode(',', $project_id);

        if ($project_id[0]) {
            $sub_admin = $sub_admin->whereIn('project_id', $project_id);
        }

        if ($start_date) {
            // Ensure start_date is formatted correctly if it contains a time component
            $start_date = \Carbon\Carbon::parse($start_date)->format('Y-m-d');

            $sub_admin = $sub_admin->whereHas('task_comments', function ($query) use ($start_date) {
                $query->where('start_date', 'like', '%' . $start_date . '%');  // Compare only the date part of start_date
            });
        }

        $sub_admin = $sub_admin->paginate(100);


        // print_r($rawQuery); exit();

        $projects = Project::orderBy('date_created', 'desc')->get();
        $user_details = User::where('user_type', 'staff')->where('status', 'active')->get();
        $ex_user_details = User::where('user_type', 'staff')->where('status', 'inactive')->get();
        // dd($ex_user_details);
        return view('admin.task.view', compact('sub_admin', 'sort_by', 'sort_task_status', 'user_details', 'projects', 'project_id', 'start_date', 'ex_user_details', 'ex_user_detail'));
    }

    public function TaskRecurringView()
    {
        $sub_admin = Task::where('task_type', 'recurring')->get();
        return view('admin.task.recurring', compact('sub_admin'));
    }


    // public function AdminStaffGet(Request $request)
    // {
    //     $user_type = $request->user_type;
    //     $sub_admin1 = User::where('user_type', $user_type)->where('status', 'active')->get(['id', 'name', 'role']);

    //     $html = '';
    //     foreach ($sub_admin1 as $row) {
    //         // Store role as data attribute on each option
    //         $html .= '<option value="' . $row->id . '" data-role="' . $row->role . '">' . $row->name . '</option>';
    //     }

    //     return $html;
    // }

    public function AdminStaffGet(Request $request)
    {
        $user_type = $request->user_type;
        $sub_admin1 = User::where('user_type', $user_type)->where('status', 'active')->get(['id', 'name', 'role', 'sub_admin_id']);

        $html = '';
        foreach ($sub_admin1 as $row) {
            // Store role + sub_admin_id as data attributes on each option
            $html .= '<option value="' . $row->id . '" data-role="' . $row->role . '" data-subadmin="' . $row->sub_admin_id . '">' . $row->name . '</option>';
        }

        return $html;
    }

    public function TaskEdit($id)
    {
        $task = Task::where('id', $id)->first();
        $projects = Project::get();
        if ($task->sub_admin_id) {
            $staff = User::where('sub_admin_id', $task->sub_admin_id)->get();
        } else {
            $staff = User::where('user_type', 'staff')
                ->where('status', 'active')
                ->get();
        }
        $sub_admin1 = User::where('user_type', '!=', 'super_admin')->where('status', 'active')->get();
        $sub_admin = User::where('user_type', 'sub_admin')->where('status', 'active')->get();
        return view('admin.task.edit', compact('sub_admin', 'staff', 'task', 'projects', 'sub_admin1'));
    }

    public function TaskDelete($id)
    {
        if ($id) {
            $user  = Task::find($id);
            if ($user->delete()) {
                return redirect()->back()->with('error', 'Task Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function TaskSingleDelete($id)
    {
        if ($id) {
            $user  = TaskStaff::find($id);
            if ($user->delete()) {
                return redirect()->back()->with('error', 'Task Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function fetchStaff(Request $request)
    {
        $data = User::where('user_type', $request->country_id)->get(["name", "id"]);

        $html = '';

        foreach ($data as $row) {
            $html .= '<option value="' . $row->id . '">' . $row->name . '</option>';
        }


        return $html;
    }

    public function TaskStore(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'task_name'    => 'required|max:255',
            'task_type'    => 'required|max:255'
        ]);

        if ($request->task_type == "custom") {

            $selected_user = $request->addmore;
            $form_date = (new \Carbon\Carbon($request->start_date))->format('d-m-Y') ?? '';
            $to_date   = (new \Carbon\Carbon($request->end_date))->format('d-m-Y') ?? '';

            if ($request->user_type == "sub_admin") {
                $user_details_check = User::where('id', $request->task_sub_admin)->first();
                $task                   = new Task();
                $task->admin_id         = Auth::user()->id;
                $task->sub_admin_id     = $request->sub_admin_id;
                $task->staff_id         = $request->task_sub_admin;
                $task->name             = $request->task_name;
                $task->description      = $request->editor1;
                $task->project_id       = $request->project_id;
                $task->task_amount      = $request->task_amount;
                $task->task_type        = $request->task_type;
                $task->assign_by        = Auth::user()->id;
                $task->start_date       = $request->start_date;
                $task->end_date         = $request->end_date;
                $task->project_follow_up  = $request->project_follow_up;
                $task->payment_follow_up  = $request->payment_follow_up;
                $task->created_at       = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
                $task->status           = "inprogress";

                if ($task->save()) {

                    $key_number     = 'T' . str_pad($task->id, 6, "0", STR_PAD_LEFT);
                    $update_task    = Task::find($task->id);
                    $update_task->task_no  = $key_number;
                    $update_task->save();

                    if ($selected_user) {
                        foreach ($selected_user as $key => $value) {
                            $task_staff             = new TaskStaff();
                            $task_staff->task_id    = $task->id;
                            $task_staff->staff_id   = $value['id'];
                            $task_staff->staff_name = $value['name'];
                            $task_staff->project_id = $request->project_id;
                            $task_staff->start_date = $value['start_date'];
                            $task_staff->end_date   = $value['end_date'];
                            $task_staff->priority   = $value['priority'];
                            $task_staff->assigned_frontend_hours = $value['assigned_frontend_hours'] ?? 0;
                            $task_staff->assigned_backend_hours  = $value['assigned_backend_hours'] ?? 0;
                            $task_staff->assigned_seo_hours      = $value['assigned_seo_hours'] ?? 0;
                            $task_staff->assigned_testing_hours  = $value['assigned_testing_hours'] ?? 0;
                            $task_staff->assigned_designer_hours = $value['assigned_designer_hours'] ?? 0;
                            $task_staff->created_id = Auth::user()->id;
                            $task_staff->status     = "inprogress";
                            $task_staff->save();

                            $message_box = "Super Admin Assign New Task";
                            $notification                = new Notification();
                            $notification->task_id       = $task->id;
                            $notification->receiver_id   = $task_staff->staff_id;
                            $notification->receiver_type = "staff";
                            $notification->message       = $message_box;
                            $notification->status        = 0;
                            $notification->save();

                            $task_details          = Task::where('id', $task->id)->first();
                            $staff_details         = User::where('id', $task_staff->staff_id)->first();
                            $data['name']          = Auth::user()->name;
                            $data['email']         = Auth::user()->email;
                            $data['logo']          = url('public/admin_assets/images/logo.png');
                            $data['task_details']  = $task_details;
                            $data['staff_details'] = $staff_details;
                            $data['start_date']    = $value['start_date'];
                            $data['end_date']      = $value['end_date'];

                            try {
                                Mail::send('mail.staff_task', $data, function ($message) use ($data) {
                                    $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                                        ->subject(Auth::user()->name . '- Assign New Task');
                                });
                            } catch (\Exception $e) {
                                // dd($e);
                            }
                        }
                    }

                    if ($request->payment_follow_up != "") {
                        $message_box = "Super Admin Assign New Task - Payment Follow Up";
                        $notification                = new Notification();
                        $notification->task_id       = $task->id;
                        $notification->receiver_id   = $request->payment_follow_up;
                        $notification->receiver_type = "staff";
                        $notification->message       = $message_box;
                        $notification->status        = 0;
                        $notification->save();
                    }

                    if ($request->project_follow_up != "") {
                        $message_box = "Super Admin Assign New Task - Project Follow Up";
                        $notification                = new Notification();
                        $notification->task_id       = $task->id;
                        $notification->receiver_id   = $request->project_follow_up;
                        $notification->receiver_type = "staff";
                        $notification->message       = $message_box;
                        $notification->status        = 0;
                        $notification->save();
                    }
                    $this->removeSimpleTaskIfPresent($request);

                    return redirect()->back()->with('success', 'Task Created Successfully');
                } else {
                    return redirect()->back()->with('error', 'Something Wrong');
                }
            } else {
                $user_details_check = User::where('id', $request->staff_id)->first();
                $task                   = new Task();
                $task->admin_id         = $request->staff_id;
                $task->sub_admin_id     = $request->sub_admin_id;
                $task->staff_id         = json_encode($request->multiple_staff);
                $task->name             = $request->task_name;
                $task->description      = $request->editor1;
                $task->project_id       = $request->project_id;
                $task->task_amount      = $request->task_amount;
                $task->task_type        = $request->task_type;
                $task->assign_by        = Auth::user()->id;
                $task->task_type        = $request->task_type;
                $task->date_type        = $request->date_type;
                $task->payment_follow_up  = $request->payment_follow_up;
                $task->project_follow_up  = $request->project_follow_up;
                $task->created_at       = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
                $task->status           = "inprogress";

                if ($task->save()) {

                    $key_number     = 'T' . str_pad($task->id, 6, "0", STR_PAD_LEFT);
                    $update_task    = Task::find($task->id);
                    $update_task->task_no  = $key_number;
                    $update_task->save();

                    if ($selected_user) {
                        foreach ($selected_user as $key => $value) {
                            $task_staff             = new TaskStaff();
                            $task_staff->task_id    = $task->id;
                            $task_staff->staff_id   = $value['id'];
                            $task_staff->staff_name = $value['name'];
                            $task_staff->project_id = $request->project_id;
                            $task_staff->start_date = $value['start_date'];
                            $task_staff->end_date   = $value['end_date'];
                            $task_staff->priority   = $value['priority'];
                            $task_staff->assigned_frontend_hours = $value['assigned_frontend_hours'] ?? 0;
                            $task_staff->assigned_backend_hours  = $value['assigned_backend_hours'] ?? 0;
                            $task_staff->assigned_seo_hours      = $value['assigned_seo_hours'] ?? 0;
                            $task_staff->assigned_testing_hours  = $value['assigned_testing_hours'] ?? 0;
                            $task_staff->assigned_designer_hours = $value['assigned_designer_hours'] ?? 0;
                            $task_staff->created_id = Auth::user()->id;
                            $task_staff->status     = "inprogress";
                            $task_staff->save();

                            $message_box = "Super Admin Assign New Task";
                            $notification                = new Notification();
                            $notification->task_id       = $task->id;
                            $notification->receiver_id   = $value['id'];
                            $notification->receiver_type = "staff";
                            $notification->message       = $message_box;
                            $notification->status        = 0;
                            $notification->save();

                            $task_details          = Task::where('id', $task->id)->first();
                            $staff_details         = User::where('id', $value['id'])->first();
                            $data['name']          = Auth::user()->name;
                            $data['email']         = Auth::user()->email;
                            $data['logo']          = url('public/admin_assets/images/logo.png');
                            $data['task_details']  = $task_details;
                            $data['staff_details'] = $staff_details;
                            $data['start_date']    = $value['start_date'];
                            $data['end_date']      = $value['end_date'];

                            try {
                                Mail::send('mail.staff_task', $data, function ($message) use ($data) {
                                    $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                                        ->subject(Auth::user()->name . '- Assign New Task');
                                });
                            } catch (\Exception $e) {
                                // dd($e);
                            }
                        }
                    }

                    if ($request->payment_follow_up != "") {
                        $message_box = "Super Admin Assign New Task - Payment Follow Up";
                        $notification                = new Notification();
                        $notification->task_id       = $task->id;
                        $notification->receiver_id   = $request->payment_follow_up;
                        $notification->receiver_type = "staff";
                        $notification->message       = $message_box;
                        $notification->status        = 0;
                        $notification->save();
                    }

                    if ($request->project_follow_up != "") {
                        $message_box = "Super Admin Assign New Task - Project Follow Up";
                        $notification                = new Notification();
                        $notification->task_id       = $task->id;
                        $notification->receiver_id   = $request->project_follow_up;
                        $notification->receiver_type = "staff";
                        $notification->message       = $message_box;
                        $notification->status        = 0;
                        $notification->save();
                    }
                     $this->removeSimpleTaskIfPresent($request);
                    return redirect()->back()->with('success', 'Task Created Successfully');
                } else {
                    return redirect()->back()->with('error', 'Something Wrong');
                }
            }
        } else {

            $form_date = (new \Carbon\Carbon($request->recurring_start_date))->format('d-m-Y H:i:s') ?? '';
            $date = new \Carbon\Carbon($request->recurring_start_date);
            $date->addDays($request->date_type);
            $to_date = $date->format('d-m-Y H:i:s');


            $user_details_check = User::where('id', $request->staff_id_1)->first();
            $task                   = new Task();
            $task->admin_id         = $user_details_check->admin_id;
            $task->sub_admin_id     = $user_details_check->sub_admin_id;
            $task->staff_id         = $request->staff_id_1;
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

                $key_number     = 'T' . str_pad($task->id, 6, "0", STR_PAD_LEFT);
                $update_task    = Task::find($task->id);
                $update_task->task_no  = $key_number;
                $update_task->save();

                $task_details               = new TaskDetails();
                $task_details->task_id      = $task->id;
                $task_details->staff_id     = $request->staff_id_1;
                $task_details->project_id   = $request->project_id;
                $task_details->start_date   = $form_date;
                $task_details->end_data     = $to_date;
                $task_details->date_type    = $request->date_type;
                $task_details->status       = "inprogress";
                $task_details->points       = "0";
                if ($task_details->save()) {

                    $task_details          = Task::where('id', $task->id)->first();
                    $staff_details         = User::where('id', $request->staff_id_1)->first();
                    $data['name']          = Auth::user()->name;
                    $data['email']         = Auth::user()->email;
                    $data['logo']          = url('public/admin_assets/images/logo.png');
                    $data['task_details']  = $task_details;
                    $data['staff_details'] = $staff_details;

                    try {
                        Mail::send('mail.task_details', $data, function ($message) use ($data) {
                            $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                                ->from("noreplywebbitech@gmail.com", 'no-reply')
                                ->subject('Admin Assign New Task');
                        });
                    } catch (\Exception $e) {
                        // dd($e);
                    }
                     $this->removeSimpleTaskIfPresent($request);
                    return redirect()->back()->with('success', 'Task Created Successfully');
                }
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
    }

    public function TaskUpdate(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            'staff_id'     => 'required|max:255',
            'task_name'    => 'required|max:255',
            'task_type'    => 'required|max:255'
        ]);

        if ($request->task_type == "custom") {

            $selected_user = $request->addmore;
            $form_date = (new \Carbon\Carbon($request->start_date))->format('d-m-Y h:i:a') ?? '';
            $to_date   = (new \Carbon\Carbon($request->end_date))->format('d-m-Y') ?? '';

            if ($request->user_type == "sub_admin") {
                $user_details_check = User::where('id', $request->task_sub_admin)->first();
                $task                   = Task::find($id);
                $task->admin_id         = Auth::user()->id;
                $task->sub_admin_id     = $request->sub_admin_id;
                $task->staff_id         = $request->task_sub_admin;
                $task->name             = $request->task_name;
                $task->description      = $request->editor1;
                $task->task_amount      = $request->task_amount;
                $task->task_type        = $request->task_type;
                $task->project_id       = $request->project_id;
                $task->assign_by        = Auth::user()->id;
                $task->start_date       = $request->start_date;
                $task->end_date         = $request->end_date;
                $task->payment_follow_up  = $request->payment_follow_up;
                $task->project_follow_up  = $request->project_follow_up;
                $task->created_at       = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
                $task->status           = "inprogress";

                if ($task->save()) {

                    $key_number     = 'T' . str_pad($task->id, 6, "0", STR_PAD_LEFT);
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
                                $task_staff->assigned_frontend_hours = $value['assigned_frontend_hours'] ?? 0;
                                $task_staff->assigned_backend_hours  = $value['assigned_backend_hours'] ?? 0;
                                $task_staff->assigned_seo_hours      = $value['assigned_seo_hours'] ?? 0;
                                $task_staff->assigned_testing_hours  = $value['assigned_testing_hours'] ?? 0;
                                $task_staff->assigned_designer_hours = $value['assigned_designer_hours'] ?? 0;
                                $task_staff->status     = "inprogress";
                                $task_staff->save();
                            } else {
                                $task_staff             = new TaskStaff();
                                $task_staff->task_id    = $task->id;
                                $task_staff->staff_id   = $value['id'];
                                $task_staff->project_id = $request->project_id;
                                $task_staff->staff_name = $value['name'];
                                $task_staff->start_date = $value['start_date'];
                                $task_staff->end_date   = $value['end_date'];
                                $task_staff->priority   = $value['priority'];
                                $task_staff->assigned_frontend_hours = $value['assigned_frontend_hours'] ?? 0;
                                $task_staff->assigned_backend_hours  = $value['assigned_backend_hours'] ?? 0;
                                $task_staff->assigned_seo_hours      = $value['assigned_seo_hours'] ?? 0;
                                $task_staff->assigned_testing_hours  = $value['assigned_testing_hours'] ?? 0;
                                $task_staff->assigned_designer_hours = $value['assigned_designer_hours'] ?? 0;
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

                                try {
                                    Mail::send('mail.staff_task', $data, function ($message) use ($data) {
                                        $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                                            ->from("noreplywebbitech@gmail.com", 'no-reply')
                                            ->subject(Auth::user()->name . '- Assign New Task');
                                    });
                                } catch (\Exception $e) {
                                    // dd($e);
                                }
                            }
                        }
                    }

                    // $this->removeSimpleTaskIfPresent($request);
                    return redirect()->back()->with('warning', 'Task Edited Successfully');
                } else {
                    return redirect()->back()->with('error', 'Something Wrong');
                }
            } else {
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

                    $key_number     = 'T' . str_pad($task->id, 6, "0", STR_PAD_LEFT);
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
                                $task_staff->assigned_frontend_hours = $value['assigned_frontend_hours'] ?? 0;
                                $task_staff->assigned_backend_hours  = $value['assigned_backend_hours'] ?? 0;
                                $task_staff->assigned_seo_hours      = $value['assigned_seo_hours'] ?? 0;
                                $task_staff->assigned_testing_hours  = $value['assigned_testing_hours'] ?? 0;
                                $task_staff->assigned_designer_hours = $value['assigned_designer_hours'] ?? 0;
                                $task_staff->status     = "inprogress";
                                $task_staff->save();
                            } else {
                                $task_staff             = new TaskStaff();
                                $task_staff->task_id    = $task->id;
                                $task_staff->staff_id   = $value['id'];
                                $task_staff->staff_name = $value['name'];
                                $task_staff->project_id = $request->project_id;
                                $task_staff->start_date = $value['start_date'];
                                $task_staff->end_date   = $value['end_date'];
                                $task_staff->priority   = $value['priority'];
                                $task_staff->assigned_frontend_hours = $value['assigned_frontend_hours'] ?? 0;
                                $task_staff->assigned_backend_hours  = $value['assigned_backend_hours'] ?? 0;
                                $task_staff->assigned_seo_hours      = $value['assigned_seo_hours'] ?? 0;
                                $task_staff->assigned_testing_hours  = $value['assigned_testing_hours'] ?? 0;
                                $task_staff->assigned_designer_hours = $value['assigned_designer_hours'] ?? 0;
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

                                try {
                                    Mail::send('mail.staff_task', $data, function ($message) use ($data) {
                                        $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                                            ->from("noreplywebbitech@gmail.com", 'no-reply')
                                            ->subject(Auth::user()->name . '- Assign New Task');
                                    });
                                } catch (\Exception $e) {
                                    // dd($e);
                                }
                            }
                        }
                    }


                    return redirect()->back()->with('warning', 'Task Edited Successfully');
                } else {
                    return redirect()->back()->with('error', 'Something Wrong');
                }
            }
        } else {
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

                $key_number     = 'T' . str_pad($task->id, 6, "0", STR_PAD_LEFT);
                $update_task    = Task::find($task->id);
                $update_task->task_no  = $key_number;
                $update_task->save();

                $task_details               = new TaskDetails();
                $task_details->task_id      = $task->id;
                $task_details->project_id   = $request->project_id;
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
                    $data['logo']          = url('public/admin_assets/images/logo.png');;
                    $data['task_details']  = $task_details;
                    $data['staff_details'] = $staff_details;

                    try {
                        Mail::send('mail.task_details', $data, function ($message) use ($data) {
                            $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                                ->from("noreplywebbitech@gmail.com", 'no-reply')
                                ->subject('Admin Assign New Task');
                        });
                    } catch (\Exception $e) {
                        // dd($e);
                    }

                    return redirect()->back()->with('warning', 'Task Edited Successfully');
                }
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
    }

    private function removeSimpleTaskIfPresent(Request $request): void
    {
        if ($request->filled('simple_task_id')) {
            SimpleTask::where('id', $request->simple_task_id)->delete();
        }
    }

    public function TaskStatusUpdate($id)
    {
        $task = Task::where('id', $id)->first();
        return view('admin.task.status', compact('task'));
    }

    public function SuperAdminModel(Request $request)
    {
        $task = TaskDetails::where('id', $request->product_id)->first();
        $view =  view('admin.task.modelrender', compact('task'))->render();
        return response()->json(['html' => $view]);
    }

    public function TaskStatus(Request $request)
    {
        // dd($request->all());

        $today_date = date('Y-m-d');

        $status             = $request->status;
        $task_details_id    = $request->task_id_reu;
        $user_comment1      = $request->user_comment;
        $task_details       = Task::find($task_details_id);
        // dd($task_details_id);

        if ($request->status == "canceled") {
            $message_box = 'Super Admin Canceled by ' . $task_details->task_no . ' this task';
        } elseif ($request->status == "rejected") {
            $message_box = 'Super Admin Rejected by ' . $task_details->task_no . ' this task';
            $task_details->user_comments = "";
        } elseif ($request->status == "completed") {
            $message_box = 'Super Admin Closed by ' . $task_details->task_no . ' this task';

            $date1 = date('Y-m-d');
            $date2 = date('Y-m-d', strtotime($task_details->end_date));
            $points = 1;
            $user = User::find($task_details->staff_id);
            if ($date2 >= $date1) {
                // dd("hi");
                $user->points += $points;
                $task_details->points = "+1";
            } else {
                // dd("hi1");
                $user->points -= $points;
                $task_details->points = "-1";
            }
            $user->save();
            $task_details->completed_date = $today_date;
        }

        $task_details->status = $status;
        $task_details->admin_comments = $user_comment1;

        if ($task_details->save()) {

            $notification                = new Notification();
            $notification->task_id       = $task_details->id;
            $notification->receiver_id   = $task_details->staff_id;
            $notification->receiver_type = "staff";
            $notification->message       = $message_box;
            $notification->status        = 0;
            $notification->save();

            $notification1                = new Notification();
            $notification1->task_id       = $task_details->id;
            $notification1->receiver_id   = $task_details->admin_id;
            $notification1->receiver_type = "sub_admin";
            $notification1->message       = $message_box;
            $notification1->status        = 0;
            $notification1->save();
            // dd($task_details->staff_id);

            if ($request->status == "completed") {
                $task_details          = Task::where('id', $task_details->id)->first();
                $staff_details         = User::where('id', $task_details->staff_id)->first();
                $data['name']          = Auth::user()->name;
                $data['email']         = Auth::user()->email;
                $data['logo']          = url('public/admin_assets/images/logo.png');;
                $data['task_details']  = $task_details;
                $data['staff_details'] = $staff_details;

                try {
                    Mail::send('mail.task_details', $data, function ($message) use ($data) {
                        $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                            ->from("noreplywebbitech@gmail.com", 'no-reply')
                            ->subject($data['task_details']['name'] . ' - task has been Completed');
                    });
                } catch (\Exception $e) {
                    // dd($e);
                }
            }

            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
        } else {
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
            $data['logo']          = url('public/admin_assets/images/logo.png');;
            $data['task_details']  = $task_details;
            $data['staff_details'] = $staff_details;

            try {
                Mail::send('mail.reopen', $data, function ($message) use ($data) {
                    $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                        ->subject('Admin - Reopen this task');
                });
            } catch (\Exception $e) {
                // dd($e);
            }

            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function TaskDetailsGet($id)
    {

        $task = TaskDetails::where('id', $id)->first();
        return view('admin.task.task_details', compact('task'));
    }

    public function TaskDetailsUpdate(Request $request)
    {
        // dd($request->all());

        $today_date = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();

        $status             = $request->status;
        $task_id            = $request->task_id;
        $user_comment1      = $request->user_comment;
        $task_details       = TaskDetails::find($task_id);
        // dd($today_date);

        if ($request->status == "rejected") {
            $status = "Rejected";
            $message_box = date('d-m-Y', strtotime($task_details->start_date)) . ' - ' . date('d-m-Y', strtotime($task_details->end_data)) . 'Recurring Task has been Rejected by Super Admin';
            $task_details->user_comments = "";
        } elseif ($request->status == "completed") {
            $status = "Completed";
            $message_box = date('d-m-Y', strtotime($task_details->start_date)) . ' - ' . date('d-m-Y', strtotime($task_details->end_data)) . 'Recurring Task has been Closed by Super Admin';
        }

        $task_details->status = $status;
        $task_details->admin_comments = $user_comment1;

        if ($task_details->save()) {

            $notification                = new Notification();
            $notification->task_id       = $task_details->id;
            $notification->receiver_id   = $task_details->staff_id;
            $notification->receiver_type = "sub_admin";
            $notification->message       = $message_box;
            $notification->url           = route('sub.task.status', $task_details->id);
            $notification->status        = 0;
            $notification->save();

            $task_details          = TaskDetails::where('id', $task_details->id)->first();
            $staff_details         = User::where('id', $task_details->staff_id)->first();
            $data['name']          = Auth::user()->name;
            $data['email']         = Auth::user()->email;
            $data['logo']          = url('public/admin_assets/images/logo.png');;
            $data['task_details']  = $task_details;
            $data['staff_details'] = $staff_details;
            $data['status']        = $status;
            // $data['staff_details']['email']
            try {
                Mail::send('mail.task_details', $data, function ($message) use ($data) {
                    $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                        ->subject(date('d-m-Y', strtotime($data['task_details']['start_date'])) . ' - ' . date('d-m-Y', strtotime($data['task_details']['end_data'])) . ' Recurring Task has been ' . $data['status'] . ' by Super Admin');
                });
            } catch (\Exception $e) {
                // dd($e);
            }

            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SuperAdminDownload(Request $request)
    {
        // dd($request->all());

        $data['sort_task_type']     = $request->sort_task_type;
        $data['sort_task_status']   = $request->sort_task_status;
        $data['project_id']         = $request->project_id;
        $data['txt_search']         = $request->txt_search ?? '';
        $data['user_id']            = Auth::user()->id;
        $data['user_type']          = "super_admin";

        $sort_task_type     = $request->sort_task_type;
        $sort_task_status   = $request->sort_task_status;
        $project_id         = $request->project_id;
        $user_name          = $request->txt_search ?? '';
        $user_id            = Auth::user()->id;
        $user_type          = "super_admin";
        // if ($user_name) {

        $user = User::where('id', $request->sort_task_type)->first();

        $book_details = TaskStaff::with(['task_details']);

        if ($sort_task_type) {
            $book_details = $book_details->where('staff_id', $sort_task_type);
        }
        if ($sort_task_status) {
            $book_details = $book_details->where('status', $sort_task_status);
        }
        if ($project_id[0]) {
            $book_details = $book_details->whereIn('project_id', $project_id);
        }

        // dd($user);
        $book_details           = $book_details->get();

        // dd($book_details);
        $data['book_details']   = $book_details;
        $data['user']           = $user;
        $data['logo']           = url('public/admin_assets/images/logo.png');;

        if ($request->report_type == "mail") {
            if ($user) {
                try {
                    Mail::send('mail.report', $data, function ($message) use ($data) {
                        $message->to($data['user']['email'], $data['user']['name'])
                            ->from("noreplywebbitech@gmail.com", 'no-reply')
                            ->subject("Report Sent By Super Admin");
                    });
                    return redirect()->back()->with('success', 'Report Sent Staff Email Address');
                } catch (\Exception $e) {
                    // dd($e);
                }
            } else {
                return redirect()->back()->with('error', 'Please Select Staff');
            }
        } elseif ($request->report_type == "whatsapp") {
            return redirect()->back()->with('error', 'No Data Available');
        } else {

            if (count($book_details)) {
                return Excel::download(new ExportTask($data), 'Single Task.xlsx');
            } else {
                return redirect()->back()->with('error', 'No Data Available');
            }
        }
    }

    public function TaskAddComment(Request $request)
    {
        // dd($request->all());

        $task_comments                = TaskComment::find($request->comment_id);
        $task_comments->admin_comment = $request->admin_comment_model;

        if ($task_comments->save()) {
            return redirect()->back()->with('success', 'Task Status Add Successfully!');
        } else {
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
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function TaskUpdateComment(Request $request)
    {
        // dd($request->all());

        $today_date = \Carbon\Carbon::now('Asia/Kolkata')->toDateTimeString();
        $points_admin = $request->previous;
        $task_comments                = TaskStaff::find($request->staff_task_id);
        $task_comments->status        = $request->staff_status;
        $task_comments->admin_comment = $request->admin_comment_model;

        if ($request->staff_status == "completed") {
            $status = "Completed";

            $date1 = strtotime($today_date);
            $date2 = strtotime($task_comments->end_date);
            $points = 1;
            $user = User::find($task_comments->staff_id);
            if ($points_admin == "on") {
                if ($user->points == null) {
                    $user->points = $points;
                } else {
                    $user->points += $points;
                }

                $task_comments->points = "+1";
            } elseif ($date2 >= $date1) {
                if ($user->points == null) {
                    $user->points = $points;
                } else {
                    $user->points += $points;
                }

                $task_comments->points = "+1";
            } else {
                if ($user->points == null) {
                    // $user->points = $points;
                } else {
                    $user->points -= $points;
                }

                // $user->points -= $points;
                $task_comments->points = "-1";
            }
            $user->save();
            $task_comments->completed_date = $today_date;
        } elseif ($request->staff_status == "rejected") {
            $status = "Rejected";
        }

        if ($task_comments->save()) {

            $task_details                = Task::where('id', $task_comments->task_id)->first();
            $message_cmd                 = Auth::user()->name . ' Update by Task - ' . $task_details->task_no;
            $notification                = new Notification();
            $notification->task_id       = $task_details->id;
            $notification->receiver_id   = $request->staff_task_id;
            $notification->url           = route('staff.view.task', $task_details->id);
            $notification->receiver_type = "admin";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();

            $staff_details           = User::where('id', $task_comments->staff_id)->first();
            $data['name']            = Auth::user()->name;
            $data['email']           = Auth::user()->email;
            $data['logo']            = url('public/admin_assets/images/logo.png');
            $data['task_details']    = $task_details;
            $data['staff_details']   = $staff_details;
            $data['status']         = $status;
            // $message->to($data['staff_details']['email'], $data['staff_details']['name'])
            try {
                Mail::send('mail.custom_task_status', $data, function ($message) use ($data) {
                    $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                        ->subject($data['task_details']['name'] . ' task has been ' . $data['status'] . ' By Super Admin');
                });
            } catch (\Exception $e) {
                // dd($e);
            }

            return redirect()->back()->with('success', 'Staff Task Status Update Successfully!');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function ClosedStatusTask($id)
    {
        // dd($id);

        $task_staff     = Task::find($id);
        $task_staff->status = "completed";
        if ($task_staff->save()) {

            $task_staff_details = TaskStaff::where('task_id', $id)->get();
            // dd($task_staff_details);
            foreach ($task_staff_details as $key => $value) {

                $task_details                = Task::where('id', $value->task_id)->first();
                $message_cmd                 = Auth::user()->name . ' Updated by Task Status - ' . $task_details->task_no;
                $notification                = new Notification();
                $notification->task_id       = $task_details->id;
                $notification->receiver_id   = $value->staff_task_id;
                $notification->url           = route('staff.view.task', $task_details->id);
                $notification->receiver_type = "admin";
                $notification->message       = $message_cmd;
                $notification->status        = 0;
                $notification->save();

                $staff_details           = User::where('id', $value->staff_id)->first();
                $data['name']            = Auth::user()->name;
                $data['email']           = Auth::user()->email;
                $data['logo']            = url('public/admin_assets/images/logo.png');
                $data['task_details']    = $task_details;
                $data['staff_details']   = $staff_details;
                $data['status']         = "Closed";
                // $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                try {
                    Mail::send('mail.closed', $data, function ($message) use ($data) {
                        $message->to($data['staff_details']['email'], $data['staff_details']['name'])
                            ->from("noreplywebbitech@gmail.com", 'no-reply')
                            ->subject(Auth::user()->name . ' has been Closed ' . $data['task_details']['name']);
                    });
                } catch (\Exception $e) {
                    // dd($e);
                }
            }

            return redirect()->back()->with('success', 'Task Status Updated Successfully!');
        }
        return redirect()->back()->with('error', 'Something Wrong');
    }

    // public function SubAdminCheck(Request $request)
    // {

    //     $get_staff = User::where('sub_admin_id', $request->id)->get();
    //     $html = '';
    //     $staff = '';

    //     if (count($get_staff) > 0) {
    //         foreach ($get_staff as $key => $value) {
    //             $html .= '<option value="' . $value->id . '">' . $value->name . '</option>';
    //         }

    //         foreach ($get_staff as $key1 => $value1) {
    //             $staff .= '<li data-original-index=' . $key1 . '><a tabindex=' . $key1 . ' class="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">' . $value1->name . '</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';
    //         }
    //     }
    //     $data['html'] = json_encode($html);
    //     $data['staff'] = json_encode($staff);
    //     // dd($staff);
    //     return $data;
    //     // echo json_encode($html);
    // }
    public function SubAdminCheck(Request $request)
    {
        $get_staff = User::where('sub_admin_id', $request->id)->where('status', 'active')->get();
        $html  = '';
        $staff = '';

        if (count($get_staff) > 0) {
            foreach ($get_staff as $key => $value) {
                // ↓ add data-role here
                $html .= '<option value="' . $value->id . '" data-role="' . $value->role . '">' . $value->name . '</option>';
            }

            foreach ($get_staff as $key1 => $value1) {
                $staff .= '<li data-original-index=' . $key1 . '><a tabindex=' . $key1 . ' class="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">' . $value1->name . '</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>';
            }
        }

        $data['html']  = json_encode($html);
        $data['staff'] = json_encode($staff);
        return $data;
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
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function FollowUpProjectComments(Request $request)
    {
        // dd($request->all());

        $follow_comment                 = new TaskFollowComment();
        $follow_comment->follower_id    = $request->project_follow_id;
        $follow_comment->task_id        = $request->task_id;
        $follow_comment->project_id     = $request->project_id;
        $follow_comment->staff_id       = $request->staff_status;
        $follow_comment->comments       = $request->admin_comment_model;
        $follow_comment->status         = $request->previous;
        $follow_comment->save();

        if ($follow_comment) {
            return redirect()->back()->with('success', 'Follow Status Updated Successfully!');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function FollowUpProjectPayment(Request $request)
    {
        // dd($request->all());

        $follow_comment                 = new PaymentComment();
        $follow_comment->follower_id    = $request->payment_follow_id;
        $follow_comment->task_id        = $request->task_id;
        $follow_comment->project_id     = $request->project_id;
        $follow_comment->task_date      = $request->task_date;
        $follow_comment->amount         = $request->amount;
        $follow_comment->comments       = $request->admin_comment_model;
        $follow_comment->save();

        if ($follow_comment) {
            return redirect()->back()->with('success', 'Payment Status Updated Successfully!');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    // public function AdminTaskCheck(Request $request)
    // {
    //     $user_id = $request->id;
    //     $task_id = $request->task_id;
    //     $text = $request->text;

    //     $TaskStaff = TaskStaff::where('task_id', $task_id)->where('staff_id', $user_id)->first();
    //     $html = "";
    //     $selected = '';
    //     $selected1 = '';
    //     $selected2 = '';
    //     if ($TaskStaff) {
    //         if ($TaskStaff->priority == "Low") {
    //             $selected = 'selected="selected"';
    //         } elseif ($TaskStaff->priority == "Medium") {
    //             $selected1 = 'selected="selected"';
    //         } elseif ($TaskStaff->priority == "High") {
    //             $selected2 = 'selected="selected"';
    //         }

    //         $html .= '<div class="form-group row">
    //             <div class="col-md-3">
    //                 <label class="form-label">Name</label>
    //                 <input type="hidden" name="addmore[' . $user_id . '][id]" value="' . $user_id . '">
    //                 <input type="text" class="form-control" name="addmore[' . $user_id . '][name]" value="' . $text . '">
    //             </div>
    //             <div class="col-md-3">
    //                 <label class="form-label">Start Date</label>
    //                 <input type="date" class="form-control" name="addmore[' . $user_id . '][start_date]" value="' . $TaskStaff->start_date . '" placeholder="test">
    //             </div>
    //             <div class="col-md-3">
    //                 <label class="form-label">End Date</label>
    //                 <input type="date" class="form-control" name="addmore[' . $user_id . '][end_date]" value="' . $TaskStaff->end_date . '" placeholder="test1">
    //             </div>
    //             <div class="col-md-3">
    //                 <div class="form-group">
    //                     <label class="form-label">Task Priority </label>
    //                     <div class="input-group mb-3">
    //                         <select class="form-control" name="addmore[' . $user_id . '][priority]">
    //                             <option value="">Select Priority</option>
    //                             <option value="High" ' . $selected2 . '>High</option>\
    //                             <option value="Medium" ' . $selected1 . '>Medium</option>
    //                             <option value="Low" ' . $selected . '>Low</option>
    //                         </select>
    //                     </div>
    //                 </div>
    //             </div>
    //         </div>';
    //     } else {
    //         $html .= '<div class="form-group row">
    //             <div class="col-md-3">
    //                 <label class="form-label">Name</label>
    //                 <input type="hidden" name="addmore[' . $user_id . '][id]" value="' . $user_id . '">
    //                 <input type="text" class="form-control" name="addmore[' . $user_id . '][name]" value="' . $text . '">
    //             </div>
    //             <div class="col-md-3">
    //                 <label class="form-label">Start Date</label>
    //                 <input type="date" class="form-control" name="addmore[' . $user_id . '][start_date]" placeholder="test">
    //             </div>
    //             <div class="col-md-3">
    //                 <label class="form-label">End Date</label>
    //                 <input type="date" class="form-control" name="addmore[' . $user_id . '][end_date]" placeholder="test1">
    //             </div>
    //             <div class="col-md-3">
    //                 <div class="form-group">
    //                     <label class="form-label">Task Priority </label>
    //                     <div class="input-group mb-3">
    //                         <select class="form-control" name="addmore[' . $user_id . '][priority]">
    //                             <option value="">Select Priority</option>
    //                             <option value="High">High</option>\
    //                             <option value="Medium">Medium</option>
    //                             <option value="Low">Low</option>
    //                         </select>
    //                     </div>
    //                 </div>
    //             </div>
    //         </div>';
    //     }

    //     return $html;
    //     // dd($TaskStaff);
    // }

    public function AdminTaskCheck(Request $request)
    {
        $user_id = $request->id;
        $task_id = $request->task_id;
        $text = $request->text;

        $TaskStaff = TaskStaff::where('task_id', $task_id)->where('staff_id', $user_id)->first();
        $html = "";
        $selected = '';
        $selected1 = '';
        $selected2 = '';
        if ($TaskStaff) {
            if ($TaskStaff->priority == "Low") {
                $selected = 'selected="selected"';
            } elseif ($TaskStaff->priority == "Medium") {
                $selected1 = 'selected="selected"';
            } elseif ($TaskStaff->priority == "High") {
                $selected2 = 'selected="selected"';
            }

            $frontend_hours = $TaskStaff->assigned_frontend_hours ?? 0;
            $backend_hours  = $TaskStaff->assigned_backend_hours ?? 0;
            $seo_hours      = $TaskStaff->assigned_seo_hours ?? 0;
            $testing_hours  = $TaskStaff->assigned_testing_hours ?? 0;
            $designer_hours = $TaskStaff->assigned_designer_hours ?? 0;

            $html .= '<div class="form-group row border-bottom pb-3 mb-3">
        <div class="col-md-3">
            <label class="form-label">Name</label>
            <input type="hidden" name="addmore[' . $user_id . '][id]" value="' . $user_id . '">
            <input type="text" class="form-control" name="addmore[' . $user_id . '][name]" value="' . $text . '">
        </div>
        <div class="col-md-3">
            <label class="form-label">Start Date</label>
            <input type="date" class="form-control" name="addmore[' . $user_id . '][start_date]" value="' . $TaskStaff->start_date . '" placeholder="test">
        </div>
        <div class="col-md-3">
            <label class="form-label">End Date</label>
            <input type="date" class="form-control" name="addmore[' . $user_id . '][end_date]" value="' . $TaskStaff->end_date . '" placeholder="test1">
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label">Task Priority </label>
                <div class="input-group mb-3">
                    <select class="form-control" name="addmore[' . $user_id . '][priority]">
                        <option value="">Select Priority</option>
                        <option value="High" ' . $selected2 . '>High</option>
                        <option value="Medium" ' . $selected1 . '>Medium</option>
                        <option value="Low" ' . $selected . '>Low</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-2">
            <label class="form-label">Frontend Hours</label>
            <input type="number" step="0.5" min="0" class="form-control" name="addmore[' . $user_id . '][assigned_frontend_hours]" value="' . $frontend_hours . '">
        </div>
        <div class="col-md-3 mt-2">
            <label class="form-label">Backend Hours</label>
            <input type="number" step="0.5" min="0" class="form-control" name="addmore[' . $user_id . '][assigned_backend_hours]" value="' . $backend_hours . '">
        </div>
        <div class="col-md-3 mt-2">
            <label class="form-label">SEO Hours</label>
            <input type="number" step="0.5" min="0" class="form-control" name="addmore[' . $user_id . '][assigned_seo_hours]" value="' . $seo_hours . '">
        </div>
        <div class="col-md-3 mt-2">
            <label class="form-label">Testing Hours</label>
            <input type="number" step="0.5" min="0" class="form-control" name="addmore[' . $user_id . '][assigned_testing_hours]" value="' . $testing_hours . '">
        </div>
        <div class="col-md-3 mt-2">
            <label class="form-label">Designer Hours</label>
            <input type="number" step="0.5" min="0" class="form-control" name="addmore[' . $user_id . '][assigned_designer_hours]" value="' . $designer_hours . '">
        </div>
    </div>';
        } else {
            $html .= '<div class="form-group row border-bottom pb-3 mb-3">
        <div class="col-md-3">
            <label class="form-label">Name</label>
            <input type="hidden" name="addmore[' . $user_id . '][id]" value="' . $user_id . '">
            <input type="text" class="form-control" name="addmore[' . $user_id . '][name]" value="' . $text . '">
        </div>
        <div class="col-md-3">
            <label class="form-label">Start Date</label>
            <input type="date" class="form-control" name="addmore[' . $user_id . '][start_date]" placeholder="test">
        </div>
        <div class="col-md-3">
            <label class="form-label">End Date</label>
            <input type="date" class="form-control" name="addmore[' . $user_id . '][end_date]" placeholder="test1">
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label">Task Priority </label>
                <div class="input-group mb-3">
                    <select class="form-control" name="addmore[' . $user_id . '][priority]">
                        <option value="">Select Priority</option>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-2">
            <label class="form-label">Frontend Hours</label>
            <input type="number" step="0.5" min="0" class="form-control" name="addmore[' . $user_id . '][assigned_frontend_hours]" value="0">
        </div>
        <div class="col-md-3 mt-2">
            <label class="form-label">Backend Hours</label>
            <input type="number" step="0.5" min="0" class="form-control" name="addmore[' . $user_id . '][assigned_backend_hours]" value="0">
        </div>
        <div class="col-md-3 mt-2">
            <label class="form-label">SEO Hours</label>
            <input type="number" step="0.5" min="0" class="form-control" name="addmore[' . $user_id . '][assigned_seo_hours]" value="0">
        </div>
        <div class="col-md-3 mt-2">
            <label class="form-label">Testing Hours</label>
            <input type="number" step="0.5" min="0" class="form-control" name="addmore[' . $user_id . '][assigned_testing_hours]" value="0">
        </div>
        <div class="col-md-3 mt-2">
            <label class="form-label">Designer Hours</label>
            <input type="number" step="0.5" min="0" class="form-control" name="addmore[' . $user_id . '][assigned_designer_hours]" value="0">
        </div>
    </div>';
        }

        return $html;
    }

    public function RecommandTaskCreate()
    {
        $sub_admin = User::where('user_type', 'sub_admin')->where('status', 'active')->get();
        $projects = Project::get();
        $sub_admin1 = User::where('user_type', '!=', 'super_admin')->where('status', 'active')->get();
        $staff_details = User::where('user_type', 'staff')
            ->where('status', 'active')
            ->get();
        return view('admin.task.recommand', compact('sub_admin', 'sub_admin1', 'projects', 'staff_details'));
    }

    public function RecommandTaskView()
    {
        $test_details = Task::where('task_type', 'recommend')->get();
        return view('admin.task.recommand_view', compact('test_details'));
    }

    public function RecommandTaskStore(Request $request)
    {
        // dd($request->all());

        $task_details               = new Task();
        $task_details->task_type    = $request->task_type;
        $task_details->name         = $request->task_name;
        $task_details->description  = $request->editor1;
        $task_details->task_type    = "recommend";
        $task_details->admin_id     = Auth::user()->id;
        $task_details->assign_by    = Auth::user()->id;
        $task_details->save();

        if ($task_details) {
            return redirect()->route('admin.recommand.task.view')->with('success', 'Recommand Task Created Successfully');
        } else {
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
            $users = $users->where(function ($q) use ($text_value_search) {
                $q->where('staff_name', 'LIKE', "%" . $text_value_search . "%")
                    ->orWhereHas('project_details', function ($q) use ($text_value_search) {
                        $q->where('name', 'LIKE', "%" . $text_value_search . "%");
                    })
                    ->orWhereHas('task_details', function ($q) use ($text_value_search) {
                        $q->where('task_no', 'LIKE', "%" . $text_value_search . "%")
                            ->orWhere('description', 'LIKE', "%" . $text_value_search . "%")
                            ->orWhere('name', 'LIKE', "%" . $text_value_search . "%");
                    });
            });
        }

        $sub_admin = $users->get();

        $view =  view('admin.task.search_task', compact('sub_admin', 'start_date'))->render();

        if ($view) {
            $data['project'] = $view;
            $data['status'] = "success";
        } else {
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

    public function TaskRequest()
    {

        $sort_by = '';

        if (request('client_id') && request('client_id') != '') {
            $sort_by = request('client_id');
        }

        if ($sort_by) {
            $client_task  = ClientTask::where('user_id', $sort_by)->where('user_type', 'client')->latest()->get();
        } else {
            $client_task  = ClientTask::where('user_type', 'client')->latest()->get();
        }

        // dd($client_task);

        $client_task1 = ClientTask::where('user_type', 'client')->whereNot('status', 'Closed')->get();
        $client_details = User::where('user_type', 'client')->latest()->get();

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
                } else {
                    $client_update = ClientTask::find($value->id);
                    $client_update->status = "In Progress";
                    $client_update->save();
                }
            }
        }

        return view('admin.task.task-request', compact('client_task', 'client_details'));
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

                    $total_time = $h . ':' . $m;
                    $hour_rate = $value->amount;

                    // dd($hour_convert[1]);
                    if (isset($m) && $m) {
                        $calculate = $h + ($m / 60);
                    } else {
                        $calculate = $h;
                    }

                    $added_rate = round($calculate, 2);
                    if ($value->amount_type == "fixed_amount") {
                        $total_amount = $hour_rate;
                    } else {
                        $total_amount = $added_rate * $hour_rate;
                    }

                    $project_amount += $total_amount;

                    $update_details = ClientTaskDetails::find($value->id);
                    $update_details->total_amount = $project_amount;
                    $update_details->save();
                }
            }
        }

        return view('admin.task.requestview', compact('client_task'));
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
        $request_view = "request_view_" . '' . $request->id;

        $view =  view('admin.task.modelrender1', compact('amount_details'))->render();
        return response()->json(['html' => $view, 'background' => $background, 'request_view' => $request_view]);
    }

    public function DeleteDetailsTask($id)
    {

        ClientTaskDetails::findOrFail($id)->delete();

        return redirect()->back()->with('error', 'Deleted Successfully');
    }

    public function TaskRequestCommentUpdate(Request $request)
    {
        // dd($request->all());

        $client_comment                         = new ClientTaskComment();
        $client_comment->client_task_id         = $request->client_task_id;
        $client_comment->client_task_details_id = $request->client_id;
        $client_comment->user_id                = Auth::user()->id;
        $client_comment->comments               = $request->editor1;
        if ($client_comment->save()) {
            $client_task_details                = ClientTaskDetails::find($request->client_id);
            $client_task_details->category      = $request->category;
            $client_task_details->amount        = $request->amount;
            $client_task_details->estimate_time = number_format($request->estimate_time, 2);
            $client_task_details->estimate_type = $request->estimate_type;
            $client_task_details->amount_type   = $request->amount_type;
            $client_task_details->alert_user    = $client_task_details->user_id;
            $client_task_details->alert_Status  = 1;
            $client_task_details->status        = "Waiting for Client Approvel";
            $client_task_details->save();

            $message_cmd                 = Auth::user()->name . ' Update by Status in Task Estimate - ' . $client_task_details->name;
            $notification                = new Notification();
            $notification->task_id       = "";
            $notification->receiver_id   = $client_task_details->user_id;
            $notification->url           = route('client.projects.task.details', $client_task_details->client_task_id) . '/#key-' . $client_task_details->id;
            $notification->receiver_type = "Client";
            $notification->message       = $message_cmd;
            $notification->status        = 0;
            $notification->save();

            return redirect()->back()->with('success', 'Estimate Time Details Updated Successfully');
        }
    }

    public function DeleteEstimateTask($id)
    {
        ClientTask::findOrFail($id)->delete();
        return redirect()->back()->with('error', 'Estimate Deleted Successfully');
    }

    public function EstimateCreate()
    {
        $client_details = User::where('user_type', 'client')->latest()->get();
        return view('admin.task.estimate-add', compact('client_details'));
    }

    public function EstimateStore(Request $request)
    {
        // dd($request->all());

        $create_task                = new ClientTask();
        $create_task->user_id       = $request->client_id;
        $create_task->user_type     = "client";
        $create_task->name          = $request->name;
        $create_task->date          = $request->confirm_date;
        $create_task->description   = $request->editor1;
        $create_task->status        = "In Progress";

        if ($create_task->save()) {

            $key_number              = 'WT' . str_pad($create_task->id, 6, "0", STR_PAD_LEFT);
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

            return redirect()->route('task.request')->with('success', 'New Task Estimate Create SuccessFully');
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

        if ($request->estimate_id) {
            $update_task                = ClientTask::find($request->estimate_id);
            $update_task->closed_date   = $request->date;
            $update_task->total_amount  = $request->total_amount;
            $update_task->total_hours   = $request->total_hours;
            $update_task->status        = $request->status;
            if ($update_task->save()) {
                return redirect()->back()->with('success', 'Estimate Details SuccessFully');
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function TaskRequestEdit($id)
    {
        $client_details = User::where('user_type', 'client')->latest()->get();
        $task_details = ClientTask::where('id', $id)->first();
        // dd(count($task_details->client_task_details));
        return view('admin.task.estimate-edit', compact('client_details', 'task_details'));
    }

    public function EstimateEditStore(Request $request, $id)
    {
        // dd($request->all());

        $create_task                = ClientTask::find($id);
        $create_task->user_id       = $request->client_id;
        $create_task->name          = $request->name;
        $create_task->user_type     = "client";
        $create_task->date          = $request->confirm_date;
        $create_task->description   = $request->editor1;
        $create_task->status        = "In Progress";

        if ($create_task->save()) {


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
                    } else {
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

            return redirect()->route('task.request')->with('warning', 'Task Estimate Edited SuccessFully');
        }

        return redirect()->back()->with('error', 'Something Wrong');
    }

    public function EstimateDescription(Request $request)
    {
        // dd($request->all());

        $update_task = ClientTask::where('id', $request->id)->first();

        $view =  view('admin.task.modelrender3', compact('update_task'))->render();
        return response()->json(['html' => $view]);
    }
}
