<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Imports\ImportCustomer;
use Storage;
use File;
use Excel;
use Illuminate\Http\Request;

class AdminCustomersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $client_details = Customers::where('customer_type', 1)->orderBy('fld_createdon', 'desc')->get();
        return view('sub_admin.customers', compact('client_details'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = Customers::find($request->conference_id);
        }
        else
        {
            $pages          = new Customers();
            $pages->fld_createdon       = date('Y-m-d');
            $pages->fld_updatedon       = date('Y-m-d');
        }
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

}
