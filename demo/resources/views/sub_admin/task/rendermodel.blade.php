<section class="content">

          <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            @include('layouts.flash-message')
                            @if($task->task_main->task_type == "recurring")
                            <?php
                                $total = $task->task_details;
                                // dd($total);
                            ?>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h4 class="box-title mt-40">General Info</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Task Created by</div>
                                            <div class="col-md-6 col_padding">- {{ $task->task_main->assign_details->name }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Task Name</div>
                                            <div class="col-md-6 col_padding">- {{ $task->task_main->name }}</div>
                                        </div>
                                    </div>
                                    <?php
                                        if ($task->task_main->date_type == "7") {
                                            $requ_date = "Weekly";
                                        }
                                        elseif ($task->task_main->date_type == "15") {
                                            $requ_date = "15 Days";
                                        }
                                        elseif ($task->task_main->date_type == "30") {
                                            $requ_date = "Monthly";
                                        }
                                        elseif ($task->task_main->date_type == "90") {
                                            $requ_date = "Quarterly";
                                        }
                                        elseif ($task->task_main->date_type == "365") {
                                            $requ_date = "Yearly";
                                        }
                                        
                                    ?>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Task Type</div>
                                            <div class="col-md-6 col_padding">
                                                @if($task->task_main->task_type == "custom")
                                                    - Single
                                                @else
                                                    - Recurring <span style="font-size: 10px;">({{ $requ_date }})</span>
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
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Description</div>
                                            <div class="col-md-6 col_padding">{!! $task->task_main->description !!}</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Status</div>
                                            <div class="col-md-6 col_padding">
                                                @if($task->status == "pending")
                                                    <span style="color: red;">Pending</span>
                                                @elseif($task->status == "payment_process")
                                                    <span style="color: #f70fdd;">Payment Progress</span>
                                                @elseif($task->status == "request_to_coordinar_completed")
                                                    <span style="color: #006c19;">Project Coordinar To be Apprd</span>
                                                @elseif($task->status == "inprogress")
                                                    <span style="color: #f7a20f;">In Progress</span>
                                                @elseif($task->status == "user_completed")
                                                    <span style="color: #14bcfc;">Completed To be Apprd</span>
                                                @elseif($task->status == "over_due")
                                                    <span style="color: red;">Over Due</span>
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
                                    
                                    
                                </div>

                                <form action="{{ route('sub_admin.recur.task.status.update') }}" method="post">
                                @csrf
                                @if($task->status == "pending" || $task->status == "inprogress" || $task->status == "rejected" || $task->status == "user_completed")
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 col_padding">
                                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                                                <label>Status  Update</label>
                                                <select class="form-control @error('status') is-invalid @enderror" name="status" id="task_status">
                                                    <option> Select Status</option>
                                                    <option value="completed">Completed</option>
                                                    <option value="recommend_to_admin">Recommend to Admin</option>
                                                    <option value="rejected">Rejected</option>
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
                            @endif
                            
                        </div>
                    </div>              
                </div>
            </div>
        </div>

</section>

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

                if (status_value == "recommend_to_admin" || status_value == "rejected" || status_value == "completed") {
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