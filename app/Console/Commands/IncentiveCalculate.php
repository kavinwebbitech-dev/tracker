<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\User;
use App\Models\Incentive;
use App\Models\IncentiveAmount;
use Illuminate\Console\Command;

class IncentiveCalculate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'incentive:calculate';

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
        

        $this_month = now()->month;
        $today_receive_amount = 0;

        $user_details = User::where('user_type', 'staff')->whereNull('group_id')->where('status', 'active')->get();
        // $incentive_amount = IncentiveAmount::get();
        if ($user_details) 
        {

            foreach ($user_details as $key => $value) {

                $incentive_amount = IncentiveAmount::where('user_id', $value->id)->get();
                if($incentive_amount)
                {
                    $incentive_amount = $incentive_amount;
                }
                else
                {
                    $incentive_amount = IncentiveAmount::get();
                }
                
                $today_project = \App\Models\Project::where('added_by', $value->id)->whereNot('status', 6)->whereMonth('created_at', $this_month)->get();
                
                if (count($today_project)) {
                    $today_receive_amount = 0;
                    foreach ($today_project as $key1 => $value1) {
                        $today_receive_amount += $value1->bid_amount;
                    }
                    
                    if ($incentive_amount) {
                        foreach ($incentive_amount as $key1 => $value1) {
                            if ($today_receive_amount >= $value1->start_amount && $today_receive_amount <= $value1->end_amount) 
                            {
                                $incenive_check = Incentive::where('user_id',$value->id)->where('month', $this_month)->where('year', now()->year)->first();
                                if ($incenive_check) {
                                    $incentive_add          = Incentive::find($incenive_check->id);
                                }
                                else
                                {
                                    $incentive_add          = new Incentive();
                                }
                                $incentive_add->user_id     = $value->id;
                                $incentive_add->month       = now()->month;
                                $incentive_add->year        = now()->year;
                                $incentive_add->amount      = $value1->amount;
                                $incentive_add->sale_amount = $today_receive_amount;
                                if ($value1->amount == 0) {
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
            

        }


        
    }
}
