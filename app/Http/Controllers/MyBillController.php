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
        if (request('search_date') && request('search_date') != '') {
            $search_date = request('search_date');
        }

        $MyBill = MyBill::orderByRaw("
            CASE
                WHEN end_date < CURDATE() THEN 1
                ELSE 0
            END
        ")->orderBy('end_date', 'asc');

        if ($search_date) {
            $MyBill->whereDate('end_date', $search_date);
        }

        $MyBill = $MyBill->paginate(100);

        $today       = date('Y-m-d');
        $reminderEnd = date('Y-m-d', strtotime('+2 days'));

        $dueSoonBills = MyBill::whereBetween('end_date', [$today, $reminderEnd])
            ->where('status', 'Active')
            ->get();

        return view('admin.MyBill', compact('MyBill', 'search_date', 'dueSoonBills'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'bill_amount' => 'required|numeric',
            'repeat_type' => 'nullable|in:none,day,week,month,year',
            'repeat_count'=> 'nullable|integer|min:1',
        ]);

        if ($request->conference_id) {
            $pages              = MyBill::find($request->conference_id);
            $pages->name        = $request->name;
            $pages->start_date  = $request->start_date;
            $pages->end_date    = $request->end_date;
            $pages->status      = $request->status;
            $pages->bill_amount = $request->bill_amount;
            $pages->repeat_type  = $request->repeat_type !== 'none' ? $request->repeat_type : null;
            $pages->repeat_count = $request->repeat_type !== 'none' ? ($request->repeat_count ?? 1) : null;
        } else {
            $unit  = $request->repeat_type ?? 'none';   // none, day, week, month, year
            $value = $request->repeat_count ?? 1;

            $date = new \Carbon\Carbon($request->bill_date);

            switch ($unit) {
                case 'day':
                    $date->addDays($value);
                    break;
                case 'week':
                    $date->addWeeks($value);
                    break;
                case 'month':
                    $date->addMonths($value);
                    break;
                case 'year':
                    $date->addYears($value);
                    break;
                default:
                    // No repeat — end date same as bill date
                    break;
            }

            $to_date = $date->format('Y-m-d');

            $pages               = new MyBill();
            $pages->name         = $request->name;
            $pages->bill_date    = $request->bill_date;
            $pages->start_date   = $request->bill_date;
            $pages->end_date     = $to_date;
            $pages->repeat_type  = $unit !== 'none' ? $unit : null;
            $pages->repeat_count = $unit !== 'none' ? $value : null;
            $pages->status       = $request->status;
            $pages->bill_amount  = $request->bill_amount;
        }

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'MyBill Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'MyBill Details Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function edit(Request $request)
    {
        $pages                 = MyBill::find($request->id);
        $data['name']          = $pages->name;
        $data['start_date']    = $pages->start_date;
        $data['end_date']      = $pages->end_date;
        $data['status']        = $pages->status;
        $data['bill_amount']   = $pages->bill_amount;
        $data['repeat_count']  = $pages->repeat_count;
        $data['repeat_type']   = $pages->repeat_type;
        $data['conference_id'] = $request->id;

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
        if (request('search_date') && request('search_date') != '') {
            $search_date = request('search_date');
        }
        $MyBill = MyBill::latest();

        if ($search_date) {
            $MyBill = $MyBill->where('expensive_date', $search_date);
        }

        $MyBill = $MyBill->paginate(100);

        return view('sub_admin.MyBill', compact('MyBill', 'search_date'));
    }

    public function Substore(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'bill_amount' => 'required|numeric',
            'repeat_type' => 'nullable|in:none,day,week,month,year',
            'repeat_count'=> 'nullable|integer|min:1',
        ]);

        if ($request->conference_id) {
            $pages              = MyBill::find($request->conference_id);
            $pages->name        = $request->name;
            $pages->start_date  = $request->start_date;
            $pages->end_date    = $request->end_date;
            $pages->status      = $request->status;
            $pages->bill_amount = $request->bill_amount;
            $pages->repeat_type  = $request->repeat_type !== 'none' ? $request->repeat_type : null;
            $pages->repeat_count = $request->repeat_type !== 'none' ? ($request->repeat_count ?? 1) : null;
        } else {
            $unit  = $request->repeat_type ?? 'none';
            $value = $request->repeat_count ?? 1;

            $date = new \Carbon\Carbon($request->bill_date);

            switch ($unit) {
                case 'day':
                    $date->addDays($value);
                    break;
                case 'week':
                    $date->addWeeks($value);
                    break;
                case 'month':
                    $date->addMonths($value);
                    break;
                case 'year':
                    $date->addYears($value);
                    break;
                default:
                    break;
            }

            $to_date = $date->format('Y-m-d');

            $pages               = new MyBill();
            $pages->name         = $request->name;
            $pages->bill_date    = $request->bill_date;
            $pages->start_date   = $request->bill_date;
            $pages->end_date     = $to_date;
            $pages->repeat_type  = $unit !== 'none' ? $unit : null;
            $pages->repeat_count = $unit !== 'none' ? $value : null;
            $pages->status       = $request->status;
            $pages->bill_amount  = $request->bill_amount;
        }

        if ($pages->save()) {
            if ($request->conference_id) {
                return redirect()->back()->with('warning', 'MyBill Details Updated Successfully');
            }
            return redirect()->back()->with('success', 'MyBill Details Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function Subedit(Request $request)
    {
        $pages                 = MyBill::find($request->id);
        $data['name']          = $pages->name;
        $data['start_date']    = $pages->start_date;
        $data['end_date']      = $pages->end_date;
        $data['status']        = $pages->status;
        $data['bill_amount']   = $pages->bill_amount;
        $data['repeat_count']  = $pages->repeat_count;
        $data['repeat_type']   = $pages->repeat_type;
        $data['conference_id'] = $request->id;

        return $data;
    }

    public function Subdelete($id)
    {
        MyBill::findOrFail($id)->delete();
        return redirect()->back()->with('error', 'MyBill Deleted Successfully');
    }
}