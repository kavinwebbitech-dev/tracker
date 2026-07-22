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
                    <div class="table-responsive">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Task Review for Riser</th>
                                <th>Staff Name</th>
                                <th>Task Name</th>
                                <th>Task Type</th>
                                <th>Assign Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($sub_admin)
                            @foreach($sub_admin as $key => $value)
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $value->admin_details->name ?? '' }}</td>
                                <td>{{ $value->staff_details->name ?? '' }}</td>
                                <td>{{ $value->name }}</td>
                                <td>
                                    <?php
                                        if ($value->date_type == "7") {
                                            $requ_date = "Weekly";
                                        }
                                        elseif ($value->date_type == "15") {
                                            $requ_date = "15 Days";
                                        }
                                        elseif ($value->date_type == "30") {
                                            $requ_date = "Monthly";
                                        }
                                        elseif ($value->date_type == "90") {
                                            $requ_date = "Quarterly";
                                        }
                                        elseif ($value->date_type == "365") {
                                            $requ_date = "Yearly";
                                        }
                                        
                                    ?>
                                    @if($value->task_type == "custom")
                                        Custom
                                    @else
                                        Recurring <span style="font-size: 10px;">({{ $requ_date }})</span>
                                    @endif</td>
                                <td>{{ date('d-m-Y H:i:s A', strtotime($value->created_at)) }}</td>
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

                                    <p style="display: flex;">
                                    @if($value->status != "completed")
                                        @if(in_array('61', $role_section))
                                       <a href="javascript:void(0);"onclick="deleteProject('{{ route('sub.task.delete', $value->id) }}')" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
                                        @endif
                                    @endif
                                        @if(in_array('62', $role_section))
                                        <a href="{{ route('sub.task.status', $value->id) }}" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-eye text-white"></i></a></td>
                                        @endif
                                    </p>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                      </table>
                    </div>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->

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