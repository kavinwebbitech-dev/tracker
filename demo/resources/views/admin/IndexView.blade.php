
<table id="example1" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Project Name</th>
            <th>Staff Name</th>
            <th>Task Name</th>
            <th>Working Hours</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $total_time_cal = 0;
            $project_amount = 0;
            $total_time_cal2 = 0;
        ?>
        @if(count($project_count)>0)
        @foreach($project_count as $key => $papup)
        
        <tr>
            <td>{{ $papup->task_details->project_details->name ?? '' }}</td>
            <td>{{ $papup->staff_name ?? '' }}</td>
            <td>{{ $papup->task_details->name }}</td>
            <td style="text-align: right;">
                <?php
                $task_comments = \App\Models\TaskComment::where('task_id', $papup->task_details->id)->where('staff_id', $papup->staff_id)->get();
                $add_hours = 0;
                $sum = strtotime('00:00:00');
                $totaltime1 = 0;

                foreach ($task_comments as $key2 => $value1) {
                    $working_time_h = number_format($value1->working_hours, 2);
                    $timeinsec = strtotime($working_time_h) - $sum;
                    $totaltime1 += $timeinsec;
                    
                }

                $total_time_cal2 += $totaltime1;

                $h2 = intval($totaltime1 / 3600);

                $totaltime1 = $totaltime1 - ($h2 * 3600);
                 
                // Minutes is obtained by dividing
                // remaining total time with 60
                $m2 = intval($totaltime1 / 60);
                 
                // Remaining value is seconds
                $s = $totaltime1 - ($m2 * 60);

                $total_time1 = $h2.':'.$m2;

                $salary = $papup->staff_details->salary;
                $hour_rate = $salary / 200;
                
                if (isset($m2) && $m2) {
                    $calculate = $h2 + ($m2 / 60);
                }
                else
                {
                    $calculate = $h2;
                }
                
                $added_rate = round($calculate, 2);
                $total_amount = $added_rate * $hour_rate;
                $project_amount += $total_amount;
                
            ?>
            {{ $total_time1 }}
            </td>
            <td style="text-align: right;">
                {{ $total_amount }}
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th style="text-align: right;">
                <?php
                    $h4 = intval($total_time_cal2 / 3600);

                    $total_time_cal2 = $total_time_cal2 - ($h4 * 3600);
                     
                    // Minutes is obtained by dividing
                    // remaining total time with 60
                    $m4 = intval($total_time_cal2 / 60);
                     
                    // Remaining value is seconds
                    $s1 = $total_time_cal2 - ($m4 * 60);

                    $total_time2 = $h4.'H:'.$m4.'M';
                ?>
                {{ $total_time2 }}
            </th>
            <th style="text-align: right;">
                {{ $project_amount }}
            </th>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right;">
                Total Staff Amount
            </td>
            <td style="text-align: right;">
                {{ $project_amount }}
            </td>
        </tr>
        
    </tfoot>
</table>
