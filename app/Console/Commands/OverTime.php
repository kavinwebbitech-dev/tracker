<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\Project;
use App\Models\TaskDetails;
use Illuminate\Console\Command;

class OverTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new_recurring:task';

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

        
        $Project = Project::where('is_renewal', 1)->latest()->get();
        foreach ($Project as $key => $value) {

            //calculate to before date
            $end_date = $value->end_date;
            $date1 = \Carbon\Carbon::create(date('d-m-Y', strtotime($value->end_date)));
            $daysAdd = 1;
            $date2 = $date1->subDays($daysAdd);
            $to_date = $date2->format('d-m-Y');

            $to = \Carbon\Carbon::parse(date('d-m-Y'));
            $from = \Carbon\Carbon::parse($to_date);

            $date23 = $to->diffInDays($from);
            // dd($date23);
            if ($date23 == 1) {
                // dd("hi1");
                $start_date = \Carbon\Carbon::create($end_date);
                $days_add   = $value->renewal_days;
                $date3      = $start_date->addDays($days_add);
                $to_date1   = $date3->format('d-m-Y');

                $date23     = strtotime(date('d-my-Y', strtotime($end_date)));
                $date24     = strtotime($to_date);
                $task_details_check = Project::where('name', $value->name)->where('end_date', $to_date1)->first();
                
                if ($task_details_check) {
                    // code...
                }
                else
                {
                    // dd("hi");
                    $user                   = new Project();
                    $user->name             = $value->name;
                    $user->sales_user_id    = $value->sales_user_id;
                    $user->sales_user_date  = $value->sales_user_date;
                    $user->start_date       = $value->end_date;
                    $user->bid_amount       = $value->bid_amount;
                    $user->total_days       = $value->total_days;
                    $user->status           = $value->status;
                    $user->description      = $value->description;
                    if ($value->is_renewal == 1) {
                        $user->is_renewal    = "1";
                        $date1 = \Carbon\Carbon::create($value->start_date);
                        $daysAdd = $value->renewal_days;
                        $date2 = $date1->subDays($daysAdd);
                        $to_date = $date2->format('Y-m-d');
                        $user->end_date         = $to_date;
                        $user->renewal_days     = $value->renewal_days;
                    }
                    // $user->save();
                    \Log::info("Project Create Successfully. Project id - ".$user->id);
                    
                }
                
            }
            else{
            }
        }
        
        // \Log::info($task);
        // return Command::SUCCESS;
    }
}
