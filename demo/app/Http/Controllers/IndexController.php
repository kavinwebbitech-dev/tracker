<?php

namespace App\Http\Controllers;


use App\Models\Task;
use App\Models\TaskDetails;
use App\Models\TaskComment;
use App\Models\TaskStaff;
use App\Models\Project;
use App\Models\User;
use App\Models\Incentive;
use App\Models\MyBill;
use App\Models\GroupStaff;
use App\Models\IncentiveAmount;
use App\Models\Notification;
use Illuminate\Http\Request;
use Auth;
use Mail;

class IndexController extends Controller
{

    public function index()
    {

        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        // \Artisan::call('schedule:run');
        
        $this_month = now()->month;
        $today_receive_amount = 0;

        $user_details = User::where('user_type', 'staff')->where('group_id', null)->get();
        
        
        $incentive_amount = IncentiveAmount::where('incen_type', 1)->get();
        // if ($user_details) 
        // {

        //     foreach ($user_details as $key => $value) {

        //         $today_project = \App\Models\Project::where('added_by', $value->id)->whereNot('status', 6)->whereMonth('created_at', $this_month)->get();
        //         if (count($today_project)) {
        //             $today_receive_amount = 0;
        //             foreach ($today_project as $key1 => $value1) {
        //                 $today_receive_amount += $value1->bid_amount;
        //             }
                    
        //             if ($incentive_amount) {
        //                 foreach ($incentive_amount as $key1 => $value1) {
        //                     if ($today_receive_amount >= $value1->start_amount && $today_receive_amount <= $value1->end_amount) 
        //                     {
        //                         $incenive_check = Incentive::where('user_id',$value->id)->where('month', $this_month)->where('year', now()->year)->first();
        //                         if ($incenive_check) {
        //                             $incentive_add          = Incentive::find($incenive_check->id);
        //                         }
        //                         else
        //                         {
        //                             $incentive_add          = new Incentive();
        //                         }
        //                         $incentive_add->user_id     = $value->id;
        //                         $incentive_add->month       = now()->month;
        //                         $incentive_add->year        = now()->year;
        //                         $incentive_add->amount      = $value1->amount;
        //                         $incentive_add->sale_amount = $today_receive_amount;
        //                         if ($value1->amount == 0) {
        //                             $incentive_add->status      = "Not Eligible";
        //                         }
        //                         else
        //                         {
        //                             $incentive_add->status      = "Eligible";
        //                         }
        //                         $incentive_add->save();
        //                     }
        //                 }
        //             }

        //         }

        //     }
            

        // }

        $group_details = GroupStaff::where('status', 'Active')->get();
        $incentive_amount1 = IncentiveAmount::where('incen_type', 2)->get();

        if (count($group_details)) 
        {

            foreach ($group_details as $key3 => $group) {
                
                $user_details1 = json_decode($group->group_user);

                $today_receive_amount1 = 0;
                foreach ($user_details1 as $key4 => $user_Value) {
                    
                    $today_project1 = \App\Models\Project::where('added_by', $user_Value)->whereNot('status', 6)->whereMonth('created_at', $this_month)->get();
                    if (count($today_project1)) {
                        foreach ($today_project1 as $key5 => $value2) {
                            $today_receive_amount1 += $value2->bid_amount;
                        }
                    }

                }

                if ($incentive_amount1) {
                    foreach ($incentive_amount1 as $key6 => $incentive) {
                        // dd($today_receive_amount1);
                        if ($today_receive_amount1 >= $incentive->start_amount && $today_receive_amount1 <= $incentive->end_amount) 
                        {
                            $incenive_check = Incentive::where('group_id',$group->id)->where('month', $this_month)->where('year', now()->year)->first();
                            if ($incenive_check) {
                                $incentive_add          = Incentive::find($incenive_check->id);
                            }
                            else
                            {
                                $incentive_add          = new Incentive();
                            }
                            $incentive_add->user_id     = $group->group_user;
                            $incentive_add->group_id    = $group->id;
                            $incentive_add->month       = now()->month;
                            $incentive_add->year        = now()->year;
                            $incentive_add->amount      = $incentive->amount;
                            $incentive_add->sale_amount = $today_receive_amount1;
                            if ($incentive->amount == 0) {
                                $incentive_add->status      = "Not Eligible";
                            }
                            else
                            {
                                $incentive_add->status      = "Eligible";
                            }
                            $incentive_add->save();
                        }
                    }
                }

            }
        } 
        else 
        {
            // code...
        }
        
        
        return view('auth.login');
    }

    public function UserLogin(Request $request)
    {
        // dd($request->all());
        $field = 'email';

        // $request->merge([$field => $request->input('login')]);

        if (Auth::attempt($request->only($field, 'password'))) {
            // dd(Auth::user()->user_type);
            if (Auth::check() && Auth::user()->user_type == 'super_admin') {
                return redirect()->route('admin.index')->with('success', 'Login Successfully');
                // return redirect('/admin');
            }
            elseif (Auth::check() && Auth::user()->user_type == 'admin') {
                return redirect()->route('two.admin.index')->with('success', 'Login Successfully');
                // return redirect('/admin');
            }
            elseif (Auth::check() && Auth::user()->user_type == 'sub_admin') {
                return redirect()->route('sub.admin.index')->with('success', 'Login Successfully');
            }
            elseif (Auth::check() && Auth::user()->user_type == 'staff') {
                return redirect()->route('staff.index')->with('success', 'Login Successfully');
            }
            elseif (Auth::check() && Auth::user()->user_type == 'client') {
                // dd("hlo");
                return redirect()->route('client.index')->with('success', 'Login Successfully');
            }
            elseif (Auth::check() && Auth::user()->user_type == 'freelancer') {
                // dd("hlo");
                return redirect()->route('freelancer.index')->with('success', 'Login Successfully');
            }
        }

        return redirect()->back()->with('error', 'These credentials do not match our records.');
    }
}
