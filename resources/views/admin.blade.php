@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
      <div class="container-full">
        <!-- Main content -->
        <?php
            $total_sub_admin     = \App\Models\User::where('admin_id', Auth::user()->id)->where('user_type', 'sub_admin')->get();
            $total_staff     = \App\Models\User::where('user_type', 'staff')->get();
            $total_task      = \App\Models\Task::get();
            $pending         = \App\Models\Task::where('status', 'pending')->get();
            $staff_com       = \App\Models\Task::where('status', 'user_completed')->get();
            $progress        = \App\Models\Task::where('status', 'inprogress')->get();
            $completed       = \App\Models\Task::where('status', 'completed')->get();
            $rejected        = \App\Models\Task::where('status', 'rejected')->get();
            $recurring       = \App\Models\Task::where('task_type', 'recurring')->get();
            $over_due        = \App\Models\Task::where('task_type', 'over_due')->get();
            // dd();
        ?>
        <style>
            .box_color1
            {
                background-color: #9b7693;
            }
            .box_color2
            {
                background-color: #68928c;
            }
            .box_color3
            {
                background-color: #5fbc87;
            }
            .box_color4
            {
                background-color: #e76b48;
            }
            .box_color5
            {
                background-color: #007da5;
            }
            .box_color6
            {
                background-color: #ff7096;
            }
        </style>
        <section class="content">
            <div class="row">
                <div class="col-lg-3 col-12">
                    <a href="{{ route('admin.sub.admin.view') }}" class="box box_color6">
                        <div class="box-body">
                            <span class="text-white icon-Equalizer fs-40"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path3"></span></span>
                            <div class="text-white fw-600 fs-18 mb-2 mt-5">Total Sub Admin</div>
                            <div class="text-white fs-24 fw-800">{{ count($total_sub_admin) }}</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-12">
                    <a href="{{ route('admin.sub.staff.view') }}" class="box box_color1">
                        <div class="box-body">
                            <span class="text-white icon-User fs-40"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path3"></span></span>
                            <div class="text-white fw-600 fs-18 mb-2 mt-5">Total Staff</div>
                            <div class="text-white fs-24 fw-800">{{ count($total_staff) }}</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-12">
                    <a href="{{ route('second.admin.task.view') }}" class="box box_color2">
                        <div class="box-body">
                            <span class="text-white mdi mdi-ticket-confirmation fs-30"><span class="path1"></span><span class="path2"></span></span>
                            <div class="text-white fw-600 fs-18 mb-2 mt-5">Total Task</div>
                            <div class="text-white fs-24 fw-800">{{ count($total_task) }}</div>
                        </div>
                    </a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-3 col-12">
                    <a href="{{ url('/') }}/admin/task/view-task?&task_type=custom&task_status=inprogress" class="box box_color3">
                        <div class="box-body">
                            <span class="text-white icon-Smile fs-40"><span class="path1"></span><span class="path2"></span></span>
                            <div class="text-white fw-600 fs-18 mb-2 mt-5">Total Progress Task</div>
                            <div class="text-white fs-24 fw-800">{{ count($progress) }}</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-12">
                    <a href="{{ url('/') }}/admin/task/view-task?&task_type=custom&task_status=over_due" class="box bg-danger bg-hover-danger">
                        <div class="box-body">
                            <span class="text-white icon-Smile fs-40"><span class="path1"></span><span class="path2"></span></span>
                            <div class="text-white fw-600 fs-18 mb-2 mt-5">Total Over Due Task</div>
                            <div class="text-white fs-24 fw-800">{{ count($over_due) }}</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-12">
                    <a href="{{ url('/') }}/admin/task/view-task?&task_type=custom&task_status=user_completed" class="box bg-info bg-hover-info">
                        <div class="box-body">
                            <span class="text-white icon-Chart-line fs-40"><span class="path1"></span><span class="path2"></span></span>
                            <div class="text-white fw-600 fs-18 mb-2 mt-5">Total Staff Completed</div>
                            <div class="text-white fs-24 fw-800">{{ count($staff_com) }}</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-12">
                    <a href="{{ url('/') }}/admin/task/view-task?&task_type=custom&task_status=completed" class="box bg-warning bg-hover-warning">
                        <div class="box-body">
                            <span class="text-white icon-Money fs-40"><span class="path1"></span><span class="path2"></span></span>
                            <div class="text-white fw-600 fs-18 mb-2 mt-5">Total Closed Task</div>
                            <div class="text-white fs-24 fw-800">{{ count($completed) }}</div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-12">
                    <a href="{{ url('/') }}/admin/task/view-task?&task_type=custom&task_status=rejected" class="box box_color4">
                        <div class="box-body">
                            <span class="text-white icon-Attachment1 fs-40"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></span>
                            <div class="text-white fw-600 fs-18 mb-2 mt-5">Total Rejected Task</div>
                            <div class="text-white fs-24 fw-800">{{ count($rejected) }}</div>
                        </div>
                    </a>
                </div>
                {{-- <div class="col-lg-3 col-12">
                    <a href="#" class="box box_color5">
                        <div class="box-body">
                            <span class="text-white mdi mdi-ticket-confirmation fs-30"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></span>
                            <div class="text-white fw-600 fs-18 mb-2 mt-5">Total Recurring Task</div>
                            <div class="text-white fs-24 fw-800">{{ count($recurring) }}</div>
                        </div>
                    </a>
                </div> --}}
                
            </div>

            
        </section>
        <!-- /.content -->
      </div>
</div>
@endsection
