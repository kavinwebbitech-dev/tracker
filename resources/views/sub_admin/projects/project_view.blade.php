@extends('layouts.sub_admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Project Details</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Project</li>
                                <li class="breadcrumb-item active" aria-current="page">Project</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>
        <style type="text/css">
            .col_padding
            {
                padding: 10px;
            }
            .delete
            {
                width: 200px;
            }
            .delete1
            {
                width: 150px;
            }
        </style>
        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            @include('layouts.flash-message')
                            
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h4 class="box-title">General Info</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Project Name</div>
                                            <div class="col-md-6 col_padding">- {{ $project->name }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Start Date</div>
                                            <div class="col-md-6 col_padding">
                                                @if($project->start_date == "0000-00-00")
                                                @else
                                                    - {{ date('d-m-Y', strtotime($project->start_date)) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Description</div>
                                            <div class="col-md-6 col_padding">{!! $project->description !!}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">End Date</div>
                                            <div class="col-md-6 col_padding">
                                                @if($project->end_date == "0000-00-00")
                                                @else
                                                    - {{ date('d-m-Y', strtotime($project->end_date)) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Sales Order Date</div>
                                            <div class="col-md-6 col_padding">
                                                @if($project->sales_user_date == "0000-00-00")
                                                @else
                                                    - {{ date('d-m-Y', strtotime($project->sales_user_date)) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Status</div>
                                            <div class="col-md-6 col_padding">
                                                @if($project->status == "0")
                                                    - Pending
                                                @elseif($project->status == "1")
                                                    - On Progress
                                                @elseif($project->status == "3")
                                                    - On Hold
                                                @elseif($project->status == "5")
                                                    - Done
                                                @elseif($project->status == "6")
                                                    - Cancel
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Sales Person Name</div>
                                            <div class="col-md-6 col_padding">- {{ $project->sales_user_details->firstname ?? '' }} {{ $project->sales_user_details->lastname ?? '' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Service</div>
                                            <div class="col-md-6 col_padding">- {{ $project->service_details->name ?? '' }}</div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>              
                </div>

                <div class="row">
                    <div class="col-12 col-md-12">

                        <div class="nav-tabs-custom">

                            <ul class="nav nav-tabs">
                              <li style="width: 32%;"><a class="active" href="#usertimeline" data-bs-toggle="tab">Task Details</a></li>
                              <li style="width: 32%;"><a href="#activity" data-bs-toggle="tab">Payment Details</a></li>
                            </ul>

                            <div class="tab-content">
                                <div class="active tab-pane" id="usertimeline">
                                    <?php
                                        $toal_time = 0;
                                        $over_time = 0;
                                        $over_project = 0;
                                    ?>
                                    <div class="panel-group panel-group-simple panel-group-continuous" id="accordion2"
                                      aria-multiselectable="true" role="tablist">

                                        @if(count($user_list) > 0)
                                        @foreach($user_list as $key => $user)
                                        <?php
                                            $user_details = \App\Models\User::where('id', $user->staff_id)->first();
                                            $task_details = \App\Models\TaskStaff::where('staff_id', $user->staff_id)->where('project_id', $project->id)->get()
                                        ?>
                                        <!-- Question 1 -->
                                        <div class="panel">
                                          <div class="panel-heading" id="question-{{ $key + 1}}" role="tab">
                                            <a class="panel-title" aria-controls="answer-{{ $key + 1}}" aria-expanded="true" data-bs-toggle="collapse"  href="#answer-{{ $key + 1}}" data-parent="#accordion2">
                                                {{ $user_details->name }}
                                            </a>
                                          </div>
                                          <div class="panel-collapse collapse @if($key == 0) show @endif" id="answer-{{ $key + 1}}" aria-labelledby="question-{{ $key + 1}}" role="tabpanel" data-bs-parent="#category-1">
                                            <div class="panel-body">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Task Details</th>
                                                        <th>Spent Time</th>
                                                        <th>Staff Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $total_time1 = 0;
                                                        $toal_project = 0;
                                                        $totaltime = 0;
                                                        $project_amount = 0;
                                                        $add_hours = 0;
                                                    ?>
                                                    @foreach ($task_details as $key1 => $task)
                                                    <?php

                                                    $staff_comments = \App\Models\TaskComment::where('task_id', $task->task_details->id)->where('staff_id', $user->staff_id)->get();
                                                    $sum = strtotime('00:00:00');
                                                    
                                                    foreach ($staff_comments as $key2 => $value1) {
                                                        $working_time_h = number_format($value1->working_hours, 2);
                                                        $timeinsec = strtotime($working_time_h) - $sum;
                                                        $totaltime += $timeinsec;
                                                        
                                                    }

                                                    $toal_time += $totaltime;

                                                    $h = intval($totaltime / 3600);
                                                    $totaltime = $totaltime - ($h * 3600);
                                                    $m = intval($totaltime / 60);
                                                    $s = $totaltime - ($m * 60);

                                                    $total_time = $h.':'.$m;
                                                    $salary = $user_details->salary;
                                                    $hour_rate = $salary / 200;
                                                    // dd($hour_rate);
                                                    // foreach ($staff_comments as $key2 => $value1) {
                                                    //     $add_hours += $value1->working_hours;
                                                    // }
                                                    
                                                    // dd($hour_convert[1]);
                                                    if (isset($m) && $m) {
                                                        $calculate = $h + ($m / 60);
                                                    }
                                                    else
                                                    {
                                                        $calculate = $h;
                                                    }
                                                    
                                                    $added_rate = round($calculate, 2);
                                                    $total_amount = $added_rate * $hour_rate;
                                                    $project_amount += $total_amount;
                                                    $toal_project += $project_amount;

                                                    ?>
                                                    <tr>
                                                        <td>
                                                            {{ $task->task_details->name }} ({{ $task->task_details->task_no }}) <a href="{{ route('sub.task.status', $task->task_details->id) }}" class="btn btn-success" style="width: 23px;height: 24px;padding: 0px;border-radius: 16px;" target="_blank"><i class="ti-eye text-white" style="font-size: 10px;"></i></a>
                                                        </td>
                                                        <td style="text-align: right;">{{ $total_time }}</td>
                                                        <td style="text-align: right;">{{ number_format($project_amount, 2) }}</td>
                                                    </tr>
                                                    @endforeach
                                                    
                                                </tbody>
                                                <tfoot>
                                                    <?php

                                                        $over_time += $toal_time;

                                                        $h1 = intval($toal_time / 3600);
                                                        $toal_time = $toal_time - ($h1 * 3600);
                                                        $m1 = intval($toal_time / 60);
                                                        $s1 = $toal_time - ($m1 * 60);
                                                        $total_time1 = $h1.':'.$m1;
                                                        $over_project += $toal_project;
                                                    ?>
                                                    <tr>
                                                        <td></td>
                                                        <td style="text-align: right;">{{ $total_time1 }}</td>
                                                        <td style="text-align: right;">{{ number_format($toal_project, 2) }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                            
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Question 1 -->
                                        @endforeach
                                        @endif
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-8" style="text-align: right;">Over All Project Working Hours</div>
                                            <div class="col-md-4" style="text-align: right;">
                                                <?php
                                                    $h2 = intval($over_time / 3600);
                                                    $over_time = $over_time - ($h2 * 3600);
                                                    $m2 = intval($over_time / 60);
                                                    $s2 = $over_time - ($m2 * 60);
                                                    $total_time1 = $h2.':'.$m2;
                                                ?>
                                                {{ $total_time1 }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-8" style="text-align: right;">Over All Project Working Amount</div>
                                            <div class="col-md-4" style="text-align: right;">
                                                {{ number_format($over_project, 2) }}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-8" style="text-align: right;">Earning Amount</div>
                                            <div class="col-md-4" style="text-align: right;">
                                                <?php
                                                    $project_amount11 = 0;
                                                    if($project->bid_amount) 
                                                    {
                                                        $project_amount11 = $project->bid_amount;
                                                    }

                                                    $total_eran = $project_amount11 - $over_project;
                                                ?>
                                                {{ number_format($total_eran, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="activity">
                                    <div class="box">
                                        <div class="box-body">
                                            <div class="row">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Payment Date</th>
                                                            <th>Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $ramian_amount = 0;
                                                        ?>
                                                        @foreach ($project->bit_amounts as $key => $value1)
                                                        <?php
                                                            $ramian_amount += $value1->fld_project_amount;
                                                        ?>
                                                        <tr>
                                                            <td>{{ date('d-m-Y', strtotime($value1->fld_payment_date)) }}</td>
                                                            <td>{{ $value1->fld_project_amount }}</td>
                                                            <td>
                                                                <p style="display:flex;">
                                                                    <a  onclick='ViewTaskModel("{{ $value1->id }}")' class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                                                                    <a href="javascript:void(0);"onclick="deleteProject('{{ route('sub_admin.projects.payment.delete', $value1->id) }}')" class="delete btn btn-danger" data-confirm="Are you Sure Delete this payment?" style="padding: 4px 12px;margin: 0px 4px;width: 40px;"><i class="ti-trash"></i></a>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td>Remaining Amount</td>
                                                            <td>{{ $project->bid_amount - $ramian_amount }}</td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>

        </section>
        <!-- /.content -->
      </div>
  </div>
  <!-- /.content-wrapper -->
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>

    <script type="text/javascript">
        function ViewTaskModel(id) {
            var project_id = id;

            if (project_id) {
                $.ajax({
                    url: "{{ route('sub_admin.projects.payment.edit.details') }}",
                    type: "POST",
                    data: {
                        id: project_id,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        $('#modal-default').modal('show');
                        $("#load_html1").html(response.html);
                    }
                });
            }

        }
    </script>
  
    <div class="modal fade" id="modal-default">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Payment Edit Details</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html1">
            
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            var deleteLinks = document.querySelectorAll('.delete');

            for (var i = 0; i < deleteLinks.length; i++) {
              deleteLinks[i].addEventListener('click', function(event) {
                  event.preventDefault();

                  var choice = confirm(this.getAttribute('data-confirm'));

                  if (choice) {
                    window.location.href = this.getAttribute('href');
                  }
              });
            }
        });
    </script>
@endsection