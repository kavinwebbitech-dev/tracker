<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskDetails;
use App\Imports\ImportUsers;
use App\Exports\ExportTask;
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

    public function SubAdminCreate()
    {
        // $admin = User::where('user_type', 'admin')->get();
        return view('second_admin.subAdmin.add');
    }

    public function SubAdminView()
    {
        // $whatsapp_cloud_api = new WhatsAppCloudApi([
        //     'from_phone_number_id' => 'your-configured-from-phone-number-id',
        //     'access_token' => 'your-facebook-whatsapp-application-token',
        // ]);
        // dd($whatsapp_cloud_api);
        // $test = $whatsapp_cloud_api->sendTextMessage('34676104574', 'Hey there! I\'m using WhatsApp Cloud API. Visit https://www.netflie.es');
        // dd($test);
        $sub_admin = User::where('user_type', 'sub_admin')->get();
        return view('second_admin.subAdmin.view', compact('sub_admin'));
    }

    public function SubAdminEdit($id)
    {
        $admin = User::where('user_type', 'admin')->get();
        $sub_admin = User::where('id', $id)->first();
        return view('second_admin.subAdmin.edit', compact('sub_admin', 'admin'));
    }

    public function SubAdminDelete($id)
    {
        if ($id) {
            $user  = User::find($id);
            if ($user->delete()) {
                return redirect()->route('admin.sub.admin.view')->with('error', 'Sub Admin Deleted Successfully');
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminStore(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users',
            'password' => 'required|confirmed'
        ]);

        $password           = Hash::make($request->password);
        $user               = new User();
        $user->name         = $request->name;
        $user->admin_id     = Auth::user()->id;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->user_type    = "sub_admin";
        $user->status       = "active";
        $user->password     = $password;
        if ($user->save()) 
        {
            
            $data['name']          = $request->name;
            $data['email']         = $request->email;
            $data['password']      = $request->password;
            $data['logo']          = "https://manchesters.in/assets/images/mainHome/logo.png";
            $data['link_url']      = url('/login');

            try{
                Mail::send('mail.user_details', $data, function($message)use($data) {
                    $message->to($data['email'], $data['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                    ->subject('Welcome to Manchesters Family');
                });
            }
            catch(\Exception $e){
                // dd($e);
            }

            return redirect()->route('admin.sub.admin.view')->with('success', 'Sub Admin Created Successfully');
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminUpdate(Request $request, $id)
    {
        // dd($request->all());

        $user               = User::find($id);
        if ($request->password) {
            $request->validate([
                'name' => 'required|max:255',
                'password' => 'required|confirmed'
            ]);

            $password           = Hash::make($request->password);
            $user->password     = $password;
        }
        $user->admin_id     = Auth::user()->id;
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->user_type    = "sub_admin";
        $user->status       = "active";
        if ($user->save()) {
            return redirect()->route('admin.sub.admin.view')->with('warning', 'Sub Admin Edited Successfully');
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubStaffCreate()
    {
        $sub_admin = User::where('admin_id', Auth::user()->id)->where('user_type', 'sub_admin')->get();
        // dd($sub_admin);
        return view('second_admin.staff.add', compact('sub_admin'));
    }

    public function SubStaffView()
    {
        $sub_admin = User::where('admin_id', Auth::user()->id)->where('user_type', 'staff')->get();
        return view('second_admin.staff.view', compact('sub_admin'));
    }


    public function SubStafTaskDetails()
    {
        $custom_task = Task::where('admin_id', Auth::user()->id)->where('task_type', 'custom')->get();
        $recurring_task = Task::where('admin_id', Auth::user()->id)->where('task_type', 'recurring')->get();
        return view('second_admin.staff.task_view', compact('custom_task', 'recurring_task'));
    }

    public function SubStaffEdit($id)
    {
        $staff = User::where('id', $id)->first();
        $sub_admin = User::where('admin_id', Auth::user()->id)->where('user_type', 'sub_admin')->get();
        // dd($sub_admin);
        return view('second_admin.staff.edit', compact('sub_admin', 'staff'));
    }

    public function SubStaffDelete($id)
    {
        if ($id) {
            $user  = User::find($id);
            if ($user->delete()) {
                return redirect()->route('admin.sub.staff.view')->with('error', 'Staff Deleted Successfully');
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

        $password           = Hash::make($request->password);
        $user               = new User();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->sub_admin_id = $request->sub_admin;
        $user->admin_id     = Auth::user()->id;
        $user->user_type    = "staff";
        $user->status       = "active";
        $user->password     = $password;
        if ($user->save()) {

            $data['name']          = $request->name;
            $data['email']         = $request->email;
            $data['password']      = $request->password;
            $data['logo']          = "https://manchesters.in/assets/images/mainHome/logo.png";
            $data['link_url']      = url('/login');

            try{
                Mail::send('mail.user_details', $data, function($message)use($data) {
                    $message->to($data['email'], $data['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                    ->subject('Welcome to Manchesters Family');
                });
            }
            catch(\Exception $e){
                // dd($e);
            }

            return redirect()->route('admin.sub.staff.view')->with('success', 'Staff Created Successfully');
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
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->sub_admin_id = $request->sub_admin;
        $user->admin_id     = Auth::user()->id;
        $user->user_type    = "staff";
        $user->status       = "active";
        if ($user->save()) {
            return redirect()->route('admin.sub.staff.view')->with('warning', 'Staff Edited Successfully');
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function StaffImport()
    {
        $sub_admin = User::where('user_type', 'sub_admin')->where('admin_id', Auth::user()->id)->get();
        return view('second_admin.import', compact('sub_admin'));
    }

    public function StaffImportStore(Request $request)
    {
        // dd($request->all());

        $this->validate($request, [
          'excel_file'  => 'required|mimes:xls,xlsx'
         ]);
        $path = $request->file('excel_file');
        
        $data['admin_id'] = Auth::user()->id;
        $data['sub_admin_id'] = $request->user_type;

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
