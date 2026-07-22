@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Task</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Task</li>
                                <li class="breadcrumb-item active" aria-current="page">View</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>

        <style type="text/css">
            #example_filter
            {
                display: none;
            }
        </style>
        <!-- Main content -->
        <section class="content">
          <div class="row">

            <div class="col-12">

             <div class="box">
                @include('layouts.flash-message')
                <div class="row">
                    <div class="col-md-9"></div>
                    <div class="col-md-3">
                        
                    </div>
                </div>
                <style type="text/css">
                    .add_button
                    {
                        margin-bottom: 10px;
                    }
                </style>

                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <a href="{{ route('task.create') }}" class="btn btn-primary btn-sm add_button">Add Task</a>
                        </div>
                        <div class="col-md-8"></div>
                        <div class="col-md-2">
                            <select id="ex_user_details" name="sort_task_type" class="form-control select2" onchange="sort_book1()">
                              <option value="">Select Ex Staff</option>
                              @if($ex_user_details)
                              @foreach($ex_user_details as $key => $value)
                              <option value="{{ $value->id }}" @if($ex_user_detail == $value->id) selected @endif>{{ $value->name }}</option>
                              @endforeach
                              @endif
                            </select>
                        </div>
                    </div>
                    
                    <form id="form-id" action="{{ route('super_admin.report.download') }}" method="post">
                    @csrf
                    <input type="hidden" name="report_type" id="report_type">
                    <div class="row" style="justify-content: center;">
                        <div class="col-md-2">
                            <input type="date" name="start_date" onfocus="'showPicker' in this && this.showPicker()" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ $start_date ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control select2 @error('project_id') is-invalid @enderror" name="project_id[]" id="project_id" multiple="multiple" data-placeholder="Select a Project">
                                <!-- <option value="">Select Project</option> -->
                                @if($projects)
                                @foreach($projects as $key => $project)
                                <option value="{{ $project->id }}" @if(in_array($project->id, $project_id)) selected @endif>{{ $project->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="sort_task_type" name="sort_task_type" class="form-control select2">
                              <option value="">Select Staff</option>
                              @if($user_details)
                              @foreach($user_details as $key => $value)
                              <option value="{{ $value->id }}" @if($sort_by == $value->id) selected @endif>{{ $value->name }}</option>
                              @endforeach
                              @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="sort_task_status" name="sort_task_status" class="form-control select2">
                              <option value="">Select Task Status</option>
                              <option value="inprogress" @if($sort_task_status == "inprogress") selected @endif>In Progress</option>
                              <option value="user_completed" @if($sort_task_status == "user_completed") selected @endif>To be Apprd</option>
                              <option value="over_due" @if($sort_task_status == "over_due") selected @endif>Over Due</option>
                              <option value="recommend_to_admin" @if($sort_task_status == "recommend_to_admin") selected @endif>Recomment to Completed</option>
                              <option value="completed" @if($sort_task_status == "completed") selected @endif>Closed</option>
                              <option value="rejected" @if($sort_task_status == "rejected") selected @endif>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <p style="display:flex;">
                                <span onclick="sort_book()"><a class="btn btn-primary" style="padding: 4px 10px;margin: 0px 2px;">Submit</a></span>

                                <!-- <span><input type="submit" class="btn btn-primary" style="padding: 4px 12px;" value="Submit"></span> -->
                                <span onclick="SentReport()" title="Download Report"><a class="btn btn-dark" style="padding: 4px 10px;margin: 0px 2px;"><i class="fa fa-download"></i></a></span>
                                <span onclick="SentMail()" title="Sent E-Mail"><a class="btn btn-success" style="padding: 4px 10px;margin: 0px 2px;"><i class="fa fa-envelope-o"></i></a></span>
                                <span onclick="SentProjectView()" title="View Project Details"><a class="btn btn-warning" style="padding: 4px 10px;margin: 0px 2px;"><i class="ti-file"></i></a></span>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <input type="text" name="text_value_search" id="text_value_search1" class="form-control" onkeyup="TextValueSearch()" style="position: absolute;width: 200px;z-index: 99;margin-top: 40px;">
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="table-responsive">
                        <!--<table id="example1" class="table table-bordered table-striped">-->
                      <table id="example" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>Task Id</th>
                                <th>Project</th>
                                <th>Assign By</th>
                                <th>Staff Name</th>
                                <th>Task Name</th>
                                <!--<th>Task Type</th>-->
                                <th>Priority</th>
                                <th>Status</th>
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
                                <td>
                                    @if($start_date)
                                        {{ date('d-m-Y', strtotime($start_date)) }}
                                    @else
                                        <?php
                                            $task_comments = \App\Models\TaskComment::where('task_id', $value->task_details->id)->where('staff_id', $value->staff_id)->orderBy('id', 'DESC')->first();
                                            // dd($task_comments);
                                        ?>
                                        @if($task_comments)
                                            {{ date('d-m-Y', strtotime($task_comments->start_date)) }}
                                        @else
                                        @endif
                                        
                                    @endif
                                </td>
                                <td>{{ $value->task_details->task_no }}</td>
                                <td>{{ $value->task_details->project_details->name ?? '' }}</td>
                                <td>{{ $value->task_details->assign_details->name ?? '' }}</td>
                                <td>
                                    {{ $value->staff_name ?? '' }}
                                </td>
                                <td>{{ $value->task_details->name ?? '' }}</td>
                                {{-- <td>
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
                                </td> --}}
                                <td>
                                    {{ $value->priority }}
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
                                    <p style="display: flex;">
                                        <a href="{{ route('admin.task.status', $value->task_details->id) }}" class="btn btn-success" style="margin: 5px 4px;"><i class="ti-eye text-white"></i></a>
                                        @if($value->status != "completed")
                                        <a href="{{ route('task.edit', $value->task_details->id) }}" class="btn btn-primary" style="margin: 5px 4px;"><i class="ion ion-edit text-white"></i></a>
                                        <a href="{{ route('admin.task.single.delete', $value->id) }}" class="btn btn-danger" style="margin: 5px 4px;"><i class="ti-trash text-white"></i></a>
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
                        </table>
                        <style type="text/css">
                          .pagination li a
                          {
                            padding: 6px;
                          }
                        </style>
                        <div class="row gy-4 align-items-center" id="seach_hide" style="margin-top: 30px;">
                            <div class="col-12">
                                {!! $sub_admin->withQueryString()->links('pagination::bootstrap-5') !!}
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
              </form>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </section>
        <!-- /.content -->
      
      </div>
  </div>
  <!-- /.content-wrapper -->
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script type="text/javascript">
      $(function () {
        "use strict";

        $('#example1').DataTable(
        {
        'paging'      : false,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true
        });
    });
  </script>
  <script type="text/javascript">
    
    function sort_book1() {
        var expert_search_url   = "{{ url('/') }}/super-admin/task/single-task";
        var ex_user_details          = $("#ex_user_details").val();

        if (ex_user_details) 
        {
            var str_search_request   = "&ex_user_details="+ex_user_details;
        }

        if(str_search_request){
            window.location.href = expert_search_url + '?' + str_search_request;
        }

    }
    
    function sort_book() {
        var expert_search_url   = "{{ url('/') }}/super-admin/task/single-task";
        var start_date          = $("#start_date").val();
        var sort_task_type      = $("#sort_task_type").val();
        var sort_task_status    = $("#sort_task_status").val();
        var project_id          = $("#project_id").val();

        if (sort_task_type) 
        {
            var str_search_request   = "&task_type="+sort_task_type+"&task_status="+sort_task_status+"&start_date="+start_date+"&project_id="+project_id;
        }

        if (sort_task_status) 
        {
            var str_search_request   = "&task_type="+sort_task_type+"&task_status="+sort_task_status+"&start_date="+start_date+"&project_id="+project_id;
        }
        if (project_id) 
        {
            var str_search_request   = "&task_type="+sort_task_type+"&task_status="+sort_task_status+"&start_date="+start_date+"&project_id="+project_id;
        }
        if (start_date) 
        {
            var str_search_request   = "&task_type="+sort_task_type+"&task_status="+sort_task_status+"&start_date="+start_date+"&project_id="+project_id;
        }
        if(str_search_request){
            window.location.href = expert_search_url + '?' + str_search_request;
        }
        
    }

    function SentProjectView()
    {
        var project_id = $("#project_id").val();
        
        if (project_id) 
        {
            $('#modal-default').modal('show');
        }
        else
        {
            alert("Please Select Project");
        }
    }

    function ViewTaskModel(ref) {
        var ele = ref;
        // alert(ele);
        $.ajax({
            url: '{{ route('super.admin.model.render') }}',
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

    var form = document.getElementById("form-id");

    document.getElementById("whatsapp-id").addEventListener("click", function () {
        $("#report_type").val('whatsapp')
      form.submit();
    });
    
    function SentMail()
    {
        $("#report_type").val('mail')
        form.submit();
    }

    function SentReport()
    {
        $("#report_type").val('excel')
        form.submit();
    }
    // document.getElementById("mail-id").addEventListener("click", function () {
        
    // });

    </script>

<style type="text/css">
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 770px;
            margin: 1.75rem auto;
        }
    }

</style>
<style type="text/css">
            .col_padding
            {
                padding: 10px;
            }
        </style>
    
    @if($project_id[0])          
    <div class="modal fade" id="modal-default">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <?php
                $project_details = \App\Models\Project::where('id', $project_id)->first();
            ?>
            <h4 class="modal-title">{{ $project_details->name ?? '' }} - Project Details</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html">
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
                    @if($sub_admin)
                    @foreach($sub_admin as $key => $papup)

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
                        <td style="text-align: right;">
                            Total Staff Amount
                        </td>
                        <td style="text-align: right;">
                            {{ $project_amount }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;">
                            Project Amount
                        </td>
                        <td style="text-align: right;">
                            {{ $project_details->bid_amount ?? '0' }}
                        </td>
                    </tr>
                    <?php
                        $total_earn_amount = $project_details->bid_amount - $project_amount;
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;">
                            Total Earning
                        </td>
                        <td style="text-align: right;">
                            {{ $total_earn_amount ?? '0' }}
                        </td>
                    </tr>
                </tfoot>
            </table>
          </div>
          <!-- <div class="modal-footer">
            <span class="btn btn-danger" data-bs-dismiss="modal">Close</span>
          </div> -->
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    @endif

    <script type="text/javascript">

        function TextValueSearch()
        {
            var text_value_search = $("#text_value_search1").val();
            var sort_task_status = $("#sort_task_status").val();
            var sort_task_type = $("#sort_task_type").val();
            var project_id = $("#project_id").val();
            var start_date = "{{ $start_date }}";

            if (text_value_search) {
                $.ajax({
                    url: "{{ route('admin.task.search') }}",
                    type: "POST",
                    data: {
                        text_value_search: text_value_search,
                        sort_task_status: sort_task_status,
                        sort_task_type: sort_task_type,
                        project_id: project_id,
                        start_date: start_date,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        
                        if (response.status) 
                        {
                            $("#example").html(response.project);
                            $("#seach_hide").hide();
                        }
                    }
                });
            }

        }

        
    </script>
@endsection