<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use Illuminate\Http\Request;

class AdminBranchesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $client_details = Branches::get();
        return view('admin.branches', compact('client_details'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = Branches::find($request->conference_id);
        }
        else
        {
            $pages          = new Branches();
        }
        $pages->fld_branch_name     = $request->fld_branch_name;
        $pages->fld_status          = $request->fld_status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Branches Updated Successfully');
            }
            return redirect()->back()->with('success', 'Branches Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {

        $pages                      = Branches::find($request->id);
        $data['fld_branch_name']    = $pages->fld_branch_name;
        $data['fld_status']         = $pages->fld_status;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages                  = Branches::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Branches Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }


    public function Staffview()
    {
        $client_details = Branches::get();
        return view('staff.branches', compact('client_details'));
    }

    public function Staffstore(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = Branches::find($request->conference_id);
        }
        else
        {
            $pages          = new Branches();
        }
        $pages->fld_branch_name     = $request->fld_branch_name;
        $pages->fld_status          = $request->fld_status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Branches Updated Successfully');
            }
            return redirect()->back()->with('success', 'Branches Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Staffedit(Request $request)
    {

        $pages                      = Branches::find($request->id);
        $data['fld_branch_name']    = $pages->fld_branch_name;
        $data['fld_status']         = $pages->fld_status;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function Staffdelete($id)
    {
        $pages                  = Branches::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Branches Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

}
