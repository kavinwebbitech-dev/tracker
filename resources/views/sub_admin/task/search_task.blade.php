<?php
    $role_section = json_decode(Auth::user()->permissions);
    if ($role_section) {
        $role_section = $role_section;
    }
    else
    {
        $role_section = [];
    }
?>
<thead>
    <tr>
        <th>No</th>
        <th>Date</th>
        <th>Task Id</th>
        <th>Project</th>
        <th>Assign By</th>
        <th>Staff Name</th>
        <th>Task Name</th>
        <th>Task Type</th>
        <th>Description</th>
        <th>Status</th>
        <th>Points</th>
        <th>Total Hours</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php
        $total_time_cal = 0;
        // dd($sub_admin);
    ?>
    @if($sub_admin)
    @foreach($sub_admin as $key => $value)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
        <td>
            {{ $value->task_details->task_no }}
        </td>
        <td>{{ $value->task_details->project_details->name ?? '' }}</td>
        <td>{{ $value->task_details->assign_details->name ?? '' }}</td>
        <td>
            {{ $value->staff_details->name ?? '' }}
            
        </td>
        <td>{{ $value->task_details->name }}</td>
        <td>
            <?php
                if ($value->task_details->date_type == "7") {
                    $requ_date = "Weekly";
                }
                elseif ($value->task_details->date_type == "15") {
                    $requ_date = "15 Days";
                }
                elseif ($value->task_details->date_type == "30") {
                    $requ_date = "Monthly";
                }
                elseif ($value->task_details->date_type == "90") {
                    $requ_date = "Quarterly";
                }
                elseif ($value->task_details->date_type == "365") {
                    $requ_date = "Yearly";
                }
                
            ?>
            @if($value->task_details->task_type == "custom")
                Single
            @elseif($value->task_details->task_type == "recurring")
                Recurring @if($requ_date)<span style="font-size: 10px;">({{ $requ_date }})</span>@endif
            @endif
        </td>
        <td>
            {!! Str::limit($value->task_details->description, 150, ' ...') !!}
        </td>
        <td>
            @if($value->status == "pending")
                <span style="color: red;">Pending</span>
            @elseif($value->status == "payment_process")
                <span style="color: #f70fdd;">Payment Progress</span>
            @elseif($value->status == "request_to_coordinar_completed")
                <span style="color: #006c19;">Project Coordinar To be Apprd</span>
            @elseif($value->status == "inprogress")
                <span style="color: #f7a20f;">In Progress</span>
            @elseif($value->status == "user_completed")
                <span style="color: #14bcfc;">Completed To be Apprd</span>
            @elseif($value->status == "over_due")
                <span style="color: red;">Over Due</span>
            @elseif($value->status == "recommend_to_admin")
                <span style="color: #cf2bc4;">Recomment to Completed</span>
            @elseif($value->status == "completed")
                <span style="color: #2de033;">Closed</span>
            @elseif($value->status == "canceled")
                <span style="color: #f21f35;">Canceled</span>
            @elseif($value->status == "rejected")
                <span style="color: #1ff2e8;">Rejected</span>
            @elseif($value->status == "hold")
                <span style="color: red;">Hold</span>
            @elseif($value->status == "reopen")
                <span style="color: red;">Reopen</span>
            @endif
        </td>
        <td>{{ $value->points ?? '0' }}</td>
        <td>
            <?php
                if($start_date)
                {
                    $task_comments = \App\Models\TaskComment::where('task_id', $value->task_details->id)->where('staff_id', $value->staff_id)->where('start_date', 'like', '%'.$start_date.'%')->get();
                }
                else
                {
                    $task_comments = \App\Models\TaskComment::where('task_id', $value->task_details->id)->where('staff_id', $value->staff_id)->get();
                }
                $add_hours = 0;
                $sum = strtotime('00:00:00');
                $totaltime = 0;

                foreach ($task_comments as $key2 => $value1) {
                    $working_time_h = number_format($value1->working_hours, 2);
                    $timeinsec = strtotime($working_time_h) - $sum;
                    $totaltime += $timeinsec;
                    
                }

                $total_time_cal += $totaltime;

                $h = intval($totaltime / 3600);

                $totaltime = $totaltime - ($h * 3600);
                 
                // Minutes is obtained by dividing
                // remaining total time with 60
                $m = intval($totaltime / 60);
                 
                // Remaining value is seconds
                $s = $totaltime - ($m * 60);

                $total_time = $h.':'.$m;
                
            ?>
            {{ $total_time }}
        </td>
        <td>
            <p style="display:flex;">
                @if(in_array('59', $role_section))
                <a href="{{ route('sub.task.status', $value->task_details->id) }}" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-eye text-white"></i></a>
                @endif
                @if($value->status != "completed")
                @if(in_array('57', $role_section))
                <a href="{{ route('sub.task.edit', $value->task_details->id) }}" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                @endif
                @if(in_array('58', $role_section))
                <a href="javascript:void(0);"onclick="deleteProject('{{ route('sub.task.single.delete', $value->id) }}')" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash"></i></a>
                @endif
                @endif
            </p>
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
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th>
            <?php
                $h1 = intval($total_time_cal / 3600);

                $total_time_cal = $total_time_cal - ($h1 * 3600);
                 
                // Minutes is obtained by dividing
                // remaining total time with 60
                $m1 = intval($total_time_cal / 60);
                 
                // Remaining value is seconds
                $s1 = $total_time_cal - ($m1 * 60);

                $total_time1 = $h1.'H:'.$m1.'M';
            ?>
            {{ $total_time1 }}
        </th>
        <th></th>
    </tr>
</tfoot>