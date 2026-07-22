<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $ExpenseCategory = ExpenseCategory::latest()->paginate(100);

        return view('admin.ExpensiveAmount', compact('ExpenseCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required',
            'status' => 'required',
        ]);

        if ($request->conference_id) {
            $pages = ExpenseCategory::find($request->conference_id);
        } else {
            $pages = new ExpenseCategory();
        }

        $pages->name   = $request->name;
        $pages->status = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Expense Category Updated Successfully');
            }
            return redirect()->back()->with('success', 'Expense Category Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {
        $pages                 = ExpenseCategory::find($request->id);
        $data['name']          = $pages->name;
        $data['status']        = $pages->status;
        $data['conference_id'] = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages = ExpenseCategory::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Expense Category Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminview()
    {
        $ExpenseCategory = ExpenseCategory::latest()->paginate(100);

        return view('sub_admin.ExpensiveAmount', compact('ExpenseCategory'));
    }

    public function SubAdminstore(Request $request)
    {
        $request->validate([
            'name'   => 'required',
            'status' => 'required',
        ]);

        if ($request->conference_id) {
            $pages = ExpenseCategory::find($request->conference_id);
        } else {
            $pages = new ExpenseCategory();
        }

        $pages->name   = $request->name;
        $pages->status = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Expense Category Updated Successfully');
            }
            return redirect()->back()->with('success', 'Expense Category Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminedit(Request $request)
    {
        $pages                 = ExpenseCategory::find($request->id);
        $data['name']          = $pages->name;
        $data['status']        = $pages->status;
        $data['conference_id'] = $request->id;

        return $data;
    }

    public function SubAdmindelete($id)
    {
        $pages = ExpenseCategory::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Expense Category Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }
}