@extends('layouts.staff')

@section('content')
<div class="content-wrapper">
      <div class="container-full">
        <!-- Main content -->
        <?php
            
            $admin_review   = \App\Models\TaskStaff::where('staff_id', Auth::user()->id)->where('status', 'user_completed')->get();
            $pending        = \App\Models\TaskStaff::where('staff_id', Auth::user()->id)->where('status', 'pending')->get();
            $progress       = \App\Models\TaskStaff::where('staff_id', Auth::user()->id)->where('status', 'inprogress')->get();
            $completed      = \App\Models\TaskStaff::where('staff_id', Auth::user()->id)->where('status', 'completed')->get();
            $rejected       = \App\Models\TaskStaff::where('staff_id', Auth::user()->id)->where('status', 'rejected')->get();
            $recurring       ="0";
            $over_due        = \App\Models\TaskStaff::where('staff_id', Auth::user()->id)->where('status', 'over_due')->get();
            $total_task      = \App\Models\TaskStaff::where('staff_id', Auth::user()->id)->get();

            $admin_review_rer   = 0;
            $pending_rer        = 0;
            $progress_rer       = 0;
            $completed_rer      = 0;
            $rejected_rer       = 0;
            $recurring_rer      = 0;
            $over_due_rer       = 0;
            $total_task_rer     = 0;
            $total_recurring     = \App\Models\Task::where('staff_id', Auth::user()->id)->where('task_type', 'recurring')->get();

            $event_details      = \App\Models\EventDetail::where('status', 'Active')->get();

            // dd($event_details);
        ?>
        <style>
            .box_color1
            {
                background-color: #9b7693;
            }
            .box_color2
            {
                background-color: #68928c;
            }
            .box_color3
            {
                background-color: #5fbc87;
            }
            .box_color4
            {
                background-color: #e76b48;
            }
            .box_color5
            {
                background-color: #007da5;
            }
            .box_color6
            {
                background-color: #ff7096;
            }
        </style>
        <section class="content">

            <div class="row">

                <div class="col-md-8">
                    
                    <div class="row">
                        <div class="col-lg-4 col-12">
                            <a href="#" class="box box_color6">
                                <div class="box-body">
                                    <span class="text-white icon-Shield-check fs-40"><span class="path1"></span><span class="path2"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Your Point</div>
                                    <div class="text-white fs-24 fw-800">{{ Auth::user()->points ?? '0' }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-12">
                            <a href="#" class="box box_color2">
                                <div class="box-body">
                                    <span class="text-white mdi mdi-ticket-confirmation fs-30"><span class="path1"></span><span class="path2"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Task</div>
                                    <div class="text-white fs-24 fw-800">{{ count($total_task) }}</div>
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
                            else
                            {
                                $incentive_details1 = \App\Models\Incentive::where('month', $this_month)->where('group_id', Auth::user()->group_id)->get();
                                foreach ($incentive_details1 as $key1 => $value1) {
                                    $total_incentive += $value1->amount;
                                }
                            }
                        ?>
                        
                        @if(Auth::user()->role == "Telecaller")
                        <div class="col-lg-4 col-12">
                            <a href="{{ route('staff.incentive') }}" class="box box_color4">
                                <div class="box-body">
                                    <span class="text-white icon-Equalizer fs-40"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path3"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Incentive Amount</div>
                                    <div class="text-white  fs-24 fw-800">{{ number_format($total_incentive, 2) }}</div>
                                </div>
                            </a>
                        </div>
                        @endif

                    </div>

                    <hr>

                    <div class="row">
                        
                        <div class="col-lg-4 col-12">
                            <a href="{{ route('staff.progress') }}" class="box box_color5">
                                <div class="box-body">
                                    <span class="text-white icon-Smile fs-40"><span class="path1"></span><span class="path2"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Progress Task</div>
                                    <div class="text-white  fs-24 fw-800">{{ count($progress) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-12">
                            <a href="{{ route('staff.over_due') }}" class="box bg-danger bg-hover-danger">
                                <div class="box-body">
                                    <span class="text-white mdi mdi-ticket-confirmation fs-30"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Over Due Task</div>
                                    <div class="text-white  fs-24 fw-800">{{ count($over_due) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-12">
                            <a href="{{ route('staff.completed') }}" class="box bg-info bg-hover-info">
                                <div class="box-body">
                                    <span class="text-white icon-Chart-line fs-40"><span class="path1"></span><span class="path2"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Admin Review</div>
                                    <div class="text-white  fs-24 fw-800">{{ count($admin_review) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-12">
                            <a href="{{ route('staff.closed') }}" class="box bg-warning bg-hover-warning">
                                <div class="box-body">
                                    <span class="text-white icon-Money fs-40"><span class="path1"></span><span class="path2"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Closed Task</div>
                                    <div class="text-white fs-24 fw-800">{{ count($completed) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-12">
                            <a href="{{ route('staff.rejected') }}" class="box box_color4">
                                <div class="box-body">
                                    <span class="text-white icon-Attachment1 fs-40"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Rejected Task</div>
                                    <div class="text-white fs-24 fw-800">{{ count($rejected) }}</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-12">
                            <a href="{{ route('staff.recurring') }}" class="bg-hover-danger box box_color1">
                                <div class="box-body">
                                    <span class="text-white mdi mdi-ticket-confirmation fs-30"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></span>
                                    <div class="text-white fw-600 fs-18 mb-2">Total Recurring Task</div>
                                    <div class="text-white fs-24 fw-800">{{ count($total_recurring) }}</div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>

                <?php
                    $today_order_amount = 0;
                    $today_order_amount1 = 0;
                    $month_order_amount = 0;
                    $month_order_amount1 = 0;
                    $today_receive_amount = 0;
                    $today_cancel_amount = 0;
                    $month_receive_amount = 0;
                    $month_cancel_amount = 0;
                    $today_date = date('Y-m-d');
                    // $today_date = "2025-01-06";
                    $this_month = now()->month;
                    
                    // dd($month_project);
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

                ?>
                <style type="text/css">
                    .img-align
                    {
                        width: 100%;
                        height: 137px;
                    }
                    .img-align img
                    {
                        height: 100%;
                        object-fit: contain;
                        width: 100%;
                        border-radius: 10px;
                    }
                </style>

                <div class="col-lg-4 col-12">
                    
                    @if($month_receive_amount)
                    @if(Auth::user()->role == "Telecaller")

                    <div class="row mb-10">
                        <div class="col-md-5">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" id="start_date" value="{{ $start_date ?? '' }}" onfocus="'showPicker' in this && this.showPicker()" value="">
                                </div>
                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" id="end_date" value="{{ $end_date ?? '' }}" onfocus="'showPicker' in this && this.showPicker()" value="">
                                </div>
                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <span class="btn btn-primary" onclick="sort_book()">Search</span>
                        </div>
                    </div>


                    <a class="box box_color1">
                        <div class="box-body">
                            <div class="row">
                                <div class="text-white fw-600 fs-14 col-md-6 col-6">Today Order Value ({{ date('d-m-Y') }})</div>
                                <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                    {{ number_format($today_order_amount, 2) }}
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
                            </div>
                        </div>
                    </a>
                    @endif
                    @endif

                    @if(count($event_details) > 0)
                    @foreach($event_details as $key => $event)

                    @if($event->user_type == Auth::user()->role)
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
                    @endif
                    @if($event->user_type == "all_staff")
                    @if($today_date >= $event->start_date && $today_date <= $event->end_date)
                    <div class="row" style="margin: 0px;">
                        
                        <div class="col-lg-12 box" style="padding: 10px;border: 1px solid;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="img-align">
                                        <img src="{{ url('/') }}/public/event_image/{{ $event->event_image }}">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h4 style="color: #000;">{{  $event->event_name }}</h4>
                                    <p style="color: #000;">{{  $event->description }}</p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    @endif
                    @endif
                    @endforeach
                    @endif

                </div>
                
            </div>
            <hr>
            <div class="row">
                
                
            </div>

            
        </section>
        <!-- /.content -->
      </div>
</div>

<?php
    $current_route = Route::currentRouteName();
?>

<script type="text/javascript">
    function sort_book() {

        var expert_search_url   = "{{ route($current_route) }}";
        var start_date          = $("#start_date").val();
        var end_date            = $("#end_date").val();

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


        var str_search_request   = "&start_date="+start_date+"&end_date="+end_date;

        if(str_search_request){
            window.location.href = expert_search_url + '?' + str_search_request;
        }
    }
</script>
@endsection
