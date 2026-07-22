<?php

namespace App\Http\Controllers;

use App\Models\LeadsFrom;
use App\Models\LeadStatus;
use App\Models\IncomeAmount;
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
        if(request('start_date') && request('start_date')!=''){
          $start_date = request('start_date');
        }
        if(request('end_date') && request('end_date')!=''){
          $end_date = request('end_date');
        }
        $IncomeAmount = IncomeAmount::latest();

        if($start_date)
        {
            $IncomeAmount = $IncomeAmount->whereDate('income_date', '>=', $start_date)
                ->whereDate('income_date', '<=', $end_date);
                
            // $IncomeAmount = $IncomeAmount->where('income_date', $start_date);
        }
        else
        {
            $IncomeAmount = $IncomeAmount;
        }

        $IncomeAmount = $IncomeAmount->paginate(100);

        return view('admin.IncomeAmount', compact('IncomeAmount', 'start_date', 'end_date'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages              = IncomeAmount::find($request->conference_id);
        }
        else
        {
            $pages              = new IncomeAmount();
        }
        $pages->name            = $request->name;
        $pages->amount          = $request->amount;
        $pages->income_date     = $request->income_date;
        $pages->description     = $request->description;
        $pages->status          = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Income Amount Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Income Amount Details Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {

        $pages                      = IncomeAmount::find($request->id);
        $data['name']               = $pages->name;
        $data['amount']             = $pages->amount;
        $data['income_date']        = $pages->income_date;
        $data['description']        = $pages->description;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function delete($id)
    {
        $pages                  = IncomeAmount::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Income Amount Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }
    
    public function Export(Request $request)
    {
        
        // dd($request->all());
        
        $start_date = $request->start_date;
        $end_date   = $request->end_date;
        
        $data['start_date']   = $start_date;
        $data['end_date']     = $end_date;
        return Excel::download(new ExportIncome($data), 'IncomeReport.xlsx');
        
    }

    public function SubAdminview()
    {
        $start_date = '';
        $end_date = '';
        if(request('start_date') && request('start_date')!=''){
          $start_date = request('start_date');
        }
        if(request('end_date') && request('end_date')!=''){
          $end_date = request('end_date');
        }
        $IncomeAmount = IncomeAmount::latest();

        if($start_date)
        {
            $IncomeAmount = $IncomeAmount->whereDate('income_date', '>=', $start_date)
                ->whereDate('income_date', '<=', $end_date);
                
            // $IncomeAmount = $IncomeAmount->where('income_date', $start_date);
        }
        else
        {
            $IncomeAmount = $IncomeAmount;
        }

        $IncomeAmount = $IncomeAmount->paginate(100);

        return view('sub_admin.IncomeAmount', compact('IncomeAmount', 'start_date', 'end_date'));
        
    }
    
    public function SubAdminstore(Request $request)
    {
        // dd($request->all());
        if ($request->conference_id) {
            $pages              = IncomeAmount::find($request->conference_id);
        }
        else
        {
            $pages              = new IncomeAmount();
        }
        $pages->name            = $request->name;
        $pages->amount          = $request->amount;
        $pages->income_date     = $request->income_date;
        $pages->description     = $request->description;
        $pages->status          = $request->status;

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'Income Amount Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'Income Amount Details Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminedit(Request $request)
    {

        $pages                      = IncomeAmount::find($request->id);
        $data['name']               = $pages->name;
        $data['amount']             = $pages->amount;
        $data['income_date']        = $pages->income_date;
        $data['description']        = $pages->description;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function SubAdmindelete($id)
    {
        $pages                  = IncomeAmount::find($id);
        if ($pages->delete()) {
            return redirect()->back()->with('error', 'Income Amount Deleted Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }
}
