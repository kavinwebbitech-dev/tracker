<?php

namespace App\Http\Controllers;

use App\Models\IncomeCategory;
use Illuminate\Http\Request;

class IncomeCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $IncomeCategory = IncomeCategory::latest()->paginate(100);

        return view('admin.IncomeCategory', compact('IncomeCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required',
            'status' => 'required',
        ]);

        if ($request->conference_id) {
            $pages = IncomeCategory::find($request->conference_id);
        } else {
            $pages = new IncomeCategory();
        }

        $pages->name   = $request->name;
        $pages->status = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Income Category Updated Successfully');
            }
            return redirect()->back()->with('success', 'Income Category Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {
        $pages                 = IncomeCategory::find($request->id);
        $data['name']          = $pages->name;
        $data['status']        = $pages->status;
        $data['conference_id'] = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages = IncomeCategory::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Income Category Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminview()
    {
        $IncomeCategory = IncomeCategory::latest()->paginate(100);

        return view('sub_admin.IncomeCategory', compact('IncomeCategory'));
    }

    public function SubAdminstore(Request $request)
    {
        $request->validate([
            'name'   => 'required',
            'status' => 'required',
        ]);

        if ($request->conference_id) {
            $pages = IncomeCategory::find($request->conference_id);
        } else {
            $pages = new IncomeCategory();
        }

        $pages->name   = $request->name;
        $pages->status = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Income Category Updated Successfully');
            }
            return redirect()->back()->with('success', 'Income Category Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminedit(Request $request)
    {
        $pages                 = IncomeCategory::find($request->id);
        $data['name']          = $pages->name;
        $data['status']        = $pages->status;
        $data['conference_id'] = $request->id;

        return $data;
    }

    public function SubAdmindelete($id)
    {
        $pages = IncomeCategory::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Income Category Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }
}