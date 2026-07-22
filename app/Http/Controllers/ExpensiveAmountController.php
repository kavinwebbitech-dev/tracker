<?php

namespace App\Http\Controllers;

use App\Models\LeadsFrom;
use App\Models\LeadStatus;
use App\Models\ExpensiveAmount;
use App\Models\ExpenseCategory;
use Auth;
use Excel;
use DB;
use App\Imports\ImportLeads;
use Illuminate\Http\Request;

class ExpensiveAmountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function view()
    // {
    //     $start_date = '';
    //     $end_date = '';
    //     $category_filter = '';

    //     if (request('start_date') && request('start_date') != '') {
    //         $start_date = request('start_date');
    //     }
    //     if (request('end_date') && request('end_date') != '') {
    //         $end_date = request('end_date');
    //     }
    //     if (request('category_filter') && request('category_filter') != '') {
    //         $category_filter = request('category_filter');
    //     }

    //     $IncomeAmount = ExpensiveAmount::with('category')->latest();

    //     if ($start_date) {
    //         $IncomeAmount = $IncomeAmount->whereDate('expensive_date', '>=', $start_date)
    //             ->whereDate('expensive_date', '<=', $end_date);
    //     }

    //     if ($category_filter) {
    //         $IncomeAmount = $IncomeAmount->where('category_id', $category_filter);
    //     }

    //     $IncomeAmount = $IncomeAmount->paginate(100);

    //     $categories = ExpenseCategory::where('status', 'Active')->orderBy('name')->get();

    //     return view('admin.ExpensiveAmount', compact('IncomeAmount', 'start_date', 'end_date', 'category_filter', 'categories'));
    // }
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

        $IncomeAmount = ExpensiveAmount::with('category')->latest();

        if ($start_date) {
            $IncomeAmount = $IncomeAmount->whereDate('expensive_date', '>=', $start_date)
                ->whereDate('expensive_date', '<=', $end_date);
        }

        if ($category_filter) {
            $IncomeAmount = $IncomeAmount->where('category_id', $category_filter);
        }

        $IncomeAmount = $IncomeAmount->paginate(100);

        // Active only — used for dropdowns (filter + add-expense modal)
        $categories = ExpenseCategory::where('status', 'Active')->orderBy('name')->get();

        // All categories regardless of status — used for the manage/list table in the modal
        $all_categories = ExpenseCategory::orderBy('name')->get();

        return view('admin.ExpensiveAmount', compact('IncomeAmount', 'start_date', 'end_date', 'category_filter', 'categories', 'all_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'category_id' => 'required',
            'amount'      => 'required|numeric',
            'expensive_date' => 'required|date',
        ]);

        if ($request->conference_id) {
            $pages = ExpensiveAmount::find($request->conference_id);
        } else {
            $pages = new ExpensiveAmount();
        }
        $pages->name           = $request->name;
        $pages->category_id    = $request->category_id;
        $pages->amount          = $request->amount;
        $pages->expensive_date  = $request->expensive_date;
        $pages->description    = $request->description;
        $pages->status         = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Expenses Amount Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Expenses Amount Details Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {
        $pages                  = ExpensiveAmount::find($request->id);
        $data['name']           = $pages->name;
        $data['category_id']    = $pages->category_id;
        $data['amount']         = $pages->amount;
        $data['expensive_date'] = $pages->expensive_date;
        $data['description']    = $pages->description;
        $data['conference_id']  = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages = ExpensiveAmount::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Expenses Amount Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
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

        $IncomeAmount = ExpensiveAmount::with('category')->latest();

        if ($start_date) {
            $IncomeAmount = $IncomeAmount->whereDate('expensive_date', '>=', $start_date)
                ->whereDate('expensive_date', '<=', $end_date);
        }

        if ($category_filter) {
            $IncomeAmount = $IncomeAmount->where('category_id', $category_filter);
        }

        $IncomeAmount = $IncomeAmount->paginate(100);

        $categories     = ExpenseCategory::where('status', 'Active')->orderBy('name')->get();
        $all_categories = ExpenseCategory::orderBy('name')->get();

        return view('sub_admin.ExpensiveAmount', compact('IncomeAmount', 'start_date', 'end_date', 'category_filter', 'categories', 'all_categories'));
    }

    public function SubAdminstore(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'category_id' => 'required',
            'amount'      => 'required|numeric',
            'expensive_date' => 'required|date',
        ]);

        if ($request->conference_id) {
            $pages = ExpensiveAmount::find($request->conference_id);
        } else {
            $pages = new ExpensiveAmount();
        }
        $pages->name           = $request->name;
        $pages->category_id    = $request->category_id;
        $pages->amount          = $request->amount;
        $pages->expensive_date  = $request->expensive_date;
        $pages->description    = $request->description;
        $pages->status         = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Expenses Amount Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Expenses Amount Details Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminedit(Request $request)
    {
        $pages                  = ExpensiveAmount::find($request->id);
        $data['name']           = $pages->name;
        $data['category_id']    = $pages->category_id;
        $data['amount']         = $pages->amount;
        $data['expensive_date'] = $pages->expensive_date;
        $data['description']    = $pages->description;
        $data['conference_id']  = $request->id;

        return $data;
    }

    public function SubAdmindelete($id)
    {
        $pages = ExpensiveAmount::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Expenses Amount Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function search(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $text_value_search = $request->text_value_search;

        $IncomeAmount = ExpensiveAmount::with('category')->latest();

        if ($start_date) {
            $IncomeAmount = $IncomeAmount->whereDate('expensive_date', '>=', $start_date)->whereDate('expensive_date', '<=', $end_date);
        }

        $IncomeAmount = $IncomeAmount->where(function ($q) use ($text_value_search) {
            $q->where('name', 'LIKE', "%" . $text_value_search . "%");
        });

        $IncomeAmount = $IncomeAmount->get();

        $view =  view('admin.ExpensiceSearch', compact('IncomeAmount'))->render();

        if ($view) {
            $data['project'] = $view;
            $data['status'] = "success";
        } else {
            $data['status'] = "error";
        }

        return $data;
    }

    public function SubAdminSearch(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $text_value_search = $request->text_value_search;

        $IncomeAmount = ExpensiveAmount::with('category')->latest();

        if ($start_date) {
            $IncomeAmount = $IncomeAmount->whereDate('expensive_date', '>=', $start_date)->whereDate('expensive_date', '<=', $end_date);
        }

        $IncomeAmount = $IncomeAmount->where(function ($q) use ($text_value_search) {
            $q->where('name', 'LIKE', "%" . $text_value_search . "%");
        });

        $IncomeAmount = $IncomeAmount->get();

        $view =  view('sub_admin.ExpensiceSearch', compact('IncomeAmount'))->render();

        if ($view) {
            $data['project'] = $view;
            $data['status'] = "success";
        } else {
            $data['status'] = "error";
        }

        return $data;
    }
}
