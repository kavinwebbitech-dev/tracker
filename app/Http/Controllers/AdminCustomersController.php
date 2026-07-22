<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use Storage;
use File;
use Excel;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exports\CustomerExport;
use App\Imports\ImportCustomer;
use Illuminate\Support\Facades\Auth;

class AdminCustomersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $client_details = Customers::where('customer_type', 1)->orderBy('fld_createdon', 'desc')->paginate(100);
        // $client_details1 = Customers::latest()->get();

        // if ($client_details1) {
        //     foreach ($client_details1 as $key => $value) {

        //         $customer_details                = Customers::find($value->id);
        //         $customer_details->customer_type = 1;
        //         $customer_details->save();

        //     }
        // }
        // dd($client_details);
        return view('admin.customers', compact('client_details'));
    }

    public function export()
    {
        return Excel::download(new CustomerExport(), 'Customers.xlsx');
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $duplicate =Customers::where('fld_phone' , $request->fld_phone);

        if($request->conference_id){
            $duplicate->where('id', $request->conferece_id);
        }

        if($duplicate->exists()){
            return redirect()->back()->withInput()->with('error','Phone number already exists.');
        }

        if ($request->conference_id) {
            $pages          = Customers::find($request->conference_id);
        }
        else
        {
            $pages          = new Customers();
            $pages->fld_createdon       = date('Y-m-d');
           
        }
         $pages->fld_updatedon       = date('Y-m-d');
        $pages->customer_type       = 1;
        $pages->fld_name            = $request->fld_name;
        $pages->fld_email           = $request->fld_email;
        $pages->fld_phone           = $request->fld_phone;
        $pages->fld_address         = $request->fld_address;
        $pages->fld_company_name    = $request->fld_company_name;
        $pages->fld_customer_gstno  = $request->fld_customer_gstno;
        $pages->fld_customer_dob    = $request->fld_customer_dob;
        $pages->fld_status          = $request->fld_status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Customers Updated Successfully');
            }
            return redirect()->back()->with('success', 'Customers Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {

        $pages                      = Customers::find($request->id);
        $data['fld_name']           = $pages->fld_name;
        $data['fld_email']          = $pages->fld_email;
        $data['fld_phone']          = $pages->fld_phone;
        $data['fld_address']        = $pages->fld_address;
        $data['fld_company_name']   = $pages->fld_company_name;
        $data['fld_customer_gstno'] = $pages->fld_customer_gstno;
        $data['fld_customer_dob']   = $pages->fld_customer_dob;
        $data['fld_status']         = $pages->fld_status;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages                  = Customers::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Customers Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function ImportStore(Request $request)
    {
        // dd($request->all());

        $this->validate($request, [
          'bulk_upoad_file'  => 'required|mimes:xls,xlsx'
         ]);
        $path = $request->file('bulk_upoad_file');

        $check_data = Excel::import(new ImportCustomer(), $path);
        
        if ($check_data) {
            return redirect()->back()->with('success', 'Customers Import Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function search(Request $request)
    {

        $text_value_search = $request->text_value_search;

        $users = Customers::where('customer_type', 1)->orderBy('fld_createdon', 'desc');

        if ($text_value_search) {
            $users = $users->where(function($q) use ($text_value_search) { 
                        $q->where('fld_name', 'LIKE', "%".$text_value_search."%")
                        ->orwhere('fld_email', 'LIKE', "%".$text_value_search."%")
                        ->orwhere('fld_phone', 'LIKE', "%".$text_value_search."%")
                        ->orwhere('fld_company_name', 'LIKE', "%".$text_value_search."%")
                        ->orwhere('fld_customer_gstno', 'LIKE', "%".$text_value_search."%");
                        
                    });
        }

        $client_details = $users->get();

        $view =  view('admin.CustomerSearch', compact('client_details'))->render();

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

    public function user_store(Request $request)
    {
        // dd($request->all());

        $password           = Hash::make($request->password);
        $user               = new User();
        $user->name         = $request->fld_name1;
        $user->email        = $request->fld_email1;
        $user->phone        = $request->fld_phone1;
        $user->cus_id       = $request->conference_id1;
        $user->user_type    = "client";
        $user->status       = "active";
        $user->password     = $password;
        $user->save();

        $pages              = Customers::find($request->conference_id1);
        $pages->fld_name    = $request->fld_name1;
        $pages->fld_email   = $request->fld_email1;
        $pages->fld_phone   = $request->fld_phone1;
        $pages->save();

        return redirect()->back()->with('success', 'Client Panel Created Successfully');

    }



    // Staff Customer
    public function Staffview()
    {
        $client_details = Customers::where('customer_type', 1) ->where('added_by', Auth::user()->id)->orderBy('fld_createdon', 'desc')->get();
        return view('staff.customers', compact('client_details'));
    }

    public function Staffstore(Request $request)
    {
        // dd($request->all());

        $duplicate =Customers::where('fld_phone' , $request->fld_phone);

        if($request->conference_id){
            $duplicate->where('id', $request->conferece_id);
        }

        if($duplicate->exists()){
            return redirect()->back()->withInput()->with('error','Phone number already exists.');
        }

        if ($request->conference_id) {
            $pages          = Customers::find($request->conference_id);
        }
        else
        {
            $pages          = new Customers();
            $pages->fld_createdon       = date('Y-m-d');
            $pages->added_by = Auth::user()->id;
        }
        $pages->fld_updatedon       = date('Y-m-d');
        $pages->customer_type       = 1;
        $pages->fld_name            = $request->fld_name;
        $pages->fld_email           = $request->fld_email;
        $pages->fld_phone           = $request->fld_phone;
        $pages->fld_address         = $request->fld_address;
        $pages->fld_company_name    = $request->fld_company_name;
        $pages->fld_customer_gstno  = $request->fld_customer_gstno;
        $pages->fld_customer_dob    = $request->fld_customer_dob;
        $pages->fld_status          = $request->fld_status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Customers Updated Successfully');
            }
            return redirect()->back()->with('success', 'Customers Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Staffedit(Request $request)
    {

        $pages                      = Customers::find($request->id);
        $data['fld_name']           = $pages->fld_name;
        $data['fld_email']          = $pages->fld_email;
        $data['fld_phone']          = $pages->fld_phone;
        $data['fld_address']        = $pages->fld_address;
        $data['fld_company_name']   = $pages->fld_company_name;
        $data['fld_customer_gstno'] = $pages->fld_customer_gstno;
        $data['fld_customer_dob']   = $pages->fld_customer_dob;
        $data['fld_status']         = $pages->fld_status;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function Staffdelete($id)
    {
        $pages                  = Customers::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Customers Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function details($id)
    {
        $customers_details    = Customers::where('id', $id)->first();
        $user_details = User::where('cus_id', $customers_details->id)->first();
        return view('admin.customers_details', compact('customers_details', 'user_details'));
    }

}
