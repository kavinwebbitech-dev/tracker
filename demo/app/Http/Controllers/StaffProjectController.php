<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\SalesPerson;
use App\Models\Customers;
use App\Models\Service;
use App\Models\Notification;
use App\Models\IncomeAmount;
use App\Models\ProjectBitAmount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Auth;

class StaffProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';

        if(request('start_date') && request('start_date')!=''){
          $start_date = request('start_date');
        }
        if(request('end_date') && request('end_date')!=''){
          $end_date = request('end_date');
        }
        if(request('status') && request('status')!='' || request('status') == 0){
          $status = request('status');
        }
        if(request('salesperson') && request('salesperson')!='' || request('salesperson') == 0){
          $salesperson1 = request('salesperson');
        }
        $users = Project::where('added_by', Auth::user()->id)->where('is_renewal', 0)->orderBy('date_created', 'desc');

        if ($start_date) {
            $users = $users->whereDate('date_created', '>=', $start_date)
                ->whereDate('date_created', '<=', $end_date);
        }
        else
        {
            $users = $users;
        }

        if ($status == "all") {
            $users = $users;
        }
        elseif($status)
        {
            $users = $users->where('status', $status);
        }
        else
        {
            $users = $users;
        }
        if($salesperson1)
        {
            $users = $users->where('sales_user_id', $salesperson1);
        }
        else
        {
            $users = $users;
        }
        $users = $users->paginate(100);
        // dd($users);
        $salesperson = SalesPerson::get();
        $recordCount = 0;
        $page_title = "Total";
        return view('staff.projects.view', compact('users', 'start_date', 'end_date', 'status','page_title', 'recordCount', 'salesperson','salesperson1'));
    }

    public function create()
    {
        $customers = Customers::where('fld_status', 1)->orderBy('id', 'desc')->get();
        $services = Service::where('status', "Active")->orderBy('id', 'desc')->get();
        $user_list = \App\Models\Project::groupBy('added_by')
              ->get(['added_by']);

        $salesperson = $user_list;
        return view('staff.projects.add', compact('salesperson', 'customers', 'services'));
    }

    public function edit($id)
    {
        $user_list = \App\Models\Project::groupBy('added_by')
              ->get(['added_by']);

        $salesperson = $user_list;
        $sub_admin = Project::where('id', $id)->first();
        $services = Service::where('status', "Active")->orderBy('id', 'desc')->get();
        $customers = Customers::where('fld_status', 1)->orderBy('id', 'desc')->get();
        return view('staff.projects.edit', compact('sub_admin', 'salesperson', 'customers', 'services'));
    }

    public function status($id)
    {
        $sub_admin = Project::where('id', $id)->first();
        return view('staff.projects.status', compact('sub_admin'));
    }

    public function delete($id)
    {
        if ($id) {
            $user  = Project::find($id);
            // dd($user);
            if ($user->delete()) {
                return redirect()->route('staff.projects.view')->with('error', 'Project Deleted Successfully');
            }
            else{
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
        else{
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|max:255',
            'amount' => 'required'
        ]);

        $user                   = new Project();
        $user->customer_id      = $request->customer;
        $user->name             = $request->name;
        $user->added_by         = Auth::user()->id;
        if($request->sales_person)
        {
            $user->sales_user_id    = $request->sales_person;
        }
        else
        {
            $user->sales_user_id    = Auth::user()->id;
        }
        $user->sales_user_id    = $request->sales_person;
        $user->sales_user_date  = $request->confirm_date;
        $user->start_date       = $request->start_date;
        $user->bid_amount       = $request->amount;
        $user->total_days       = $request->alert_days;
        $user->status           = $request->status;
        $user->description      = $request->editor1;
        $user->services_id      = $request->services;

        if ($request->project_type == "renewal_projects") {
            $user->is_renewal    = "1";
            $date1 = \Carbon\Carbon::create($request->start_date);
            $daysAdd = $request->renewal_days;
            $date2 = $date1->subDays($daysAdd);
            $to_date = $date2->format('Y-m-d');
            $user->end_date         = $to_date;
            $user->renewal_days     = $request->renewal_days;
        }
        else
        {
            $user->is_renewal    = "0";
            $user->end_date      = $request->end_date;
        }
        
        if ($user->save()) 
        {
            return redirect()->route('staff.projects.view')->with('success', 'Project Created Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function payment_update(Request $request)
    {
        // dd($request->all());

        $ProjectBitAmount                       = new ProjectBitAmount();
        $ProjectBitAmount->fld_project_id       = $request->project_id;
        $ProjectBitAmount->fld_project_amount   = $request->payment_amount;
        $ProjectBitAmount->fld_payment_date     = $request->payment_date;
        $ProjectBitAmount->fld_status           = 0;

        if ($ProjectBitAmount->save()) 
        {

            $projects_id = Project::where('id', $request->project_id)->first();

            $pages                  = new IncomeAmount();
            $pages->name            = $projects_id->name;
            $pages->amount          = $request->payment_amount;
            $pages->income_date     = $request->payment_date;
            $pages->description     = $request->description;
            $pages->project_id      = $ProjectBitAmount->id;
            $pages->save();
            
            return redirect()->back()->with('success', 'Payment Details Added Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function payment_details(Request $request)
    {
        // dd($request->all());

        $amount_details     = ProjectBitAmount::where('fld_project_id', $request->id)->get();
        $project_details     = Project::where('id', $request->id)->first();
        // dd($amount_details);
        $view =  view('staff.projects.modelrender', compact('amount_details', 'project_details'))->render();
        return response()->json(['html'=>$view]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|max:255',
            'amount' => 'required'
        ]);

        $date1 = \Carbon\Carbon::create($request->start_date);
        $daysAdd = $request->renewal_days;
        $date2 = $date1->subDays($daysAdd);
        $to_date = $date2->format('Y-m-d');
        
        $user                   = Project::find($id);
        $user->customer_id      = $request->customer;
        $user->name             = $request->name;
        if($request->sales_person)
        {
            $user->sales_user_id    = $request->sales_person;
        }
        else
        {
            $user->sales_user_id    = Auth::user()->id;
        }
        $user->sales_user_date  = $request->confirm_date;
        $user->start_date       = $request->start_date;
        $user->end_date         = $to_date;
        $user->bid_amount       = $request->amount;
        $user->total_days       = $request->alert_days;
        $user->status           = $request->status;
        $user->description      = $request->editor1;
        $user->services_id      = $request->services;

        if ($request->project_type == "renewal_projects") {
            $user->is_renewal    = "1";
            $date1 = \Carbon\Carbon::create($request->start_date);
            $daysAdd = $request->renewal_days;
            $date2 = $date1->subDays($daysAdd);
            $to_date = $date2->format('Y-m-d');
            $user->end_date         = $to_date;
            $user->renewal_days     = $request->renewal_days;
        }
        else
        {
            $user->is_renewal    = "0";
            $user->end_date      = $request->end_date;
        }

        if ($user->save()) 
        {

            $sub_admin = \App\Models\User::whereNot('user_type', 'staff')->where('status', 'active')->get();

            if($sub_admin)
            {
                foreach ($sub_admin as $key => $value) {

                    $message_box = Auth::user()->name .' Edit the Project - '. $request->name;
                    $notification                = new Notification();
                    $notification->task_id       = "";
                    $notification->receiver_id   = $value->id;
                    $notification->receiver_type = "sub_admin";
                    $notification->message       = $message_box;
                    $notification->status        = 0;
                    $notification->save();

                }
            }
            
            return redirect()->route('staff.projects.view')->with('success', 'Project Updated Successfully');
        }
        else
        {
            return redirect()->back()->with('error', 'Something Wrong');
        }

    }

    public function filter(Request $request)
    {
        // dd($request->all());

        $users = Project::where('added_by', Auth::user()->id)->orderBy('date_created', 'desc');
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $status = $request->status;
        if ($request->start_date) {
            $users = $users->whereDate('date_created', '>=', $request->start_date)
                ->whereDate('date_created', '<=', $request->end_date)
                ->get();
        }
        if ($request->status) {
            $users = $users->where('status', $request->status)->get();
        }

        return view('staff.projects.view', compact('users', 'start_date', 'end_date', 'status'));

        
    }

    public function OnGoingProject()
    {
        
        $users = Project::where('added_by', Auth::user()->id)->orderBy('date_created', 'desc')->where('status', 1)->get();
        $page_title = "On Going";
        return view('staff.projects.status', compact('users','page_title'));
    }

    public function PendingProject()
    {
        
        $users = Project::where('added_by', Auth::user()->id)->orderBy('date_created', 'desc')->where('status', 0)->get();
        $page_title = "Pending";
        return view('staff.projects.status', compact('users','page_title'));
    }

    public function OnHoldProject()
    {
        
        $users = Project::where('added_by', Auth::user()->id)->orderBy('date_created', 'desc')->where('status', 3)->get();
        $page_title = "On Hold";
        return view('staff.projects.status', compact('users','page_title'));
    }

    public function CompletedProject()
    {
        
        $users = Project::where('added_by', Auth::user()->id)->orderBy('date_created', 'desc')->where('status', 5)->get();
        $page_title = "Completed";
        return view('staff.projects.status', compact('users','page_title'));
    }

    public function RenewalProject()
    {
        $start_date = '';
        $end_date = '';
        $status = '';

        if(request('start_date') && request('start_date')!=''){
          $start_date = request('start_date');
        }
        if(request('end_date') && request('end_date')!=''){
          $end_date = request('end_date');
        }
        if(request('status') && request('status')!='' || request('status') == 0){
          $status = request('status');
        }

        $users = Project::where('added_by', Auth::user()->id)->orderBy('date_created', 'desc')->where('is_renewal', 1);

        if ($start_date) {
            $users = $users->whereDate('date_created', '>=', $start_date)
                ->whereDate('date_created', '<=', $end_date);
        }
        else
        {
            $users = $users;
        }

        if ($status == "0") {
            $users = $users->where('status', $status);
        }
        elseif($status)
        {
            $users = $users->where('status', $status);
        }
        else
        {
            $users = $users;
        }
        $users = $users->get();
        // dd($users);
        $page_title = "Renewal";

        $filteredRecordsQuery = DB::table('project_list AS pl')
            ->leftJoin('payment_allocation AS pa', 'pa.fld_projectid', '=', 'pl.id')
            ->leftJoin('users AS u', 'u.id', '=', 'pa.fld_userid')
            ->leftJoin('user_payment AS up', function ($join) {
                $join->on('up.project_id', '=', 'pl.id')
                     ->on('up.user_ids', '=', 'u.id');
            })
            ->leftJoin('sales_users AS su', 'su.id', '=', 'pl.sales_user_id')
            ->leftJoin('project_bit_amount AS pb', 'pb.fld_project_id', '=', 'pl.id')
            ->select(
                'pl.id',
                'pl.name AS pname',
                'su.firstname AS sales_firstname',
                'su.lastname AS sales_lastname',
                'pl.bid_amount',
                DB::raw('SUM(pa.fld_payment_allocate) AS payment_allocated'),
                DB::raw('SUM(pb.fld_project_amount) AS total_paid'),
                DB::raw('(pl.bid_amount - SUM(pb.fld_project_amount)) AS remaining_amt'),
                'pl.start_date',
                'pl.end_date',
                'pl.is_renewal',
                'pl.status',
                'pl.sales_user_date',
                DB::raw('MAX(pb.fld_payment_date) AS last_payment_date'),
                'pl.total_days',
                DB::raw('(DATEDIFF(CURDATE(), MAX(pb.fld_payment_date)) - pl.total_days) AS remaining_date')
            )
            ->where('pl.is_renewal', 1)
            ->whereNotNull('pl.start_date')
            ->where('pl.start_date', '!=', '')
            ->where('pl.start_date', '!=', '0000-00-00')
            ->whereNotNull('pb.fld_project_id')
            ->groupBy(
                'pl.id',
                'pl.name',
                'su.firstname',
                'su.lastname',
                'pl.start_date',
                'pl.end_date',
                'pl.bid_amount',
                'pl.is_renewal',
                'pl.status',
                'pl.sales_user_date',
                'pl.total_days'
            )
            ->having(DB::raw('DATEDIFF(CURDATE(), MAX(pb.fld_payment_date))'), '>=', DB::raw('pl.total_days'));

        // Count the number of records
        $recordCount = $filteredRecordsQuery->count();
        
        return view('staff.projects.view', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount'));
        // $users = Project::orderBy('date_created', 'desc')->where('is_renewal', 1)->get();
        // $page_title = "Renewal";
        // return view('admin.projects.view', compact('users','page_title'));
    }
    
    public function ViewProject($id)
    {
        $project = Project::where('id', $id)->first();
        return view('staff.projects.project_view', compact('project'));
    }

    public function RenewalProjectView()
    {
        $filteredRecordsQuery = DB::table('project_list AS pl')
            ->leftJoin('payment_allocation AS pa', 'pa.fld_projectid', '=', 'pl.id')
            ->leftJoin('users AS u', 'u.id', '=', 'pa.fld_userid')
            ->leftJoin('user_payment AS up', function ($join) {
                $join->on('up.project_id', '=', 'pl.id')
                     ->on('up.user_ids', '=', 'u.id');
            })
            ->leftJoin('sales_users AS su', 'su.id', '=', 'pl.sales_user_id')
            ->leftJoin('project_bit_amount AS pb', 'pb.fld_project_id', '=', 'pl.id')
            ->select(
                'pl.id',
                'pl.name AS pname',
                'su.firstname AS sales_firstname',
                'su.lastname AS sales_lastname',
                'pl.bid_amount',
                DB::raw('SUM(pa.fld_payment_allocate) AS payment_allocated'),
                DB::raw('SUM(pb.fld_project_amount) AS total_paid'),
                DB::raw('(pl.bid_amount - SUM(pb.fld_project_amount)) AS remaining_amt'),
                'pl.start_date',
                'pl.end_date',
                'pl.is_renewal',
                'pl.status',
                'pl.sales_user_date',
                DB::raw('MAX(pb.fld_payment_date) AS last_payment_date'),
                'pl.total_days',
                DB::raw('(DATEDIFF(CURDATE(), MAX(pb.fld_payment_date)) - pl.total_days) AS remaining_date')
            )
            ->where('pl.is_renewal', 1)
            ->whereNotNull('pl.start_date')
            ->where('pl.start_date', '!=', '')
            ->where('pl.start_date', '!=', '0000-00-00')
            ->whereNotNull('pb.fld_project_id')
            ->groupBy(
                'pl.id',
                'pl.name',
                'su.firstname',
                'su.lastname',
                'pl.start_date',
                'pl.end_date',
                'pl.bid_amount',
                'pl.is_renewal',
                'pl.status',
                'pl.sales_user_date',
                'pl.total_days'
            )
            ->orderBy('pl.date_created', 'desc')
            ->having(DB::raw('DATEDIFF(CURDATE(), MAX(pb.fld_payment_date))'), '>=', DB::raw('pl.total_days'));
        
        // Count the number of records
        $project = $filteredRecordsQuery->get();
        return view('staff.projects.renewal_details', compact('project'));
    }

}
