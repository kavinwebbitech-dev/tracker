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
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
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
                        <div class="row">
                            @include('layouts.flash-message')
                            
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h4 class="box-title mt-40">General Info</h4>
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;">
                                    <div class="col-md-4 col_padding">Task Created by</div>
                                    <div class="col-md-4 col_padding">{{ $task->task_main->assign_details->name ?? '' }}</div>
                                </div>
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;">
                                    <div class="col-md-4 col_padding">Task Name</div>
                                    <div class="col-md-4 col_padding">{{ $task->task_main->name ?? '' }}</div>
                                </div>
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;">
                                    <div class="col-md-4 col_padding">Description</div>
                                    <div class="col-md-4 col_padding">{!! $task->task_main->description !!}</div>
                                </div>
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;">
                                    <div class="col-md-4 col_padding">Start Date</div>
                                    <div class="col-md-4 col_padding">{{ $task->start_date }}</div>
                                </div>
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;">
                                    <div class="col-md-4 col_padding">End Date</div>
                                    <div class="col-md-4 col_padding">{{ $task->end_data }}</div>
                                </div>
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;">
                                    <div class="col-md-4 col_padding">Create Date</div>
                                    <div class="col-md-4 col_padding">{{ date('d-m-Y - h:i A', strtotime($task->created_at)) }}</div>
                                </div>
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;">
                                    <div class="col-md-4 col_padding">Points</div>
                                    <div class="col-md-4 col_padding">
                                        @if($task->points == "+1")
                                            On time Complete (+1)
                                        @elseif($task->points == "-1")
                                            Over Due (-1)
                                        @endif
                                    </div>
                                </div>
                                @if($task->completed_date)
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;">
                                    @if($task->status == "reopen")
                                    <div class="col-md-4 col_padding">Reopen Date</div>
                                    @else
                                    <div class="col-md-4 col_padding">Completed Date</div>
                                    @endif
                                    <div class="col-md-4 col_padding">{{ date('d-m-Y - h:i A', strtotime($task->completed_date)) }}</div>
                                </div>
                                @endif
                                <form action="{{ route('admin.task.details.update') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="task_details_id" value="{{ $task->id }}">
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;">
                                    <div class="col-md-4 col_padding">Status</div>
                                    <div class="col-md-4 col_padding">
                                        @if($task->status == "completed" || $task->status == "canceled")
                                            @if($task->status == "completed")
                                                Completed
                                            @elseif($task->status == "canceled")
                                                Canceled
                                            @endif
                                        @else
                                            <select class="form-control @error('status') is-invalid @enderror" name="status" id="task_status">
                                                <option value="completed" @if($task->status == "completed") selected @endif>Closed</option>
                                                <option value="rejected" @if($task->status == "rejected") selected @endif>Rejected</option>
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                @if($task->user_comments)
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;">
                                    <div class="col-md-4 col_padding">Staff Comments</div>
                                    <div class="col-md-4 col_padding">{{ $task->user_comments }}</div>
                                </div>
                                @endif
                                @if($task->admin_comments)
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;">
                                    <div class="col-md-4 col_padding">Admin Comments</div>
                                    <div class="col-md-4 col_padding">{{ $task->admin_comments }}</div>
                                </div>
                                @endif
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;display: none;" id="status_update_box">
                                    <div class="col-md-4 col_padding">Your Comment</div>
                                    <div class="col-md-4 col_padding">
                                        <textarea rows="6" name="user_comment" style="width: 100%;"></textarea>
                                    </div>
                                </div>
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;display: none;" id="status_update_button">
                                    <div class="col-md-4 col_padding">
                                        <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                                    </div>
                                </div>
                                </form>
                                {{-- @if($task->status == "completed" || $task->status == "canceled")
                                <span class="btn btn-warning" style="margin-top: 10px;" id="reopen_check"> Reopen</span>
                                @endif --}}
                                </form>


                                <form method="post" action="{{ route('admin.task.reopen') }}">
                                    @csrf
                                    <div class="row" style="border-bottom: 1px solid #d1d1d1;display: none;" id="reopen_box">
                                        <div class="col-md-4 col_padding">Reopen Comment</div>
                                        <div class="col-md-4 col_padding">
                                            <textarea rows="6" name="user_comment" style="width: 100%;"></textarea>
                                        </div>
                                        <input type="hidden" name="reopen_status" value="reopen">
                                        <input type="hidden" name="reopen_task" value="{{ $task->id }}">
                                    </div>
                                    <div class="row" style="border-bottom: 1px solid #d1d1d1;display: none;" id="reopen_button">
                                        <div class="col-md-4 col_padding">
                                            <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                                        </div>
                                    </div>
                                </form>

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
  <script src="<?php echo url('');?>/public/admin/js/vendors.min.js"></script>
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

                if (status_value == "progress" || status_value == "user_completed" || status_value == "completed" || status_value == "rejected" || status_value == "canceled" || status_value == "hold") {
                    $("#status_update_box").show();
                    $("#status_update_button").show();
                }
                else
                {
                    $("#status_update_box").hide();
                    $("#status_update_button").hide();
                }
                
            });
        });
  </script>
@endsection