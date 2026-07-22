<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskDetails;
use App\Exports\ExportTask;
use App\Imports\ImportUsers;
use App\Exports\ExportRecurringTask;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Auth;
use Mail;
use PDF;
use File;
use Excel;
use DB;

class SubAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function SubStaffCreate()
    {
        $sub_admin = User::where('user_type', 'sub_admin')->where('status', 'active')->get();
        return view('sub_admin.staff.add', compact('sub_admin'));
    }

    public function SubStaffView()
    {
        $sub_admin = User::where('sub_admin_id', Auth::user()->id)->where('user_type', 'staff')->where('status', 'active')->get();
        return view('sub_admin.staff.view', compact('sub_admin'));
    }


    public function SubStafTaskDetails()
    {
        $custom_task = Task::where('admin_id', Auth::user()->id)->where('task_type', 'custom')->get();
        $recurring_task = Task::where('admin_id', Auth::user()->id)->where('task_type', 'recurring')->get();
        return view('sub_admin.staff.task_view', compact('custom_task', 'recurring_task'));
    }

    public function SubStaffEdit($id)
    {
        $staff = User::where('id', $id)->where('status', 'active')->first();
        $sub_admin = User::where('user_type', 'sub_admin')->where('status', 'active')->get();
        return view('sub_admin.staff.edit', compact('sub_admin', 'staff'));
    }

    public function SubStaffDelete($id)
    {
        if ($id) {
            $user  = User::find($id);
            if ($user->delete()) {
                return redirect()->route('staff.view')->with('error', 'Staff Deleted Successfully');
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubStaffStore(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name'          => 'required|max:255',
            'email'         => 'required|max:255|unique:users',
            'password'      => 'required|confirmed'
        ]);

        $user_details = User::where('id', Auth::user()->id)->first();
        // dd($user_details);
        $password           = Hash::make($request->password);
        $user               = new User();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->salary       = $request->salary;
        $user->role         = $request->role;
        $user->admin_id     = $user_details->admin_id;
        $user->sub_admin_id = Auth::user()->id;
        $user->user_type    = "staff";
        $user->status       = "active";
        $user->password     = $password;
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

            return redirect()->route('staff.view')->with('success', 'Staff Created Successfully');
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubStaffUpdate(Request $request, $id)
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
        $user_details = User::where('id', Auth::user()->id)->first();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->salary       = $request->salary;
        $user->role         = $request->role;
        $user->admin_id     = $user_details->admin_id;
        $user->sub_admin_id = Auth::user()->id;
        $user->user_type    = "staff";
        $user->status       = $request->status;
        if ($user->save()) {
            return redirect()->route('staff.view')->with('warning', 'Staff Edited Successfully');
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function StaffTaskDetails($id)
    {
        // dd($id);
        $custom_task = Task::where('staff_id', $id)->where('task_type', 'custom')->get();
        $recurring_task = Task::where('staff_id', $id)->where('task_type', 'recurring')->get();
        $user_details = User::where('id', $id)->first();
        // dd($recurring_task);
        return view('sub_admin.staff.task_view', compact('custom_task', 'recurring_task', 'user_details'));
    }

    public function SubStaffReport(Request $request)
    {
        // dd($request->all());
        $title = $request->row_check;
        $data['user_id'] = $request->user_id;
        $data['user_type'] = "staff_id";
        $data['task_type'] = "custom";
        if ($request->row_check == "Pending") {
            $task_status = "pending";
            $data['task_status'] = $task_status;
        }
        elseif ($request->row_check == "Progress") {
            $task_status = "progress";
            $data['task_status'] = $task_status;
        }
        elseif ($request->row_check == "Staff Completed") {
            $task_status = "user_completed";
            $data['task_status'] = $task_status;
        }
        elseif ($request->row_check == "Completed") {
            $task_status = "completed";
            $data['task_status'] = $task_status;
        }
        elseif ($request->row_check == "Canceled") {
            $task_status = "canceled";
            $data['task_status'] = $task_status;
        }
        elseif ($request->row_check == "Rejected") {
            $task_status = "rejected";
            $data['task_status'] = $task_status;
        }
        elseif ($request->row_check == "Reopen") {
            $task_status = "hold";
            $data['task_status'] = $task_status;
        }
        elseif ($request->row_check == "Completed") {
            $task_status = "reopen";
            $data['task_status'] = $task_status;
        }
        $data2 = Task::where('admin_id', $request->user_id)->where('task_type', 'custom')->where('status', $task_status)->get();
        // dd($data2);
        if (count($data2)) {
            return Excel::download(new ExportTask($data), $title.'.xlsx');
        }
        else
        {   
            return redirect()->back()->with('error', 'No Data Available');
        }

    }

    public function SubAdminRecurringReport($id)
    {
        // dd($id);
        $data['task_id'] = $id;
        $data2 = TaskDetails::where('task_id', $id)->get();
        // dd($data2);
        if (count($data2)) {
            return Excel::download(new ExportRecurringTask($data), 'Recurring.xlsx');
        }
        else
        {   
            return redirect()->back()->with('error', 'No Data Available');
        }
    }

    public function StaffImport()
    {
        $sub_admin = User::where('user_type', 'sub_admin')->where('admin_id', Auth::user()->id)->where('status', 'active')->get();
        return view('sub_admin.import', compact('sub_admin'));
    }

    public function StaffImportStore(Request $request)
    {
        // dd($request->all());

        $this->validate($request, [
          'excel_file'  => 'required|mimes:xls,xlsx'
         ]);

        $path = $request->file('excel_file');

        $data['admin_id'] = Auth::user()->admin_id;
        $data['sub_admin_id'] = Auth::user()->id;

        $check_data = Excel::import(new ImportUsers($data), $path);
        
        if ($check_data) {
            return redirect()->back()->with('success', 'Staff Import Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

}
