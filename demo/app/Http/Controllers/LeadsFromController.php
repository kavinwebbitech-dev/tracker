<?php

namespace App\Http\Controllers;

use App\Models\LeadsFrom;
use App\Models\LeadStatus;
use App\Models\Service;
use Auth;
use Excel;
use DB;
use App\Imports\ImportLeads;
use App\Exports\LeadsFormExport;
use Illuminate\Http\Request;

class LeadsFromController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $leads_from = LeadsFrom::latest();

        if(request('start_date') && request('start_date')!=''){
          $start_date = request('start_date');
        }
        if(request('end_date') && request('end_date')!=''){
          $end_date = request('end_date');
        }
        if(request('service') && request('service')!=''){
          $service_get1 = request('service');
        }
        if(request('status') && request('status')!='' || request('status') == 0){
          $status = request('status');
        }
        if(request('salesperson') && request('salesperson')!='' || request('salesperson') == 0){
          $salesperson1 = request('salesperson');
        }

        if ($start_date) {
            $leads_from = $leads_from->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date);
        }
        else
        {
            $leads_from = $leads_from;
        }
        if($salesperson1)
        {
            $leads_from = $leads_from->where('added_by', $salesperson1);
        }
        else
        {
            $leads_from = $leads_from;
        }
        
        if($service_get1)
        {
            $leads_from = $leads_from->where('service', $service_get1);
        }
        else
        {
            $leads_from = $leads_from;
        }
        if ($status == "all") {
            $leads_from = $leads_from->paginate(100);
        }
        elseif($status)
        {
            $leads_from = $leads_from->where('status', $status)->paginate(100);
        }
        else
        {
            $leads_from = $leads_from->paginate(100);
        }

        // dd($leads_from);

        $user_list = \App\Models\LeadsFrom::groupBy('added_by')
              ->get(['added_by']);

        $salesperson = $user_list;

        $service = Service::where('status', 'Active')->get();
        $service_id = Service::where('status', 'Active')->get();
        $lead_status = LeadStatus::where('status', 'Active')->get();
        return view('admin.leads_from', compact('leads_from', 'service', 'service_id', 'lead_status', 'salesperson', 'start_date', 'end_date','salesperson1','service_get1', 'status'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = LeadsFrom::find($request->conference_id);
        }
        else
        {
            $pages          = new LeadsFrom();
            $pages->added_by  = Auth::user()->id;
        }
        $pages->name           = $request->name;
        $pages->business_name  = $request->business_name;
        $pages->contact_no     = $request->contact_no;
        $pages->service        = $request->service;
        $pages->status         = $request->status;
        $pages->dob            = $request->dob;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Leads From Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Leads From Details Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {

        $pages                      = LeadsFrom::find($request->id);
        $data['name']               = $pages->name;
        $data['business_name']      = $pages->business_name;
        $data['contact_no']         = $pages->contact_no;
        $data['service']            = $pages->service;
        $data['status']             = $pages->status;
        $data['dob']                = $pages->dob;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages                  = LeadsFrom::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Leads From Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function doneLeads($id)
    {
        $pages                  = LeadsFrom::find($id);
        if ($pages) {
            $pages->status = "Completed";
            $pages->save();
            return redirect()->back()->with('success', 'Leads From Status Update Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function DownloadLeads()
    {
        // dd(request('start_date'));
        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $leads_from = LeadsFrom::latest();

        if(request('start_date') && request('start_date')!=''){
          $start_date = request('start_date');
        }
        if(request('end_date') && request('end_date')!=''){
          $end_date = request('end_date');
        }
        if(request('service') && request('service')!=''){
          $service_get1 = request('service');
        }
        if(request('status') && request('status')!='' || request('status') == 0){
          $status = request('status');
        }
        if(request('salesperson') && request('salesperson')!='' || request('salesperson') == 0){
          $salesperson1 = request('salesperson');
        }

        if ($start_date) {
            $leads_from = $leads_from->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date);
        }
        else
        {
            $leads_from = $leads_from;
        }
        if($salesperson1)
        {
            $leads_from = $leads_from->where('added_by', $salesperson1);
        }
        else
        {
            $leads_from = $leads_from;
        }
        
        if($service_get1)
        {
            $leads_from = $leads_from->where('service', $service_get1);
        }
        else
        {
            $leads_from = $leads_from;
        }
        if ($status == "all") {
            $leads_from = $leads_from->get();
        }
        elseif($status)
        {
            $leads_from = $leads_from->where('status', $status)->get();
        }
        else
        {
            $leads_from = $leads_from->get();
        }

        return Excel::download(new LeadsFormExport($leads_from), 'Lead Status.xlsx');

    }

    public function bulk_upload(Request $request)
    {
        // dd($request->all());

        $this->validate($request, [
          'upload_file'  => 'required|mimes:xls,xlsx'
         ]);
        $path = $request->file('upload_file');

        $data['service_id'] = $request->service_id;

        $check_data = Excel::import(new ImportLeads($data), $path);

        if ($check_data) {
            return redirect()->back()->with('success', 'Leads From Import Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function Report(Request $request)
    {
        // dd($request->all());
        $pages                  = LeadsFrom::find($request->leads_from);
        if ($pages) {
            $pages->status = $request->service;
            $pages->save();
            return redirect()->back()->with('success', 'Leads From Status Update Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }


    // Sub Admin Leads Form
    public function Subview()
    {
        $leads_from = LeadsFrom::get();
        $service = Service::where('status', 'Active')->get();
        $service_id = Service::where('status', 'Active')->get();
        $lead_status = LeadStatus::where('status', 'Active')->get();
        return view('sub_admin.leads_from', compact('leads_from', 'service', 'service_id', 'lead_status'));
    }

    public function Substore(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = LeadsFrom::find($request->conference_id);
        }
        else
        {
            $pages          = new LeadsFrom();
            $pages->added_by  = Auth::user()->id;
        }
        $pages->name           = $request->name;
        $pages->business_name  = $request->business_name;
        $pages->contact_no     = $request->contact_no;
        $pages->service        = $request->service;
        $pages->dob            = $request->dob;
        $pages->status         = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Leads From Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Leads From Details Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Subedit(Request $request)
    {

        $pages                      = LeadsFrom::find($request->id);
        $data['name']               = $pages->name;
        $data['business_name']      = $pages->business_name;
        $data['contact_no']         = $pages->contact_no;
        $data['service']            = $pages->service;
        $data['dob']                = $pages->dob;
        $data['status']             = $pages->status;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function Subdelete($id)
    {
        $pages                  = LeadsFrom::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Leads From Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Subbulk_upload(Request $request)
    {
        // dd($request->all());

        $this->validate($request, [
          'upload_file'  => 'required|mimes:xls,xlsx'
         ]);
        $path = $request->file('upload_file');

        $data['service_id'] = $request->service_id;

        $check_data = Excel::import(new ImportLeads($data), $path);

        if ($check_data) {
            return redirect()->back()->with('success', 'Leads From Import Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubdoneLeads($id)
    {
        $pages                  = LeadsFrom::find($id);
        if ($pages) {
            $pages->status = "Completed";
            $pages->save();
            return redirect()->back()->with('success', 'Leads From Status Update Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubReport(Request $request)
    {
        // dd($request->all());
        $pages                  = LeadsFrom::find($request->leads_from);
        if ($pages) {
            $pages->status = $request->service;
            $pages->save();
            return redirect()->back()->with('success', 'Leads From Status Update Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }


    // Staff Leads Form
    public function Staffview()
    {
        $leads_from = LeadsFrom::where('added_by', Auth::user()->id)->get();
        $service = Service::where('status', 'Active')->get();
        $service_id = Service::where('status', 'Active')->get();
        $lead_status = LeadStatus::where('status', 'Active')->get();
        return view('staff.leads_from', compact('leads_from', 'service', 'service_id', 'lead_status'));
    }

    public function Staffstore(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = LeadsFrom::find($request->conference_id);
        }
        else
        {
            $pages          = new LeadsFrom();
            $pages->added_by  = Auth::user()->id;
        }
        $pages->name           = $request->name;
        $pages->business_name  = $request->business_name;
        $pages->contact_no     = $request->contact_no;
        $pages->service        = $request->service;
        $pages->dob            = $request->dob;
        $pages->status         = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Leads From Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Leads From Details Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Staffedit(Request $request)
    {

        $pages                      = LeadsFrom::find($request->id);
        $data['name']               = $pages->name;
        $data['business_name']      = $pages->business_name;
        $data['contact_no']         = $pages->contact_no;
        $data['service']            = $pages->service;
        $data['dob']                = $pages->dob;
        $data['status']             = $pages->status;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function Staffdelete($id)
    {
        $pages                  = LeadsFrom::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Leads From Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Staffbulk_upload(Request $request)
    {
        // dd($request->all());

        $this->validate($request, [
          'upload_file'  => 'required|mimes:xls,xlsx'
         ]);
        $path = $request->file('upload_file');

        $data['service_id'] = $request->service_id;

        $check_data = Excel::import(new ImportLeads($data), $path);

        if ($check_data) {
            return redirect()->back()->with('success', 'Leads From Import Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function StaffReport(Request $request)
    {
        // dd($request->all());
        $pages                  = LeadsFrom::find($request->leads_from);
        if ($pages) {
            $pages->status = $request->service;
            $pages->save();
            return redirect()->back()->with('success', 'Leads From Status Update Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

}
