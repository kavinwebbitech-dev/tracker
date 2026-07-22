@extends('layouts.staff')

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
                                <li class="breadcrumb-item"><a href="{{ route('staff.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
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
                                <th>No</th>
                                <!-- <th>Date</th> -->
                                <th>Task Id</th>
                                <th>Project</th>
                                <th>Assign By</th>
                                <th>Task Name</th>
                                <th>Task Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($sub_admin)
                            @foreach($sub_admin as $key => $value)
                            
                            <tr>
                                <td>{{ $key + 1}}</td>
                                {{-- <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td> --}}
                                <td>
                                    {{ $value->task_no ?? '' }}
                                </td>
                                <td>{{ $value->project_details->name ?? '' }}</td>
                                <td>{{ $value->assign_details->name ?? '' }}</td>
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
                                    @if($value->payment_follow_up == Auth::user()->id)
                                        Payment Follow Up
                                    @else
                                        Project Follow Up
                                    @endif
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
                                    <a href="{{ route('staff.follow.up.project.details', $value->id) }}" class="btn btn-success" style="margin: 5px 4px;"><i class="ti-eye text-white"></i></a>
                                </td>
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
<script type="text/javascript">

    function ViewTaskModel(ref) {
        var ele = ref;
        // alert(ele);
        $.ajax({
            url: '{{ route('staff.model.render') }}',
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
<!-- /.modal -->
@endsection