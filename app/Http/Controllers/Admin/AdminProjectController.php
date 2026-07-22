<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SalesPerson;
use App\Models\RenewProject;
use App\Models\ProjectBitAmount;
use App\Models\Customers;
use App\Models\Service;
use App\Models\User;
use App\Models\ExpensiveAmount;
use App\Models\IncomeAmount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Auth;

class AdminProjectController extends Controller
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
        $service_get1 = '';
        $total_amount = 0;
        $total_pening = 0;
        $user_title_name = "Webbitech";

        $project_get = Project::where('is_renewal', 0)->orderBy('date_created', 'desc')->get();
        foreach ($project_get as $key => $value) {
            $total_amount = 0;
            $bit_amounts = $value->bit_amounts;
            if (count($bit_amounts) > 0) {
                foreach ($bit_amounts as $key1 => $value1) {
                    $total_amount += $value1->fld_project_amount;
                }
            }

            if ($value->bid_amount == 0 && $total_amount == 0 || $value->bid_amount == $total_amount) {
                $project_update = Project::find($value->id);
                $project_update->payment_status = "completed";
                $project_update->save();
            }
        }

        $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        // dd($project_get);
        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }
        if (request('service') && request('service') != '') {
            $service_get1 = request('service');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }
        if (request('status') && request('status') != '' || request('status') == 0) {
            $status = request('status');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }
        if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
            $salesperson1 = request('salesperson');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }


        if ($start_date) {
            $users = $users->whereDate('sales_user_date', '>=', $start_date)
                ->whereDate('sales_user_date', '<=', $end_date);
        } else {
            $users = $users;
        }
        if ($salesperson1) {
            $users = $users->where('added_by', $salesperson1);
            if ($salesperson1) {
                $user_details = User::where('id', $salesperson1)->first();
                $user_title_name = $user_details->name;
            }
        } else {
            $users = $users;
        }
        if ($service_get1) {
            $users = $users->where('services_id', $service_get1);
        } else {
            $users = $users;
        }
        if ($status == "all") {
            $users = $users->paginate(100);
        } elseif ($status) {
            $users = $users->where('status', $status)->paginate(100);
        } else {
            $users = $users->paginate(50);
        }
        // $users = $users->paginate(50);
        // dd($users);

        // $user_list = \App\Models\Project::groupBy('added_by')
        //       ->get(['added_by']);
        $user_list = User::where('status', 'active')->whereIn(
            'id',
            Project::whereNotNull('added_by')
                ->distinct()
                ->pluck('added_by')
        )
            ->orderBy('name')
            ->get();

        // dd($user_list);

        $salesperson = $user_list;

        $service_get = Service::get();
        $recordCount = 0;
        $page_title = "Total";
        return view('sub_admin.projects.view', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1', 'service_get1', 'service_get', 'user_title_name'));
    }

    public function CompletedProject()
    {
        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $total_amount = 0;
        $total_pening = 0;



        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
        }
        if (request('service') && request('service') != '') {
            $service_get1 = request('service');
        }
        if (request('status') && request('status') != '' || request('status') == 0) {
            $status = request('status');
        }
        if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
            $salesperson1 = request('salesperson');
        }

        $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc')->where('status', 5);
        // $users = Project::where('is_renewal', 0)->where('payment_status', null)->orderBy('date_created', 'desc');

        if ($start_date) {
            $users = $users->whereDate('date_created', '>=', $start_date)
                ->whereDate('date_created', '<=', $end_date);
        } else {
            $users = $users;
        }

        if ($status == "all") {
            $users = $users;
        } elseif ($status) {
            $users = $users->where('status', $status);
        } else {
            $users = $users;
        }
        if ($salesperson1) {
            $users = $users->where('sales_user_id', $salesperson1);
        } else {
            $users = $users;
        }
        if ($service_get1) {
            $users = $users->where('services_id', $service_get1);
        } else {
            $users = $users;
        }
        $users = $users->paginate(50);

        $page_title = "Completed";
        $salesperson = SalesPerson::get();
        $service_get = Service::get();
        $recordCount = 0;

        return view('sub_admin.projects.done', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1', 'service_get1', 'service_get'));
    }

    public function RenewalProject()
    {
        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $total_amount = 0;
        $total_pening = 0;

        $project_get = Project::where('is_renewal', 1)->orderBy('date_created', 'desc')->get();
        foreach ($project_get as $key => $value) {
            $total_amount = 0;
            $bit_amounts = $value->bit_amounts;
            if (count($bit_amounts) > 0) {
                foreach ($bit_amounts as $key1 => $value1) {
                    $total_amount += $value1->fld_project_amount;
                }
            }

            if ($value->bid_amount == 0 && $total_amount == 0 || $value->bid_amount == $total_amount) {
                $project_update = Project::find($value->id);
                $project_update->payment_status = "completed";
                $project_update->save();
            }
        }

        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
        }
        if (request('service') && request('service') != '') {
            $service_get1 = request('service');
        }
        if (request('status') && request('status') != '' || request('status') == 0) {
            $status = request('status');
        }
        if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
            $salesperson1 = request('salesperson');
        }

        $users = Project::where('is_renewal', 1)->where('payment_status', null)->orderBy('date_created', 'desc');

        if ($start_date) {
            $users = $users->whereDate('date_created', '>=', $start_date)
                ->whereDate('date_created', '<=', $end_date);
        } else {
            $users = $users;
        }
        if ($salesperson1) {
            $users = $users->where('sales_user_id', $salesperson1);
        } else {
            $users = $users;
        }
        if ($status == "all") {
            $users = $users;
        } elseif ($status) {
            $users = $users->where('status', $status);
        } else {
            $users = $users;
        }
        if ($service_get1) {
            $users = $users->where('services_id', $service_get1);
        } else {
            $users = $users;
        }
        $users = $users->paginate(50);
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
        $salesperson = SalesPerson::get();
        $service_get = Service::get();
        return view('sub_admin.projects.renewal', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1', 'service_get1', 'service_get'));
        // $users = Project::orderBy('date_created', 'desc')->where('is_renewal', 1)->get();
        // $page_title = "Renewal";
        // return view('admin.projects.view', compact('users','page_title'));
    }

    public function PaymentCompleteView()
    {
        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';

        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
        }
        if (request('status') && request('status') != '' || request('status') == 0) {
            $status = request('status');
        }
        if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
            $salesperson1 = request('salesperson');
        }
        $users = Project::where('is_renewal', 0)->where('payment_status', 'completed')->orderBy('date_created', 'desc');

        if ($start_date) {
            $users = $users->whereDate('date_created', '>=', $start_date)
                ->whereDate('date_created', '<=', $end_date);
        } else {
            $users = $users;
        }

        if ($status == "all") {
            $users = $users;
        } elseif ($status) {
            $users = $users->where('status', $status);
        } else {
            $users = $users;
        }
        if ($salesperson1) {
            $users = $users->where('sales_user_id', $salesperson1);
        } else {
            $users = $users;
        }
        $users = $users->get();
        // $users = $users->where('bid_amount', '==', 'bit_amounts_sum_fld_project_amount')->paginate(50);
        // dd($users);
        $salesperson = SalesPerson::get();
        $recordCount = 0;
        $page_title = "Total";
        return view('sub_admin.projects.completed', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1'));
    }

    public function create()
    {
        $customers = Customers::where('customer_type', 1)->where('fld_status', 1)->orderBy('id', 'desc')->get();
        $services = Service::where('status', "Active")->orderBy('id', 'desc')->get();
        // $user_list = \App\Models\Project::groupBy('added_by')
        //       ->get(['added_by']);
        $user_list = User::where('status', 'active')
            ->whereIn(
                'id',
                Project::whereNotNull('added_by')
                    ->distinct()
                    ->pluck('added_by')
            )
            ->orderBy('name')
            ->get();

        $salesperson = $user_list;
        return view('sub_admin.projects.add', compact('salesperson', 'customers', 'services'));
    }

    public function edit($id)
    {
        // $user_list = \App\Models\Project::groupBy('added_by')
        //       ->get(['added_by']);
        $user_list = User::where('status', 'active')
            ->whereIn(
                'id',
                Project::whereNotNull('added_by')
                    ->distinct()
                    ->pluck('added_by')
            )
            ->orderBy('name')
            ->get();

        $salesperson = $user_list;
        $sub_admin = Project::where('id', $id)->first();
        $services = Service::where('status', "Active")->orderBy('id', 'desc')->get();
        $customers = Customers::where('customer_type', 1)->where('fld_status', 1)->orderBy('id', 'desc')->get();
        return view('sub_admin.projects.edit', compact('sub_admin', 'salesperson', 'customers', 'services'));
    }

    public function status($id)
    {
        $sub_admin = Project::where('id', $id)->first();
        return view('sub_admin.projects.status', compact('sub_admin'));
    }

    // public function delete($id)
    // {
    //     if ($id) {
    //         $user  = Project::find($id);
    //         // dd($user);
    //         if ($user->delete()) {
    //             return redirect()->route('admin.projects.view')->with('error', 'Project Deleted Successfully');
    //         }
    //         else{
    //             return redirect()->back()->with('error', 'Something Wrong');
    //         }
    //     }
    //     else{
    //         return redirect()->back()->with('error', 'Something Wrong');
    //     }
    // }
    public function delete($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Something Wrong');
        }

        $project = Project::find($id);

        if (!$project) {
            return redirect()->back()->with('error', 'Project Not Found');
        }

        if ($project->delete()) {

            if (auth()->user()->user_type == 'sub_admin') {
                return redirect()->route('sub_admin.projects.view')
                    ->with('success', 'Project Deleted Successfully');
            }

            return redirect()->route('admin.projects.view')
                ->with('success', 'Project Deleted Successfully');
        }

        return redirect()->back()->with('error', 'Something Wrong');
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
        $user->added_by         = $request->sales_person;
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
        } else {
            $user->is_renewal    = "0";
            $user->end_date      = $request->end_date;
        }

        // dd($user);
        if ($user->save()) {
            return redirect()->route('sub_admin.projects.view')->with('success', 'Project Created Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function follow_store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|max:255',
            'amount' => 'required'
        ]);

        if ($request->customer) {
            $sales_person = Customers::find($request->customer);
            $sales_person->customer_type = 1;
            $sales_person->save();
        }

        $user                   = new Project();
        $user->customer_id      = $request->customer;
        $user->name             = $request->name;
        $user->added_by         = $request->sales_person;
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
        } else {
            $user->is_renewal    = "0";
            $user->end_date      = $request->end_date;
        }

        // dd($user);
        if ($user->save()) {
            return redirect()->route('sub_admin.projects.view')->with('success', 'Project Created Successfully');
        } else {
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

        if ($ProjectBitAmount->save()) {

            $projects_id = Project::where('id', $request->project_id)->first();

            $pages                  = new IncomeAmount();
            $pages->name            = $projects_id->name;
            $pages->amount          = $request->payment_amount;
            $pages->income_date     = $request->payment_date;
            $pages->description     = $request->description;
            $pages->project_id      = $ProjectBitAmount->id;
            $pages->save();

            return redirect()->back()->with('success', 'Payment Details Added Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function payment_details(Request $request)
    {
        // dd($request->all());

        $amount_details     = ProjectBitAmount::where('fld_project_id', $request->id)->get();
        $project_details     = Project::where('id', $request->id)->first();
        // dd($amount_details);
        $view =  view('sub_admin.projects.modelrender', compact('amount_details', 'project_details'))->render();
        return response()->json(['html' => $view]);
    }

    public function payment_edit_details(Request $request)
    {
        // dd($request->all());

        $amount_details     = ProjectBitAmount::where('id', $request->id)->first();
        // dd($amount_details);
        $view =  view('sub_admin.projects.modelrender1', compact('amount_details'))->render();
        return response()->json(['html' => $view]);
    }

    public function payment_edit_update(Request $request)
    {
        // dd($request->all());

        $amount_details     = ProjectBitAmount::find($request->payment_id);
        $amount_details->fld_payment_date     = $request->fld_payment_date;
        $amount_details->fld_project_amount     = $request->fld_project_amount;

        if ($amount_details->save()) {

            $check_data = IncomeAmount::where('project_id', $request->payment_id)->first();
            if ($check_data) {
                $pages              = IncomeAmount::find($check_data->id);
            } else {
                $pages              = new IncomeAmount();
            }
            $pages->amount          = $request->fld_project_amount;
            $pages->income_date     = $request->fld_payment_date;
            $pages->save();

            return redirect()->back()->with('success', 'Payment Edited Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|max:255',
            'amount' => 'required'
        ]);

        $total_amount = 0;

        $date1 = \Carbon\Carbon::create($request->start_date);
        $daysAdd = $request->renewal_days;
        $date2 = $date1->subDays($daysAdd);
        $to_date = $date2->format('Y-m-d');

        $previous_url = $request->previous_url;

        $user                   = Project::find($id);
        $user->customer_id      = $request->customer;
        $user->name             = $request->name;
        $user->sales_user_date  = $request->confirm_date;
        $user->start_date       = $request->start_date;
        $user->end_date         = $to_date;
        $user->bid_amount       = $request->amount;
        $user->total_days       = $request->alert_days;
        $user->status           = $request->status;
        $user->description      = $request->editor1;
        $user->services_id      = $request->services;

        if ($request->refunded == "on") {
            $user->refunded  = "refunded";
        } else {
            $user->refunded  = "";
        }

        if ($request->project_type == "renewal_projects") {
            $user->is_renewal    = "1";
            $date1 = \Carbon\Carbon::create($request->start_date);
            $daysAdd = $request->renewal_days;
            $date2 = $date1->subDays($daysAdd);
            $to_date = $date2->format('Y-m-d');
            $user->end_date         = $to_date;
            $user->renewal_days     = $request->renewal_days;
        } else {
            $user->is_renewal    = "0";
            $user->end_date      = $request->end_date;
        }

        // dd($user);
        if ($user->save()) {
            if ($request->refunded == "on") {

                $bit_amounts = $user->bit_amounts;
                if (count($bit_amounts) > 0) {
                    foreach ($bit_amounts as $key1 => $value1) {
                        $total_amount += $value1->fld_project_amount;
                    }
                }

                $expence_check = ExpensiveAmount::where('project_id', $user->id)->first();

                if ($expence_check) {
                    $add_expencive = ExpensiveAmount::find($expence_check->id);
                    $add_expencive->name = $user->name;
                    $add_expencive->amount = $total_amount;
                    $add_expencive->description = "Project Refund";
                    $add_expencive->expensive_date = date('Y-m-d');
                    $add_expencive->project_id = $user->id;
                    $add_expencive->save();
                } else {
                    $add_expencive = new ExpensiveAmount();
                    $add_expencive->name = $user->name;
                    $add_expencive->amount = $total_amount;
                    $add_expencive->description = "Project Refund";
                    $add_expencive->expensive_date = date('Y-m-d');
                    $add_expencive->project_id = $user->id;
                    $add_expencive->save();
                }
            }

            if ($request->previous_url) {
                return redirect($previous_url)->with('success', 'Project Created Successfully');
            } else {
                return redirect()->route('sub_admin.projects.view')->with('success', 'Project Created Successfully');
            }
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function filter(Request $request)
    {
        // dd($request->all());

        $users = Project::orderBy('date_created', 'desc');
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

        return view('sub_admin.projects.view', compact('users', 'start_date', 'end_date', 'status'));
    }

    public function OnGoingProject()
    {

        $users = Project::orderBy('date_created', 'desc')->where('status', 1)->paginate(50);
        $page_title = "On Going";
        return view('sub_admin.projects.status', compact('users', 'page_title'));
    }

    public function PendingProject()
    {

        $users = Project::orderBy('date_created', 'desc')->where('status', 0)->paginate(50);
        $page_title = "Pending";
        return view('sub_admin.projects.status', compact('users', 'page_title'));
    }

    public function OnHoldProject()
    {

        $users = Project::orderBy('date_created', 'desc')->where('status', 3)->paginate(50);
        $page_title = "On Hold";
        return view('sub_admin.projects.status', compact('users', 'page_title'));
    }

    public function CancelProject()
    {

        $users = Project::orderBy('date_created', 'desc')->where('status', 6)->paginate(50);
        $page_title = "Cancel";
        return view('sub_admin.projects.status', compact('users', 'page_title'));
    }

    public function ViewProject($id)
    {
        $project = Project::where('id', $id)->first();

        $user_list = \App\Models\TaskStaff::where('project_id', $id)
            ->groupBy('staff_id')
            ->get(['staff_id']);

        return view('sub_admin.projects.project_view', compact('project', 'user_list'));
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
        return view('sub_admin.projects.renewal_details', compact('project'));
    }

    public function RenewalDelete($id)
    {
        if ($id) {
            $user  = Project::find($id);
            // dd($user);
            if ($user->delete()) {
                return redirect()->route('sub_admin.projects.renewal')->with('error', 'Project Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SearchView(Request $request)
    {
        // dd($request->all());
        $total_amount = 0;
        $total_pening = 0;


        $start_date         = $request->start_date ?? '';
        $end_date           = $request->end_date ?? '';
        $status             = $request->status ?? '';
        $salesperson1       = $request->salesperson ?? '';
        $service_get1       = $request->service ?? '';
        $text_value_search  = $request->text_value_search ?? '';

        $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');

        if ($start_date) {
            $users = $users->whereDate('sales_user_date', '>=', $start_date)
                ->whereDate('sales_user_date', '<=', $end_date);
        } else {
            $users = $users;
        }

        if ($status == "all") {
            $users = $users;
        } elseif ($status) {
            $users = $users->where('status', $status);
        } else {
            $users = $users;
        }
        if ($salesperson1) {
            $users = $users->where('sales_user_id', $salesperson1);
        } else {
            $users = $users;
        }
        if ($service_get1) {
            $users = $users->where('services_id', $service_get1);
        } else {
            $users = $users;
        }

        // $users = $users->where(function($q) use ($text_value_search) { 
        //                 $q->where('name', 'LIKE', "%".$text_value_search."%")
        //                 ->orwhere('description', 'LIKE', "%".$text_value_search."%")
        //                 ->orWhereHas('sales_user_details', function($q) use ($text_value_search){
        //                     $q->where('firstname', 'LIKE', "%".$text_value_search."%");
        //                 });
        //             });
        // if(!empty($text_value_search))
        // {
        //     $users->where(function ($q) use ($text_value_search) {
        //         $q
        //         ->where('name', 'like', "%{$text_value_search}%")
        //         // ->orWhere('description', 'like', "%{$text_value_search}%")
        //         ->orWhereHas('sales_user_details', function ($sq) use ($text_value_search) {
        //             $sq->where('firstname', 'like', "%{$text_value_search}%");
        //         });
        //     });
        // }
        if (!empty($text_value_search)) {
            $users = $users->where(function ($q) use ($text_value_search) {

                $q->where('name', 'LIKE', '%' . $text_value_search . '%')
                    // ->orWhereHas('sales_user_details', function ($sq) use ($text_value_search) {

                    //     $sq->where('firstname', 'LIKE', '%' . $text_value_search . '%');

                    // })
                ;
            });
        }

        $users = $users->get();


        $view =  view('sub_admin.projects.search', compact('users', 'start_date', 'end_date', 'status', 'salesperson1', 'service_get1'))->render();

        if ($view) {
            $data['project'] = $view;
            $data['status'] = "success";
        } else {
            $data['status'] = "error";
        }

        return $data;
    }

    public function PaymentDelete($id)
    {


        if ($id) {

            $project_bit = ProjectBitAmount::find($id);

            $imcount_amount = IncomeAmount::where('project_id', $id)->get();

            if (count($imcount_amount) > 0) {
                foreach ($imcount_amount as $key => $value) {

                    if ($value->amount == $project_bit->fld_project_amount) {

                        $imcount_amount_details = IncomeAmount::find($value->id);
                        $imcount_amount_details->delete();
                    }
                }
            }
            if ($project_bit->delete()) {
                return redirect()->back()->with('success', 'Payment Deleted');
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        }
    }

    public function statusUpdate(Request $request)
    {
        $project = Project::find($request->id);

        if ($project) {
            $project->status = $request->status;
            $project->save();
        }

        return response()->json([
            'success' => true
        ]);
    }
}
