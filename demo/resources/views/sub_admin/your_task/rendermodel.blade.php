
    <!-- Content Wrapper. Contains page content -->
    <div class="box">
        <div class="box-body">
            
            @include('layouts.flash-message')
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6 col_padding">Task Created by</div>
                        <div class="col-md-6 col_padding">- {{ $task->task_main->assign_details->name ?? '' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6 col_padding">Task Name</div>
                        <div class="col-md-6 col_padding">- {{ $task->task_main->name ?? '' }}</div>
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
                            @elseif($value->status == "over_due")
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
                <div class="col-md-6">
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
        });
  </script>
