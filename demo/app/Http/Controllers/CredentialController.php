<?php

namespace App\Http\Controllers;

use App\Models\Credential;
use App\Imports\ImportCredentials;
use Auth;
use Excel;
use Illuminate\Http\Request;

class CredentialController extends Controller
{

    public function view()
    {
        
        $credential_details = Credential::latest()->get();
        return view('admin.Credential', compact('credential_details'));

    }

    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = Credential::find($request->conference_id);
        }
        else
        {
            $pages          = new Credential();
        }
        $pages->added_by    = Auth::user()->id;
        $pages->name        = $request->name;
        $pages->username    = $request->username;
        $pages->password    = $request->password;
        $pages->description = $request->description;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Credential Updated Successfully');
            }
            return redirect()->back()->with('success', 'Credential Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {

        $pages                  = Credential::find($request->id);
        $data['title']          = $pages->name;
        $data['username']      = $pages->username;
        $data['password']       = $pages->password;
        $data['description']    = $pages->description;
        $data['conference_id']  = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages                  = Credential::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Credential Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function bulk_upload(Request $request)
    {
        $this->validate($request, [
          'bulk_upoad_file'  => 'required|mimes:xls,xlsx'
         ]);
        $path = $request->file('bulk_upoad_file');

        $check_data = Excel::import(new ImportCredentials(), $path);

        if ($check_data) {
            return redirect()->back()->with('success', 'Credential Bulk Import Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubView()
    {
        
        $credential_details = Credential::latest()->get();
        return view('sub_admin.Credential', compact('credential_details'));

    }

    public function SubStore(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages          = Credential::find($request->conference_id);
        }
        else
        {
            $pages          = new Credential();
        }
        $pages->added_by    = Auth::user()->id;
        $pages->name        = $request->name;
        $pages->username    = $request->username;
        $pages->password    = $request->password;
        $pages->description = $request->description;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Credential Updated Successfully');
            }
            return redirect()->back()->with('success', 'Credential Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubEdit(Request $request)
    {

        $pages                  = Credential::find($request->id);
        $data['title']          = $pages->name;
        $data['username']      = $pages->username;
        $data['password']       = $pages->password;
        $data['description']    = $pages->description;
        $data['conference_id']  = $request->id;

        return $data;
    }

    public function SubDelete($id)
    {
        $pages                  = Credential::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Credential Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubBulk_upload(Request $request)
    {
        $this->validate($request, [
          'bulk_upoad_file'  => 'required|mimes:xls,xlsx'
         ]);
        $path = $request->file('bulk_upoad_file');

        $check_data = Excel::import(new ImportCredentials(), $path);

        if ($check_data) {
            return redirect()->back()->with('success', 'Credential Bulk Import Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }
    public function credentialsBulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        Credential::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Selected Career deleted successfully!'
        ]);
    }

}