<?php

namespace App\Http\Controllers;

use App\Models\LeadsFrom;
use App\Models\LeadStatus;
use App\Models\ExpensiveAmount;
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

    public function view()
    {
        $start_date = '';
        if(request('start_date') && request('start_date')!=''){
          $start_date = request('start_date');
        }

        $end_date = '';
        if(request('end_date') && request('end_date')!=''){
          $end_date = request('end_date');
        }

        $IncomeAmount = ExpensiveAmount::latest();

        if($start_date)
        {
            $IncomeAmount = $IncomeAmount->whereDate('expensive_date', '>=', $start_date)->whereDate('expensive_date', '<=', $end_date);
        }
        else
        {
            $IncomeAmount = $IncomeAmount;
        }

        $IncomeAmount = $IncomeAmount->paginate(100);

        return view('admin.ExpensiveAmount', compact('IncomeAmount', 'start_date', 'end_date'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages              = ExpensiveAmount::find($request->conference_id);
        }
        else
        {
            $pages              = new ExpensiveAmount();
        }
        $pages->name            = $request->name;
        $pages->amount          = $request->amount;
        $pages->expensive_date  = $request->expensive_date;
        $pages->description     = $request->description;
        $pages->status          = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Expenses Amount Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Expenses Amount Details Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {

        $pages                      = ExpensiveAmount::find($request->id);
        $data['name']               = $pages->name;
        $data['amount']             = $pages->amount;
        $data['expensive_date']     = $pages->expensive_date;
        $data['description']        = $pages->description;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages                  = ExpensiveAmount::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Expenses Amount Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminview()
    {
        
        $start_date = '';
        if(request('start_date') && request('start_date')!=''){
          $start_date = request('start_date');
        }

        $end_date = '';
        if(request('end_date') && request('end_date')!=''){
          $end_date = request('end_date');
        }

        $IncomeAmount = ExpensiveAmount::latest();

        if($start_date)
        {
            $IncomeAmount = $IncomeAmount->whereDate('expensive_date', '>=', $start_date)->whereDate('expensive_date', '<=', $end_date);
        }
        else
        {
            $IncomeAmount = $IncomeAmount;
        }

        $IncomeAmount = $IncomeAmount->paginate(100);

        return view('sub_admin.ExpensiveAmount', compact('IncomeAmount', 'start_date', 'end_date'));
        
    }

    public function SubAdminstore(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages              = ExpensiveAmount::find($request->conference_id);
        }
        else
        {
            $pages              = new ExpensiveAmount();
        }
        $pages->name            = $request->name;
        $pages->amount          = $request->amount;
        $pages->expensive_date  = $request->expensive_date;
        $pages->description     = $request->description;
        $pages->status          = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Expenses Amount Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Expenses Amount Details Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminedit(Request $request)
    {

        $pages                      = ExpensiveAmount::find($request->id);
        $data['name']               = $pages->name;
        $data['amount']             = $pages->amount;
        $data['expensive_date']     = $pages->expensive_date;
        $data['description']        = $pages->description;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function SubAdmindelete($id)
    {
        $pages                  = ExpensiveAmount::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Expenses Amount Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function search(Request $request)
    {

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $text_value_search = $request->text_value_search;

        $IncomeAmount = ExpensiveAmount::latest();

        if($start_date)
        {
            $IncomeAmount = $IncomeAmount->whereDate('expensive_date', '>=', $start_date)->whereDate('expensive_date', '<=', $end_date);
        }

        $IncomeAmount = $IncomeAmount->where(function($q) use ($text_value_search) { 
                        $q->where('name', 'LIKE', "%".$text_value_search."%");
                    });

        $IncomeAmount = $IncomeAmount->get();

        $view =  view('admin.ExpensiceSearch', compact('IncomeAmount'))->render();

        if ($view) {
            $data['project'] = $view;
            $data['status'] = "success";
        }
        else
        {
            $data['status'] = "error";
        }

        return $data;

    }

    public function SubAdminSearch(Request $request)
    {

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $text_value_search = $request->text_value_search;

        $IncomeAmount = ExpensiveAmount::latest();

        if($start_date)
        {
            $IncomeAmount = $IncomeAmount->whereDate('expensive_date', '>=', $start_date)->whereDate('expensive_date', '<=', $end_date);
        }

        $IncomeAmount = $IncomeAmount->where(function($q) use ($text_value_search) { 
                        $q->where('name', 'LIKE', "%".$text_value_search."%");
                    });

        $IncomeAmount = $IncomeAmount->get();

        $view =  view('sub_admin.ExpensiceSearch', compact('IncomeAmount'))->render();

        if ($view) {
            $data['project'] = $view;
            $data['status'] = "success";
        }
        else
        {
            $data['status'] = "error";
        }

        return $data;

    }

}
