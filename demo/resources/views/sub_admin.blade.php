@extends('layouts.sub_admin')

@section('content')
<div class="content-wrapper">
      <div class="container-full">
        <!-- Main content -->
        <?php
            
            $total_staff     = \App\Models\User::where('sub_admin_id', Auth::user()->id)->where('user_type', 'staff')->get();
            $total_recurring     = \App\Models\Task::where('sub_admin_id', Auth::user()->id)->where('task_type', 'recurring')->get();
            // $total_task      = \App\Models\TaskStaff::where('sub_admin_id', Auth::user()->id)->get();
            // $pending         = \App\Models\TaskStaff::where('sub_admin_id', Auth::user()->id)->where('status', 'pending')->get();
            $project_id = Auth::user()->id;

            $total_task = \App\Models\TaskStaff::with(['task_details'])->whereHas('task_details', function ($query) use ($project_id){
                    $query->where('sub_admin_id', '=', $project_id);
                })->get();

            $pending = \App\Models\TaskStaff::with(['task_details'])->whereHas('task_details', function ($query) use ($project_id){
                    $query->where('sub_admin_id', '=', $project_id);
                })->where('status', 'pending')->get();

            $staff_com = \App\Models\TaskStaff::with(['task_details'])->whereHas('task_details', function ($query) use ($project_id){
                    $query->where('sub_admin_id', '=', $project_id);
                })->where('status', 'user_completed')->get();


            $progress = \App\Models\TaskStaff::with(['task_details'])->whereHas('task_details', function ($query) use ($project_id){
                    $query->where('sub_admin_id', '=', $project_id);
                })->where('status', 'inprogress')->get();

            $completed = \App\Models\TaskStaff::with(['task_details'])->whereHas('task_details', function ($query) use ($project_id){
                    $query->where('sub_admin_id', '=', $project_id);
                })->where('status', 'completed')->get();

            $rejected = \App\Models\TaskStaff::with(['task_details'])->whereHas('task_details', function ($query) use ($project_id){
                    $query->where('sub_admin_id', '=', $project_id);
                })->where('status', 'rejected')->get();

            $over_due = \App\Models\TaskStaff::with(['task_details'])->whereHas('task_details', function ($query) use ($project_id){
                    $query->where('sub_admin_id', '=', $project_id);
                })->where('status', 'over_due')->get();

            $recomand = \App\Models\TaskStaff::with(['task_details'])->whereHas('task_details', function ($query) use ($project_id){
                    $query->where('sub_admin_id', '=', $project_id);
                })->where('status', 'recommend_to_admin')->get();

            $user_list = \App\Models\Project::groupBy('added_by')
              ->get(['added_by']);

            $salesperson = $user_list;
        ?>

        <style>
            .box_color1
            {
                background-color: #9b7693;
                color: #fff;
                text-decoration: blink;
            }
            .box_color2
            {
                background-color: #68928c;
                color: #fff;
                text-decoration: blink;
            }
            .box_color3
            {
                background-color: #5fbc87;
                color: #fff;
                text-decoration: blink;
            }
            .box_color4
            {
                background-color: #e76b48;
                color: #fff;
                text-decoration: blink;
            }
            .box_color5
            {
                background-color: #007da5;
                color: #fff;
                text-decoration: blink;
            }
            .box_color6
            {
                background-color: #ff7096;
                color: #fff;
                text-decoration: blink;
            }
        </style>
        <section class="content">
            <div class="row">

                <div class="col-md-8">
                    <div class="row">
                        <div class="col-lg-3 col-12">
                            <a href="{{ route('staff.view') }}" class="box box_color1">
                                <div class="box-body">
                                    <span class="text-white icon-User fs-40"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path3"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Staff</div>
                                    <div class="text-white fs-24 fw-800">{{ count($total_staff) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-12">
                            <a href="{{ route('sub.task.view') }}" class="box box_color2">
                                <div class="box-body">
                                    <span class="text-white mdi mdi-ticket-confirmation fs-30"><span class="path1"></span><span class="path2"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Task</div>
                                    <div class="text-white fs-24 fw-800">{{ count($total_task) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-12">
                            <a href="{{ route('sub.task.recurring.view') }}" class="bg-hover-danger box box_color1">
                                <div class="box-body">
                                    <span class="text-white mdi mdi-ticket-confirmation fs-30"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Recurring Task</div>
                                    <div class="text-white fs-24 fw-800">{{ count($total_recurring) }}</div>
                                </div>
                            </a>
                        </div>
                        <?php
                            $total_incentive = 0;
                            $this_month = now()->month;
                            $incentive_details = \App\Models\Incentive::where('month', $this_month)->where('user_id', Auth::user()->id)->get();
                            if (count($incentive_details) > 0) {
                                foreach ($incentive_details as $key => $value) {
                                    $total_incentive += $value->amount;
                                }
                            }
                        ?>
                        @if($total_incentive)
                        <div class="col-lg-3 col-12">
                            <a href="{{ route('staff.incentive') }}" class="box box_color4">
                                <div class="box-body">
                                    <span class="text-white icon-Equalizer fs-40"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path3"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Incentive Amount</div>
                                    <div class="text-white  fs-24 fw-800">{{ number_format($total_incentive, 2) }}</div>
                                </div>
                            </a>
                        </div>
                        @endif

                        <div class="col-lg-3 col-12">
                            <a href="{{ url('/') }}/sub_admin/task/view-task?&task_status=completed" class="box box_color4">
                                <div class="box-body">
                                    <span class="text-white icon-Smile fs-40"><span class="path1"></span><span class="path2"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Progress Task</div>
                                    <div class="text-white fs-24 fw-800">{{ count($progress) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-12">
                            <a href="{{ url('/') }}/sub_admin/task/view-task?&task_status=over_due" class="box bg-danger bg-hover-danger">
                                <div class="box-body">
                                    <i class="icon-File" style="font-size: 40px;color: #fff;"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path3"></span></i>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Over Due Task</div>
                                    <div class="text-white fs-24 fw-800">{{ count($over_due) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-12">
                            <a href="{{ url('/') }}/sub_admin/task/view-task?&task_status=user_completed" class="box bg-info bg-hover-info">
                                <div class="box-body">
                                    <span class="text-white icon-Chart-line fs-40"><span class="path1"></span><span class="path2"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Staff Completed</div>
                                    <div class="text-white fs-24 fw-800">{{ count($staff_com) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-12">
                            <a href="{{ url('/') }}/sub_admin/task/view-task?&task_status=recommend_to_admin" class="box box_color2">
                                <div class="box-body">
                                    <span class="text-white icon-User fs-40"><span class="path1"></span><span class="path2"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Recommend Task</div>
                                    <div class="text-white fs-24 fw-800">{{ count($recomand) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-12">
                            <a href="{{ url('/') }}/sub_admin/task/view-task?&task_status=completed" class="box bg-warning bg-hover-warning">
                                <div class="box-body">
                                    <span class="text-white icon-Money fs-40"><span class="path1"></span><span class="path2"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Closed Task</div>
                                    <div class="text-white fs-24 fw-800">{{ count($completed) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-12">
                            <a href="{{ url('/') }}/sub_admin/task/view-task?&task_status=rejected" class="box box_color5">
                                <div class="box-body">
                                    <span class="text-white icon-Attachment1 fs-40"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Rejected Task</div>
                                    <div class="text-white fs-24 fw-800">{{ count($rejected) }}</div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <?php
                        $today_order_amount = 0;
                        $today_order_amount1 = 0;
                        $month_order_amount = 0;
                        $month_order_amount1 = 0;
                        $today_receive_amount = 0;
                        $today_cancel_amount = 0;
                        $month_receive_amount = 0;
                        $month_cancel_amount = 0;

                        if ($today_project) {
                            foreach ($today_project as $key => $value) {
                                $today_order_amount += $value->bid_amount;
                                $today_bid = \App\Models\ProjectBitAmount::where('fld_project_id', $value->id)->get();
                                if ($today_bid) {
                                    foreach ($today_bid as $key3 => $value3) {
                                        $today_receive_amount += $value3->fld_project_amount;
                                    }
                                }
                            }
                        }
                        if ($today_cancel_project) {
                            foreach ($today_cancel_project as $key => $value) {
                                $today_order_amount1 += $value->bid_amount;
                                $today_bid = \App\Models\ProjectBitAmount::where('fld_project_id', $value->id)->get();
                                if ($today_bid) {
                                    foreach ($today_bid as $key3 => $value3) {
                                        $today_cancel_amount += $value3->fld_project_amount;
                                    }
                                }
                            }
                        }

                        if ($month_project) {
                            foreach ($month_project as $key1 => $value1) {
                                // dump($value1);
                                $month_order_amount += $value1->bid_amount;
                                $month_bid = \App\Models\ProjectBitAmount::where('fld_project_id', $value1->id)->get();
                                if ($month_bid) {
                                    foreach ($month_bid as $key4 => $value4) {
                                        $month_receive_amount += $value4->fld_project_amount;
                                    }
                                }
                            }
                        }
                        

                        if ($month_cancel_project) {
                            foreach ($month_cancel_project as $key1 => $value1) {
                                $month_order_amount1 += $value1->bid_amount;
                                $month_bid = \App\Models\ProjectBitAmount::where('fld_project_id', $value1->id)->get();
                                if ($month_bid) {
                                    foreach ($month_bid as $key4 => $value4) {
                                        $month_cancel_amount += $value4->fld_project_amount;
                                    }
                                }
                            }
                        }
                        // dump($month_project);
                    ?>
                    <div class="row mb-10">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group in-bord mb-3">
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" id="start_date" value="{{ $start_date ?? '' }}" onfocus="'showPicker' in this && this.showPicker()" value="">
                                </div>
                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group in-bord mb-3">
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" id="end_date" value="{{ $end_date ?? '' }}" onfocus="'showPicker' in this && this.showPicker()" value="">
                                </div>
                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control @error('salesperson') is-invalid @enderror" name="salesperson" id="salesperson">
                                    <option value="">Select Sales Person</option>
                                    @if($salesperson)
                                    @foreach($salesperson as $key => $person)
                                    @if($person->added_by)
                                    <?php
                                        $user_details = \App\Models\User::where('id', $person->added_by)->first();
                                        // dd($user_details);
                                    ?>
                                    <option value="{{ $user_details->id }}" @if($salesperson1 == $user_details->id) selected @endif>{{ $user_details->name ?? '' }}</option>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                        </div>
                        <div class="col-md-6">
                            <span class="btn btn-primary" onclick="sort_book()">Search</span>
                        </div>
                    </div>

                    <a class="box" style="background: #2d2d2d;">
                        <div class="box-body">
                            <div class="row">
                                <div class="text-white fw-600 fs-14 col-md-6 col-6">Today Order Value ({{ date('d-m-Y') }})</div>
                                <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                    {{ number_format($today_order_amount, 2) }} <span onclick="OrderAmount('{{ $today_date }}')" style="border: 1px solid #fff;border-radius: 50px;padding: 0px 4px;cursor: pointer;"><i class="ti-eye text-white" style="font-size: 13px;margin-top: -4px;position: relative;top: -1px;"></i></span>
                                </div>
                                <hr>
                                <div class="text-white fw-600 fs-14 col-md-6 col-6">Received Amount</div>
                                <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                    {{ number_format($today_receive_amount, 2) }}
                                </div>
                                <hr>
                                <div class="text-white fw-600 fs-14 col-md-6 col-6">Cancel Amount</div>
                                <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                    {{ number_format($today_cancel_amount, 2) }}
                                </div>
                                <hr>

                                @if($start_date || $salesperson1 || $end_date)
                                @if($start_date)
                                <div class="text-white fw-600 fs-14 col-md-6 col-6">{{ date('d-m-Y', strtotime($start_date))}} to {{ date('d-m-Y', strtotime($end_date))}} Order Value</div>
                                <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                    {{ number_format($month_order_amount, 2) }} 
                                </div>
                                <hr>
                                <div class="text-white fw-600 fs-14 col-md-6 col-6">{{ date('d-m-Y', strtotime($start_date))}} to {{ date('d-m-Y', strtotime($end_date))}} Received Amount</div>
                                <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                    {{ number_format($month_receive_amount, 2) }}
                                </div>
                                <hr>
                                <div class="text-white fw-600 fs-14 col-md-6 col-6">{{ date('d-m-Y', strtotime($start_date))}} to {{ date('d-m-Y', strtotime($end_date))}} Cancel Amount</div>
                                <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                    {{ number_format($month_cancel_amount, 2) }}
                                </div>
                                @else
                                <div class="text-white fw-600 fs-14 col-md-6 col-6">Total Order Value</div>
                                <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                    {{ number_format($month_order_amount, 2) }}
                                </div>
                                <hr>
                                <div class="text-white fw-600 fs-14 col-md-6 col-6">This Month Received Amount</div>
                                <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                    {{ number_format($month_receive_amount, 2) }}
                                </div>
                                <hr>
                                <div class="text-white fw-600 fs-14 col-md-6 col-6">This Month Cancel Amount</div>
                                <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                    {{ number_format($month_cancel_amount, 2) }}
                                </div>
                                @endif
                                @else
                                <div class="text-white fw-600 fs-14 col-md-6 col-6">Total Order Value</div>
                                <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                    {{ number_format($month_order_amount, 2) }} <span onclick="TotalOrderAmount('{{ $this_month }}', '{{$this_year}}')" style="border: 1px solid #fff;border-radius: 50px;padding: 0px 4px;cursor: pointer;"><i class="ti-eye text-white" style="font-size: 13px;margin-top: -4px;position: relative;top: -1px;"></i></span>
                                </div>
                                <hr>
                                <div class="text-white fw-600 fs-14 col-md-6 col-6">This Month Received Amount</div>
                                <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                    {{ number_format($month_receive_amount, 2) }}
                                </div>
                                <hr>
                                <div class="text-white fw-600 fs-14 col-md-6 col-6">This Month Cancel Amount</div>
                                <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                    {{ number_format($month_cancel_amount, 2) }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </a>

                    @if(count($event_details) > 0)
                    @foreach($event_details as $key => $event)
                    @if($today_date >= $event->start_date && $today_date <= $event->end_date)
                    <div class="row" style="margin: 0px;">
                        
                        <div class="col-lg-12 box bg-warning bg-hover-warning" style="padding: 10px;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="img-align">
                                        <img src="{{ url('/') }}/public/event_image/{{ $event->event_image }}">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h4>{{  $event->event_name }}</h4>
                                    <p>{{  $event->description }}</p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @endif
                </div>
                
            </div>
            
            
        </section>
        <!-- /.content -->
      </div>
</div>

<?php
    $current_route = Route::currentRouteName();
?>

<div class="modal fade" id="modal-default">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Today Order Details</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html">

          </div>
      </div>
  </div>
</div>
<script type="text/javascript">
    function OrderAmount(ref) {
        
        var ele = ref;
        // alert(ele);
        $.ajax({
            url: '{{ route('sub_admin.project.details') }}',
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}', 
                product_id: ele
            },
            success: function (response) {
                // console.log(response);
                $("#load_html").html(response.html);
                $('#modal-default').modal('show');
            }
        });

    }

    function TotalOrderAmount(month, year)
    {
        // alert(month, year);
        var month = month;
        var year = year;
        
        var expert_search_url   = "{{ url('/') }}/sub_admin/this-month-projects";
        var str_search_request   = expert_search_url + '/' + month + '/' + year;
        
        if(str_search_request){
            window.location.href = str_search_request;
        }

    }

    function sort_book() {

        var expert_search_url   = "{{ route($current_route) }}";
        var start_date          = $("#start_date").val();
        var end_date            = $("#end_date").val();
        var salesperson         = $("#salesperson").val();

        if (start_date) {
            start_date = start_date;
        }
        else
        {
            start_date = "";
        }
        if (end_date) 
        {
            end_date = end_date;
            
        }
        else
        {
            end_date = "";
        }

        if (salesperson) 
        {
            salesperson = salesperson;
            
        }
        else
        {
            salesperson = "";
        }

        var str_search_request   = "&start_date="+start_date+"&end_date="+end_date+"&salesperson="+salesperson;

        if(str_search_request){
            window.location.href = expert_search_url + '?' + str_search_request;
        }
    }
</script>
@endsection
