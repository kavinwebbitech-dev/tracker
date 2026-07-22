<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Client;
use Auth;
use Mail;
use PDF;
use File;
use Excel;
use DB;
use App\Imports\ImportClients;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ClientIndex()
    {
        $client_details = Client::get();
        $service_details = Service::get();
        return view('admin.client', compact('service_details', 'client_details'));
    }

    public function ClientStore(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = Client::find($request->conference_id);
        }
        else
        {
            $pages          = new Client();
        }
        $pages->cat_id      = $request->cat_id;
        $pages->name        = $request->name;
        $pages->email       = $request->email;
        $pages->phone       = $request->phone;
        $pages->address     = $request->address;
        $pages->gst_number  = $request->gst_number;
        $pages->company_name = $request->company_name;
        $pages->status      = $request->status;
        $pages->description = $request->description;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Client Updated Successfully');
            }
            return redirect()->back()->with('success', 'Client Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function ClientEdit(Request $request)
    {

        $pages                  = Client::find($request->id);
        $data['cat_id']         = $pages->cat_id;
        $data['name']           = $pages->name;
        $data['email']          = $pages->email;
        $data['phone']          = $pages->phone;
        $data['address']        = $pages->address;
        $data['gst_number']     = $pages->gst_number;
        $data['company_name']   = $pages->company_name;
        $data['status']         = $pages->status;
        $data['description']    = $pages->description;
        $data['conference_id']  = $request->id;

        return $data;
    }

    public function ClientDelete($id)
    {
        $pages                  = Client::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Client Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function index()
    {
        $service_details = Service::get();
        return view('admin.service', compact('service_details'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages      = Service::find($request->conference_id);
        }
        else
        {
            $pages      = new Service();
        }
        $pages->name   = $request->title;
        $pages->status  = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Service Updated Successfully');
            }
            return redirect()->back()->with('success', 'Service Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {

        $pages                  = Service::find($request->id);
        $data['title']          = $pages->name;
        $data['status']         = $pages->status;
        $data['conference_id']  = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages                  = Service::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Service Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function clientImportStore(Request $request)
    {
        // dd($request->all());

        $this->validate($request, [
          'bulk_upoad_file'  => 'required|mimes:xls,xlsx'
         ]);
        $path = $request->file('bulk_upoad_file');

        $check_data = Excel::import(new ImportClients(), $path);
        
        if ($check_data) {
            return redirect()->back()->with('success', 'Client Import Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }


    public function StaffClientIndex()
    {
        $client_details = Client::get();
        $service_details = Service::get();
        return view('staff.client', compact('service_details', 'client_details'));
    }

    public function StaffClientStore(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = Client::find($request->conference_id);
        }
        else
        {
            $pages          = new Client();
        }
        $pages->cat_id      = $request->cat_id;
        $pages->name        = $request->name;
        $pages->email       = $request->email;
        $pages->phone       = $request->phone;
        $pages->address     = $request->address;
        $pages->gst_number  = $request->gst_number;
        $pages->company_name = $request->company_name;
        $pages->status      = $request->status;
        $pages->description = $request->description;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Client Updated Successfully');
            }
            return redirect()->back()->with('success', 'Client Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function StaffClientEdit(Request $request)
    {

        $pages                  = Client::find($request->id);
        $data['cat_id']         = $pages->cat_id;
        $data['name']           = $pages->name;
        $data['email']          = $pages->email;
        $data['phone']          = $pages->phone;
        $data['address']        = $pages->address;
        $data['gst_number']     = $pages->gst_number;
        $data['company_name']   = $pages->company_name;
        $data['status']         = $pages->status;
        $data['description']    = $pages->description;
        $data['conference_id']  = $request->id;

        return $data;
    }

    public function StaffClientDelete($id)
    {
        $pages                  = Client::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Client Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Staffindex()
    {
        $service_details = Service::get();
        return view('staff.service', compact('service_details'));
    }

    public function Staffstore(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages      = Service::find($request->conference_id);
        }
        else
        {
            $pages      = new Service();
        }
        $pages->name   = $request->title;
        $pages->status  = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Service Updated Successfully');
            }
            return redirect()->back()->with('success', 'Service Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Staffedit(Request $request)
    {

        $pages                  = Service::find($request->id);
        $data['title']          = $pages->name;
        $data['status']         = $pages->status;
        $data['conference_id']  = $request->id;

        return $data;
    }

    public function Staffdelete($id)
    {
        $pages                  = Service::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Service Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function StaffclientImportStore(Request $request)
    {
        // dd($request->all());

        $this->validate($request, [
          'bulk_upoad_file'  => 'required|mimes:xls,xlsx'
         ]);
        $path = $request->file('bulk_upoad_file');

        $check_data = Excel::import(new ImportClients(), $path);
        
        if ($check_data) {
            return redirect()->back()->with('success', 'Client Import Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function SubAdminindex()
    {
        $service_details = Service::get();
        return view('sub_admin.service', compact('service_details'));
    }

    public function SubAdminstore(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages      = Service::find($request->conference_id);
        }
        else
        {
            $pages      = new Service();
        }
        $pages->name   = $request->title;
        $pages->status  = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Service Updated Successfully');
            }
            return redirect()->back()->with('success', 'Service Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminedit(Request $request)
    {

        $pages                  = Service::find($request->id);
        $data['title']          = $pages->name;
        $data['status']         = $pages->status;
        $data['conference_id']  = $request->id;

        return $data;
    }

    public function SubAdmindelete($id)
    {
        $pages                  = Service::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Service Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }


}
