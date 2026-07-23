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
            .delete
            {
                width: 200px;
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
                            
                            @if($task->status == "completed" || $task->status == "reopen")
                            
                            @else
                                @if(Auth::user()->id == $task->project_follow_up)
                                    <a href="{{ route('sub_admin.change.coordinar.task', $task->id) }}" class="delete btn btn-primary" data-confirm="Are you Sure Completed Project Follow Up?">Project Follow Up Completed</a>
                                @else
                                    <a href="{{ route('sub_admin.change.payment.task', $task->id) }}" class="delete btn btn-primary" data-confirm="Are you Sure Completed Payment Follow Up?">Payment Follow Up Completed</a>
                                @endif
                            @endif
                            @if($task->task_type == "recurring")
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
                                            <div class="col-md-6 col_padding">- {{ $task->staff_details->name ?? '' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Task Name</div>
                                            <div class="col-md-6 col_padding">- {{ $task->name }}</div>
                                        </div>
                                    </div>
                                    <?php
                                        if ($task->date_type == "7") {
                                            $requ_date = "Weekly";
                                        }
                                        elseif ($task->date_type == "15") {
                                            $requ_date = "15 Days";
                                        }
                                        elseif ($task->date_type == "30") {
                                            $requ_date = "Monthly";
                                        }
                                        elseif ($task->date_type == "90") {
                                            $requ_date = "Quarterly";
                                        }
                                        elseif ($task->date_type == "365") {
                                            $requ_date = "Yearly";
                                        }
                                        
                                    ?>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Task Type</div>
                                            <div class="col-md-6 col_padding">
                                                @if($task->task_type == "custom")
                                                    - Custom
                                                @else
                                                    - Recurring <span style="font-size: 10px;">({{ $requ_date }})</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Assign Name</div>
                                            <div class="col-md-6 col_padding">- {{ $task->staff_details->name ?? '' }}</div>
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
                                            <div class="col-md-6 col_padding">- {{ date('d-m-Y', strtotime($task->end_date)) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Description</div>
                                            <div class="col-md-6 col_padding">{!! $task->description !!}</div>
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

                                @if($task->status != "completed")
                                <form action="{{ route('sub_admin.recur.task.status.update') }}" method="post">
                                    @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Status</div>
                                            <div class="col-md-6 col_padding">
                                                <select class="form-control @error('status') is-invalid @enderror" name="status" id="task_status">
                                                    <option>Select Status</option>
                                                    <option value="completed" @if($task->status == "completed") selected @endif>Closed</option>
                                                    <option value="rejected" @if($task->status == "rejected") selected @endif>Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="display: none;" id="status_update_box">
                                    <div class="col-md-6 col_padding">
                                        <input type="hidden" name="task_id_reu" value="{{ $task->id }}">
                                        <textarea rows="6" name="user_comment" style="width: 100%;"></textarea>
                                    </div>
                                </div>
                                <div class="row" style="border-bottom: 1px solid #d1d1d1;display: none;" id="status_update_button">
                                    <div class="col-md-4 col_padding">
                                        <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                                    </div>
                                </div>
                                
                                </form>
                                @endif
                                
                            </div>
                            @else
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
                                            <div class="col-md-6 col_padding">Project Name</div>
                                            <div class="col-md-6 col_padding">- {{ $task->project_details->name ?? '' }}</div>
                                        </div>
                                    </div>
                                    <?php
                                        if ($task->date_type == "7") {
                                            $requ_date = "Weekly";
                                        }
                                        elseif ($task->date_type == "15") {
                                            $requ_date = "15 Days";
                                        }
                                        elseif ($task->date_type == "30") {
                                            $requ_date = "Monthly";
                                        }
                                        elseif ($task->date_type == "90") {
                                            $requ_date = "Quarterly";
                                        }
                                        elseif ($task->date_type == "365") {
                                            $requ_date = "Yearly";
                                        }
                                        
                                    ?>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Task Type</div>
                                            <div class="col-md-6 col_padding">
                                                @if($task->task_type == "custom")
                                                    - Single
                                                @else
                                                    - Recurring <span style="font-size: 10px;">({{ $requ_date }})</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Create Date</div>
                                            <div class="col-md-6 col_padding">- {{ date('d-m-Y H:i:s A', strtotime($task->created_at)) }}</div>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6 col_padding">Description</div>
                                            <div class="col-md-6 col_padding">{!! $task->description !!}</div>
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
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                        <?php
                                            $project_amount = 0;
                                            $user_details = $task->project_follow_up_details;
                                            $user_details1 = $task->payment_follow_up_details;
                                        ?>
                                          @if($task->task_staff)
                                          @foreach($task->task_staff as $key => $staff)
                                            <li><a href="#activity{{ $key }}" data-bs-toggle="tab" @if($key == 0) class="active" @endif >{{ $staff->staff_name }}</a></li>
                                          @endforeach
                                            @if(Auth::user()->id == $task->project_follow_up)
                                            @if(isset($user_details) && $user_details)
                                            <li><a href="#activity10" data-bs-toggle="tab">Follow Up Comment</a></li>
                                            @endif
                                            @endif
                                            @if(Auth::user()->id == $task->payment_follow_up)
                                            @if(isset($user_details1) && $user_details1)
                                            <li><a href="#activity11" data-bs-toggle="tab">Payment Comment</a></li>
                                            @endif
                                            @else
                                            @if(isset($user_details) && $user_details)
                                            <li><a href="#activity10" data-bs-toggle="tab">Follow Up Comment</a></li>
                                            @endif
                                            @if(isset($user_details1) && $user_details1)
                                            <li><a href="#activity11" data-bs-toggle="tab">Payment Comment</a></li>
                                            @endif
                                            @endif
                                          @endif
                                        </ul>

                                        <div class="tab-content">
                                          <!-- /.tab-pane -->
                                          <?php
                                            $tasf_task = $task->task_staff;
                                            // dd($tasf_task);
                                          ?>
                                          @if(count($tasf_task))
                                          @foreach($tasf_task as $key1 => $staff)

                                          <div class="@if($key1 == 0) active @endif tab-pane" id="activity{{ $key1 }}">       
                                                <?php
                                                    $add_hours = 0;
                                                    $staff_comments = \App\Models\TaskComment::where('task_id', $staff->task_id)->where('staff_id', $staff->staff_id)->get();
                                                    // dd($staff_comments);
                                                    foreach ($staff_comments as $key2 => $value1) {
                                                        $add_hours += $value1->working_hours;
                                                    }
                                                    $salary = $staff->staff_details->salary;
                                                    $hour_rate = $salary / 200;
                                                    $hour_convert = explode('.', $add_hours);
                                                    // dd($hour_convert[1]);
                                                    if (isset($hour_convert[1]) && $hour_convert[1]) {
                                                        $calculate = $hour_convert[0] + ($hour_convert[1] / 60);
                                                    }
                                                    else
                                                    {
                                                        $calculate = $hour_convert[0];
                                                    }
                                                    
                                                    $added_rate = round($calculate, 2);
                                                    $total_amount = $added_rate * $hour_rate;
                                                    $project_amount += $total_amount;
                                                ?>
                                                <div class="box b-1 no-shadow">
                                                  <div class="media bb-1 border-fade">
                                                    <img class="avatar avatar-lg" src="<?php echo url('');?>/public/admin_assets/images/avatar/3.jpg" alt="...">
                                                    <div class="media-body">
                                                      <p>
                                                        <strong>{{ $staff->staff_details->name ?? '' }}</strong>
                                                        <time class="float-end text-fade" datetime="2017">Start Date: {{ date('d-m-Y', strtotime($staff->start_date)) }}</time>
                                                      </p>
                                                      <p>
                                                        <small>{{ $staff->staff_details->role ?? '' }}</small>
                                                        <time class="float-end text-fade" datetime="2017">End Date: {{ date('d-m-Y', strtotime($staff->end_date)) }}</time>
                                                      </p>
                                                      {{-- <p>
                                                        <small>Total Hours: {{ $add_hours }}</small>
                                                        <time class="float-end text-fade" datetime="2017">Total Amount: {{ $total_amount }}</time>
                                                      </p> --}}
                                                      <p>
                                                        <small>Task Status: 
                                                            @if($staff->status == "pending")
                                                                <span style="color: red;">Pending</span>
                                                            @elseif($staff->status == "payment_process")
                                                                <span style="color: #f70fdd;">Payment Progress</span>
                                                            @elseif($staff->status == "request_to_coordinar_completed")
                                                                <span style="color: #006c19;">Project Coordinar To be Apprd</span>
                                                            @elseif($staff->status == "inprogress")
                                                                <span style="color: #f7a20f;">In Progress</span>
                                                            @elseif($staff->status == "user_completed")
                                                                <span style="color: #14bcfc;">Completed To be Apprd</span>
                                                            @elseif($staff->status == "over_due")
                                                                <span style="color: red;">Over Due</span>
                                                            @elseif($staff->status == "recommend_to_admin")
                                                                <span style="color: #cf2bc4;">Recomment to Completed</span>
                                                            @elseif($staff->status == "completed")
                                                                <span style="color: #2de033;">Closed</span>
                                                            @elseif($staff->status == "canceled")
                                                                <span style="color: #f21f35;">Canceled</span>
                                                            @elseif($staff->status == "rejected")
                                                                <span style="color: #1ff2e8;">Rejected</span>
                                                            @elseif($staff->status == "hold")
                                                                <span style="color: red;">Hold</span>
                                                            @elseif($staff->status == "reopen")
                                                                <span style="color: red;">Reopen</span>
                                                            @endif
                                                        </small>
                                                        @if($staff->status == "completed" || $staff->status == "canceled")
                                                        @else
                                                        <time class="float-end btn btn-primary" datetime="2017" onclick='ViewTaskModel1("{{ $staff->id }}","{{ $staff->task_id }}","{{ $staff->staff_id }}")' style="padding: 5px 10px;font-size: 12px;">Change Status
                                                        </time>
                                                        @endif
                                                      </p>
                                                    </div>
                                                  </div>
                                                </div>

                                                @if(count($staff_comments))
                                                <div class="table-responsive" >
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>S No</th>
                                                                <th>Name</th>
                                                                <th>Start Date</th>
                                                                <th>End Date</th>
                                                                <th>Working Hours</th>
                                                                <th>User Comment</th>
                                                                <th>Admin Comment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            
                                                            @foreach($staff_comments as $key3 => $value)
                                                            <tr>
                                                                <td>{{ $key3 + 1}}</td>
                                                                <td>{{ $staff->staff_details->name ?? '' }}</td>
                                                                <td>{{ date('d-m-Y h:i A', strtotime($value->start_date)) }}</td>
                                                                <td>{{ date('d-m-Y h:i A', strtotime($value->end_date)) }}</td>
                                                                <td>{{ $value->working_hours }}</td>
                                                                <td>{{ $value->user_comment }}</td>
                                                                <td>{{ $value->admin_comment }}</td>
                                                                <td>
                                                                    <p style="display: flex;margin-bottom: 0px;">
                                                                        <a onclick="ViewTaskModel5('{{ $value->id }}')" class="btn btn-success" style="padding: 2px 6px;margin: 10px 1px;"><i class="ion ion-edit text-white"></i></a>

                                                                        @if($value->admin_comment == "")
                                                                        <a onclick='ViewTaskModel("{{$value->id}}")' class="btn btn-success" style="padding: 2px 6px;margin: 10px 1px;"><i class="ti-eye text-white"></i></a>
                                                                        @endif
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @endif
                                          </div>
                                          @endforeach
                                          @endif
                                          @if(Auth::user()->id == $task->project_follow_up)
                                          @if(isset($user_details) && $user_details)
                                          <div class="tab-pane" id="activity10">
                                            
                                            <div class="box b-1 no-shadow">
                                                <div class="media bb-1 border-fade">

                                                    @if($user_details->profile_picture)
                                                        <img class="rounded-circle" src="<?php echo url('');?>/public/profile/{{$user_details->profile_picture}}" style="width: 70px;" alt="User Avatar">
                                                    @elseif($user_details->gender == "female")
                                                        <img class="rounded-circle" src="<?php echo url('');?>/public/admin_assets/images/avatar5.png" alt="User Avatar" style="width: 70px;">
                                                    @else
                                                        <img class="rounded-circle" src="<?php echo url('');?>/public/admin/images/user3-128x128.jpg" alt="User Avatar">
                                                    @endif
                                                    <div class="media-body">
                                                        
                                                        <p>
                                                            <strong>{{ $user_details->name ?? '' }}</strong>
                                                            @if(Auth::user()->id == $task->project_follow_up)
                                                                <time class="float-end btn btn-primary" datetime="2017" onclick='ProjectFollowUp()' style="padding: 5px 10px;font-size: 12px;">Change Status
                                                                </time>
                                                            @endif
                                                        </p>
                                                        
                                                        <p>
                                                            <small>Project Coordinar</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                $task_follow_comments =  \App\Models\TaskFollowComment::where('follower_id', $user_details->id)->where('task_id', $task->id)->get();
                                            ?>
                                            @if(count($task_follow_comments))
                                                <div class="table-responsive" >
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>S No</th>
                                                                <th>Name</th>
                                                                <th>Comments</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($task_follow_comments as $key => $value)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $value->staff_details->name ?? '' }}</td>
                                                                <td>{{ $value->comments ?? '' }}</td>
                                                                <td>{{ $value->status ?? '' }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                          </div>
                                          @endif
                                          @endif
                                          @if(Auth::user()->id == $task->payment_follow_up)
                                          @if(isset($user_details1) && $user_details1)
                                          <div class="tab-pane" id="activity11">
                                            
                                            <div class="box b-1 no-shadow">
                                                <div class="media bb-1 border-fade">

                                                    @if($user_details1->profile_picture)
                                                        <img class="rounded-circle" src="<?php echo url('');?>/public/profile/{{$user_details1->profile_picture}}" alt="User Avatar" style="width: 70px;">
                                                    @elseif($user_details1->gender == "female")
                                                        <img class="rounded-circle" src="<?php echo url('');?>/public/admin_assets/images/avatar5.png" alt="User Avatar" style="width: 70px;">
                                                    @else
                                                        <img class="rounded-circle" src="<?php echo url('');?>/public/admin/images/user3-128x128.jpg" alt="User Avatar">
                                                    @endif
                                                    <div class="media-body">
                                                        
                                                        <p>
                                                            <strong>{{ $user_details1->name ?? '' }}</strong>
                                                            @if(Auth::user()->id == $task->payment_follow_up)
                                                                <time class="float-end btn btn-primary" datetime="2017" onclick='PaymentFollowUp()' style="padding: 5px 10px;font-size: 12px;">Change Status
                                                            </time>
                                                            @endif
                                                        </p>
                                                        <p>
                                                            <small>Payment Follower</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php

                                                $payment_follow_comments =  \App\Models\PaymentComment::where('follower_id', $user_details1->id)->where('task_id', $task->id)->get();
                                            ?>
                                            @if(isset($payment_follow_comments) && count($payment_follow_comments))
                                                <div class="table-responsive" >
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>S No</th>
                                                                <th>Date</th>
                                                                <th>Amount</th>
                                                                <th>Comments</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($payment_follow_comments as $key => $value)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ date('d-m-Y', strtotime($value->task_date)) }}</td>
                                                                <td>{{ $value->amount ?? '' }}</td>
                                                                <td>{{ $value->comments ?? '' }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                           </div>
                                           @endif
                                          @else
                                          @if(isset($user_details) && $user_details)
                                          <div class="tab-pane" id="activity10">
                                            
                                            <div class="box b-1 no-shadow">
                                                <div class="media bb-1 border-fade">

                                                    @if($user_details->profile_picture)
                                                        <img class="rounded-circle" src="<?php echo url('');?>/public/profile/{{$user_details->profile_picture}}" style="width: 70px;" alt="User Avatar">
                                                    @elseif($user_details->gender == "female")
                                                        <img class="rounded-circle" src="<?php echo url('');?>/public/admin_assets/images/avatar5.png" alt="User Avatar" style="width: 70px;">
                                                    @else
                                                        <img class="rounded-circle" src="<?php echo url('');?>/public/admin/images/user3-128x128.jpg" alt="User Avatar">
                                                    @endif
                                                    <div class="media-body">
                                                        
                                                        <p>
                                                            <strong>{{ $user_details->name ?? '' }}</strong>
                                                            @if(Auth::user()->id == $task->project_follow_up)
                                                                <time class="float-end btn btn-primary" datetime="2017" onclick='ProjectFollowUp()' style="padding: 5px 10px;font-size: 12px;">Change Status
                                                                </time>
                                                            @endif
                                                        </p>
                                                        
                                                        <p>
                                                            <small>Project Coordinar</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                $task_follow_comments =  \App\Models\TaskFollowComment::where('follower_id', $user_details->id)->where('task_id', $task->id)->get();
                                            ?>
                                            @if(count($task_follow_comments))
                                                <div class="table-responsive" >
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>S No</th>
                                                                <th>Name</th>
                                                                <th>Comments</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($task_follow_comments as $key => $value)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $value->staff_details->name ?? '' }}</td>
                                                                <td>{{ $value->comments ?? '' }}</td>
                                                                <td>{{ $value->status ?? '' }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                          </div>
                                          @endif
                                          @if(isset($user_details1) && $user_details1)
                                          <div class="tab-pane" id="activity11">
                                            
                                            <div class="box b-1 no-shadow">
                                                <div class="media bb-1 border-fade">

                                                    @if($user_details1->profile_picture)
                                                        <img class="rounded-circle" src="<?php echo url('');?>/public/profile/{{$user_details1->profile_picture}}" alt="User Avatar" style="width: 70px;">
                                                    @elseif($user_details1->gender == "female")
                                                        <img class="rounded-circle" src="<?php echo url('');?>/public/admin_assets/images/avatar5.png" alt="User Avatar" style="width: 70px;">
                                                    @else
                                                        <img class="rounded-circle" src="<?php echo url('');?>/public/admin/images/user3-128x128.jpg" alt="User Avatar">
                                                    @endif
                                                    <div class="media-body">
                                                        
                                                        <p>
                                                            <strong>{{ $user_details1->name ?? '' }}</strong>
                                                            @if(Auth::user()->id == $task->payment_follow_up)
                                                                <time class="float-end btn btn-primary" datetime="2017" onclick='PaymentFollowUp()' style="padding: 5px 10px;font-size: 12px;">Change Status
                                                            </time>
                                                            @endif
                                                        </p>
                                                        <p>
                                                            <small>Payment Follower</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php

                                                $payment_follow_comments =  \App\Models\PaymentComment::where('follower_id', $user_details1->id)->where('task_id', $task->id)->get();
                                            ?>
                                            @if(isset($payment_follow_comments) && count($payment_follow_comments))
                                                <div class="table-responsive" >
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>S No</th>
                                                                <th>Date</th>
                                                                <th>Amount</th>
                                                                <th>Comments</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($payment_follow_comments as $key => $value)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ date('d-m-Y', strtotime($value->task_date)) }}</td>
                                                                <td>{{ $value->amount ?? '' }}</td>
                                                                <td>{{ $value->comments ?? '' }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                           </div>
                                           @endif
                                          @endif
                                          <!-- /.tab-pane -->
                                        </div>
                                        <!-- /.tab-content -->
                                    </div>
                                </div>
                            @endif
                            <style type="text/css">
                                .nav-tabs-custom > .nav-tabs > li {
                                    width: 20%;
                                }
                            </style>
                            
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

    function ViewTaskModel(ref) {
        var ele = ref;
        // alert(ele);
        if (ref) {
            $('#modal-default').modal('show');
            $("#comment_id").val(ref);
        }
    }

    function ProjectFollowUp() {
        $('#modal-default7').modal('show');
    }
    function PaymentFollowUp() {
        $('#modal-default2').modal('show');
    }

    function ViewTaskModel1(id, task_id, staff_id) {
        var task_id = task_id;
        var staff_task_id = id;
        var staff_id = staff_id;
        // alert(ele);
        if (task_id) {
            $('#modal-default1').modal('show');
            $("#staff_task_id").val(staff_task_id);
            $("#task_id").val(task_id);
            $("#staff_id").val(staff_id);
        }
    }

    function ViewRecurTaskModel(ref) {
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
                $('#modal-default1').modal('show');
            }
        });
    }

    </script>
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

                if (status_value == "completed" || status_value == "rejected" || status_value == "canceled") {
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
        function ViewTaskModel5(ref) {
            var ele = ref;
            // alert(ele);
            if (ref) {
                $('#myModal7').modal('show');
                $("#task_comment_id").val(ref);
            }
        }
    </script>

    <div class="modal fade" id="modal-default7">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Change Status</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html">
            <form action="{{ route('sub_admin.follow.up.comments.project') }}" method="post">
                @csrf
                <input type="hidden" name="task_id" id="task_id" value="{{ $task->id }}">
                <input type="hidden" name="project_id" id="project_id" value="{{ $task->project_id }}">
                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <select class="form-control" name="staff_status" id="staff_status">
                            <option value="">Select Staff</option>
                            @if($tasf_task)
                            @foreach($tasf_task as $key => $value)
                                <option value="{{ $value->staff_id }}">{{ $value->staff_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4" id="point_en">
                        <div class="form-group">
                            <label class="form-label">Correction</label>
                            <div class="form-group ichack-input">
                                <label>
                                  <input type="radio" class="flat-red" id="previous" value="Correction" name="previous" required>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="point_en">
                        <div class="form-group">
                            <label class="form-label">Rework</label>
                            <div class="form-group ichack-input">
                                <label>
                                  <input type="radio" class="flat-red" id="previous" value="Rework" name="previous" required>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="point_en">
                        <div class="form-group">
                            <label class="form-label">Completed</label>
                            <div class="form-group ichack-input">
                                <label>
                                  <input type="radio" class="flat-red" id="previous" value="Completed" name="previous" required>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <textarea rows="6" name="admin_comment_model" style="width: 100%;" placeholder="Comments...."></textarea>
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

    <div class="modal fade" id="myModal7">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Update Comments</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html">
            <form action="{{ route('sub_admin.task.details.time.update') }}" method="post">
                @csrf
                <input type="hidden" name="task_comment_id" id="task_comment_id">
                <!-- <input type="text" name="working_hours_1"> -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Working Hours</label>
                            <div class="input-group in-bord mb-3">
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
                        <textarea rows="6" name="comment_model" style="width: 100%;"></textarea>
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

    <div class="modal fade" id="modal-default2">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Change Status</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html">
            <form action="{{ route('sub_admin.follow.up.project.payment') }}" method="post">
                @csrf
                <input type="hidden" name="task_id" id="task_id" value="{{ $task->id }}">
                <input type="hidden" name="project_id" id="project_id" value="{{ $task->project_id }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Enter Date</label>
                            <div class="input-group in-bord mb-3">
                                <input type="date" name="task_date" class="form-control @error('task_date') is-invalid @enderror" placeholder="Enter Amount" required>
                            </div>
                            @error('task_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Enter Amount</label>
                            <div class="input-group in-bord mb-3">
                                <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Enter Amount" required>
                            </div>
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <textarea rows="6" name="admin_comment_model" style="width: 100%;" placeholder="Comments...."></textarea>
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

    <div class="modal fade" id="modal-default1">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Change Status</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html">
            <form action="{{ route('sub_admin.task.update.comment') }}" method="post">
                @csrf
                <input type="hidden" name="task_id" id="task_id">
                <input type="hidden" name="staff_task_id" id="staff_task_id">
                <input type="hidden" name="staff_id" id="staff_id">
                <div class="row">
                    <div class="col-md-6" style="margin-bottom: 10px;">
                        <select class="form-control" name="staff_status" id="staff_status" onchange="pointsOpen()">
                            <option value="">Select Status</option>
                            <option value="recommend_to_admin">Recommend to Admin</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-12" id="point_en" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">Points</label>
                            <div class="form-group ichack-input">
                                <label>
                                  <input type="checkbox" class="flat-red" id="previous" name="previous">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <textarea rows="6" name="admin_comment_model" style="width: 100%;" placeholder="Comments...."></textarea>
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

    <div class="modal fade" id="modal-default">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Admin Comment</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html">
            <form action="{{ route('admin.task.add.comment') }}" method="post">
                @csrf
                <input type="hidden" name="comment_id" id="comment_id">
                <div class="row">
                    <div class="col-md-12">
                        <textarea rows="6" name="admin_comment_model" style="width: 100%;" required></textarea>
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
    <script type="text/javascript">
        function pointsOpen()
        {
            var staff_status1 = $("#staff_status").val();

            if (staff_status1 == "completed") {
                $("#point_en").show();
            }
            else
            {
                $("#point_en").hide();
            }

        }
    </script>
@endsection