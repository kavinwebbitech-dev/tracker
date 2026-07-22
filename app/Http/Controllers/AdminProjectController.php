<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\SalesPerson;
use App\Models\RenewProject;
use App\Models\ProjectBitAmount;
use App\Models\Customers;
use App\Models\Service;
use App\Models\User;
use App\Models\ExpensiveAmount;
use App\Models\IncomeAmount;
use App\Models\TaskStaff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskComment;
use Auth;
// use PDF;
// use Dompdf\Dompdf;
use Mpdf\Mpdf;


class AdminProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function view()
    // {
    //     $start_date = '';
    //     $end_date = '';
    //     $status = '';
    //     $salesperson1 = '';
    //     $service_get1 = '';
    //     $total_amount = 0;
    //     $total_pening = 0;
    //     $user_title_name = "Webbitech";

    //     $project_get = Project::where('is_renewal', 0)->orderBy('date_created', 'desc')->get();
    //     foreach ($project_get as $key => $value) {
    //         $total_amount = 0;
    //         $bit_amounts = $value->bit_amounts;
    //         if (count($bit_amounts) > 0) {
    //             foreach ($bit_amounts as $key1 => $value1) {
    //                 $total_amount += $value1->fld_project_amount;
    //             }
    //         }

    //         if ($value->bid_amount == 0 && $total_amount == 0 || $value->bid_amount == $total_amount) {
    //             $project_update = Project::find($value->id);
    //             $project_update->payment_status = "completed";
    //             $project_update->save();
    //         }
    //     }

    //     $users = Project::where('is_renewal', 0)->where('payment_status', null)->where('added_year', null)->orderBy('date_created', 'desc');
    //     // dd($project_get);
    //     if (request('start_date') && request('start_date') != '') {
    //         $start_date = request('start_date');
    //         $users = Project::where('is_renewal', 0)->where('added_year', null)->orderBy('date_created', 'desc');
    //     }
    //     if (request('end_date') && request('end_date') != '') {
    //         $end_date = request('end_date');
    //         $users = Project::where('is_renewal', 0)->where('added_year', null)->orderBy('date_created', 'desc');
    //     }
    //     if (request('service') && request('service') != '') {
    //         $service_get1 = request('service');
    //         $users = Project::where('is_renewal', 0)->where('added_year', null)->orderBy('date_created', 'desc');
    //     }
    //     if (request('status') && request('status') != '' || request('status') == 0) {
    //         $status = request('status');
    //         $users = Project::where('is_renewal', 0)->where('added_year', null)->orderBy('date_created', 'desc');
    //     }
    //     if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
    //         $salesperson1 = request('salesperson');
    //         $users = Project::where('is_renewal', 0)->where('added_year', null)->orderBy('date_created', 'desc');
    //     }

    //     if ($start_date) {
    //         $users = $users->whereDate('sales_user_date', '>=', $start_date)
    //             ->whereDate('sales_user_date', '<=', $end_date);
    //     } else {
    //         $users = $users;
    //     }
    //     if ($salesperson1) {
    //         $users = $users->where('added_by', $salesperson1);
    //         if ($salesperson1) {
    //             $user_details = User::where('id', $salesperson1)->first();
    //             $user_title_name = $user_details->name;
    //         }
    //     } else {
    //         $users = $users;
    //     }
    //     if ($service_get1) {
    //         $users = $users->where('services_id', $service_get1);
    //     } else {
    //         $users = $users;
    //     }
    //     if ($status == "all") {
    //         $users = $users->paginate(1000);
    //     } elseif ($status) {
    //         $users = $users->where('status', $status)->paginate(100);
    //     } else {
    //         $users = $users->paginate(100);
    //     }
    //     // $users = $users->paginate(50);
    //     // dd($users);

    //     $user_list = \App\Models\Project::groupBy('added_by')
    //         ->get(['added_by']);

    //     $salesperson = $user_list;

    //     $service_get = Service::get();
    //     $recordCount = 0;
    //     $page_title = "Total";
    //     $staffs = User::where('user_type', 'staff')->get();
    //     return view('admin.projects.view', compact('users', 'staffs', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1', 'service_get1', 'service_get', 'user_title_name'));
    // }

   

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

        $users = Project::where('is_renewal', 0)->where('payment_status', null)->where('added_year', null)->orderBy('date_created', 'desc');
        // dd($project_get);
        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
            $users = Project::where('is_renewal', 0)->where('added_year', null)->orderBy('date_created', 'desc');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
            $users = Project::where('is_renewal', 0)->where('added_year', null)->orderBy('date_created', 'desc');
        }
        if (request('service') && request('service') != '') {
            $service_get1 = request('service');
            $users = Project::where('is_renewal', 0)->where('added_year', null)->orderBy('date_created', 'desc');
        }
        if (request('status') && request('status') != '' || request('status') == 0) {
            $status = request('status');
            $users = Project::where('is_renewal', 0)->where('added_year', null)->orderBy('date_created', 'desc');
        }
        if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
            $salesperson1 = request('salesperson');
            $users = Project::where('is_renewal', 0)->where('added_year', null)->orderBy('date_created', 'desc');
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
        // Replace all three paginate calls:
if ($status == "all") {
    $users = $users->with('tasks')->paginate(1000);
} elseif ($status) {
    $users = $users->where('status', $status)->with('tasks')->paginate(100);
} else {
    $users = $users->with('tasks')->paginate(100);
}
        // $users = $users->paginate(50);
        // dd($users);

        $user_list = \App\Models\Project::groupBy('added_by')
            ->get(['added_by']);

        $salesperson = $user_list;

        $service_get = Service::get();
        $recordCount = 0;
        $page_title = "Total";
        $staffs = User::where('user_type', 'staff')->where('status', 'active')->get();
        return view('admin.projects.view', compact('users', 'staffs', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1', 'service_get1', 'service_get', 'user_title_name'));
    }

    public function getAssignedStaff(Request $request)
    {
        $project = Project::findOrFail($request->id);

        $assignedIds = json_decode($project->assigned_staff, true) ?? [];

        $assignments = collect($assignedIds)->map(function ($staffId) use ($project) {
            $logged = DB::table('task_comments')
                ->where('project_id', $project->id)
                ->where('staff_id', $staffId)
                ->where('is_delete', 0) // exclude soft-deleted comments, matches your is_delete flag
                ->sum('working_hours');

            return [
                'staff_id' => $staffId,
                'logged_hours' => (float) $logged,
            ];
        })->values();

        return response()->json([
            'project' => [
                'frontend_hours' => $project->frontend_hours,
                'backend_hours'  => $project->backend_hours,
                'seo_hours'      => $project->seo_hours,
                'testing_hours'  => $project->testing_hours,
            ],
            'assigned_staff' => $assignedIds,
            'assignments'    => $assignments,
        ]);
    }

    // Save the staff selection for a project (keeps your original simple assigned_staff json column)
    public function assignStaffWithHours(Request $request)
{
    $request->validate([
        'project_id' => 'required|exists:project_list,id',
        'staff_ids'  => 'required|array|min:1',
    ]);

    $project = Project::findOrFail($request->project_id);
    $project->assigned_staff = json_encode($request->staff_ids);
    $project->save();

    // Create a task for this assignment — adjust column names to match your `tasks` table
    $task = Task::create([
        'project_id' => $project->id,
        'name'       => $project->name,
        'status'     => 0, // adjust to whatever "pending/new" is in your tasks table
    ]);

    // One TaskComment per assigned staff member
    foreach ($request->staff_ids as $staffId) {
        TaskComment::create([
            'task_id'       => $task->id,
            'project_id'    => $project->id,   // direct, no join needed
            'staff_id'      => $staffId,
            'start_date'    => now(),
            'working_hours' => 0,
            'user_comment'  => 'Assigned to project',
            'is_delete'     => 0,
        ]);
    }

    return redirect()->back()->with('success', 'Staff Assigned Successfully');
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
        $user_title_name = "Webbitech";

        $users = Project::where('is_renewal', 0)->where('status', 5)->orderBy('date_created', 'desc');
        // dd($project_get);
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

        $user_list = \App\Models\Project::groupBy('added_by')
            ->get(['added_by']);

        $salesperson = $user_list;

        $service_get = Service::get();
        $recordCount = 0;
        $page_title = "On Going";
        // return view('admin.projects.status', compact('users', 'start_date', 'end_date', 'status','page_title', 'recordCount', 'salesperson','salesperson1','service_get1', 'service_get', 'user_title_name'));

        return view('admin.projects.done', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1', 'service_get1', 'service_get', 'user_title_name'));
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
            $users = $users->where('added_by', $salesperson1);
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

        $user_list = \App\Models\Project::groupBy('added_by')
            ->get(['added_by']);

        $salesperson = $user_list;

        $service_get = Service::get();
        return view('admin.projects.renewal', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1', 'service_get1', 'service_get'));
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
            $users = $users->where('added_by', $salesperson1);
        } else {
            $users = $users;
        }
        $users = $users->paginate(100);
        // $users = $users->where('bid_amount', '==', 'bit_amounts_sum_fld_project_amount')->paginate(50);
        // dd($users);
        $user_list = \App\Models\Project::groupBy('added_by')
            ->get(['added_by']);

        $salesperson = $user_list;

        $recordCount = 0;
        $page_title = "Total";
        return view('admin.projects.completed', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1'));
    }

    public function create()
    {
        $customers = Customers::where('customer_type', 1)->where('fld_status', 1)->orderBy('id', 'desc')->get();
        $services = Service::where('status', "Active")->orderBy('id', 'desc')->get();
        $user_list = \App\Models\Project::groupBy('added_by')
            ->get(['added_by']);

        $salesperson = $user_list;
        return view('admin.projects.add', compact('salesperson', 'customers', 'services'));
    }

    public function edit($id)
    {
        $user_list = \App\Models\Project::groupBy('added_by')
            ->get(['added_by']);

        $salesperson = $user_list;
        $sub_admin = Project::where('id', $id)->first();
        $services = Service::where('status', "Active")->orderBy('id', 'desc')->get();
        $customers = Customers::where('customer_type', 1)->where('fld_status', 1)->orderBy('id', 'desc')->get();
        return view('admin.projects.edit', compact('sub_admin', 'salesperson', 'customers', 'services'));
    }

    public function status($id)
    {
        $sub_admin = Project::where('id', $id)->first();
        return view('admin.projects.status', compact('sub_admin'));
    }

    public function delete($id)
    {
        if ($id) {
            $user  = Project::find($id);
            // dd($user);
            if ($user->delete()) {
                return redirect()->route('admin.projects.view')->with('error', 'Project Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|max:255',
            'amount' => 'required',
            'frontend_hours' => 'nullable|numeric',
            'backend_hours' => 'nullable|numeric',
            'seo_hours' => 'nullable|numeric',
            'testing_hours' => 'nullable|numeric',
            'design_hours' => 'nullable|numeric',
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
        $user->frontend_hours = $request->frontend_hours;
        $user->backend_hours = $request->backend_hours;
        $user->seo_hours = $request->seo_hours;
        $user->testing_hours = $request->testing_hours;
        $user->designer_hours = $request->design_hours;

        $user->assigned_staff = $request->assigned_staff;

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
            return redirect()->route('admin.projects.view')->with('success', 'Project Created Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $total_amount = 0;
        $request->validate([
            'name' => 'required|max:255',
            'amount' => 'required'
        ]);

        $date1 = \Carbon\Carbon::create($request->start_date);
        $daysAdd = $request->renewal_days;
        $date2 = $date1->addDays($daysAdd);
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
        $user->frontend_hours = $request->frontend_hours;
        $user->backend_hours = $request->backend_hours;
        $user->seo_hours = $request->seo_hours;
        $user->testing_hours = $request->testing_hours;
        $user->designer_hours = $request->designer_hours;

        $user->assigned_staff = $request->assigned_staff;

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
                return redirect()->route('admin.projects.view')->with('success', 'Project Created Successfully');
            }
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
            $date2 = $date1->addDays($daysAdd);
            $to_date = $date2->format('Y-m-d');
            $user->end_date         = $to_date;
            $user->renewal_days     = $request->renewal_days;
        } else {
            $user->is_renewal    = "0";
            $user->end_date      = $request->end_date;
        }

        // dd($user);
        if ($user->save()) {
            return redirect()->route('admin.projects.view')->with('success', 'Project Created Successfully');
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

            $project_details = Project::where('id', $request->project_id)->first();
            $customer_details = Customers::where('id', $project_details->customer_id)->first();
            if ($customer_details) {
                $user_name1 = "Client";
                if ($customer_details) {
                    $user_name1 = $customer_details->fld_name;
                }
                $phone_number = $customer_details->fld_phone;
            }

            $already_check = ProjectBitAmount::where('fld_project_id', $request->project_id)->get();
            if (count($already_check) > 1) {
                // code...
            } else {

                $user_name          = $user_name1;
                // $phone_number       = $phone_number;
                $phone_number       = "9629163650";
                $image_url          = url('/public/admin_assets/images/logo.png');
                $template_name      = "advance_payment";
                $advan_amount       = $request->payment_amount;
                $full_amount        = $project_details->bid_amount;

                $whats_app_camp = ProjectAmount($user_name, $phone_number, $image_url, $template_name, $advan_amount, $full_amount);
            }

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
        $view =  view('admin.projects.modelrender', compact('amount_details', 'project_details'))->render();
        return response()->json(['html' => $view]);
    }

    public function payment_edit_details(Request $request)
    {
        // dd($request->all());

        $amount_details     = ProjectBitAmount::where('id', $request->id)->first();
        // dd($amount_details);
        $view =  view('admin.projects.modelrender1', compact('amount_details'))->render();
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

        return view('admin.projects.view', compact('users', 'start_date', 'end_date', 'status'));
    }

    public function OnGoingProject()
    {

        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $total_amount = 0;
        $total_pening = 0;
        $user_title_name = "Webbitech";

        $users = Project::where('is_renewal', 0)->where('status', 1)->orderBy('date_created', 'desc');
        // dd($project_get);
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

        $user_list = \App\Models\Project::groupBy('added_by')
            ->get(['added_by']);

        $salesperson = $user_list;

        $service_get = Service::get();
        $recordCount = 0;
        $page_title = "On Going";
        return view('admin.projects.status', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1', 'service_get1', 'service_get', 'user_title_name'));

        // $users = Project::orderBy('date_created', 'desc')->where('status', 1)->paginate(50);
        // $page_title = "On Going";
        // return view('admin.projects.status', compact('users','page_title'));
    }

    public function PendingProject()
    {

        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $total_amount = 0;
        $total_pening = 0;
        $user_title_name = "Webbitech";

        $users = Project::where('is_renewal', 0)->where('status', 0)->orderBy('date_created', 'desc');
        // dd($project_get);
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

        $user_list = \App\Models\Project::groupBy('added_by')
            ->get(['added_by']);

        $salesperson = $user_list;

        $service_get = Service::get();
        $recordCount = 0;
        $page_title = "Pending";
        return view('admin.projects.status', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1', 'service_get1', 'service_get', 'user_title_name'));
    }

    public function OnHoldProject()
    {

        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $total_amount = 0;
        $total_pening = 0;
        $user_title_name = "Webbitech";

        $users = Project::where('is_renewal', 0)->where('status', 3)->orderBy('date_created', 'desc');
        // dd($project_get);
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

        $user_list = \App\Models\Project::groupBy('added_by')
            ->get(['added_by']);

        $salesperson = $user_list;

        $service_get = Service::get();
        $recordCount = 0;
        $page_title = "On Hold";
        return view('admin.projects.status', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1', 'service_get1', 'service_get', 'user_title_name'));
    }

    public function CancelProject()
    {

        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $total_amount = 0;
        $total_pening = 0;
        $user_title_name = "Webbitech";

        $users = Project::where('is_renewal', 0)->where('status', 6)->orderBy('date_created', 'desc');
        // dd($project_get);
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

        $user_list = \App\Models\Project::groupBy('added_by')
            ->get(['added_by']);

        $salesperson = $user_list;

        $service_get = Service::get();
        $recordCount = 0;
        $page_title = "Completed";
        return view('admin.projects.status', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1', 'service_get1', 'service_get', 'user_title_name'));
    }

    public function ViewProject($id)
    {
        $project = Project::where('id', $id)->first();

        $user_list = \App\Models\TaskStaff::where('project_id', $id)
            ->groupBy('staff_id')
            ->get(['staff_id']);

        return view('admin.projects.project_view', compact('project', 'user_list'));
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
        return view('admin.projects.renewal_details', compact('project'));
    }

    public function RenewalDelete($id)
    {
        if ($id) {
            $user  = Project::find($id);
            // dd($user);
            if ($user->delete()) {
                return redirect()->route('admin.projects.renewal')->with('error', 'Project Deleted Successfully');
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

        // $users = Project::where('is_renewal', 0)->where('payment_status', null)->whereNot('status', 6)->orderBy('date_created', 'desc');

        $users = Project::orderBy('date_created', 'desc');

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
        //                 ->orWhereHas('sales_user_details', function($q) use ($text_value_search){
        //                     $q->where('firstname', 'LIKE', "%".$text_value_search."%");
        //                 });
        //             });
        $users = $users->where(function ($query) use ($text_value_search) {
            $query->where('name', 'like', '%' . trim($text_value_search) . '%')
                ->orWhereHas('sales_user_details', function ($q) use ($text_value_search) {
                    $q->where('firstname', 'like', '%' . trim($text_value_search) . '%');
                });
        });

        $users = $users->get();

        $view =  view('admin.projects.search', compact('users', 'start_date', 'end_date', 'status', 'salesperson1', 'service_get1'))->render();

        if ($view) {
            $data['project'] = $view;
            $data['status'] = "success";
        } else {
            $data['status'] = "error";
        }

        return $data;
    }

    public function CompletedSearchView(Request $request)
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

        $users = Project::where('is_renewal', 0)->where('payment_status', 'completed')->orderBy('date_created', 'desc');

        // $users = Project::orderBy('date_created', 'desc');


        $users = $users->where(function ($q) use ($text_value_search) {
            $q->where('name', 'LIKE', "%" . $text_value_search . "%")
                ->orwhere('description', 'LIKE', "%" . $text_value_search . "%")
                ->orWhereHas('sales_user_details', function ($q) use ($text_value_search) {
                    $q->where('firstname', 'LIKE', "%" . $text_value_search . "%");
                });
        });

        $users = $users->get();

        // dd($users);

        $view =  view('admin.projects.search', compact('users', 'start_date', 'end_date', 'status', 'salesperson1', 'service_get1'))->render();

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

    public function DownloadProject11()
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

        $users = Project::where('is_renewal', 0)->where('payment_status', null)->orderBy('date_created', 'desc');
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
        $limit = 1000;
        if ($status == "all") {
            $users = $users->limit($limit)->get();
        } elseif ($status) {
            $users = $users->where('status', $status)->limit($limit)->get();
        } else {
            $users = $users->limit($limit)->get();
        }
        // $users = $users->paginate(50);
        // dd($users);
        // $users = $users->chunk(25)->collapse();

        $user_list = \App\Models\Project::groupBy('added_by')
            ->get(['added_by']);

        $salesperson = $user_list;

        $service_get = Service::get();
        $recordCount = 0;
        $page_title = "Total";

        $data = [
            'users' => $users,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $status,
            'page_title' => $page_title,
            'recordCount' => $recordCount,
            'salesperson' => $salesperson,
            'salesperson1' => $salesperson1,
            'service_get1' => $service_get1,
            'service_get' => $service_get,
            'user_title_name' => $user_title_name,
        ];
        $page_user_tile = "Project List - " . $user_title_name;

        $pdfname = $page_user_tile . '.pdf';
        // PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        // Render the PDF view into a variable
        $dompdf = new Dompdf();
        $html = view('admin.projects.view_invoice', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Get the PDF content
        $pdfContent = $dompdf->output();

        // Return the PDF content with appropriate headers to open in a new tab
        // return view('admin.projects.view_invoice', compact('users', 'start_date', 'end_date', 'status','page_title', 'recordCount', 'salesperson','salesperson1','service_get1', 'service_get', 'user_title_name'));

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename=' . $pdfname);

        $pdf = PDF::loadView('admin.projects.view_invoice', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1', 'service_get1', 'service_get', 'user_title_name'));

        return $pdf->download($pdfname);

        // Get the PDF content
        // $pdfContent = $dompdf->output();
        // $pdf = PDF::loadView('admin.domainhosting.gsuite_invoice', compact('domainhosting'));
        // $pdfname = 'tetss.pdf';

        // return view('admin.projects.view_invoice', compact('users', 'start_date', 'end_date', 'status','page_title', 'recordCount', 'salesperson','salesperson1','service_get1', 'service_get', 'user_title_name'));

    }
    public function DownloadProject()
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

        $users = Project::where('is_renewal', 0)->where('payment_status', null)->orderBy('date_created', 'desc');
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
        // $limit = 200; 
        if ($status == "all") {
            $users = $users->get();
        } elseif ($status) {
            $users = $users->where('status', $status)->get();
        } else {
            $users = $users->get();
        }
        // $users = $users->paginate(50);
        // dd($users);

        $user_list = \App\Models\Project::groupBy('added_by')
            ->get(['added_by']);

        $salesperson = $user_list;

        $service_get = Service::get();
        $recordCount = 0;
        $page_title = "Total";

        $data = [
            'users' => $users,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $status,
            'page_title' => $page_title,
            'recordCount' => $recordCount,
            'salesperson' => $salesperson,
            'salesperson1' => $salesperson1,
            'service_get1' => $service_get1,
            'service_get' => $service_get,
            'user_title_name' => $user_title_name,
        ];
        $page_user_tile = "Project List - " . $user_title_name;

        $pdfname = $page_user_tile . '.pdf';
        // PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);
        $html = view('admin.projects.view_invoice', $data)->render();
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 10,
            'margin_right' => 10,
            'tempDir' => storage_path('app/mpdf'),
            'default_font_size' => 9,
            'default_font' => 'dejavusans'
        ]);
        $mpdf->SetAutoPageBreak(true, 15);
        $mpdf->shrink_tables_to_fit = 1;

        $mpdf->WriteHTML($html);

        return response($mpdf->Output('Project_List.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename=Project_List.pdf');
    }
    public function EmployeeProject()
    {
        $start_date = '';
        $end_date = '';
        $staff = '';
        $users = '';
        $select_ex_staff = '';
        $salesperson1 = '';
        $service_get1 = '';
        $total_amount = 0;
        $total_pening = 0;
        $project_details = "";


        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
        }

        if (request('staff') && request('staff') != '') {
            $staff = request('staff');
        }

        if (request('select_ex_staff') && request('select_ex_staff') != '') {
            $select_ex_staff = request('select_ex_staff');
        }

        if ($start_date && $end_date && $staff) {
            $project_details = \App\Models\TaskComment::whereDate('start_date', '>=', $start_date)->whereDate('start_date', '<=', $end_date)->where('staff_id', $staff)->orderBy('start_date', 'DESC')->get();
        } elseif ($staff) {
            $project_details = \App\Models\TaskComment::where('staff_id', $staff)->orderBy('start_date', 'DESC')->get();
        } elseif ($select_ex_staff) {
            $project_details = \App\Models\TaskComment::where('staff_id', $select_ex_staff)->orderBy('start_date', 'DESC')->get();
        }

        // dd($project_details);
        $staff_details = User::where('status', 'active')->where('user_type', 'staff')->get();
        $staff_details1 = User::where('status', 'inactive')->where('user_type', 'staff')->get();

        return view('admin.projects.employee_report', compact('users', 'start_date', 'end_date', 'staff_details', 'staff', 'project_details', 'staff_details1', 'select_ex_staff'));
    }

    public function YearWiseStore(Request $request)
    {
        // dd($request->all());

        $selected_project = $request->select_invoice;

        foreach ($selected_project as $key => $value) {

            if ($value) {
                $add_year = Project::find($value);
                $add_year->added_year = $request->year_wise_year;
                $add_year->save();
            }
        }

        return redirect()->back()->with('success', 'Year Wise Project Move Successfully');
    }

    // public function YearWiseStore(Request $request)
    // {
    //     if (!$request->has('select_invoice') || empty($request->select_invoice)) {
    //         return redirect()->back()
    //             ->with('error', 'Please select at least one project.');
    //     }

    //     foreach ($request->select_invoice as $value) {

    //         $project = Project::find($value);

    //         if ($project) {
    //             $project->added_year = $request->year_wise_year;
    //             $project->save();
    //         }
    //     }

    //     return redirect()->back()
    //         ->with('success', 'Year Wise Project Move Successfully');
    // }

    public function YearWiseProject($id)
    {
        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $total_amount = 0;
        $total_pening = 0;
        $user_title_name = "Webbitech";

        $users = Project::where('is_renewal', 0)->where('added_year', $id)->orderBy('date_created', 'desc');
        // dd($project_get);
        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
            $users = Project::where('is_renewal', 0)->where('added_year', $id)->orderBy('date_created', 'desc');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
            $users = Project::where('is_renewal', 0)->where('added_year', $id)->orderBy('date_created', 'desc');
        }
        if (request('service') && request('service') != '') {
            $service_get1 = request('service');
            $users = Project::where('is_renewal', 0)->where('added_year', $id)->orderBy('date_created', 'desc');
        }
        if (request('status') && request('status') != '' || request('status') == 0) {
            $status = request('status');
            $users = Project::where('is_renewal', 0)->where('added_year', $id)->orderBy('date_created', 'desc');
        }
        if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
            $salesperson1 = request('salesperson');
            $users = Project::where('is_renewal', 0)->where('added_year', $id)->orderBy('date_created', 'desc');
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
            $users = $users->paginate(1000);
        } elseif ($status) {
            $users = $users->where('status', $status)->paginate(100);
        } else {
            $users = $users->paginate(100);
        }
        // $users = $users->paginate(50);
        // dd($users);

        $user_list = \App\Models\Project::groupBy('added_by')
            ->get(['added_by']);

        $salesperson = $user_list;

        $service_get = Service::get();
        $recordCount = 0;
        $page_title = "Total";
        return view('admin.projects.view1', compact('users', 'start_date', 'end_date', 'status', 'page_title', 'recordCount', 'salesperson', 'salesperson1', 'service_get1', 'service_get', 'user_title_name', 'id'));
    }
    public function StopRecurringProject($id)
    {

        $project_details = Project::findOrFail($id);
        $project_details->stop_project = "1";
        if ($project_details->save()) {
            return redirect()->back()->with('warning', 'Project Updated Successfully');
        }
        return redirect()->back()->with('error', 'Something Wrong');
    }

    public function StartNewRecurring(Request $request)
    {
        // dd($request->all());

        $project_details = Project::where('id', $request->estimate_id)->first();
        // dd($project_details);

        $project_save = Project::find($project_details->id);
        $project_save->renew_start = 1;
        $project_save->save();

        $user                   = new Project();
        $user->customer_id      = $project_details->customer_id;
        $user->name             = $project_details->name;
        $user->added_by         = $project_details->added_by;
        $user->sales_user_date  = $project_details->sales_user_date;
        $user->start_date       = $request->date;
        $user->bid_amount       = $project_details->bid_amount;
        $user->total_days       = $project_details->total_days;
        $user->status           = $project_details->status;
        $user->description      = $project_details->description;
        $user->services_id      = $project_details->services_id;

        if ($project_details->is_renewal == "1") {
            $user->is_renewal    = "1";
            $date1 = \Carbon\Carbon::create($request->date);
            $daysAdd = $request->renewal_days;
            $date2 = $date1->subDays($daysAdd);
            $to_date = $date2->format('Y-m-d');
            $user->end_date         = $to_date;
            $user->renewal_days     = $request->renewal_days;
        }

        // dd($user);
        if ($user->save()) {
            return redirect()->back()->with('success', 'Project Restart Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }
}
