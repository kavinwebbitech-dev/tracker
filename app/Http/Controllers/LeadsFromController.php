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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LeadsFromController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function view()
    // {
    //     $start_date = '';
    //     $end_date = '';
    //     $status = '';
    //     $salesperson1 = '';
    //     $service_get1 = '';
    //     $leads_from = LeadsFrom::latest();

    //     if (request('start_date') && request('start_date') != '') {
    //         $start_date = request('start_date');
    //     }
    //     if (request('end_date') && request('end_date') != '') {
    //         $end_date = request('end_date');
    //     }
    //     if (request('service') && request('service') != '') {
    //         $service_get1 = request('service');
    //     }
    //     if (request('status') && request('status') != '' || request('status') == 0) {
    //         $status = request('status');
    //     }
    //     if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
    //         $salesperson1 = request('salesperson');
    //     }

    //     if ($start_date) {
    //         $leads_from = $leads_from->whereDate('created_at', '>=', $start_date)
    //             ->whereDate('created_at', '<=', $end_date);
    //     } else {
    //         $leads_from = $leads_from;
    //     }
    //     if ($salesperson1) {
    //         $leads_from = $leads_from->where('added_by', $salesperson1);
    //     } else {
    //         $leads_from = $leads_from;
    //     }

    //     if ($service_get1) {
    //         $leads_from = $leads_from->where('service', $service_get1);
    //     } else {
    //         $leads_from = $leads_from;
    //     }
    //     if ($status == "all") {
    //         $leads_from = $leads_from->paginate(100);
    //     } elseif ($status) {
    //         $leads_from = $leads_from->where('status', $status)->paginate(100);
    //     } else {
    //         $leads_from = $leads_from->paginate(100);
    //     }

    //     // dd($leads_from);

    //     $user_list = \App\Models\LeadsFrom::groupBy('added_by')
    //         ->get(['added_by']);

    //     $salesperson = $user_list;

    //     $service = Service::where('status', 'Active')->get();
    //     $service_id = Service::where('status', 'Active')->get();
    //     $lead_status = LeadStatus::where('status', 'Active')->get();
    //     return view('admin.leads_from', compact('leads_from', 'service', 'service_id', 'lead_status', 'salesperson', 'start_date', 'end_date', 'salesperson1', 'service_get1', 'status'));
    // }
    public function view()
    {
        $start_date = request('start_date') ?: '';
        $end_date = request('end_date') ?: '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $leads_from = LeadsFrom::latest();

        if (request('service') && request('service') != '') {
            $service_get1 = request('service');
        }
        if (request('status') && request('status') != '' || request('status') == 0) {
            $status = request('status');
        }
        if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
            $salesperson1 = request('salesperson');
        }

        // ✅ each bound applied independently, no cross-dependency
        if ($start_date != '') {
            $leads_from = $leads_from->whereDate('created_at', '>=', $start_date);
        }
        if ($end_date != '') {
            $leads_from = $leads_from->whereDate('created_at', '<=', $end_date);
        }

        if ($salesperson1) {
            $leads_from = $leads_from->where('added_by', $salesperson1);
        }

        if ($service_get1) {
            $leads_from = $leads_from->where('service', $service_get1);
        }

        if ($status == "all") {
            $leads_from = $leads_from->paginate(100);
        } elseif ($status) {
            $statusName = LeadStatus::where('id', $status)->value('name');
            $leads_from = $leads_from->where(function ($q) use ($status, $statusName) {
                $q->where('status', $status)
                    ->orWhere('status', $statusName);
            })->paginate(100);
        } else {
            $leads_from = $leads_from->paginate(100);
        }

        $user_list = \App\Models\LeadsFrom::groupBy('added_by')->get(['added_by']);
        $salesperson = $user_list;

        $service = Service::where('status', 'Active')->get();
        $service_id = Service::where('status', 'Active')->get();
        $lead_status = LeadStatus::where('status', 'Active')->get();

        return view('admin.leads_from', compact(
            'leads_from',
            'service',
            'service_id',
            'lead_status',
            'salesperson',
            'start_date',
            'end_date',
            'salesperson1',
            'service_get1',
            'status'
        ));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        if ($request->conference_id) {
            $pages          = LeadsFrom::find($request->conference_id);
        } else {
            $pages          = new LeadsFrom();
            $pages->added_by  = Auth::user()->id;
        }
        $pages->name           = $request->name;
        $pages->business_name  = $request->business_name;
        $pages->contact_no     = $request->contact_no;
        $pages->service        = $request->service;
        $pages->status         = $request->status;
        // $pages->dob            = $request->dob;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Leads From Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Leads From Details Added Successfully');
        } else {
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
        // $data['dob']                = $pages->dob;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages                  = LeadsFrom::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Leads From Deleted Successfully');
        } else {
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
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    // public function DownloadLeads()
    // {
    //     // dd(request('start_date'));
    //     $start_date = '';
    //     $end_date = '';
    //     $status = '';
    //     $salesperson1 = '';
    //     $service_get1 = '';
    //     $leads_from = LeadsFrom::latest();

    //     if (request('start_date') && request('start_date') != '') {
    //         $start_date = request('start_date');
    //     }
    //     if (request('end_date') && request('end_date') != '') {
    //         $end_date = request('end_date');
    //     }
    //     if (request('service') && request('service') != '') {
    //         $service_get1 = request('service');
    //     }
    //     if (request('status') && request('status') != '' || request('status') == 0) {
    //         $status = request('status');
    //     }
    //     if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
    //         $salesperson1 = request('salesperson');
    //     }

    //     if ($start_date) {
    //         $leads_from = $leads_from->whereDate('created_at', '>=', $start_date)
    //             ->whereDate('created_at', '<=', $end_date);
    //     } else {
    //         $leads_from = $leads_from;
    //     }
    //     if ($salesperson1) {
    //         $leads_from = $leads_from->where('added_by', $salesperson1);
    //     } else {
    //         $leads_from = $leads_from;
    //     }

    //     if ($service_get1) {
    //         $leads_from = $leads_from->where('service', $service_get1);
    //     } else {
    //         $leads_from = $leads_from;
    //     }
    //     if ($status == "all") {
    //         $leads_from = $leads_from->get();
    //     } elseif ($status) {
    //         $leads_from = $leads_from->where('status', $status)->get();
    //     } else {
    //         $leads_from = $leads_from->get();
    //     }

    //     return Excel::download(new LeadsFormExport($leads_from), 'Lead Status.xlsx');
    // }

    public function DownloadLeads()
    {
        $start_date = request('start_date') ?: '';
        $end_date = request('end_date') ?: '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $leads_from = LeadsFrom::latest();

        if (request('service') && request('service') != '') {
            $service_get1 = request('service');
        }
        if (request('status') && request('status') != '' || request('status') == 0) {
            $status = request('status');
        }
        if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
            $salesperson1 = request('salesperson');
        }

        // ✅ independent date bounds — fixes only-one-date-picked and empty-string comparison
        if ($start_date != '') {
            $leads_from = $leads_from->whereDate('created_at', '>=', $start_date);
        }
        if ($end_date != '') {
            $leads_from = $leads_from->whereDate('created_at', '<=', $end_date);
        }

        if ($salesperson1) {
            $leads_from = $leads_from->where('added_by', $salesperson1);
        }

        if ($service_get1) {
            $leads_from = $leads_from->where('service', $service_get1);
        }

        // ✅ same old-text vs new-id status mismatch fix as the view() method
        if ($status == "all") {
            $leads_from = $leads_from->get();
        } elseif ($status) {
            $statusName = LeadStatus::where('id', $status)->value('name');
            $leads_from = $leads_from->where(function ($q) use ($status, $statusName) {
                $q->where('status', $status)
                    ->orWhere('status', $statusName);
            })->get();
        } else {
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
        } else {
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
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }


    // Sub Admin Leads Form
    // public function Subview()
    // {
    //     $leads_from = LeadsFrom::get();
    //     $service = Service::where('status', 'Active')->get();
    //     $service_id = Service::where('status', 'Active')->get();
    //     $lead_status = LeadStatus::where('status', 'Active')->get();
    //     return view('sub_admin.leads_from', compact('leads_from', 'service', 'service_id', 'lead_status'));
    // }

    // public function Subview()
    // {
    //     $start_date = '';
    //     $end_date = '';
    //     $status = '';
    //     $salesperson1 = '';
    //     $service_get1 = '';
    //     $leads_from = LeadsFrom::latest();

    //     if (request('start_date') && request('start_date') != '') {
    //         $start_date = request('start_date');
    //     }
    //     if (request('end_date') && request('end_date') != '') {
    //         $end_date = request('end_date');
    //     }
    //     if (request('service') && request('service') != '') {
    //         $service_get1 = request('service');
    //     }
    //     if (request('status') && request('status') != '' || request('status') == 0) {
    //         $status = request('status');
    //     }
    //     if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
    //         $salesperson1 = request('salesperson');
    //     }

    //     if ($start_date) {
    //         $leads_from = $leads_from->whereDate('created_at', '>=', $start_date)
    //             ->whereDate('created_at', '<=', $end_date);
    //     }
    //     if ($salesperson1) {
    //         $leads_from = $leads_from->where('added_by', $salesperson1);
    //     }
    //     if ($service_get1) {
    //         $leads_from = $leads_from->where('service', $service_get1);
    //     }
    //     if ($status == "all") {
    //         $leads_from = $leads_from->paginate(100);
    //     } elseif ($status) {
    //         $leads_from = $leads_from->where('status', $status)->paginate(100);
    //     } else {
    //         $leads_from = $leads_from->paginate(100);
    //     }

    //     $user_list = \App\Models\LeadsFrom::groupBy('added_by')->get(['added_by']);
    //     $salesperson = $user_list;

    //     $service = Service::where('status', 'Active')->get();
    //     $service_id = Service::where('status', 'Active')->get();
    //     $lead_status = LeadStatus::where('status', 'Active')->get();

    //     return view('sub_admin.leads_from', compact(
    //         'leads_from',
    //         'service',
    //         'service_id',
    //         'lead_status',
    //         'salesperson',
    //         'start_date',
    //         'end_date',
    //         'salesperson1',
    //         'service_get1',
    //         'status'
    //     ));
    // }
    public function Subview()
    {
        $start_date = request('start_date') ?: '';
        $end_date = request('end_date') ?: '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $leads_from = LeadsFrom::latest();

        if (request('service') && request('service') != '') {
            $service_get1 = request('service');
        }
        if (request('status') && request('status') != '' || request('status') == 0) {
            $status = request('status');
        }
        if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
            $salesperson1 = request('salesperson');
        }

        // ✅ each bound applied independently, no cross-dependency
        if ($start_date != '') {
            $leads_from = $leads_from->whereDate('created_at', '>=', $start_date);
        }
        if ($end_date != '') {
            $leads_from = $leads_from->whereDate('created_at', '<=', $end_date);
        }

        if ($salesperson1) {
            $leads_from = $leads_from->where('added_by', $salesperson1);
        }

        if ($service_get1) {
            $leads_from = $leads_from->where('service', $service_get1);
        }

        if ($status == "all") {
            $leads_from = $leads_from->paginate(100);
        } elseif ($status) {
            $statusName = LeadStatus::where('id', $status)->value('name');
            $leads_from = $leads_from->where(function ($q) use ($status, $statusName) {
                $q->where('status', $status)
                    ->orWhere('status', $statusName);
            })->paginate(100);
        } else {
            $leads_from = $leads_from->paginate(100);
        }

        $user_list = \App\Models\LeadsFrom::groupBy('added_by')->get(['added_by']);
        $salesperson = $user_list;

        $service = Service::where('status', 'Active')->get();
        $service_id = Service::where('status', 'Active')->get();
        $lead_status = LeadStatus::where('status', 'Active')->get();

        return view('sub_admin.leads_from', compact(
            'leads_from',
            'service',
            'service_id',
            'lead_status',
            'salesperson',
            'start_date',
            'end_date',
            'salesperson1',
            'service_get1',
            'status'
        ));
    }

    public function Substore(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = LeadsFrom::find($request->conference_id);
        } else {
            $pages          = new LeadsFrom();
            $pages->added_by  = Auth::user()->id;
        }
        $pages->name           = $request->name;
        $pages->business_name  = $request->business_name;
        $pages->contact_no     = $request->contact_no;
        $pages->service        = $request->service;
        // $pages->dob            = $request->dob;
        $pages->status         = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Leads From Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Leads From Details Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Subedit(Request $request)
    {
        $pages = LeadsFrom::find($request->id);

        if (!$pages) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        return response()->json([
            'name'          => $pages->name,
            'business_name' => $pages->business_name,
            'contact_no'    => $pages->contact_no,
            'service'       => $pages->service,
            'status'        => $pages->status,
            'conference_id' => $pages->id, // ✅ uncomment/restore
        ]);
    }

    public function Subdelete($id)
    {
        $pages                  = LeadsFrom::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Leads From Deleted Successfully');
        } else {
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
        } else {
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
        } else {
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
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }


    // Staff Leads Form
    public function Staffview()
    {
        $start_date = '';
        $end_date = '';
        $status = '';
        $service_get1 = '';

        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
        }
        if (request('service') && request('service') != '') {
            $service_get1 = request('service');
        }
        if (request('status') && request('status') != '' || request('status') == 0) {
            $status = request('status');
        }

        $leads_from = LeadsFrom::where('added_by', Auth::user()->id)->latest();

        if ($start_date) {
            $leads_from = $leads_from->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date);
        }
        if ($service_get1) {
            $leads_from = $leads_from->where('service', $service_get1);
        }
        if ($status == "all") {
            $leads_from = $leads_from->paginate(100);
        } elseif ($status) {
            $leads_from = $leads_from->where('status', $status)->paginate(100);
        } else {
            $leads_from = $leads_from->paginate(100);
        }

        $service = Service::where('status', 'Active')->get();
        $service_id = Service::where('status', 'Active')->get();
        $lead_status = LeadStatus::where('status', 'Active')->get();

        return view('staff.leads_from', compact(
            'leads_from',
            'service',
            'service_id',
            'lead_status',
            'start_date',
            'end_date',
            'service_get1',
            'status'
        ));
    }

    public function Staffstore(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = LeadsFrom::find($request->conference_id);
        } else {
            $pages          = new LeadsFrom();
            $pages->added_by  = Auth::user()->id;
        }
        $pages->name           = $request->name;
        $pages->business_name  = $request->business_name;
        $pages->contact_no     = $request->contact_no;
        $pages->service        = $request->service;
        // $pages->dob            = $request->dob;
        $pages->status         = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Leads From Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Leads From Details Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Staffedit(Request $request)
    {
        $pages = LeadsFrom::find($request->id);

        if (!$pages) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        return response()->json([
            'name'          => $pages->name,
            'business_name' => $pages->business_name,
            'contact_no'    => $pages->contact_no,
            'service'       => $pages->service,
            'status'        => $pages->status,
            'conference_id' => $pages->id,
        ]);
    }
    public function Staffdelete($id)
    {
        $pages                  = LeadsFrom::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Leads From Deleted Successfully');
        } else {
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
        } else {
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
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }




    public function whatsappview()
    {
        $start_date = '';
        $end_date = '';
        $status = request('status', 'Phone Not Picked'); // default status
        $service_get1 = '';

        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
        }
        if (request('service') && request('service') != '') {
            $service_get1 = request('service');
        }

        $leads_from = LeadsFrom::latest();

        if ($start_date) {
            $leads_from = $leads_from->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date);
        }
        if ($service_get1) {
            $leads_from = $leads_from->where('service', $service_get1);
        }

        if ($status == "all") {
            $leads_from = $leads_from->paginate(100);
        } elseif ($status) {
            $leads_from = $leads_from->where('status', $status)->paginate(100);
        } else {
            $leads_from = $leads_from->paginate(100);
        }

        $service = Service::where('status', 'Active')->get();
        $service_id = Service::where('status', 'Active')->get();
        $lead_status = LeadStatus::where('status', 'Active')->get();

        return view('staff.leads_whatsapp', compact(
            'leads_from',
            'service',
            'service_id',
            'lead_status',
            'start_date',
            'end_date',
            'service_get1',
            'status'
        ));
    }
    public function whatsappReport(Request $request)
    {
        $pages = LeadsFrom::find($request->leads_from);
        if ($pages) {
            $pages->status = $request->service; // "service" field here actually carries the selected status value
            $pages->save();
            return redirect()->back()->with('success', 'Leads From Status Update Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function whatsappbulkviewReport(Request $request)
    {
        $request->validate([
            'leads_from' => 'required|array|min:1',
            'service'    => 'required|string', // carries selected status value
        ]);

        $updated = LeadsFrom::whereIn('id', $request->leads_from)
            ->update(['status' => $request->service]);

        if ($updated) {
            return redirect()->back()->with('success', $updated . ' Lead(s) Status Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function bulk_delete(Request $request)
    {
        $request->validate(['lead_ids' => 'required|array']);
        LeadsFrom::whereIn('id', $request->lead_ids)->delete();
        return redirect()->back()->with('error', count($request->lead_ids) . ' Lead(s) Deleted Successfully');
    }

    public function bulk_send(Request $request)
    {
        $request->validate([
            'lead_ids'   => 'required|array|min:1',
            'message'    => 'required|string',
            'image'      => 'nullable|image|max:5120', // 5MB
        ]);

        $leads = LeadsFrom::whereIn('id', $request->lead_ids)->get();

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Save directly into public/uploads/whatsapp folder — no symlink needed
            $destinationPath = public_path('uploads/whatsapp');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);

            $imageUrl = url('uploads/whatsapp/' . $fileName); // must be publicly reachable by WhatsApp API
        }

        $sent = 0;
        $failed = 0;
        $errors = [];

        $token   = config('services.whatsapp.token');
        $phoneId = config('services.whatsapp.phone_id');

        foreach ($leads as $lead) {
            $to = preg_replace('/\D/', '', $lead->contact_no);
            if (!$to) {
                $failed++;
                $errors[] = "Lead #{$lead->id}: invalid phone number";
                continue;
            }

            try {
                if ($imageUrl) {
                    $payload = [
                        'messaging_product' => 'whatsapp',
                        'to' => $to,
                        'type' => 'image',
                        'image' => [
                            'link' => $imageUrl,
                            'caption' => $request->message,
                        ],
                    ];
                } else {
                    $payload = [
                        'messaging_product' => 'whatsapp',
                        'to' => $to,
                        'type' => 'text',
                        'text' => ['body' => $request->message],
                    ];
                }

                $response = Http::withToken($token)
                    ->post("https://graph.facebook.com/v20.0/{$phoneId}/messages", $payload);

                if ($response->successful()) {
                    $sent++;
                } else {
                    $failed++;
                    $errors[] = "Lead #{$lead->id}: " . $response->body();
                    Log::error('WhatsApp send failed', [
                        'lead_id' => $lead->id,
                        'response' => $response->body(),
                    ]);
                }
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = "Lead #{$lead->id}: " . $e->getMessage();
                Log::error('WhatsApp send exception', [
                    'lead_id' => $lead->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'message' => "Sent: {$sent}, Failed: {$failed}" . ($failed ? ' — check logs for details.' : ''),
            'sent' => $sent,
            'failed' => $failed,
        ]);
    }



    // public function whatsappDownloadLeads(Request $request)
    // {
    //     $status = 'Phone not picked'; // always locked

    //     $leads_from = LeadsFrom::where('status', $status) // no added_by restriction - download covers all
    //         ->latest();

    //     // Only apply date filter if BOTH dates are explicitly passed
    //     if ($request->filled('start_date') && $request->filled('end_date')) {
    //         // Explicit filter provided - use exactly that range
    //         $leads_from = $leads_from->whereDate('created_at', '>=', $request->start_date)
    //             ->whereDate('created_at', '<=', $request->end_date);
    //     } else {
    //         // No filter given - default to current year only, matching table behavior
    //         $leads_from = $leads_from->whereYear('created_at', now()->year);
    //     }

    //     // Only apply service filter if explicitly passed - otherwise ALL services included
    //     if ($request->filled('service')) {
    //         $leads_from = $leads_from->where('service', $request->service);
    //     }

    //     $leads_from = $leads_from->get();

    //     return Excel::download(new LeadsFormExport($leads_from), 'Whatsapp Leads.xlsx');
    // }
    //     public function whatsappDownloadLeads(Request $request)
    // {
    //     ini_set('memory_limit', '512M');
    //     set_time_limit(300);

    //     $leads_from = LeadsFrom::whereRaw('LOWER(status) = ?', ['phone not picked'])->latest();

    //     if ($request->filled('start_date') && $request->filled('end_date')) {
    //         $leads_from = $leads_from->whereDate('created_at', '>=', $request->start_date)
    //             ->whereDate('created_at', '<=', $request->end_date);
    //     } else {
    //         $leads_from = $leads_from->whereYear('created_at', now()->year);
    //     }

    //     if ($request->filled('service')) {
    //         $leads_from = $leads_from->where('service', $request->service);
    //     }

    //     $leads_from = $leads_from->get();

    //     return Excel::download(new LeadsFormExport($leads_from), 'Whatsapp Leads.xlsx');
    // }


    public function subwhatsappview()
    {
        $start_date = '';
        $end_date = '';
        $status = request('status', 'Phone Not Picked'); // default status
        $service_get1 = '';

        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
        }
        if (request('service') && request('service') != '') {
            $service_get1 = request('service');
        }

        $leads_from = LeadsFrom::latest();

        if ($start_date) {
            $leads_from = $leads_from->whereDate('created_at', '>=', $start_date)
                ->whereDate('created_at', '<=', $end_date);
        }
        if ($service_get1) {
            $leads_from = $leads_from->where('service', $service_get1);
        }

        if ($status == "all") {
            $leads_from = $leads_from->paginate(100);
        } elseif ($status) {
            $leads_from = $leads_from->where('status', $status)->paginate(100);
        } else {
            $leads_from = $leads_from->paginate(100);
        }

        $service = Service::where('status', 'Active')->get();
        $service_id = Service::where('status', 'Active')->get();
        $lead_status = LeadStatus::where('status', 'Active')->get();

        return view('sub_admin.leads_whatsapp', compact(
            'leads_from',
            'service',
            'service_id',
            'lead_status',
            'start_date',
            'end_date',
            'service_get1',
            'status'
        ));
    }
    public function subwhatsappReport(Request $request)
    {
        $pages = LeadsFrom::find($request->leads_from);
        if ($pages) {
            $pages->status = $request->service; // "service" field here actually carries the selected status value
            $pages->save();
            return redirect()->back()->with('success', 'Leads From Status Update Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function subwhatsappbulkviewReport(Request $request)
    {
        $request->validate([
            'leads_from' => 'required|array|min:1',
            'service'    => 'required|string', // carries selected status value
        ]);

        $updated = LeadsFrom::whereIn('id', $request->leads_from)
            ->update(['status' => $request->service]);

        if ($updated) {
            return redirect()->back()->with('success', $updated . ' Lead(s) Status Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function subbulk_delete(Request $request)
    {
        $request->validate(['lead_ids' => 'required|array']);
        LeadsFrom::whereIn('id', $request->lead_ids)->delete();
        return redirect()->back()->with('error', count($request->lead_ids) . ' Lead(s) Deleted Successfully');
    }


    public function whatsappDownloadLeads(Request $request)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $statusName = 'Phone not picked'; // matches the locked filter on this view
        $statusId = LeadStatus::where('name', $statusName)->value('id');

        $leads_from = LeadsFrom::where(function ($q) use ($statusName, $statusId) {
            $q->whereRaw('LOWER(status) = ?', [strtolower($statusName)]);
            if ($statusId) {
                $q->orWhere('status', $statusId);
            }
        })->latest();

        if ($request->filled('start_date')) {
            $leads_from = $leads_from->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $leads_from = $leads_from->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('service')) {
            $leads_from = $leads_from->where('service', $request->service);
        }

        $leads_from = $leads_from->get();

        return Excel::download(new LeadsFormExport($leads_from), 'Whatsapp Leads.xlsx');
    }
}
