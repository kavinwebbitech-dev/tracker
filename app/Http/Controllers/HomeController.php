<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\TaskDetails;
use App\Exports\ExportTask;
use App\Models\Notification;
use App\Models\Incentive;
use App\Models\GSuide;
use App\Models\IncentiveAmount;
use App\Models\EventDetail;
use App\Exports\ExportRecurringTask;
use Auth;
use Mail;
use PDF;
use File;
use Excel;
use DB;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function admin_index()
    {
        $today_date = date('Y-m-d');
        $different_date = "";
        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $users = \App\Models\User::all();
        $user_details = \App\Models\User::whereNot('user_type', 'super_admin')->where('status', 'active')->get();

        // dd($user_details);

        $year_calculate = [];

        foreach ($user_details as $key => $value) {
            if ($value->join_date) {
                $day_calculate = $this->calculateDynamicAnniversary($value->id);
                // dd($day_calculate);
                $year_calculate[$value->id] = [
                    "name" => $day_calculate['employee_name'],
                    "next_anniversary" => $day_calculate['next_anniversary']
                ];
            }
        }

        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }
        if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
            $salesperson1 = request('salesperson');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }

        $today_date = date('Y-m-d');
        // $today_date = "2025-01-06";
        $this_month = now()->month;
        $this_year = now()->year;

        $today_project = Project::where('created_at', 'like', '%' . $today_date . '%')->get();
        $today_cancel_project = Project::where('created_at', 'like', '%' . $today_date . '%')->where('status', 6)->get();
        // $month_project = Project::latest();
        // $month_cancel_project = Project::where('status', 6);

        // dd($start_date);
        if ($start_date && $end_date) {
            $month_project = Project::latest()->whereDate('sales_user_date', '>=', $start_date)
                ->whereDate('sales_user_date', '<=', $end_date);
            $month_cancel_project = Project::latest()->where('status', 6)->whereDate('sales_user_date', '>=', $start_date)
                ->whereDate('sales_user_date', '<=', $end_date);
        } elseif ($start_date) {

            $month_project = Project::latest()->whereDate('sales_user_date', '>=', $start_date)
                ->whereDate('sales_user_date', '<=', $start_date);
            $month_cancel_project = Project::latest()->where('status', 6)->whereDate('sales_user_date', '>=', $start_date)
                ->whereDate('sales_user_date', '<=', $start_date);

            $end_date = $start_date;
        } else {
            $month_project = Project::whereMonth('sales_user_date', $this_month)->whereYear('sales_user_date', $this_year)->latest();
            $month_cancel_project = Project::where('status', 6)->whereMonth('sales_user_date', $this_month)->whereYear('sales_user_date', $this_year)->latest();
        }


        if ($start_date || $end_date || $salesperson1) {
            $month_project = $month_project;
            $month_cancel_project = $month_cancel_project;
        } else {
            $month_project = $month_project->whereMonth('sales_user_date', $this_month);
            $month_cancel_project = $month_cancel_project->whereMonth('sales_user_date', $this_month);
        }

        if ($salesperson1) {
            $month_project = $month_project->where('added_by', $salesperson1);
            $month_cancel_project = $month_cancel_project->where('added_by', $salesperson1);
        }

        $month_project = $month_project->get();
        $month_cancel_project = $month_cancel_project->get();

        // $projects_progress = Project::with(['tasks', 'task_staff.task_comments'])->latest()->get();
        $projects_progress_query = Project::whereHas('task_staff')
            ->with(['tasks', 'task_staff', 'service']);

        if ($salesperson1) {
            $projects_progress_query->where('added_by', $salesperson1);
        }

        if ($start_date && $end_date) {
            $projects_progress_query->whereDate('date_created', '>=', $start_date)
                ->whereDate('date_created', '<=', $end_date);
        } elseif ($start_date) {
            $projects_progress_query->whereDate('date_created', $start_date);
        }

        \App\Models\TaskStaff::loadLoggedHoursMap();

        $projects_progress = $projects_progress_query->latest()->get();
        $projects_progress->each(function ($project) {
            $serviceName = optional($project->service)->name ?? '';
            $project->service_category = $this->getServiceCategory($serviceName);
        });

        // Remove fully completed projects before sending to the view
        $projects_progress = $projects_progress->reject(function ($project) {
            return ($project->progress_percent ?? 0) >= 100;
        })->values();

        $total_sub_admin  = \App\Models\User::where('user_type', 'sub_admin')->where('status', 'active')->get();
        $total_staff      = \App\Models\User::where('user_type', 'staff')->where('status', 'active')->get();
        $total_task       = \App\Models\TaskStaff::get();
        $pending          = \App\Models\TaskStaff::where('status', 'pending')->get();
        $staff_com        = \App\Models\TaskStaff::where('status', 'user_completed')->get();
        $progress         = \App\Models\TaskStaff::where('status', 'inprogress')->get();
        $completed        = \App\Models\TaskStaff::where('status', 'completed')->get();
        $rejected         = \App\Models\TaskStaff::where('status', 'rejected')->get();
        $over_due         = \App\Models\TaskStaff::where('status', 'over_due')->get();
        $recomand         = \App\Models\TaskStaff::where('status', 'recommend_to_admin')->get();
        $total_recurring  = \App\Models\Task::where('task_type', 'recurring')->get();
        // $salesperson      = \App\Models\Project::groupBy('added_by')->get(['added_by']);
        $salesperson = \App\Models\User::whereIn(
            'id',
            \App\Models\Project::select('added_by')
                ->whereNotNull('added_by')
                ->distinct()
        )
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('home', compact(
            'year_calculate',
            'today_date',
            'this_month',
            'this_year',
            'today_project',
            'today_cancel_project',
            'month_project',
            'month_cancel_project',
            'start_date',
            'salesperson1',
            'end_date',
            'projects_progress',
            'total_sub_admin',
            'total_staff',
            'total_task',
            'pending',
            'staff_com',
            'progress',
            'completed',
            'rejected',
            'over_due',
            'recomand',
            'total_recurring',
            'salesperson'
        ));
    }

    public function allProjects($col)
    {
        // $col is 'web' or 'logo' — matches your dashboard column keys
        $category = request('category', 'all');
        $time     = request('time', 'all');

        $query = Project::whereHas('task_staff')
            ->with(['tasks', 'task_staff', 'service']);

        \App\Models\TaskStaff::loadLoggedHoursMap();

        $allProjects = $query->latest()->get();

        $allProjects->each(function ($project) {
            $serviceName = optional($project->service)->name ?? '';
            $project->service_category = $this->getServiceCategory($serviceName);
        });

        // Restrict to this column's category group (web+digital vs logo)
        if ($col === 'web') {
            $allProjects = $allProjects->whereIn('service_category', ['web', 'digital'])->values();
        } elseif ($col === 'logo') {
            $allProjects = $allProjects->where('service_category', 'logo')->values();
        }

        // Apply the dropdown filters (category + time), same logic as the dashboard JS
        if ($category !== 'all') {
            $allProjects = $allProjects->where('service_category', $category)->values();
        }

        $now              = \Carbon\Carbon::now();
        $thirtyDaysAgo    = $now->copy()->subDays(30);
        $startOfThisMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $endOfLastMonth   = $now->copy()->startOfMonth()->subDay();

        if ($time === 'alltaskcomplted') {
            $allProjects = $allProjects->filter(function ($project) {
                return ($project->progress_percent ?? 0) >= 100;
            })->values();
        } else {
            // Non-completed filters exclude completed projects, matching dashboard behavior
            $allProjects = $allProjects->reject(function ($project) {
                return ($project->progress_percent ?? 0) >= 100;
            })->values();

            if ($time !== 'all') {
                $allProjects = $allProjects->filter(function ($project) use ($time, $thirtyDaysAgo, $startOfThisMonth, $startOfLastMonth, $endOfLastMonth) {
                    $raw = $project->date_created ?? $project->created_at;
                    if (!$raw) return false;

                    $rowDate = \Carbon\Carbon::parse($raw);

                    return match ($time) {
                        'recent'  => $rowDate->gte($thirtyDaysAgo),
                        'old'     => $rowDate->lt($thirtyDaysAgo),
                        'month'   => $rowDate->gte($startOfThisMonth),
                        'lmonth'  => $rowDate->gte($startOfLastMonth) && $rowDate->lte($endOfLastMonth),
                        default   => true,
                    };
                })->values();
            }
        }

        $columnTitle = $col === 'web' ? 'Web Development & Digital Marketing' : 'Logo Design';

        return view('all_projects', compact('allProjects', 'col', 'category', 'time', 'columnTitle'));
    }
    // Add this method inside HomeController

    private function getServiceCategory($serviceName)
    {
        $serviceName = strtolower(trim($serviceName ?? ''));

        $webDevelopment = [
            'website design',
            'ecommerce product',
            'custom software',
            'website updation',
            'mobile app development',
            'software development',
        ];

        $logoDesign = [
            'logo design',
            'brochure design',
            'graphics design',
            'business card design & printing',
            'business card printing',
            'printing',
            'power point presentation',
            'video editing',
        ];

        $digitalMarketing = [
            'google ads (paid)',
            'search engine optimization',
            'facebook ads',
            'social media handling',
            'google my business',
        ];

        if (in_array($serviceName, $webDevelopment)) return 'web';
        if (in_array($serviceName, $logoDesign)) return 'logo';
        if (in_array($serviceName, $digitalMarketing)) return 'digital';

        // fallback keyword matching for combined/new service names
        if (str_contains($serviceName, 'website') || str_contains($serviceName, 'software') || str_contains($serviceName, 'app')) {
            return 'web';
        }
        if (str_contains($serviceName, 'design') || str_contains($serviceName, 'print') || str_contains($serviceName, 'video') || str_contains($serviceName, 'presentation')) {
            return 'logo';
        }
        if (str_contains($serviceName, 'ads') || str_contains($serviceName, 'seo') || str_contains($serviceName, 'social') || str_contains($serviceName, 'google my business')) {
            return 'digital';
        }

        return 'other';
    }

    private function calculateDynamicAnniversary($employeeId)
    {
        // Retrieve the employee by ID
        $employee = User::find($employeeId);

        // Check if the employee exists
        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        // dd($employee);

        // Get the hire date and create a Carbon instance
        $hireDate = \Carbon\Carbon::parse($employee->join_date);

        // Get the current date (today)
        $currentDate = \Carbon\Carbon::now();

        // Calculate the employee's anniversary for this year
        $anniversaryThisYear = $hireDate->setYear($currentDate->year);
        // dd($anniversaryThisYear);

        // Check if the anniversary for this year has passed or not
        if ($anniversaryThisYear->isPast()) {
            // If the anniversary has already passed, calculate for the next year
            $nextAnniversary = $hireDate->addYear();
        } else {
            // If the anniversary hasn't passed yet, it's this year's anniversary
            $nextAnniversary = $anniversaryThisYear;
        }

        $data['employee_name'] = $employee->name;
        $data['next_anniversary'] = $nextAnniversary->toDateString();

        return $data;
    }

    public function two_admin_index()
    {
        return view('admin');
    }

    public function sub_admin_index()
    {
        $today_date = date('Y-m-d');
        $different_date = "";
        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $users = \App\Models\User::all();
        $user_details = \App\Models\User::whereNot('user_type', 'super_admin')->get();

        $year_calculate = [];

        foreach ($user_details as $key => $value) {
            if ($value->join_date) {
                $day_calculate = $this->calculateDynamicAnniversary($value->id);
                // dd($day_calculate);
                $year_calculate[$value->id] = [
                    "name" => $day_calculate['employee_name'],
                    "next_anniversary" => $day_calculate['next_anniversary']
                ];
            }
        }

        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }
        if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
            $salesperson1 = request('salesperson');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }

        $today_date = date('Y-m-d');
        // $today_date = "2025-01-06";
        $this_month = now()->month;
        $this_year = now()->year;

        $today_project = Project::where('created_at', 'like', '%' . $today_date . '%')->get();
        $today_cancel_project = Project::where('created_at', 'like', '%' . $today_date . '%')->where('status', 6)->get();
        $month_project = Project::whereMonth('sales_user_date', $this_month)->whereYear('sales_user_date', $this_year)->latest();
        $month_cancel_project = Project::where('status', 6)->whereMonth('sales_user_date', $this_month)->whereYear('sales_user_date', $this_year)->latest();

        if ($start_date && $end_date) {
            $month_project = $month_project->whereDate('sales_user_date', '>=', $start_date)
                ->whereDate('sales_user_date', '<=', $end_date);
            $month_cancel_project = $month_cancel_project->whereDate('sales_user_date', '>=', $start_date)
                ->whereDate('sales_user_date', '<=', $end_date);
        } elseif ($start_date) {

            $month_project = $month_project->whereDate('sales_user_date', '>=', $start_date)
                ->whereDate('sales_user_date', '<=', $start_date);
            $month_cancel_project = $month_cancel_project->whereDate('sales_user_date', '>=', $start_date)
                ->whereDate('sales_user_date', '<=', $start_date);

            $end_date = $start_date;
        } else {
            $month_project = $month_project;
            $month_cancel_project = $month_cancel_project;
        }

        if ($start_date || $end_date || $salesperson1) {
            $month_project = $month_project;
            $month_cancel_project = $month_cancel_project;
        } else {
            $month_project = $month_project->whereMonth('sales_user_date', $this_month);
            $month_cancel_project = $month_cancel_project->whereMonth('sales_user_date', $this_month);
        }

        if ($salesperson1) {
            $month_project = $month_project->where('added_by', $salesperson1);
            $month_cancel_project = $month_cancel_project->where('added_by', $salesperson1);
        }

        $month_project = $month_project->get();
        $month_cancel_project = $month_cancel_project->get();


        $event_details = EventDetail::latest()->get();

        return view('sub_admin', compact('year_calculate', 'today_date', 'this_month', 'this_year', 'today_project', 'today_cancel_project', 'month_project', 'month_cancel_project', 'start_date', 'salesperson1', 'end_date', 'event_details'));
    }

    public function staff_index()
    {
        // dd(8);
        $today_date = date('Y-m-d');
        $different_date = "";
        $start_date = '';
        $end_date = '';
        $status = '';
        $salesperson1 = '';
        $service_get1 = '';
        $users = \App\Models\User::all();
        $user_details = \App\Models\User::whereNot('user_type', 'super_admin')->get();

        $year_calculate = [];

        foreach ($user_details as $key => $value) {
            if ($value->join_date) {
                $day_calculate = $this->calculateDynamicAnniversary($value->id);
                // dd($day_calculate);
                $year_calculate[$value->id] = [
                    "name" => $day_calculate['employee_name'],
                    "next_anniversary" => $day_calculate['next_anniversary']
                ];
            }
        }

        if (request('start_date') && request('start_date') != '') {
            $start_date = request('start_date');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }
        if (request('end_date') && request('end_date') != '') {
            $end_date = request('end_date');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }
        if (request('salesperson') && request('salesperson') != '' || request('salesperson') == 0) {
            $salesperson1 = request('salesperson');
            $users = Project::where('is_renewal', 0)->orderBy('date_created', 'desc');
        }

        $today_date = date('Y-m-d');
        // $today_date = "2025-01-06";
        $this_month = now()->month;
        $this_year = now()->year;

        $today_project = Project::where('added_by', Auth::user()->id)->where('created_at', 'like', '%' . $today_date . '%')->get();
        $today_cancel_project = Project::where('added_by', Auth::user()->id)->where('created_at', 'like', '%' . $today_date . '%')->where('status', 6)->get();
        $month_project = Project::where('added_by', Auth::user()->id);
        $month_cancel_project = Project::where('added_by', Auth::user()->id)->where('status', 6);

        if ($start_date) {
            $month_project = $month_project->whereDate('sales_user_date', '>=', $start_date)
                ->whereDate('sales_user_date', '<=', $end_date);
            $month_cancel_project = $month_cancel_project->whereDate('sales_user_date', '>=', $start_date)
                ->whereDate('sales_user_date', '<=', $end_date);
        }
        if ($start_date || $end_date || $salesperson1) {
            $month_project = $month_project;
            $month_cancel_project = $month_cancel_project;
        } else {
            $month_project = $month_project->whereMonth('sales_user_date', $this_month);
            $month_cancel_project = $month_cancel_project->whereMonth('sales_user_date', $this_month);
        }

        if ($salesperson1) {
            $month_project = $month_project->where('added_by', $salesperson1);
            $month_cancel_project = $month_cancel_project->where('added_by', $salesperson1);
        }

        $month_project = $month_project->get();
        $month_cancel_project = $month_cancel_project->get();

        $event_details = EventDetail::latest()->get();
        // dd($month_project);

        return view('staff', compact('year_calculate', 'today_date', 'this_month', 'this_year', 'today_project', 'today_cancel_project', 'month_project', 'month_cancel_project', 'start_date', 'salesperson1', 'end_date', 'event_details'));
    }

    public function SubAdminCreate()
    {
        $admin = User::where('user_type', 'admin')->where('status', 'active')->get();
        return view('admin.subAdmin.add', compact('admin'));
    }

    public function SubAdminView()
    {
        // $whatsapp_cloud_api = new WhatsAppCloudApi([
        //     'from_phone_number_id' => 'your-configured-from-phone-number-id',
        //     'access_token' => 'your-facebook-whatsapp-application-token',
        // ]);
        // dd($whatsapp_cloud_api);
        // $test = $whatsapp_cloud_api->sendTextMessage('34676104574', 'Hey there! I\'m using WhatsApp Cloud API. Visit https://www.netflie.es');
        // dd($test);
        $sub_admin = User::where('user_type', 'sub_admin')->where('status', 'active')->get();
        return view('admin.subAdmin.view', compact('sub_admin'));
    }

    public function SubAdminEdit($id)
    {
        $admin = User::where('user_type', 'admin')->where('status', 'active')->get();
        $sub_admin = User::where('id', $id)->where('status', 'active')->first();
        return view('admin.subAdmin.edit', compact('sub_admin', 'admin'));
    }

    public function SubAdminDelete($id)
    {
        if ($id) {
            $user  = User::find($id);
            if ($user->delete()) {
                return redirect()->route('sub.admin.view')->with('error', 'Sub Admin Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Something Wrong');
            }
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminStore(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users',
            'password' => 'required'
        ]);

        $password           = Hash::make($request->password);
        $user               = new User();
        $user->name         = $request->name;
        $user->admin_id     = Auth::user()->id;
        $user->email        = $request->email;
        $user->join_date    = $request->join_date;
        $user->salary       = $request->salary;
        $user->role          = $request->role;
        $user->phone        = $request->phone;
        $user->user_type    = "sub_admin";
        $user->status       = "active";
        $user->password     = $password;
        $user->permissions  = json_encode($request->permissions);
        if ($user->save()) {

            $data['name']          = $request->name;
            $data['email']         = $request->email;
            $data['password']      = $request->password;
            $data['logo']          = url('public/admin_assets/images/logo.png');
            $data['link_url']      = url('/login');

            try {
                Mail::send('mail.user_details', $data, function ($message) use ($data) {
                    $message->to($data['email'], $data['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                        ->subject('Welcome to Webbitech Family');
                });
            } catch (\Exception $e) {
                // dd($e);
            }

            return redirect()->route('sub.admin.view')->with('success', 'Sub Admin Created Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminUpdate(Request $request, $id)
    {
        // dd($request->all());

        $user               = User::find($id);
        if ($request->password) {
            $request->validate([
                'name' => 'required|max:255',
                'password' => 'required'
            ]);

            $password           = Hash::make($request->password);
            $user->password     = $password;
        }
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->admin_id     = Auth::user()->id;
        $user->salary       = $request->salary;
        $user->phone        = $request->phone;
        $user->join_date    = $request->join_date;
        $user->role         = $request->role;
        $user->user_type    = "sub_admin";
        $user->status       = $request->status;
        $user->permissions  = json_encode($request->permissions);
        if ($user->save()) {

            $data['name']          = $request->name;
            $data['email']         = $request->email;
            $data['password']      = $request->password;
            $data['logo']          = url('public/admin_assets/images/logo.png');
            $data['link_url']      = url('/login');

            try {
                Mail::send('mail.user_details', $data, function ($message) use ($data) {
                    $message->to($data['email'], $data['name'])
                        ->from("noreplywebbitech@gmail.com", 'no-reply')
                        ->subject('Welcome to Webbitech Family');
                });
            } catch (\Exception $e) {
                // dd($e);
            }

            return redirect()->route('sub.admin.view')->with('warning', 'Sub Admin Edited Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function StaffProfile()
    {
        $user = User::where('id', Auth::user()->id)->first();
        return view('staff.profile', compact('user'));
    }

    public function StaffProfileUpload(Request $request, $id)
    {
        // dd($request->all());

        $user               = User::find($id);
        if ($request->password) {
            $request->validate([
                'name' => 'required|max:255',
                'password' => 'required|confirmed'
            ]);

            $password           = Hash::make($request->password);
            $user->password     = $password;
        } else {
            $request->validate([
                'name' => 'required|max:255'
            ]);
        }

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $filename = time() . '.' . $image->extension();
            $destinationPath = public_path('/profile');
            // dd($destinationPath);
            $image->move($destinationPath, $filename);
            $user->profile_picture           = $filename;
        }

        $user->name           = $request->name;
        $user->phone          = $request->phone;
        $user->gender         = $request->gender;
        $user->address        = $request->address;
        $user->city           = $request->city;
        $user->state          = $request->state;
        $user->country        = $request->country;
        $user->zip_code       = $request->zip_code;
        $user->date_of_birth  = $request->dob;
        $user->join_date      = $request->join_date;
        if ($user->save()) {
            return redirect()->route('staff.profile')->with('warning', 'Profile Edited Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function ClientProfile()
    {
        $user = User::where('id', Auth::user()->id)->first();
        return view('client.profile', compact('user'));
    }

    public function ClientProfileUpload(Request $request, $id)
    {
        // dd($request->all());

        $user               = User::find($id);
        if ($request->password) {
            $request->validate([
                'name' => 'required|max:255',
                'password' => 'required|confirmed'
            ]);

            $password           = Hash::make($request->password);
            $user->password     = $password;
        } else {
            $request->validate([
                'name' => 'required|max:255'
            ]);
        }

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $filename = time() . '.' . $image->extension();
            $destinationPath = public_path('/profile');
            // dd($destinationPath);
            $image->move($destinationPath, $filename);
            $user->profile_picture           = $filename;
        }

        $user->name           = $request->name;
        $user->phone          = $request->phone;
        $user->gender         = $request->gender;
        $user->address        = $request->address;
        $user->city           = $request->city;
        $user->state          = $request->state;
        $user->country        = $request->country;
        $user->zip_code       = $request->zip_code;
        $user->date_of_birth  = $request->dob;
        $user->join_date      = $request->join_date;
        if ($user->save()) {
            return redirect()->route('client.profile')->with('warning', 'Profile Edited Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminProfile()
    {
        $user = User::where('id', Auth::user()->id)->first();
        // dd($user);
        return view('sub_admin.profile', compact('user'));
    }

    public function SubAdminProfileUpload(Request $request, $id)
    {
        // dd($request->all());

        $user               = User::find($id);
        if ($request->password) {
            $request->validate([
                'name' => 'required|max:255',
                'password' => 'required|confirmed'
            ]);

            $password           = Hash::make($request->password);
            $user->password     = $password;
        } else {
            $request->validate([
                'name' => 'required|max:255'
            ]);
        }

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $filename = time() . '.' . $image->extension();
            $destinationPath = public_path('/profile');
            // dd($destinationPath);
            $image->move($destinationPath, $filename);
            $user->profile_picture           = $filename;
        }

        $user->name           = $request->name;
        $user->phone          = $request->phone;
        $user->gender         = $request->gender;
        $user->address        = $request->address;
        $user->city           = $request->city;
        $user->state          = $request->state;
        $user->country        = $request->country;
        $user->zip_code       = $request->zip_code;
        $user->date_of_birth  = $request->dob;
        if ($user->save()) {
            return redirect()->route('sub.admin.profile')->with('warning', 'Profile Edited Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function AdminProfile()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $incentive_details = IncentiveAmount::where('incen_type', '1')->get();
        $group_incentive_details = IncentiveAmount::where('incen_type', '2')->get();
        $event_details = EventDetail::latest()->get();
        $gsuide_details = GSuide::latest()->get();

        return view('admin.profile', compact('user', 'incentive_details', 'event_details', 'group_incentive_details', 'gsuide_details'));
    }

    public function AdminProfileUpload(Request $request, $id)
    {
        // dd($request->all());

        $user               = User::find($id);
        if ($request->password) {
            $request->validate([
                'name' => 'required|max:255',
                'password' => 'required|confirmed'
            ]);

            $password           = Hash::make($request->password);
            $user->password     = $password;
        } else {
            $request->validate([
                'name' => 'required|max:255'
            ]);
        }

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $filename = time() . '.' . $image->extension();
            $destinationPath = public_path('/profile');
            // dd($destinationPath);
            $image->move($destinationPath, $filename);
            $user->profile_picture           = $filename;
        }

        $user->name           = $request->name;
        $user->phone          = $request->phone;
        $user->gender         = $request->gender;
        $user->address        = $request->address;
        $user->city           = $request->city;
        $user->state          = $request->state;
        $user->country        = $request->country;
        $user->zip_code       = $request->zip_code;
        $user->date_of_birth  = $request->dob;
        if ($user->save()) {
            return redirect()->route('admin.profile')->with('warning', 'Profile Edited Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }

    public function SubAdminTaskDetails($id)
    {
        $custom_task = Task::where('admin_id', $id)->where('task_type', 'custom')->get();
        $recurring_task = Task::where('admin_id', $id)->where('task_type', 'recurring')->get();
        $user_details = User::where('id', $id)->first();
        // dd($recurring_task);
        return view('admin.subAdmin.task_view', compact('custom_task', 'recurring_task', 'user_details'));
    }

    public function SubAdminReport(Request $request)
    {
        // dd($request->all());
        $title = $request->row_check;
        $data['user_id'] = $request->user_id;
        $data['user_type'] = "admin_id";
        $data['task_type'] = "custom";
        if ($request->row_check == "Pending") {
            $task_status = "pending";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Progress") {
            $task_status = "progress";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Staff Completed") {
            $task_status = "user_completed";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Completed") {
            $task_status = "completed";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Canceled") {
            $task_status = "canceled";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Rejected") {
            $task_status = "rejected";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Reopen") {
            $task_status = "hold";
            $data['task_status'] = $task_status;
        } elseif ($request->row_check == "Completed") {
            $task_status = "reopen";
            $data['task_status'] = $task_status;
        }
        $data2 = Task::where('admin_id', $request->user_id)->where('task_type', 'custom')->where('status', $task_status)->get();
        // dd($data2);
        if (count($data2)) {
            return Excel::download(new ExportTask($data), $title . '.xlsx');
        } else {
            return redirect()->back()->with('error', 'No Data Available');
        }
    }

    public function SubAdminRecurringReport($id)
    {
        // dd($id);
        $data['task_id'] = $id;
        $data2 = TaskDetails::where('task_id', $id)->get();
        // dd($data2);
        if (count($data2)) {
            return Excel::download(new ExportRecurringTask($data), 'Recurring.xlsx');
        } else {
            return redirect()->back()->with('error', 'No Data Available');
        }
    }

    public function Incentive()
    {

        $incentive_details = Incentive::where('user_id', Auth::user()->id)->orWhere('group_id', Auth::user()->group_id)->get();

        return view('staff.incentive', compact('incentive_details'));
    }

    public function IncentiveSave(Request $request)
    {
        // dd($request->all());

        $data_details = $request->addmore;
        IncentiveAmount::where('incen_type', 1)->forceDelete();

        foreach ($data_details as $key => $value) {

            $incentive_save                 = new IncentiveAmount();
            $incentive_save->start_amount   = $value['start_amount'];
            $incentive_save->end_amount     = $value['end_amount'];
            $incentive_save->amount         = $value['incentive'];
            $incentive_save->incen_type     = 1;
            $incentive_save->save();
        }

        return redirect()->back()->with('success', 'Incentive Amount Details Updated');
    }

    public function IncentiveGroupSave(Request $request)
    {
        // dd($request->all());

        $data_details = $request->addmore1;
        IncentiveAmount::where('incen_type', 2)->forceDelete();

        foreach ($data_details as $key => $value) {

            $incentive_save                 = new IncentiveAmount();
            $incentive_save->start_amount   = $value['start_amount'];
            $incentive_save->end_amount     = $value['end_amount'];
            $incentive_save->amount         = $value['incentive'];
            $incentive_save->incen_type     = 2;
            $incentive_save->save();
        }

        return redirect()->back()->with('success', 'Group Incentive Amount Details Updated');
    }

    public function GSuideSave(Request $request)
    {
        // dd($request->all());

        $data_details = $request->addmore4;
        GSuide::latest()->forceDelete();
        // dd($data_details);
        foreach ($data_details as $key => $value) {
            // dd($value);
            $incentive_save                 = new GSuide();
            $incentive_save->start_email    = $value['start_email'];
            $incentive_save->end_email      = $value['end_email'];
            $incentive_save->amount         = $value['amount'];
            $incentive_save->actual_price   = $value['actual_price'];
            $incentive_save->email_type     = $value['email_type'];
            $incentive_save->save();
        }

        return redirect()->back()->with('success', 'G Suide Details Updated');
    }

    public function ProjectDetails(Request $request)
    {
        // dd($request->all());

        $search_date = $request->product_id;
        $user_details = User::get();
        $view =  view('admin.modelrender', compact('user_details', 'search_date'))->render();

        return response()->json(['html' => $view]);
    }

    public function ThisMonthProjects($month, $year)
    {
        // dd($year);
        $date_calculate = $year . '-' . $month;
        $firstMonth = \Carbon\Carbon::createFromDate($year, $month);
        // dd($firstMonth);
        $staff_id = '';

        if (request('staff_id') && request('staff_id') != '') {
            $staff_id = request('staff_id');
        }

        if ($staff_id) {
            $month_project = \App\Models\Project::whereYear('sales_user_date', '=', $year)
                ->whereMonth('sales_user_date', '=', $month)
                ->where('added_by', $staff_id)
                ->latest()->get();
        } else {
            $month_project = \App\Models\Project::whereYear('sales_user_date', '=', $year)
                ->whereMonth('sales_user_date', '=', $month)
                ->latest()->get();
        }

        $user_list = \App\Models\Project::whereYear('sales_user_date', '=', $year)
            ->whereMonth('sales_user_date', '=', $month)
            ->groupBy('added_by')
            ->get(['added_by']);

        // dd($month_project);

        return view('admin.OrderProject.index', compact('month_project', 'month', 'year', 'user_list', 'staff_id'));
    }

    public function SubProjectDetails(Request $request)
    {
        // dd($request->all());

        $search_date = $request->product_id;
        $user_details = User::get();
        $view =  view('admin.modelrender', compact('user_details', 'search_date'))->render();

        return response()->json(['html' => $view]);
    }

    public function SubThisMonthProjects($month, $year)
    {
        // dd($year);
        $date_calculate = $year . '-' . $month;
        $firstMonth = \Carbon\Carbon::createFromDate($year, $month);

        $staff_id = '';

        if (request('staff_id') && request('staff_id') != '') {
            $staff_id = request('staff_id');
        }

        if ($staff_id) {
            $month_project = \App\Models\Project::whereYear('created_at', '=', $year)
                ->whereMonth('created_at', '=', $month)
                ->where('added_by', $staff_id)
                ->latest()->get();
        } else {
            $month_project = \App\Models\Project::whereYear('created_at', '=', $year)
                ->whereMonth('created_at', '=', $month)
                ->latest()->get();
        }


        $user_list = \App\Models\Project::whereYear('created_at', '=', $year)
            ->whereMonth('created_at', '=', $month)
            ->groupBy('added_by')
            ->get(['added_by']);

        // dd($user_list);

        return view('sub_admin.OrderProject.index', compact('month_project', 'month', 'year', 'user_list', 'staff_id'));
    }

    public function client_index()
    {

        return view('client');
    }

    public function freelancer_index()
    {

        return view('freelancer');
    }

    public function FreelancerProfile()
    {
        $user = User::where('id', Auth::user()->id)->first();
        return view('freelancer.profile', compact('user'));
    }

    public function FreelancerProfileUpload(Request $request, $id)
    {
        // dd($request->all());

        $user               = User::find($id);
        if ($request->password) {
            $request->validate([
                'name' => 'required|max:255',
                'password' => 'required|confirmed'
            ]);

            $password           = Hash::make($request->password);
            $user->password     = $password;
        } else {
            $request->validate([
                'name' => 'required|max:255'
            ]);
        }

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $filename = time() . '.' . $image->extension();
            $destinationPath = public_path('/profile');
            // dd($destinationPath);
            $image->move($destinationPath, $filename);
            $user->profile_picture           = $filename;
        }

        $user->name           = $request->name;
        $user->phone          = $request->phone;
        $user->gender         = $request->gender;
        $user->address        = $request->address;
        $user->city           = $request->city;
        $user->state          = $request->state;
        $user->country        = $request->country;
        $user->zip_code       = $request->zip_code;
        $user->date_of_birth  = $request->dob;
        $user->join_date      = $request->join_date;
        if ($user->save()) {
            return redirect()->route('client.profile')->with('warning', 'Profile Edited Successfully');
        } else {
            return redirect()->back()->with('error', 'Something Wrong');
        }
    }
}
