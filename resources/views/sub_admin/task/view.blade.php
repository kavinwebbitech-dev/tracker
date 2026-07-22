@extends('layouts.sub_admin')

@section('content')
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
                                <li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Task</li>
                                <li class="breadcrumb-item active" aria-current="page">View</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
          <div class="row">

            <div class="col-12">

             <div class="box">
                @include('layouts.flash-message')
                <!-- /.box-header -->
                <div class="box-body">

                    <form action="{{ route('sub_admin.report.download') }}" method="post">
                        @csrf
                    <input type="hidden" name="report_type" id="report_type">
                    <div class="row" style="justify-content: end;">
                        <p style="display:flex;width: 200px;">
                            <select class="form-control select2 @error('project_id') is-invalid @enderror" name="project_id" id="project_id" onchange="sort_book()">
                                <option value="">Select Project</option>
                                @if($projects)
                                @foreach($projects as $key => $project)
                                <option value="{{ $project->id }}" @if($project_id == $project->id) selected @endif>{{ $project->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </p>
                        <p style="display:flex;width: 200px;">
                            <select id="sort_task_type" name="sort_task_type" class="form-control select2" onchange="sort_book()">
                              <option value="">Select Staff</option>
                              @if($user_details)
                              @foreach($user_details as $key => $value)
                              <option value="{{ $value->id }}" @if($sort_by == $value->id) selected @endif>{{ $value->name }}</option>
                              @endforeach
                              @endif
                            </select>
                        </p>
                        <p style="display:flex;width: 200px;">
                            <select id="sort_task_status" name="sort_task_status" class="form-control select2" onchange="sort_book()">
                              <option value="">Select Task Status</option>
                              <option value="inprogress" @if($sort_task_status == "inprogress") selected @endif>In Progress</option>
                              <option value="user_completed" @if($sort_task_status == "user_completed") selected @endif>To be Apprd</option>
                              <option value="over_due" @if($sort_task_status == "over_due") selected @endif>Over Due</option>
                              <option value="recommend_to_admin" @if($sort_task_status == "recommend_to_admin") selected @endif>Recomment to Completed</option>
                              <option value="completed" @if($sort_task_status == "completed") selected @endif>Closed</option>
                              <option value="rejected" @if($sort_task_status == "rejected") selected @endif>Rejected</option>
                            </select>
                        </p>
                        <p style="display:flex;width: 200px;">
                            <span><input type="submit" class="btn btn-primary" style="padding: 4px 12px;" value="Report Download"></span>
                            <span onclick="SentMail()"><a class="btn btn-success" style="padding: 4px 10px;margin: 0px 2px;"><i class="fa fa-envelope-o"></i></a></span>
                        </p>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <input type="text" name="text_value_search" id="text_value_search1" class="form-control" onkeyup="TextValueSearch()" style="position: relative;width: 200px;z-index: 99;">
                        </div>
                    </div>
                    <br>

                    <div class="table-responsive">
                      <table id="open_derach" class="table table-bordered table-striped">
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
    
        function sort_book() {
            var expert_search_url = "{{ route('sub.task.view') }}";
            var sort_task_type = $("#sort_task_type").val();
            var sort_task_status = $("#sort_task_status").val();
            var sort_project_id = $("#project_id").val();

            var params = new URLSearchParams();

            if (sort_task_type) {
                params.append("task_type", sort_task_type);
            }
            if (sort_task_status) {
                params.append("task_status", sort_task_status);
            }
            if (sort_project_id) {
                params.append("project_id", sort_project_id);
            }

            if ([...params].length > 0) {
                window.location.href = expert_search_url + "?" + params.toString();
            } else {
                window.location.href = expert_search_url;
            }
        }
    </script>

    <script type="text/javascript">

        function ViewTaskModel(ref) {
            var ele = ref;
            // alert(ele);
            $.ajax({
                url: '{{ route('sub.admin.model.render') }}',
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
        <!-- modal Area -->              
      <div class="modal fade" id="modal-default">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Task Details</h4>
                <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
              </div>
              <div class="modal-body" id="load_html">
                <p>One fine body&hellip;</p>
              </div>
              <!-- <div class="modal-footer">
                <span class="btn btn-danger" data-bs-dismiss="modal">Close</span>
              </div> -->
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
      </div>

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
                    url: "{{ route('sub_admin.task.search') }}",
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
                            $("#open_derach").html(response.project);
                            $("#seach_hide").hide();
                        }
                    }
                });
            }

        }

        
    </script>
    <!-- /.modal -->
@endsection