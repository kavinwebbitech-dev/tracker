@extends('layouts.dashboard')


@section('meta_name') {{ "Task View" }} @stop

@section('content')


<style type="text/css">
    .count-icons{
        text-align: center;
    }
    .count-icons p{
        font-size: 16px;
        margin-bottom: 5px;
        font-weight: 400;
    }
    
    .count-icons .count-right-icon{
        margin-right: 10px;
    }
</style>
<style type="text/css">
    .col_padding
    {
        padding: 10px;
    }
    .modal-dialog
    {
        max-width: 800px;
    }
</style>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Estimate List</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">Task Request</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
            </div>
        </div>
        <style>
            .content1
            {
                font-size: 14px;
                font-weight: 400;
                color: #000;
                max-width: 250px;
                 display: -webkit-box;
                text-overflow: ellipsis;
                -webkit-line-clamp: 5;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
        <!-- Main content -->
        <section class="content">
          <div class="row">

            <div class="col-12">
                <div class="box">

                @include('layouts.flash-message')
                <!-- /.box-header -->
                <div class="box-body">
                    
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('admin.task.request.create') }}" class="btn btn-primary">
                                Create Task Estimate
                            </a>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-3">
                            <div class="input-group mb-3">
                                <select class="form-control select2 @error('client_id') is-invalid @enderror" name="client_id" id="client_id" onchange="ClientTaskSearch()">
                                    <option value=""> Select Client</option>
                                    @if($client_details)
                                    @foreach($client_details as $key => $details)
                                    <option value="{{ $details->id }}">{{ $details->client_details->fld_name }} ({{ $details->client_details->fld_phone }}) - {{ $details->client_details->fld_company_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    
                    <!-- <form action="{{ route('admin.projects.filter') }}" method="post"> -->
                    @csrf
 
                    
                    
                    <div class="table-responsive creative-table-wrapper">
                      <table class="table table-bordered table-hover display nowrap margin-top-10 w-p100 creative-table nowrap-table">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Client Name</th>
                                <th>Task Name</th>
                                <th>Date</th>
                                <th>Total Time</th>
                                <th>Total Amount</th>
                                <th>Approv Amount</th>
                                <th>Description</th>
                                <th class="noExport">Status</th>
                                <th class="noExport">Action</th>
                            </tr>
                        </thead>
                        
                        <tbody>
    @if($client_task)
    @foreach($client_task as $key => $value)

    <?php
        $total_time_cal = 0;
        $total_amount = 0;
        $total_amount1 = 0;

        $task_details = $value->client_task_details;
        $task_details1 = $value->client_task_approv;

        $add_hours = 0;
        $sum = strtotime('00:00:00');
        $totaltime = 0;

        foreach ($task_details as $key2 => $value1) {

            $working_time_h = number_format($value1->estimate_time, 2);

            $timeinsec = strtotime($working_time_h) - $sum;

            $totaltime += $timeinsec;

            $total_amount += $value1->total_amount;

            if ($value1->amount) {
                // code...
            }
            else
            {
                $update_appro = \App\Models\ClientTaskDetails::find($value1->id);

                $update_appro->total_amount = 0;

                $update_appro->save();
            }
        }

        foreach ($task_details1 as $key3 => $value2) {
            $total_amount1 += $value2->total_amount;
        }

        $total_time_cal += $totaltime;

        $h = intval($totaltime / 3600);

        $totaltime = $totaltime - ($h * 3600);

        $m = intval($totaltime / 60);

        $s = $totaltime - ($m * 60);

        $total_time = $h.':'.$m;
    ?>

    <tr 
        @if($value->status == "Approved") style="background: #90EE90;" 
        @elseif($value->status == "Closed") style="background: #f94381;" 
        @endif
    >

        <td class="text-muted fw-bold sorting_1">
            {{ $key + 1 }}
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-person-workspace text-primary"></i>

                <span class="fw-semibold">
                    {{ $value->user_details->name }}
                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <div class="cell-icon icon-name">
                    <i class="bi bi-folder-fill"></i>
                </div>

                <span class="fw-semibold">
                    {{ $value->name }}
                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-calendar-fill text-success"></i>

                <span class="fw-semibold">
                    {{ date('d-m-Y', strtotime($value->date)) }}
                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-clock-fill text-info"></i>

                <span class="fw-semibold">
                    {{ $total_time }}
                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-currency-rupee text-success"></i>

                <span class="fw-semibold">

                    @if($value->client_task_details[0]->amount_type == "fixed_amount")
                        @if($total_amount)
                            ₹ {{ number_format($total_amount, 2) }}
                        @endif
                    @else
                        @if($total_amount)
                            $ {{ number_format($total_amount, 2) }}
                        @endif
                    @endif

                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-cash-stack text-warning"></i>

                <span class="fw-semibold">

                    @if($value->client_task_details[0]->amount_type == "fixed_amount")
                        @if($total_amount1)
                            ₹ {{ number_format($total_amount1, 2) }}
                        @endif
                    @else
                        @if($total_amount1)
                            $ {{ number_format($total_amount1, 2) }}
                        @endif
                    @endif

                </span>

            </div>
        </td>

        <td>
            @if($value->description)

            <div class="icon-text-wrapper align-items-start">

                <i class="bi bi-chat-left-text-fill text-secondary mt-1"></i>

                <div>

                    <div class="content1">
                        {!! $value->description !!}
                    </div>

                    <span onclick="ReadMore('{{ $value->id }}')"
                        style="cursor: pointer;color: #00afef;font-size: 14px;">
                        Read More
                    </span>

                </div>

            </div>

            @endif
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-flag-fill text-danger"></i>

                <span class="fw-semibold">
                    {{ $value->status }}
                </span>

            </div>
        </td>

        <td>
            <p style="display: flex;margin-bottom: 0px;">

                <a href="{{ route('admin.task.request.edit', $value->id) }}"
                    class="creative-btn-action action-edit"
                    style="padding: 4px 12px;margin: 0px 4px;">
                    <i class="ion ion-edit text-white"></i>
                </a>

                <a href="{{ route('task.request.details', $value->id) }}"
                    class="creative-btn-action btn-success"
                    style="padding: 4px 12px;margin: 0px 4px;">
                    <i class="ti-eye text-white"></i>
                </a>

                <a href="javascript:void(0);"onclick="deleteProject('{{ route('admin.projects.task.estimate.delete', $value->id) }}')"
                    class="delete creative-btn-action action-delete"
                    data-confirm="Are you Sure Delete this Task Estimate?"
                    style="padding: 4px 12px;margin: 0px 4px;">
                    <i class="ti-trash text-white"></i>
                </a>

                <a onclick="TaskComments('{{ $value->id }}')"
                    class="creative-btn-action action-user"
                    style="padding: 4px 12px;margin: 0px 4px;">
                    <i class="ti-check text-white"></i>
                </a>

            </p>
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
  <style type="text/css">
      #example_filter label
      {
        display: none;
      }
      #text_search_value
      {
        display: none;
      }
  </style>

    <?php
        $current_route = Route::currentRouteName();
    ?>
    <!-- /.content-wrapper -->
    <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
    <div class="modal fade" id="modal-default">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Task Estimate Update</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html1">

            <form action="{{ route('admin.task.estimate.close') }}" method="post">
            @csrf
            <input type="hidden" name="estimate_id" id="estimate_id">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Close Date</label>
                        <div class="input-group mb-3">
                            <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" placeholder="Task Name" onfocus="'showPicker' in this && this.showPicker()" value="{{ old('date') }}" required>
                            <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                        </div>
                        @error('task_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Total Amount</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control @error('total_amount') is-invalid @enderror" name="total_amount" placeholder="Task Amount" value="{{ old('total_amount') }}" required>
                            <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                        </div>
                        @error('total_amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select class="form-control @error('status') is-invalid @enderror" name="status" id="status">
                        <option value="">Select Status</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>
                
            </div>
            <div class="row">
                <!-- <div class="col-md-6"></div> -->
                <div class="col-md-12">
                    <div class="box-footer text-end">
                        <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                        <!-- <button type="submit" class="btn btn-primary">
                          <i class="ti-save-alt"></i> Save
                        </button> -->
                    </div> 
                </div>
            </div>
            </form>

          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    
    <div class="modal fade" id="modal-default1">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Task Estimate Description</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html2">

            

          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <script type="text/javascript">
        function TaskComments(id) {
            var project_id = id;

            $('#modal-default').modal('show');
            $('#estimate_id').val(project_id);
            
        }
        function ReadMore(id)
        {
            
            var project_id = id;

            if (project_id) {
                $.ajax({
                    url: "{{ route('admin.task.request.description') }}",
                    type: "POST",
                    data: {
                        id: project_id,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        
                        $('#modal-default1').modal('show');
                        $("#load_html2").html(response.html);

                    }
                });
            }
        }
    </script>

    <script type="text/javascript">
        function ClientTaskSearch() {
            var expert_search_url   = "{{ route($current_route) }}";
            var client_id = $("#client_id").val();

            var str_search_request   = "&client_id="+client_id;
        
            if(str_search_request){
                window.location.href = expert_search_url + '?' + str_search_request;
            }

        }
    </script>

@endsection