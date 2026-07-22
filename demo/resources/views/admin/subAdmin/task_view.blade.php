@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->     
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Task Details</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Staff</li>
                                <li class="breadcrumb-item active" aria-current="page">Task Details</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-12 col-lg-12 col-xl-12">
            @include('layouts.flash-message')
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li><a class="active" href="#usertimeline" data-bs-toggle="tab">Custom Task</a></li>
                  <li><a href="#activity" data-bs-toggle="tab">Recurring Task</a></li>
                  <!-- <li><a href="#settings" data-bs-toggle="tab">Settings</a></li> -->
                </ul>

                <div class="tab-content">

                 <div class="active tab-pane" id="usertimeline">
                    <div class="row">
                        <form action="{{ route('sub.admin.staff.report') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{ $user_details->id }}">
                            <a style="text-align: center;padding: 10px 10px;float: right;"><input type="submit" name="row_check" value="Reopen" class="btn btn-success"></a>
                            <a style="text-align: center;padding: 10px 10px;float: right;"><input type="submit" name="row_check" value="Canceled" class="btn btn-success"></a>
                            <a style="text-align: center;padding: 10px 10px;float: right;"><input type="submit" name="row_check" value="Rejected" class="btn btn-success"></a>
                            <a style="text-align: center;padding: 10px 10px;float: right;"><input type="submit" name="row_check" value="Completed" class="btn btn-success"></a>
                            <a style="text-align: center;padding: 10px 10px;float: right;"><input type="submit" name="row_check" value="Staff Completed" class="btn btn-success"></a>
                            <a style="text-align: center;padding: 10px 10px;float: right;"><input type="submit" name="row_check" value="Progress" class="btn btn-success"></a>
                            <a style="text-align: center;padding: 10px 10px;float: right;"><input type="submit" name="row_check" value="Pending" class="btn btn-success"></a> 
                        </form>
                    </div>
                    
                    <div class="table-responsive">
                      <table id="example2" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Staff Name</th>
                                <!-- <th>Staff Name</th> -->
                                <th>Task Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Points</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($custom_task)
                            @foreach($custom_task as $key => $value)
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $value->staff_details->name }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ date('d-m-Y', strtotime($value->start_date)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($value->end_date)) }}</td>
                                <td>
                                    @if($value->status == "pending")
                                        Pending
                                    @elseif($value->status == "progress")
                                        Progress
                                    @elseif($value->status == "user_completed")
                                        User Completed
                                    @elseif($value->status == "completed")
                                        Completed
                                    @elseif($value->status == "canceled")
                                        Canceled
                                    @elseif($value->status == "rejected")
                                        Rejected
                                    @elseif($value->status == "hold")
                                        Hold
                                    @elseif($value->status == "reopen")
                                        Reopen
                                    @endif
                                </td>
                                <td>{{ $value->points }}</td>
                                <td>{{-- <a href="{{ route('task.edit', $value->id) }}" class="btn btn-primary" style="padding: 4px 12px;margin: 10px 4px;"><i class="ion ion-edit text-white"></i></a>  <a href="{{ route('task.delete', $value->id) }}" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a> --}}<a href="{{ route('admin.task.status', $value->id) }}" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-eye text-white"></i></a></td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S No</th>
                                <th>Staff Name</th>
                                <!-- <th>Staff Name</th> -->
                                <th>Task Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Points</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>    
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="activity">           
                    <div class="table-responsive">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Staff Name</th>
                                <th>Task Name</th>
                                <th>Task Type</th>
                                <th>Assign Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($recurring_task))
                            @foreach($recurring_task as $key => $value1)
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $value1->staff_details->name }}</td>
                                <td>{{ $value1->name }}</td>
                                <td>
                                    <?php
                                        if ($value1->date_type == "7") {
                                            $requ_date = "Weekly";
                                        }
                                        elseif ($value1->date_type == "15") {
                                            $requ_date = "15 Days";
                                        }
                                        elseif ($value1->date_type == "30") {
                                            $requ_date = "Monthly";
                                        }
                                        elseif ($value1->date_type == "90") {
                                            $requ_date = "Quarterly";
                                        }
                                        elseif ($value1->date_type == "365") {
                                            $requ_date = "Yearly";
                                        }
                                        
                                    ?>
                                    @if($value1->task_type == "custom")
                                        Custom
                                    @else
                                        Recurring <span style="font-size: 10px;">({{ $requ_date }})</span>
                                    @endif</td>
                                <td>{{ date('d-m-Y H:i:s A', strtotime($value1->created_at)) }}</td>
                                
                                <td>{{-- <a href="{{ route('task.edit', $value1->id) }}" class="btn btn-primary" style="padding: 4px 12px;margin: 10px 4px;"><i class="ion ion-edit text-white"></i></a> <a href="{{ route('task.delete', $value1->id) }}" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a> --}} <a href="{{ route('admin.task.status', $value1->id) }}" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-eye text-white"></i></a><a href="{{ route('sub.admin.recurring.report', $value1->id) }}" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-download text-white"></i></a></td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S No</th>
                                <th>Staff Name</th>
                                <th>Task Name</th>
                                <th>Task Type</th>
                                <th>Assign Date</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                  <!-- /.tab-pane -->

                </div>
                <!-- /.tab-content -->
              </div>
              <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->

          </div>
          <!-- /.row -->

        </section>
        <!-- /.content -->
      </div>
  </div>
  <!-- /.content-wrapper -->


@endsection