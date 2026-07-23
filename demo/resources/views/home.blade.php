@extends('layouts.dashboard')

@section('content')

<!-- style.css
color_theme.css
tracker-header.css -->

<div class="content-wrapper">
      <div class="container-full">
        <!-- Main content -->
        
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
            .state-overview .card {
                display: block;
                margin-bottom: 4px;
                border: none;
                background: none;
            }
            
            .state-overview .symbol, .state-overview .value {
                display: inline-block;
                text-align: center;
            }
            
            .state-overview .value  {
                float: right;
            
            }
            
            .state-overview .value h1, .state-overview .value p  {
                margin: 0;
                padding: 0;
                color: #c6cad6;
            }
            
            .state-overview .value h1 {
                font-weight: 300;
            }
            
            .state-overview .symbol i {
                color: #fff;
                font-size: 50px;
            }
            
            .state-overview .symbol {
                width: 38%;
                padding: 25px 15px;
                -webkit-border-radius: 4px 0px 0px 4px;
                border-radius: 4px 0px 0px 4px;
            }
            
            .state-overview .value {
                width: 62%;
                padding-top: 20px;
            }
            td
            {
                border: 1px solid #fff;
            }
            td a
            {
                color: #fff;
            }
            td a:hover
            {
                color: #fff;
            }
        </style>
        <section class="content">
            <div class="row">

                <div class="col-md-3">
                    <div class="row">
                        <table border="1" cellpadding="10" cellspacing="0">
                            <tbody>
                                <tr class="box_color1" onclick="window.open('{{ route('sub.admin.view') }}','_blank')" style="cursor:pointer;">
                                    <td class="text-white">Sub Admin</td>
                                    <td class="text-white" style="text-align: right;">{{ $data['total_sub_admin'] }}</td>
                                </tr>
                                <tr class="box_color2" onclick="window.open('{{ route('sub.staff.view') }}','_blank')" style="cursor:pointer;">
                                    <td class="text-white">Total Staff</td>
                                    <td class="text-white" style="text-align: right;">{{ $data['total_staff'] }}</td>
                                </tr>
                                <tr class="box_color3" onclick="window.open('{{ route('task.view') }}','_blank')" style="cursor:pointer;">
                                    <td>Total Task</td>
                                    <td style="text-align: right;">{{ $data['total_task'] }}</td>
                                </tr>
                                <tr class="box_color1" onclick="window.open('{{ route('recurring.task.view') }}','_blank')" style="cursor:pointer;">
                                    <td class="text-white">Recurring Task</td>
                                    <td class="text-white" style="text-align: right;">{{ $data['total_recurring'] }}</td>
                                </tr>
                                <tr class="box_color5" onclick="window.open('{{ url('/') }}/super-admin/task/single-task?&task_status=inprogress','_blank')" style="cursor:pointer;">
                                    <td class="text-white">Progress Task</td>
                                    <td class="text-white" style="text-align: right;">{{ $data['progress'] }}</td>
                                </tr>
                                <tr class="bg-danger" onclick="window.open('{{ url('/') }}/super-admin/task/single-task?&task_status=over_due','_blank')" style="cursor:pointer;">
                                    <td class="text-white">Over Due Task</td>
                                    <td class="text-white" style="text-align: right;">{{ $data['over_due'] }}</td>
                                </tr>
                                <tr class="bg-info" onclick="window.open('{{ url('/') }}/super-admin/task/single-task?&task_status=user_completed','_blank')" style="cursor:pointer;">
                                    <td class="text-white">Staff Completed</td>
                                    <td class="text-white" style="text-align: right;">{{ $data['staff_com'] }}</td>
                                </tr>
                                <tr class="box_color2" onclick="window.open('{{ url('/') }}/super-admin/task/single-task?&task_status=recommend_to_admin','_blank')" style="cursor:pointer;">
                                    <td class="text-white">Recommend Task</td>
                                    <td class="text-white" style="text-align: right;">{{ $data['recomand'] }}</td>
                                </tr>
                                <tr class="box_color2" onclick="window.open('{{ url('/') }}/super-admin/task/single-task?&task_status=completed','_blank')" style="cursor:pointer;">
                                    <td class="text-white">Closed Task</td>
                                    <td class="text-white" style="text-align: right;">{{ $data['completed'] }}</td>
                                </tr>
                                <tr class="box_color4" onclick="window.open('{{ url('/') }}/super-admin/task/single-task?&task_status=rejected','_blank')" style="cursor:pointer;">
                                    <td class="text-white">Rejected Task</td>
                                    <td class="text-white" style="text-align: right;">{{ $data['rejected'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-3" style="height: 400px;overflow-y: auto;">
                    <div class="row mb-2">
                        <select class="form-control select2" name="service_search[]" id="service_search" multiple="multiple" onchange="sort_book()" data-placeholder="Select Service">
                            <!-- <option value="">Select Service</option> -->
                            @if(count($ServiceDetails1)>0)
                            @foreach($ServiceDetails1 as $Service1)
                            <option value="{{ $Service1->id }}" @if(in_array($Service1->id, $service_search)) selected @endif>{{ $Service1->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="row">

                        @if(count($ServiceDetails)>0)
                        @foreach($ServiceDetails as $Service)
                        @if(count($Service->project_details)>0)
                        <div class="col-md-12 col-lg-12">
                            <div class="card employee-card shadow-sm" onclick="EmplayeeDetails('{{$Service->id}}')" style="cursor:pointer;">
                                <div class="card-body">

                                    <h5 class="card-title mb-3" style="font-size: 16px;">{{ $Service->name }}</h5>

                                    @foreach($Service->project_details as $key1 => $project)
                                    @if(count($project->project_task)>0)
                                    <div class="mb-2 text-primary">
                                        <a href="{{ route('admin.projects.view.details', $project->id) }}" 
                                           class="d-flex justify-content-between text-decoration-none text-primary" target="_blank">
                                           
                                            <span>{{ $project->name }}</span>
                                            <strong>{{ count($project->project_task) }}</strong>
                                            
                                        </a>
                                    </div>
                                    @endif
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @endif

                    </div>
                </div>
                <div class="col-md-3" style="height: 400px;overflow-y: auto;">
                    <div class="row">
                        @if(count($project_user)>0)
                        @foreach($project_user as $detail)

                            @if($detail->task_details->count() > 0)

                            @php
                                $tasks = $detail->task_details;

                                $total     = $tasks->count();
                                $pending = $tasks->whereIn('status', [
                                    'pending',
                                    'payment_process',
                                    'request_to_coordinar_completed',
                                    'inprogress',
                                    'user_completed',
                                    'recommend_to_admin'
                                ])->count();
                                $project_count = $tasks->pluck('project_id')->unique()->count();
                                $completed = $tasks->where('status', 'completed')->count();
                                $rejected  = $tasks->where('status', 'over_due')->count();
                                $efficiency = $total 
                                    ? round(($completed / $total) * 100) 
                                    : 0;
                            @endphp

                            <div class="col-md-6 col-lg-6">
                                <div class="card employee-card shadow-sm" onclick="EmplayeeDetails('{{$detail->id}}')" style="cursor:pointer;">
                                    <div class="card-body">

                                        <h5 class="card-title mb-3" style="font-size: 16px;">{{ $detail->name }}</h5>

                                        <div class="d-flex justify-content-between mb-2 text-primary">
                                            <span>Total Project</span>
                                            <strong>{{ $project_count }}</strong>
                                        </div>

                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Tasks</span>
                                            <strong>{{ $total }}</strong>
                                        </div>

                                        <div class="d-flex justify-content-between mb-2 text-warning">
                                            <span>Pending</span>
                                            <strong>{{ $pending }}</strong>
                                        </div>

                                        <div class="d-flex justify-content-between mb-2 text-success">
                                            <span>Completed</span>
                                            <strong>{{ $completed }}</strong>
                                        </div>

                                        <div class="progress" style="margin-bottom: 0px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ $efficiency }}%">
                                                {{ $efficiency }}%
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            @endif

                        @endforeach
                    @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="col-lg-12">
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
                            <div class="col-md-4">
                                <select class="form-control select2 @error('salesperson') is-invalid @enderror" name="salesperson" id="salesperson">
                                        <option value="">Sales Person</option>
                                        @if($salesperson)
                                        @foreach($salesperson as $key => $person)
                                        @if($person->added_by)
                                        <?php
                                            $user_details = \App\Models\User::where('id', $person->added_by)->where('status', 'active')->first();
                                            // dd($user_details);
                                        ?>
                                        @if($user_details)
                                        <option value="{{ $user_details->id }}" @if($salesperson1 == $user_details->id) selected @endif>{{ $user_details->name ?? '' }}</option>
                                        @endif
                                        @endif
                                        @endforeach
                                        @endif
                                    </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control select2 @error('salesperson') is-invalid @enderror" name="salesperson2" id="salesperson2">
                                        <option value="">Ex Sales Person</option>
                                        @if($salesperson)
                                        @foreach($salesperson as $key => $person)
                                        @if($person->added_by)
                                        <?php
                                            $user_details = \App\Models\User::where('id', $person->added_by)->where('status', 'inactive')->first();
                                            // dd($user_details);
                                        ?>
                                        @if($user_details)
                                        <option value="{{ $user_details->id }}" @if($salesperson2 == $user_details->id) selected @endif>{{ $user_details->name ?? '' }}</option>
                                        @endif
                                        @endif
                                        @endforeach
                                        @endif
                                    </select>
                            </div>
                            <div class="col-md-4">
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
                                    <div class="text-white fw-600 fs-14 col-md-6 col-6">Today Received Amount</div>
                                    <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                        {{ number_format($today_receive_amount, 2) }}
                                    </div>
                                    <hr>
                                    <div class="text-white fw-600 fs-14 col-md-6 col-6">Today Cancel Amount</div>
                                    <div class="text-white text-end fs-16 fw-800 col-md-6 col-6">
                                        {{ number_format($today_cancel_amount, 2) }}
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </a>
                        <a class="box" style="background: #3ba5c1;">
                            <div class="box-body">
                                <div class="row">
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
                                    <div class="text-white fw-600 fs-14 col-md-6 col-6">This Month Order Value</div>
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
                                    <div class="text-white fw-600 fs-14 col-md-6 col-6">This Month Order Value</div>
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
                        
                    </div>
                </div>
                
            </div>
            
            <div class="row">
                @foreach($year_calculate as $key => $value)
                <?php
                    
                    $user_Details = \App\Models\User::where('id',$key)->first();
                    // dd($user_Details);
                    $check_date = $value['next_anniversary'];
                    
                    if ($check_date) {
                        $toDate = \Carbon\Carbon::parse($check_date);
                        $add_days = $toDate->addYear();
                        if ($add_days > $today_date) {
                            $days = $add_days->diffInDays($today_date);
                            $different_date = $days;
                        }
                        else
                        {
                            $different_date = "";
                        }
                        $currentDate = \Carbon\Carbon::now();
                        
                        $day1 = \Carbon\Carbon::parse($value['next_anniversary']);
                        $daysDifference = $day1->diffInDays($currentDate);
                        $hireDate = \Carbon\Carbon::parse($user_Details->join_date);
                        $yearsDifference = $hireDate->diffInYears($currentDate);
                    }
                    // dump($daysDifference);
                ?>
                @if($daysDifference && $daysDifference <= 30 || $daysDifference == "364")
                <div class="col-lg-2 box bg-warning bg-hover-warning" style="margin: 10px;padding: 10px;">
                    <div class="row">
                        <div class="col-md-3">
                            @if($user_Details->profile_picture)
                            <img class="rounded-circle" src="<?php echo url('');?>/public/profile/{{$user_Details->profile_picture}}" alt="User Avatar">
                            @else
                            @if($user_Details->gender == "male")
                            <img class="rounded-circle" src="<?php echo url('');?>/public/admin_assets/images/avatar2.png" alt="User Avatar">
                            @elseif($user_Details->gender == "female")
                            <img class="rounded-circle" src="<?php echo url('');?>/public/admin_assets/images/avatar04.png" alt="User Avatar">
                            @else
                            <img class="rounded-circle" src="<?php echo url('');?>/public/admin_assets/images/avatar2.png" alt="User Avatar">
                            @endif
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h4>{{ $value['name'] }}</h4>
                            @if($daysDifference == "364")
                                <p>{{ date('d-m-Y') }}</p>
                                <p>Happy {{ $yearsDifference }} Year Anniversary</p>
                            @else
                                <p>{{ date('d-m-Y', strtotime($value['next_anniversary'])) }}</p>
                                <p>{{ $daysDifference }} to go - {{ $yearsDifference + 1 }} Year Anniversary</p>
                            @endif
                            
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            
        </section>
        <!-- /.content -->
      </div>
</div>

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

<div class="modal fade" id="modal-default1">
  <div class="modal-dialog" role="document" style="max-width: 800px;width: 800px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="EmplyeName"> - Project Details</h4>
        <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
      </div>
      <div class="modal-body" id="load_html1">
        
      </div>
      <!-- <div class="modal-footer">
        <span class="btn btn-danger" data-bs-dismiss="modal">Close</span>
      </div> -->
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<?php
    $current_route = Route::currentRouteName();
?>

<script type="text/javascript">
    function OrderAmount(ref) {
        
        var ele = ref;
        // alert(ele);
        $.ajax({
            url: '{{ route('super.admin.project.details') }}',
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
        
        var expert_search_url   = "{{ url('/') }}/super-admin/this-month-projects";
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
        var salesperson2         = $("#salesperson2").val();
        var service_search      = $("#service_search").val();

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

        if (salesperson2) 
        {
            salesperson2 = salesperson2;
            
        }
        else
        {
            salesperson2 = "";
        }

        if (service_search) 
        {
            service_search = service_search;
            
        }
        else
        {
            service_search = "";
        }

        var str_search_request   = "&start_date="+start_date+"&end_date="+end_date+"&salesperson="+salesperson+"&salesperson2="+salesperson2+"&service_search="+service_search;

        if(str_search_request){
            window.location.href = expert_search_url + '?' + str_search_request;
        }
    }

    function EmplayeeDetails(ref) {
        var ele = ref;
        // alert(ele);
        $.ajax({
            url: '{{ route('super.admin.user.model.render') }}',
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele
            },
            success: function (response) {
                // console.log(response);
                $("#load_html1").html(response.html);
                $("#EmplyeName").html(response.EmplyeName);
                $('#modal-default1').modal('show');
            }
        });
    }

</script>
@endsection
