@extends('layouts.freelancer')


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
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Task Estimate</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('freelancer.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Freelancer</li>
                                <li class="breadcrumb-item active" aria-current="page">Task Estimate</li>
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
                    
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('freelancer.projects.create') }}" class="btn btn-primary">
                                Create Task Estimate
                            </a>
                        </div>
                        <div class="col-md-9"></div>
                    </div>
                    <br>
                    <!-- <form action="{{ route('admin.projects.filter') }}" method="post"> -->
                    @csrf
                    
                    
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Task Name</th>
                                <th>Date</th>
                                <th>Total Time</th>
                                <th>Total Amount</th>
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

                                $task_details = $value->client_task_details;

                                $add_hours = 0;
                                $sum = strtotime('00:00:00');
                                $totaltime = 0;

                                foreach ($task_details as $key2 => $value1) {
                                    $working_time_h = number_format($value1->estimate_time, 2);
                                    $timeinsec = strtotime($working_time_h) - $sum;
                                    $totaltime += $timeinsec;
                                    $total_amount += $value1->total_amount;
                                    
                                }

                                $total_time_cal += $totaltime;

                                $h = intval($totaltime / 3600);

                                $totaltime = $totaltime - ($h * 3600);
                                 
                                // Minutes is obtained by dividing
                                // remaining total time with 60
                                $m = intval($totaltime / 60);
                                 
                                // Remaining value is seconds
                                $s = $totaltime - ($m * 60);

                                $total_time = $h.':'.$m;
                                
                            ?>
                            
                            <tr @if($value->status == "Approved") style="background: #90EE90;" @endif>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ date('d-m-Y', strtotime($value->date)) }}</td>
                                <td>
                                    {{ $total_time }}
                                </td>
                                <td>
                                    @if($total_amount) ₹ {{ number_format($total_amount, 2) }}@endif @if($value->amount) ₹ {{ number_format($value->amount, 2) }}@endif
                                </td>
                                <td>
                                    {!! $value->description !!}
                                </td>
                                <td>
                                    {{ $value->status }}
                                </td>
                                <td>
                                    <p style="display: flex;margin-bottom: 0px;">
                                        <a href="{{ route('freelancer.projects.task.details', $value->id) }}" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-eye text-white"></i></a>
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
  <!-- /.content-wrapper -->
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
  

@endsection