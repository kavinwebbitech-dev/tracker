<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Service;
use App\Imports\FollowUpImport;
use App\Exports\FollowUpExport;
use Storage;
use File;
use Excel;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $client_details = Customers::where('customer_type', 2)->orderBy('fld_createdon', 'desc')->paginate(100);
        return view('admin.follow_up', compact('client_details'));
    }

    public function export()
    {
        return Excel::download(new FollowUpExport(), 'Follow Up Customers.xlsx');
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
        $pages->customer_type       = 2;
        $pages->fld_name            = $request->fld_name;
        $pages->fld_email           = $request->fld_email;
        $pages->fld_phone           = $request->fld_phone;
        $pages->fld_address         = $request->fld_address;
        $pages->fld_company_name    = $request->fld_company_name;
        $pages->fld_customer_gstno  = $request->fld_customer_gstno;
        $pages->fld_status          = $request->fld_status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Follow Up Updated Successfully');
            }
            return redirect()->back()->with('success', 'Follow Up Added Successfully');
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
        $data['fld_status']         = $pages->fld_status;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages                  = Customers::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Follow Up Deleted Successfully');
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

        $check_data = Excel::import(new FollowUpImport(), $path);
        
        if ($check_data) {
            return redirect()->back()->with('success', 'Follow Up Import Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function search(Request $request)
    {

        $text_value_search = $request->text_value_search;

        $users = Customers::where('customer_type', 2)->orderBy('fld_createdon', 'desc');

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

    public function project($id)
    {

        $customers = Customers::where('id', $id)->first();
        $services = Service::where('status', "Active")->orderBy('id', 'desc')->get();

        $user_list = \App\Models\Project::groupBy('added_by')
              ->get(['added_by']);

        $salesperson = $user_list;
        
        return view('admin.projects.follow-add', compact('salesperson', 'customers', 'services'));

    }

    public function SubView()
    {
        $client_details = Customers::where('customer_type', 2)->orderBy('fld_createdon', 'desc')->paginate(100);
        return view('sub_admin.follow_up', compact('client_details'));
    }

    public function SubExport()
    {
        return Excel::download(new FollowUpExport(), 'Follow Up Customers.xlsx');
    }

    public function SubStore(Request $request)
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
        $pages->customer_type       = 2;
        $pages->fld_name            = $request->fld_name;
        $pages->fld_email           = $request->fld_email;
        $pages->fld_phone           = $request->fld_phone;
        $pages->fld_address         = $request->fld_address;
        $pages->fld_company_name    = $request->fld_company_name;
        $pages->fld_customer_gstno  = $request->fld_customer_gstno;
        $pages->fld_status          = $request->fld_status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Follow Up Updated Successfully');
            }
            return redirect()->back()->with('success', 'Follow Up Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubEdit(Request $request)
    {

        $pages                      = Customers::find($request->id);
        $data['fld_name']           = $pages->fld_name;
        $data['fld_email']          = $pages->fld_email;
        $data['fld_phone']          = $pages->fld_phone;
        $data['fld_address']        = $pages->fld_address;
        $data['fld_company_name']   = $pages->fld_company_name;
        $data['fld_customer_gstno'] = $pages->fld_customer_gstno;
        $data['fld_status']         = $pages->fld_status;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function SubDelete($id)
    {
        $pages                  = Customers::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Follow Up Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubImportStore(Request $request)
    {
        // dd($request->all());

        $this->validate($request, [
          'bulk_upoad_file'  => 'required|mimes:xls,xlsx'
         ]);
        $path = $request->file('bulk_upoad_file');

        $check_data = Excel::import(new FollowUpImport(), $path);
        
        if ($check_data) {
            return redirect()->back()->with('success', 'Follow Up Import Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function SubSearch(Request $request)
    {

        $text_value_search = $request->text_value_search;

        $users = Customers::where('customer_type', 2)->orderBy('fld_createdon', 'desc');

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

        $view =  view('sub_admin.follow_up_search', compact('client_details'))->render();

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

    public function SubProject($id)
    {

        $customers = Customers::where('id', $id)->first();
        $services = Service::where('status', "Active")->orderBy('id', 'desc')->get();

        $user_list = \App\Models\Project::groupBy('added_by')
              ->get(['added_by']);

        $salesperson = $user_list;
        
        return view('sub_admin.projects.follow-add', compact('salesperson', 'customers', 'services'));

    }

}
