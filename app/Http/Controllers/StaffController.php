<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskDetails;
use App\Models\GroupStaff;
use App\Models\IncentiveAmount;
use App\Exports\ExportTask;
use App\Exports\ExportRecurringTask;
use Auth;
use Mail;
use PDF;
use File;
use Excel;
use DB;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function SubStaffCreate()
    {
        $sub_admin = User::where('user_type', 'sub_admin')->where('status', 'active')->get();
        return view('admin.staff.add', compact('sub_admin'));
    }
    // public function staffProjectView()
    // {
    //     $sub_admin = User::where('user_type', 'sub_admin')->where('status', 'active')->get();
    //     return view('admin.staff.projectview', compact('sub_admin'));
    // }

//     public function staffProjectView()
//     {
//         // $staff_list = User::where('user_type', 'staff')
//         //     ->where('status', 'active')
//         //     ->with(['task_staff.project_details'])
//         //     ->get();
//         // $staff_list = User::where('user_type', 'staff')
//         // ->where('status', 'active')
//         // ->whereHas('task_staff.project_details', function ($q) {
//         //     $q->where('status', 'inprogress');
//         // })
//         // ->with([
//         //     'task_staff' => function ($q) {
//         //         $q->whereHas('project_details', function ($query) {
//         //             // $query->where('status', '1');
//         //         })->with('project_details');
//         //     }
//         // ])
//         // ->get();

//        $status = request('status', 'inprogress'); // Default = inprogress

// $staff_list = User::where('user_type', 'staff')
//     ->where('status', 'active')
//     ->whereHas('task_staff', function ($q) use ($status) {
//         $q->where('status', $status);
//     })
//     ->with([
//         'task_staff' => function ($q) use ($status) {
//             $q->where('status', $status)
//               ->with('project_details');
//         }
//     ])
//     ->get();
        
//             // dd( $staff_list);
//         \App\Models\TaskStaff::loadLoggedHoursMap();

//         return view('admin.staff.projectview', compact('staff_list'));
//     }

public function staffProjectView()
{
    $staff_list = User::where('user_type', 'staff')
        ->where('status', 'active')
        ->with(['task_staff.project_details'])
        ->get();

    \App\Models\TaskStaff::loadLoggedHoursMap();

    return view('admin.staff.projectview', compact('staff_list'));
}

    public function SubStaffView()
    {
        $sub_admin = User::where('user_type', 'staff')->where('status', 'active')->get();
        return view('admin.staff.view', compact('sub_admin'));
    }

    public function SubStaffEdit($id)
    {
        $staff = User::where('id', $id)->where('status', 'active')->first();
        $sub_admin = User::where('user_type', 'sub_admin')->where('status', 'active')->get();
        return view('admin.staff.edit', compact('sub_admin', 'staff'));
    }


    public function SubStafTaskDetails($id)
    {
        $custom_task = Task::where('staff_id', $id)->where('task_type', 'custom')->get();
        $recurring_task = Task::where('staff_id', $id)->where('task_type', 'recurring')->get();
        $user_details = User::where('id', $id)->first();
        // dd($recurring_task);
        return view('admin.staff.task_view', compact('custom_task', 'recurring_task', 'user_details'));
    }

    public function SubStaffReport(Request $request)
    {
        // dd($request->all());
        $title = $request->row_check;
        $data['user_id'] = $request->user_id;
        $data['user_type'] = "admin_id";
        $data['task_type'] = "custom";
        $data['user_type'] = "staff_id";
        if ($request->row_check == "Pending") {
            $task_status = "pending";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Progress") {
            $task_status = "progress";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Staff Completed") {
            $task_status = "user_completed";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Completed") {
            $task_status = "completed";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Canceled") {
            $task_status = "canceled";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Rejected") {
            $task_status = "rejected";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Reopen") {
            $task_status = "hold";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Completed") {
            $task_status = "reopen";
            $data['task_status'] = $task_status;
        }
        $data2 = Task::where('staff_id', $request->user_id)->where('task_type', 'custom')->where('status', $task_status)->get();
        // dd($data2);
        if (count($data2)) {
            return Excel::download(new ExportTask($data), $title . '.xlsx');
        } else {
            return redirect()->back()->with('error', 'No Data Available');
        }
    }

    public function SubStaffRecurringReport($id)
    {
        // dd($id);
        $data['task_id'] = $id;
        $data2 = TaskDetails::where('task_id', $id)->get();
        // dd($data2);
        if (count($data2)) {
            return Excel::download(new ExportRecurringTask($data), 'Recurring.xlsx');
        } else {
            return redirect()->back()->with('error', 'No Data Available');
        }
    }

    public function SubStaffDelete($id)
    {
        if ($id) {
            $user  = User::find($id);
            if ($user->delete()) {
                return redirect()->route('sub.staff.view')->with('error', 'Staff Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubStaffStore(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'sub_admin'     => 'required|max:255',
            'name'          => 'required|max:255',
            'email'         => 'required|max:255|unique:users',
            'password'      => 'required|confirmed'
        ]);
        $sub_admin_check = User::where('id', $request->sub_admin)->first();

        $password            = Hash::make($request->password);
        $user                = new User();
        $user->name          = $request->name;
        $user->email         = $request->email;
        $user->phone         = $request->phone;
        $user->salary        = $request->salary;
        $user->join_date     = $request->join_date;
        $user->role          = $request->role;
        $user->admin_id      = $sub_admin_check->admin_id;
        $user->sub_admin_id  = $request->sub_admin;
        $user->user_type     = "staff";
        $user->status        = "active";
        $user->password      = $password;
        $user->permissions   = json_encode($request->permissions);
        if ($user->save()) {

            $data['name']          = $request->name;
            $data['email']         = $request->email;
            $data['password']      = $request->password;
            $data['logo']          = url('public/admin_assets/images/logo.png');
            $data['link_url']      = url('/login');

            try {
                Mail::send('mail.user_details', $data, function ($message) use ($data) {
                    $message->to($data['email'], $data['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                        ->subject('Welcome to Webbitech Family');
                });
            } catch (\Exception $e) {
                // dd($e);
            }

            return redirect()->route('sub.staff.view')->with('success', 'Staff Created Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubStaffUpdate(Request $request, $id)
    {
        // dd($request->all());

        $user               = User::find($id);
        if ($request->password) {
            $request->validate([
                'sub_admin'     => 'required|max:255',
                'name'          => 'required|max:255',
                'password'      => 'required|confirmed'
            ]);

            $password           = Hash::make($request->password);
            $user->password     = $password;
        }
        $sub_admin_check = User::where('id', $request->sub_admin)->first();
        $user->salary        = $request->salary;
        $user->name          = $request->name;
        $user->email         = $request->email;
        $user->phone         = $request->phone;
        $user->join_date     = $request->join_date;
        $user->role          = $request->role;
        $user->admin_id      = $sub_admin_check->admin_id;
        $user->sub_admin_id  = $request->sub_admin;
        $user->user_type     = "staff";
        $user->status        = $request->status;

        $user->permissions   = json_encode($request->permissions);
        if ($user->save()) {
            return redirect()->route('sub.staff.view')->with('warning', 'Stafff Edited Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubStafGroup()
    {

        $group_staff = GroupStaff::get();
        return view('admin.staff.task_view', compact('group_staff'));
    }

    public function SubStafGroupAdd()
    {

        $sub_admin = User::where('user_type', 'staff')->where('status', 'active')->get();
        return view('admin.staff.group-add', compact('sub_admin'));
    }

    public function SubStaffGroupEdit($id)
    {

        $sub_admin = User::where('user_type', 'staff')->where('status', 'active')->get();
        $group_staff = GroupStaff::where('id', $id)->first();
        return view('admin.staff.group-edit', compact('sub_admin', 'group_staff'));
    }

    public function SubStaffGroupDelete($id)
    {
        if ($id) {
            $user  = GroupStaff::findOrFail($id);
            if ($user->delete()) {
                return redirect()->route('sub.task.group')->with('error', 'Group Staff Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubStaffGroupStore(Request $request)
    {
        // dd($request->all());

        $user                = new GroupStaff();
        $user->group_user    = json_encode($request->staff_id);
        $user->group_name    = $request->group_name;
        $user->status        = $request->status;
        if ($user->save()) {

            foreach ($request->staff_id as $key => $value) {

                $user_details = User::find($value);
                $user_details->group_id = $user->id;
                $user_details->save();
            }

            $key_number              = 'WSG' . str_pad($user->id, 3, "0", STR_PAD_LEFT);
            $update_task             = GroupStaff::find($user->id);
            $update_task->group_id   = $key_number;
            $update_task->save();

            return redirect()->route('sub.task.group')->with('success', 'Staff Group Created Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubStaffGroupUpdate(Request $request, $id)
    {
        // dd($request->all());

        $user                = GroupStaff::find($id);
        $user->group_user    = json_encode($request->staff_id);
        $user->group_name    = $request->group_name;
        $user->status        = $request->status;
        if ($user->save()) {

            foreach ($request->staff_id as $key => $value) {

                $user_details = User::find($value);
                $user_details->group_id = $user->id;
                $user_details->save();
            }

            return redirect()->route('sub.task.group')->with('success', 'Staff Group Edited Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }


    public function SubStafIncentive()
    {

        $group_staff = IncentiveAmount::select(
            'user_id',
            DB::raw('SUM(amount) as total_amount')
        )
            ->whereNotNull('user_id')
            ->where('user_id', '!=', '')
            ->groupBy('user_id')
            ->get();
        return view('admin.staff.incentive-view', compact('group_staff'));
    }

    public function SubStafIncentiveAdd()
    {

        $sub_admin = User::where('user_type', 'staff')->where('status', 'active')->get();
        $incentive_details = IncentiveAmount::get();
        return view('admin.staff.incentive-add', compact('sub_admin', 'incentive_details'));
    }

    public function SubStaffIncentiveEdit($id)
    {
        $user_details = User::where('id', $id)->first();
        $sub_admin = User::where('user_type', 'staff')->where('status', 'active')->get();
        $incentive_details = IncentiveAmount::where('user_id', $id)->get();
        return view('admin.staff.incentive-edit', compact('sub_admin', 'incentive_details', 'user_details'));
    }

    public function SubStaffIncentiveDelete($id)
    {
        if ($id) {
            IncentiveAmount::where('user_id', $id)->where('incen_type', 1)->forceDelete();
            return redirect()->route('sub.task.incentive')->with('error', 'Staff Incentive Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubStaffIncentiveStore(Request $request)
    {
        // dd($request->all());

        $data_details = $request->addmore;

        if ($request->staff_id) {

            foreach ($request->staff_id as $key => $value) {
                // dd($value);
                if ($data_details) {

                    foreach ($data_details as $key1 => $value1) {

                        $incentive_save                 = new IncentiveAmount();
                        $incentive_save->user_id        = $value;
                        $incentive_save->start_amount   = $value1['start_amount'];
                        $incentive_save->end_amount     = $value1['end_amount'];
                        $incentive_save->amount         = $value1['incentive'];
                        $incentive_save->incen_type     = 1;
                        $incentive_save->status         = "Active";
                        $incentive_save->save();
                    }
                }
            }

            return redirect()->route('sub.task.incentive')->with('success', 'Staff Incentive Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubStaffIncentiveUpdate(Request $request)
    {
        // dd($request->all());

        $data_details = $request->addmore;

        if ($request->staff_id) {

            if ($data_details) {

                IncentiveAmount::where('user_id', $request->staff_id)->where('incen_type', 1)->forceDelete();

                foreach ($data_details as $key1 => $value1) {

                    $incentive_save                 = new IncentiveAmount();
                    $incentive_save->user_id        = $request->staff_id;
                    $incentive_save->start_amount   = $value1['start_amount'];
                    $incentive_save->end_amount     = $value1['end_amount'];
                    $incentive_save->amount         = $value1['incentive'];
                    $incentive_save->incen_type     = 1;
                    $incentive_save->status         = "Active";
                    $incentive_save->save();
                }
            }

            return redirect()->route('sub.task.incentive')->with('warning', 'Staff Incentive Edited Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }
}
