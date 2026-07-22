<?php

namespace App\Http\Controllers;


use App\Models\LeadStatus;
use Auth;
use Excel;
use DB;
use App\Imports\ImportLeads;
use Illuminate\Http\Request;

class LeadStatusController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $lead_status = LeadStatus::get();
        return view('admin.lead_status', compact('lead_status'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = LeadStatus::find($request->conference_id);
        }
        else
        {
            $pages          = new LeadStatus();
        }
        $pages->name        = $request->name;
        $pages->status      = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Leads Status Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Leads Status Details Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {

        $pages                      = LeadStatus::find($request->id);
        $data['name']               = $pages->name;
        $data['status']             = $pages->status;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages                  = LeadStatus::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Leads Status Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }


    // Sub Admin Leads Form
    public function Subview()
    {
        $lead_status = LeadStatus::get();
        return view('sub_admin.lead_status', compact('lead_status'));
    }

    public function Substore(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = LeadStatus::find($request->conference_id);
        }
        else
        {
            $pages          = new LeadStatus();
        }
        $pages->name           = $request->name;
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

        $pages                      = LeadStatus::find($request->id);
        $data['name']               = $pages->name;
        $data['status']             = $pages->status;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function Subdelete($id)
    {
        $pages                  = LeadStatus::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Leads From Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }


}
