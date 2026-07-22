@extends('layouts.sub_admin')

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
                                <li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Task</li>
                                <li class="breadcrumb-item active" aria-current="page">Details</li>
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
        </style>
        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-body">
                        @include('layouts.flash-message')
                        <?php
                            $total = $task->task_details;
                        ?>
                        @if($task->task_type == "recurring")
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col_padding">Task Created by</div>
                                    <div class="col-md-6 col_padding">- {{ $task->assign_details->name ?? '' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col_padding">Task Name</div>
                                    <div class="col-md-6 col_padding">- {{ $task->name }}</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col_padding">Create Date</div>
                                    <div class="col-md-6 col_padding">- {{ date('d-m-Y H:i:s A', strtotime($task->created_at)) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col_padding">Status</div>
                                    <div class="col-md-6 col_padding">
                                        @if($task->status == "pending")
                                            <span style="color: red;">Pending</span>
                                        @elseif($task->status == "inprogress")
                                            <span style="color: #f7a20f;">In Progress</span>
                                        @elseif($task->status == "user_completed")
                                            <span style="color: #f5be0a;">Completed By Staff</span>
                                        @elseif($task->status == "over_due")
                                            <span style="color: #14bcfc;">Over Due</span>
                                        @elseif($task->status == "recommend_to_admin")
                                            <span style="color: #cf2bc4;">Recomment to Completed</span>
                                        @elseif($task->status == "completed")
                                            <span style="color: #2de033;">Closed</span>
                                        @elseif($task->status == "canceled")
                                            <span style="color: #f21f35;">Canceled</span>
                                        @elseif($task->status == "rejected")
                                            <span style="color: #1ff2e8;">Rejected</span>
                                        @elseif($task->status == "hold")
                                            <span style="color: red;">Hold</span>
                                        @elseif($task->status == "reopen")
                                            <span style="color: red;">Reopen</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col_padding">Start Date</div>
                                    <div class="col-md-6 col_padding">- {{ date('d-m-Y', strtotime($task->start_date)) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col_padding">End Date</div>
                                    <div class="col-md-6 col_padding">- {{ date('d-m-Y', strtotime($task->end_data)) }}</div>
                                </div>
                            </div> --}}
                            @if($task->user_comments)
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col_padding">Staff Comments</div>
                                    <div class="col-md-6 col_padding">{{ $task->user_comments }}</div>
                                </div>
                            </div>
                            @endif
                            @if($task->admin_comments)
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col_padding">Admin Comments</div>
                                    <div class="col-md-6 col_padding">{{ $task->admin_comments }}</div>
                                </div>
                            </div>
                            @endif
                            
                            @if($task->completed_date)
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col_padding">
                                        @if($task->status == "reopen")
                                            Reopen Date
                                        @else
                                            Completed Date
                                        @endif
                                    </div>
                                    <div class="col-md-6 col_padding">{{ date('d-m-Y - h:i A', strtotime($task->completed_date)) }}</div>
                                </div>
                            </div>
                            @endif
                            
                            <form action="{{ route('sub.admin.task.details.status.update') }}" method="post">
                            @csrf
                            @if($task->status == "pending" || $task->status == "inprogress" || $task->status == "rejected")
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col_padding">
                                            <input type="hidden" name="task_details_id" value="{{ $task->id }}">
                                            <label>Stats  Update</label>
                                            <select class="form-control @error('status') is-invalid @enderror" name="status" id="task_status">
                                                <option> Select Status</option>
                                                <option value="user_completed" @if($task->status == "user_completed") selected @endif>Completed</option>
                                            </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="row" style="display: none;" id="status_update_box">
                                <div class="col-md-6 col_padding">
                                    <textarea rows="6" name="user_comment" style="width: 100%;"></textarea>
                                </div>
                            </div>
                            <div class="row" style="border-bottom: 1px solid #d1d1d1;display: none;" id="status_update_button">
                                <div class="col-md-4 col_padding">
                                    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                                </div>
                            </div>
                            </form>

                        </div>
                        @else
                            @if($task_staff->status == "progress" || $task_staff->status == "rejected" || $task_staff->status == "reopen")
                                <a href="{{ route('sub_admin.change.status.task', $task_staff->id) }}" class="delete btn btn-primary" data-confirm="Are you Completed this task?">Request to Complete</a>
                            <!-- <ul>
                                <li style="list-style-type: none;"><span data-bs-target="#myModal" data-bs-toggle="modal" class="">Request to Complete</span></li>
                            </ul> -->
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 col_padding">Task Created by</div>
                                        <div class="col-md-6 col_padding">- {{ $task->admin_details->name ?? '' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 col_padding">Task Name</div>
                                        <div class="col-md-6 col_padding">- {{ $task->name }}</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 col_padding">Create Date</div>
                                        <div class="col-md-6 col_padding">- {{ date('d-m-Y H:i:s A', strtotime($task->created_at)) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 col_padding">Start Date</div>
                                        <div class="col-md-6 col_padding">- {{ date('d-m-Y', strtotime($task_staff->start_date)) }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 col_padding">End Date</div>
                                        <div class="col-md-6 col_padding">- {{ date('d-m-Y', strtotime($task_staff->end_date)) }}</div>
                                    </div>
                                </div>
                                @if($task->user_comments)
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 col_padding">Staff Comments</div>
                                        <div class="col-md-6 col_padding">{{ $task->user_comments }}</div>
                                    </div>
                                </div>
                                @endif
                                @if($task->admin_comments)
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 col_padding">Admin Comments</div>
                                        <div class="col-md-6 col_padding">{{ $task->admin_comments }}</div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($task->completed_date)
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 col_padding">
                                            @if($task->status == "reopen")
                                                Reopen Date
                                            @else
                                                Completed Date
                                            @endif
                                        </div>
                                        <div class="col-md-6 col_padding">- {{ date('d-m-Y - h:i A', strtotime($task->completed_date)) }}</div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 col_padding">Status</div>
                                        <div class="col-md-6 col_padding">
                                            @if($task_staff->status == "pending")
                                                - <span style="color: red;">Pending</span>
                                            @elseif($task_staff->status == "progress")
                                                - <span style="color: #f7a20f;">In Progress</span>
                                            @elseif($task_staff->status == "user_completed")
                                                - <span style="color: #14bcfc;">Completed To be Apprd</span>
                                            @elseif($task_staff->status == "over_due")
                                                - <span style="color: #14bcfc;">Over Due</span>
                                            @elseif($task_staff->status == "recommend_to_admin")
                                                - <span style="color: #cf2bc4;">Recomment to Completed</span>
                                            @elseif($task_staff->status == "completed")
                                                - <span style="color: #2de033;">Closed</span>
                                            @elseif($task_staff->status == "canceled")
                                                - <span style="color: #f21f35;">Canceled</span>
                                            @elseif($task_staff->status == "rejected")
                                                - <span style="color: #1ff2e8;">Rejected</span>
                                            @elseif($task_staff->status == "hold")
                                                - <span style="color: red;">Hold</span>
                                            @elseif($task_staff->status == "reopen")
                                                - <span style="color: red;">Reopen</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3 col_padding">Description</div>
                                        <div class="col-md-9 col_padding">{!! $task->description !!}</div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <h4 >Task Update</h4>
                                    </div>
                                    <div class="col-md-6"></div>
                                </div>
                                @if($task_staff->status != "completed" || $task_staff->status != "rejected" || $task_staff->status != "canceled")
                                <form action="{{ route('sub_admin.task.comment.update') }}" method="post">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Start Date</label>

                                            <div class="input-group mb-3">
                                                <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" required>
                                                <!-- <span class="input-group-text"><i class="ti-lock"></i></span> -->
                                            </div>
                                            @error('start_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">End Date</label>
                                            <div class="input-group mb-3">
                                                <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" required>
                                                <!-- <span class="input-group-text"><i class="ti-lock"></i></span> -->
                                            </div>
                                            @error('end_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Comments</label>
                                            <div class="input-group mb-3">
                                                <input type="text" name="user_comment" class="form-control @error('user_comment') is-invalid @enderror" placeholder="Comments" required>
                                            </div>
                                            @error('user_comment')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Working Hours</label>
                                            <div class="input-group mb-3">
                                                <input type="text" name="working_hours" class="form-control @error('working_hours') is-invalid @enderror" placeholder="Working Hours" required>
                                            </div>
                                            @error('working_hours')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label"></label>
                                            <input type="submit" name="submit" value="Submit" class="btn btn-success" style="position: relative;bottom: -20px;">
                                        </div>
                                    </div>
                                </div>
                                </form>
                                @endif
                                @if(count($task_comments))
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>S No</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Hours</th>
                                                <th>User Comment</th>
                                                <th>Admin Comment</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            
                                            @foreach($task_comments as $key => $value)
                                            <tr>
                                                <td>{{ $key + 1}}</td>
                                                <td>{{ date('d-m-Y h:i A', strtotime($value->start_date)) }}</td>
                                                <td>{{ date('d-m-Y h:i A', strtotime($value->end_date)) }}</td>
                                                <td>{{ $value->working_hours }}</td>
                                                <td>{{ $value->user_comment }}</td>
                                                <td>{{ $value->admin_comment }}</td>
                                                <td><a onclick="ViewTaskModel5('{{ $value->id }}')" class="btn btn-success" style="padding: 2px 6px;margin: 0px 1px;"><i class="ion ion-edit text-white"></i></a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                        @endif
                        
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
        $(document).ready(function () {

            $('#reopen_check').on('click', function () {
                $("#reopen_box").show();
                $("#reopen_button").show();
                $("#reopen_check").hide();
            });
            
            $('#task_status').on('change', function () {

                var status_value = this.value;
                var task_id = "{{ $task->id }}";

                $("#status_update_box").hide();
                $("#status_update_button").hide();

                if (status_value == "user_completed" || status_value == "rejected") {
                    $("#status_update_box").show();
                    $("#status_update_button").show();
                }
                else
                {
                    $("#status_update_box").hide();
                    $("#status_update_button").hide();
                }
                
            });

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
  <script type="text/javascript">

    function ViewTaskModel(ref) {
        var ele = ref;
        // alert(ele);
        $.ajax({
            url: '{{ route('sub_admin.your.model.render') }}',
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

<script type="text/javascript">
    function ViewTaskModel5(ref) {
        var ele = ref;
        // alert(ele);
        if (ref) {
            $('#myModal7').modal('show');
            $("#task_comment_id").val(ref);
        }
    }
</script>
<div class="modal fade" id="myModal7">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update Comments</h4>
        <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
      </div>
      <div class="modal-body" id="load_html">
        <form action="{{ route('your.task.details.time.update') }}" method="post">
            @csrf
            <input type="hidden" name="task_comment_id" id="task_comment_id">
            <!-- <input type="text" name="working_hours_1"> -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Working Hours</label>
                        <div class="input-group mb-3">
                            <input type="text" name="working_hours_1" class="form-control @error('working_hours_1') is-invalid @enderror" placeholder="Working Hours" required>
                        </div>
                        @error('working_hours_1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <textarea rows="6" name="comment_model" style="width: 100%;" required></textarea>
                </div>
                <div class="col-md-4">
                    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                </div>
            </div>
        </form>
      </div>
      <!-- <div class="modal-footer">
        <span class="btn btn-danger" data-bs-dismiss="modal">Close</span>
      </div> -->
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

@endsection