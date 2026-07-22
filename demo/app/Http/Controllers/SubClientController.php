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

class SubClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ClientIndex()
    {
        $client_details = Client::get();
        $service_details = Service::get();
        return view('sub_admin.client', compact('service_details', 'client_details'));
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

    
}
