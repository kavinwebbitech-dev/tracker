<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Task;
use App\Models\MyBill;
use App\Models\Project;
use App\Models\TaskDetails;
use App\Models\Notification;
use Illuminate\Console\Command;

class OverDueDailyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'over:due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $from = 'USD'; // Example from currency
        $to = 'INR'; // Example to currency
        $amount = 1;  // Example amount

        $exchangeRate = get_exchange_rate($to);

        $user_details = User::find(1);
        $user_details->rupees_amount = $exchangeRate;
        $user_details->save();

        $task = TaskStaff::where('task_type', 'custom')->where('status', 'inprogress')->get();
        $task1 = TaskStaff::where('task_type', 'custom')->where('status', 'over_due')->get();
        foreach ($task as $key => $value) {

            //calculate to before date
            $form_date = date('Y-m-d', strtotime($value->end_date));
            $my_time = date('Y-m-d');

            if ($form_date < $my_time) {
                $task = TaskStaff::find($value->id);
                $task->status = "over_due";
                if ($task->save()) {
                    $message_box = "Over Due Alert!";
                    $notification1                = new Notification();
                    $notification1->task_id       = $value->task_id;
                    $notification1->receiver_id   = $value->staff_id;
                    $notification1->receiver_type = "staff";
                    $notification1->message       = $message_box;
                    $notification1->status        = 0;
                    $notification1->save();
                }

                \Log::info($value->id.' Alert Send Successfully!');
            }
            
        }
        foreach ($task1 as $key => $value) {

            $message_box = $value->task_no.' Over Due Alert!';
            $notification1                = new Notification();
            $notification1->task_id       = $value->id;
            $notification1->receiver_id   = $value->staff_id;
            $notification1->receiver_type = "staff";
            $notification1->message       = $message_box;
            $notification1->status        = 0;
            $notification1->save();
            
        }

        $task_details1 = MyBill::where('status', 'Active')->get();

        if (count($task_details1) > 0) {
            foreach ($task_details1 as $key => $value2) 
            {
                
                //calculate to before date
                $end_date = $value2->end_date;
                $date1 = \Carbon\Carbon::create($value2->end_date);
                $daysAdd = 3;
                $date2 = $date1->subDays($daysAdd);
                $to_date = $date2->format('d-m-Y');
                

                $to = \Carbon\Carbon::parse(date('d-m-Y'));
                $from = \Carbon\Carbon::parse($value2->end_date);

                $date23 = $to->diffInDays($from);
                
                if ($date23 == 3) 
                {
                    // dd($date23, $value2, $to);
                    $comments5 = $value2->name." is overdue in the next 3 days.";
                    $status5  = "Over Due Alert!";
                    $WhatsAppSend = whatsapp_sent_recur($comments5, $status5);

                    \Log::info($value2->name.' Due Alert 3 day Send Successfully!');
                    
                }
                elseif ($date23 == 2)
                {
                    // dd($date23, $value2);
                    $comments5 = $value2->name." is overdue in the next 2 days.";
                    $status5  = "Over Due Alert!";
                    $WhatsAppSend = whatsapp_sent_recur($comments5, $status5);

                    \Log::info($value2->name.' Due Alert 2 day Send Successfully!');

                }
                elseif ($date23 == 1)
                {
                    // dd($date23, $value2);
                    $comments5 = $value2->name." is overdue in tomorrow.";
                    $status5  = "Over Due Alert!";
                    $WhatsAppSend = whatsapp_sent_recur($comments5, $status5);

                    \Log::info($value2->name.' Due Alert 1 day Send Successfully!');
                }
                else{
                }
                
            }
        }

        $task = Project::where('is_renewal', 1)->get();
        
        foreach ($task as $key => $value12) 
        {

            //calculate to before date
            $end_date = $value12->end_date;
            $date1 = \Carbon\Carbon::create($value12->end_date);
            $daysAdd = 1;
            $date2 = $date1->subDays($daysAdd);
            $to_date = $date2->format('d-m-Y');
            

            $to = \Carbon\Carbon::parse(date('d-m-Y'));
            $from = \Carbon\Carbon::parse($to_date);

            $date23 = $to->diffInDays($from);
            // dd($date23);
            if ($date23 <= 1) {
                
                $start_date = \Carbon\Carbon::create($end_date);
                $days_add = $value12->date_type;
                $date3 = $start_date->addDays($days_add);
                $to_date1 = $date3->format('d-m-Y');

                $date23 = strtotime(date('d-my-Y', strtotime($end_date)));
                $date24 = strtotime($to_date);
                $task_details_check = Task::where('description', $value12->description)->where('end_date', date('Y-m-d', strtotime($to_date1)))->first();
                // dd();
                
                if ($task_details_check) {
                    // \Log::info($value12->task_no);
                    // \Log::info($value12->staff_id);
                    // \Log::info($to_date1);
                    // \Log::info($task_details_check->task_no);
                }
                else
                {
                    // \Log::info($value12->task_no);
                    // \Log::info($to_date1);
                    $task_details               = new Task();
                    $task_details->name         = $value12->name;
                    $task_details->description  = $value12->description;
                    $task_details->assign_by    = $value12->assign_by;
                    $task_details->admin_id     = $value12->admin_id;
                    $task_details->sub_admin_id = $value12->sub_admin_id;
                    $task_details->staff_id     = $value12->staff_id;
                    $task_details->date_type    = $value12->date_type;
                    $task_details->task_type    = $value12->task_type;
                    $task_details->start_date   = date('Y-m-d', strtotime($end_date));
                    $task_details->end_date     = date('Y-m-d', strtotime($to_date1));
                    $task_details->status       = "inprogress";
                    if ($task_details->save()) {
                        
                        \Log::info($whats_data);
                
                    }
                }
                
            }
            else{
            }
        }

        $project_details = Project::where('is_renewal', 1)->latest()->get();
        
        foreach ($project_details as $key => $value13) 
        {
            // dd($value12);
            //calculate to before date
            if ($value13->end_date) {
                $end_date = $value13->end_date;
                $date1 = \Carbon\Carbon::create($value13->end_date);
                $daysAdd = 1;
                $date2 = $date1->subDays($daysAdd);
                $to_date = $date2->format('d-m-Y');
                

                $to = \Carbon\Carbon::parse(date('d-m-Y'));
                $from = \Carbon\Carbon::parse($to_date);

                $date23 = $to->diffInDays($from);
                // dd($date23);
                if ($date23 <= 1) {
                    
                    $start_date = \Carbon\Carbon::create($end_date);
                    $days_add = $value13->renewal_days;
                    $date3 = $start_date->addDays($days_add);
                    $to_date1 = $date3->format('d-m-Y');

                    $date23 = strtotime(date('d-my-Y', strtotime($end_date)));
                    $date24 = strtotime($to_date);
                    $task_details_check = Project::where('description', $value13->description)->where('stop_project', 0)->where('end_date', date('Y-m-d', strtotime($to_date1)))->first();
                    // dd($value12);
                    
                    if ($task_details_check) {
                        // \Log::info($value12->task_no);
                        // \Log::info($value12->staff_id);
                        // \Log::info($to_date1);
                        // \Log::info($task_details_check->task_no);
                    }
                    else
                    {
                        // \Log::info($value12->task_no);
                        // \Log::info($to_date1);
                        $user                   = new Project();
                        $user->customer_id      = $value13->customer_id;
                        $user->name             = $value13->name;
                        $user->added_by         = $value13->added_by;
                        $user->sales_user_date  = $value13->sales_user_date;
                        $user->start_date       = $value13->end_date;
                        $user->bid_amount       = $value13->bid_amount;
                        $user->total_days       = $value13->total_days;
                        $user->status           = 0;
                        $user->description      = $value13->description;
                        $user->services_id      = $value13->services_id;

                        $user->is_renewal    = "1";
                        $date1 = \Carbon\Carbon::create(date('Y-m-d', strtotime($value13->end_date)));
                        $daysAdd = $days_add;
                        $date2 = $date1->addDays($daysAdd);
                        $to_date = $date2->format('Y-m-d');
                        $user->end_date         = $to_date;
                        $user->renewal_days     = $days_add;
                        $user->main_project_id  = $value13->id;
                        // dd($user);
                        if ($user->save()) {
                            
                        }
                    }
                    
                }
                else{
                }
            }
            
        }
        
        $my_bill_details = MyBill::where('status', 'Active')->latest()->get();
        
        foreach ($my_bill_details as $key => $my_bill) 
        {
            // dd($value12);
            //calculate to before date
            if ($my_bill->end_date) {
                $end_date = $my_bill->end_date;
                $date1 = \Carbon\Carbon::create($my_bill->end_date);
                $daysAdd = 1;
                $date2 = $date1->subDays($daysAdd);
                $to_date = $date2->format('d-m-Y');
                
                // dd($to_date);
                $to = \Carbon\Carbon::parse(date('d-m-Y'));
                $from = \Carbon\Carbon::parse($to_date);

                $date23 = $to->diffInDays($from);
                // dd($date23);
                if ($date23 <= 1) {
                    // dd($my_bill);
                    $start_date = \Carbon\Carbon::create($end_date);
                    $days_add = $my_bill->recurring_type;
                    $date3 = $start_date->addDays($days_add);
                    $to_date1 = $date3->format('d-m-Y');

                    $date23 = strtotime(date('d-my-Y', strtotime($end_date)));
                    $date24 = strtotime($to_date);
                    $task_details_check = MyBill::where('name', $my_bill->name)->where('bill_amount', $my_bill->bill_amount)->where('end_date', date('Y-m-d', strtotime($to_date1)))->first();
                    // dd($task_details_check);
                    
                    if ($task_details_check) {
                        // \Log::info($value12->task_no);
                        // \Log::info($value12->staff_id);
                        // \Log::info($to_date1);
                        // \Log::info($task_details_check->task_no);
                    }
                    else
                    {
                        
                        $pages                  = new MyBill();
                        $pages->name            = $my_bill->name;
                        $pages->bill_date       = $my_bill->bill_date;
                        $pages->start_date      = $my_bill->end_date;
                        $pages->end_date        = date('Y-m-d', strtotime($to_date1));
                        $pages->recurring_type  = $my_bill->recurring_type;
                        $pages->status          = $my_bill->status;
                        $pages->bill_amount     = $my_bill->bill_amount;
                        $pages->save();
                        
                    }
                    
                }
                else{
                }
            }
            
        }
        
    }
}
