<?php

namespace App\Http\Controllers;

use App\Models\LeadsFrom;
use App\Models\LeadStatus;
use App\Models\IncomeAmount;
use App\Models\IncomeCategory;
use Auth;
use Excel;
use DB;
use App\Imports\ImportLeads;
use App\Exports\ExportIncome;
use Illuminate\Http\Request;

class IncomeAmountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $start_date = '';
        $end_date = '';
        $category_filter = '';

        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
        }
        if (request('category_filter') && request('category_filter') != '') {
            $category_filter = request('category_filter');
        }

        $IncomeAmount = IncomeAmount::with('category')->latest();

        if ($start_date) {
            $IncomeAmount = $IncomeAmount->whereDate('income_date', '>=', $start_date)
                ->whereDate('income_date', '<=', $end_date);
        }

        if ($category_filter) {
            $IncomeAmount = $IncomeAmount->where('category_id', $category_filter);
        }

        $IncomeAmount = $IncomeAmount->paginate(100);

        // Active only — used for the filter dropdown and "Add Income Amount" modal dropdown
        $categories        = IncomeCategory::where('status', 'Active')->orderBy('name')->get();
        $income_categories = $categories;

        // All categories regardless of status — used for the manage/list table inside the category modal
        $all_categories = IncomeCategory::orderBy('name')->get();

        return view('admin.IncomeAmount', compact('IncomeAmount', 'start_date', 'end_date', 'category_filter', 'categories', 'income_categories', 'all_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'category_id' => 'required',
            'amount'      => 'required|numeric',
            'income_date' => 'required|date',
        ]);

        if ($request->conference_id) {
            $pages = IncomeAmount::find($request->conference_id);
        } else {
            $pages = new IncomeAmount();
        }
        $pages->name        = $request->name;
        $pages->category_id = $request->category_id;
        $pages->amount       = $request->amount;
        $pages->income_date  = $request->income_date;
        $pages->description  = $request->description;
        $pages->status       = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Income Amount Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Income Amount Details Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {
        $pages                 = IncomeAmount::find($request->id);
        $data['name']          = $pages->name;
        $data['category_id']   = $pages->category_id;
        $data['amount']        = $pages->amount;
        $data['income_date']   = $pages->income_date;
        $data['description']  = $pages->description;
        $data['conference_id'] = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages = IncomeAmount::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Income Amount Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Export(Request $request)
    {
        $start_date      = $request->start_date;
        $end_date        = $request->end_date;
        $category_filter = $request->category_filter;

        $data['start_date']      = $start_date;
        $data['end_date']        = $end_date;
        $data['category_filter'] = $category_filter;

        return Excel::download(new ExportIncome($data), 'IncomeReport.xlsx');
    }
    public function SubAdminview()
    {
        $start_date = '';
        $end_date = '';
        $category_filter = '';

        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
        }
        if (request('category_filter') && request('category_filter') != '') {
            $category_filter = request('category_filter');
        }

        $IncomeAmount = IncomeAmount::with('category')->latest();

        if ($start_date) {
            $IncomeAmount = $IncomeAmount->whereDate('income_date', '>=', $start_date)
                ->whereDate('income_date', '<=', $end_date);
        }

        if ($category_filter) {
            $IncomeAmount = $IncomeAmount->where('category_id', $category_filter);
        }

        $IncomeAmount = $IncomeAmount->paginate(100);

        $categories        = IncomeCategory::where('status', 'Active')->orderBy('name')->get();
        $income_categories = $categories;
        $all_categories     = IncomeCategory::orderBy('name')->get();

        return view('sub_admin.IncomeAmount', compact('IncomeAmount', 'start_date', 'end_date', 'category_filter', 'categories', 'income_categories', 'all_categories'));
    }

    public function SubAdminstore(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'category_id' => 'required',
            'amount'      => 'required|numeric',
            'income_date' => 'required|date',
        ]);

        if ($request->conference_id) {
            $pages = IncomeAmount::find($request->conference_id);
        } else {
            $pages = new IncomeAmount();
        }
        $pages->name        = $request->name;
        $pages->category_id = $request->category_id;
        $pages->amount       = $request->amount;
        $pages->income_date  = $request->income_date;
        $pages->description  = $request->description;
        $pages->status       = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Income Amount Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Income Amount Details Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminedit(Request $request)
    {
        $pages                 = IncomeAmount::find($request->id);
        $data['name']          = $pages->name;
        $data['category_id']   = $pages->category_id;
        $data['amount']        = $pages->amount;
        $data['income_date']   = $pages->income_date;
        $data['description']  = $pages->description;
        $data['conference_id'] = $request->id;

        return $data;
    }

    public function SubAdmindelete($id)
    {
        $pages = IncomeAmount::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Income Amount Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }
}
