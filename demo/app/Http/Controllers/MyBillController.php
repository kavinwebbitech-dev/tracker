<?php

namespace App\Http\Controllers;

use App\Models\MyBill;
use Illuminate\Http\Request;

class MyBillController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function view()
    {
        $search_date = '';
        if(request('search_date') && request('search_date')!=''){
          $search_date = request('search_date');
        }
        $MyBill = MyBill::latest();

        if($search_date)
        {
            $MyBill = $MyBill->where('expensive_date', $search_date);
        }
        else
        {
            $MyBill = $MyBill;
        }

        $MyBill = $MyBill->paginate(100);

        return view('admin.MyBill', compact('MyBill', 'search_date'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $date = new \Carbon\Carbon($request->bill_date);
        $date->addDays($request->recurring_type);
        $to_date = $date->format('Y-m-d');
        // dd($to_date);

        if ($request->conference_id) {
            $pages                  = MyBill::find($request->conference_id);
            $pages->name            = $request->name;
            $pages->start_date      = $request->start_date;
            $pages->end_date        = $request->end_date;
            $pages->status          = $request->status;
            $pages->bill_amount     = $request->bill_amount;
        }
        else
        {
            $pages                  = new MyBill();
            $pages->name            = $request->name;
            $pages->bill_date       = $request->bill_date;
            $pages->start_date      = $request->bill_date;
            $pages->end_date        = $to_date;
            $pages->recurring_type  = $request->recurring_type;
            $pages->status          = $request->status;
            $pages->bill_amount     = $request->bill_amount;
        }

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'MyBill Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'MyBill Details Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {

        $pages                      = MyBill::find($request->id);
        $data['name']               = $pages->name;
        $data['start_date']         = $pages->start_date;
        $data['end_date']           = $pages->end_date;
        $data['status']             = $pages->status;
        $data['bill_amount']        = $pages->bill_amount;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function delete($id)
    {

        MyBill::findOrFail($id)->delete();
        return redirect()->back()->with('error', 'MyBill Deleted Successfully');

    }

    public function Subview()
    {
        $search_date = '';
        if(request('search_date') && request('search_date')!=''){
          $search_date = request('search_date');
        }
        $MyBill = MyBill::latest();

        if($search_date)
        {
            $MyBill = $MyBill->where('expensive_date', $search_date);
        }
        else
        {
            $MyBill = $MyBill;
        }

        $MyBill = $MyBill->paginate(100);

        return view('sub_admin.MyBill', compact('MyBill', 'search_date'));
    }

    public function Substore(Request $request)
    {
        // dd($request->all());

        $date = new \Carbon\Carbon($request->bill_date);
        $date->addDays($request->recurring_type);
        $to_date = $date->format('Y-m-d');
        // dd($to_date);

        if ($request->conference_id) {
            $pages                  = MyBill::find($request->conference_id);
            $pages->name            = $request->name;
            $pages->start_date      = $request->start_date;
            $pages->end_date        = $request->end_date;
            $pages->status          = $request->status;
            $pages->bill_amount     = $request->bill_amount;
        }
        else
        {
            $pages                  = new MyBill();
            $pages->name            = $request->name;
            $pages->bill_date       = $request->bill_date;
            $pages->start_date      = $request->bill_date;
            $pages->end_date        = $to_date;
            $pages->recurring_type  = $request->recurring_type;
            $pages->status          = $request->status;
            $pages->bill_amount     = $request->bill_amount;
        }

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'MyBill Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'MyBill Details Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Subedit(Request $request)
    {

        $pages                      = MyBill::find($request->id);
        $data['name']               = $pages->name;
        $data['start_date']         = $pages->start_date;
        $data['end_date']           = $pages->end_date;
        $data['status']             = $pages->status;
        $data['bill_amount']        = $pages->bill_amount;
        $data['conference_id']      = $request->id;

        return $data;
    }

    public function Subdelete($id)
    {

        MyBill::findOrFail($id)->delete();
        return redirect()->back()->with('error', 'MyBill Deleted Successfully');

    }

}
