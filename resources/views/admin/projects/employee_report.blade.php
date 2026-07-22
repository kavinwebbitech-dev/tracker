@extends('layouts.dashboard')

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
                    <h3 class="page-title">Employee Project List</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">Employee Project List</li>
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
                    
                    <!-- <form action="{{ route('admin.projects.filter') }}" method="post"> -->
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="input-group in-bord mb-3">
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" id="start_date" value="{{ $start_date ?? '' }}" onfocus="'showPicker' in this && this.showPicker()" required>
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
                                <div class="input-group in-bord mb-3">
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" id="end_date" value="{{ $end_date ?? '' }}" onfocus="'showPicker' in this && this.showPicker()" required>
                                </div>
                                @error('end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="input-group in-bord mb-3">
                                    <select class="form-control select2 @error('select_staff') is-invalid @enderror" name="select_staff" id="select_staff">
                                        <option value="">Select Staff</option>
                                        @if($staff_details)
                                        @foreach($staff_details as $key => $staff_del)
                                        <option value="{{ $staff_del->id }}" @if($staff == $staff_del->id) selected @endif>{{ $staff_del->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('select_staff')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <input type="submit" name="submit" class="btn btn-primary" onclick="sort_book()" value="Submit">
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="input-group in-bord mb-3">
                                    <select class="form-control select2 @error('select_ex_staff') is-invalid @enderror" name="select_ex_staff" id="select_ex_staff" onchange="sort_book1()">
                                        <option value="">Select Ex Staff</option>
                                        @if($staff_details1)
                                        @foreach($staff_details1 as $key => $staff_del)
                                        <option value="{{ $staff_del->id }}" @if($select_ex_staff == $staff_del->id) selected @endif>{{ $staff_del->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('select_ex_staff')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            {{-- <a href="" id="image_url" class="btn btn-success" target="_blank">Report Download</a> --}}
                        </div>
                        <div class="col-md-8"></div>
                        <div class="col-md-2">
                            <input type="text" name="text_value_search" id="text_value_search" class="form-control" onkeyup="text_value_search()" style="margin-bottom: 10px;">
                        </div>
                    </div>
                    <!-- </form> -->
                    <style type="text/css">
                        .add_button
                        {
                            margin-bottom: 10px;
                        }
                    </style>
                    <div class="table-responsive creative-table-wrapper">
                      <table id="example_render" class="table table-bordered table-hover display nowrap margin-top-10 w-p100 creative-table" style="table-layout: fixed !important;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Staff Name</th>
                                <th>Staff Commend</th>
                                <th>Total Working Hours</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <?php
                            $total_amount1 = 0;
                            $total_pening = 0;
                            $extra_time = 0;
                            $over_project = 0;
                            $over_time = 0;
                            $total_amount = 0;
                            $sum = strtotime('00:00:00');
                            $total_time = 0;
                            $project_amount = 0;
                            $toal_project = 0;
                        ?>
                        <tbody>
    @if($project_details)
    @foreach($project_details as $key => $value)

    <?php
        $toal_time = 0;
        $totaltime = 0;

        $working_time_h = number_format($value->working_hours, 2);

        $timeinsec = strtotime($working_time_h) - $sum;

        $totaltime += $timeinsec;

        $toal_time += $totaltime;

        $h = intval($totaltime / 3600);

        $totaltime = $totaltime - ($h * 3600);

        $m = intval($totaltime / 60);

        $s = $totaltime - ($m * 60);

        $total_time = $h.':'.$m;

        $salary = $value->user_details->salary;

        $hour_rate = $salary / 200;

        if (isset($m) && $m) {
            $calculate = $h + ($m / 60);
        }
        else
        {
            $calculate = $h;
        }

        $added_rate = round($calculate, 2);

        $total_amount = $added_rate * $hour_rate;

        $project_amount += $total_amount;

        $toal_project += $project_amount;

        $over_time += $toal_time;

        $h1 = intval($toal_time / 3600);

        $toal_time = $toal_time - ($h1 * 3600);

        $m1 = intval($toal_time / 60);

        $s1 = $toal_time - ($m1 * 60);

        $total_time1 = $h1.':'.$m1;

        $over_project += $total_amount;

        $extra_time += $over_time;
    ?>

    <tr>

        <?php
            $total_pening += $total_amount;
        ?>

        <td>

            <div class="icon-text-wrapper">

                <i class="bi bi-calendar-fill text-success"></i>

                <span class="fw-semibold">
                    {{ date('d-m-Y', strtotime($value->start_date)) }}
                </span>

            </div>

        </td>

        <td>

            <div class="icon-text-wrapper">

                <div class="cell-icon icon-name">
                    <i class="bi bi-folder-fill"></i>
                </div>

                <span class="fw-semibold">
                    {{ $value->task_details->project_details->name ?? '' }}
                </span>

            </div>

        </td>

        <td>

            <div class="icon-text-wrapper">

                <i class="bi bi-person-workspace text-primary"></i>

                <span class="fw-semibold">
                    {{ $value->user_details->name ?? '' }}
                </span>

            </div>

        </td>

        <td>

            <div class="icon-text-wrapper">

                <i class="bi bi-chat-left-text-fill text-warning"></i>

                <span class="fw-semibold">
                    {{ $value->user_comment }}
                </span>

            </div>

        </td>

        <td>

            <div class="icon-text-wrapper">

                <i class="bi bi-clock-fill text-info"></i>

                <span class="fw-semibold">
                    {{ $value->working_hours }}
                </span>

            </div>

        </td>

        <td>

            <div class="icon-text-wrapper">

                <i class="bi bi-currency-rupee text-success"></i>

                <span class="fw-bold">
                    {{ number_format($total_amount, 2) }}
                </span>

            </div>

        </td>

    </tr>

    @endforeach
    @endif
</tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <div class="icon-text-wrapper mb-1">
                                        <i class="bi bi-clock-fill text-info"></i>
                                        <span class="fw-semibold"> 
                                            <?php
                                        $h2 = intval($over_time / 3600);
                                        $over_time = $over_time - ($h2 * 3600);
                                        $m2 = intval($over_time / 60);
                                        $s2 = $over_time - ($m2 * 60);
                                        $total_time2 = $h2.':'.$m2;
                                    ?>
                                    {{ $total_time2 }}
                                        </span>
                                    </div>
                                </td> 
                                <td>
                                    <div class="icon-text-wrapper mb-1">
                                        <i class="bi bi-currency-rupee text-success"></i>
                                        <span class="fw-semibold"> {{ number_format($total_pening, 2) }}</span>
                                    </div>
                                </td>    
                            </tr>
                        </tfoot>

                        </table>
                        <style type="text/css">
                          .pagination li a
                          {
                            padding: 6px;
                          }
                        </style>
                        {{-- <div class="row gy-4 align-items-center" id="seach_hide" style="margin-top: 30px;">
                            <div class="col-12">
                                {!! $users->withQueryString()->links('pagination::bootstrap-5') !!}
                                
                            </div>
                        </div> --}}
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
  <?php
    $current_route = Route::currentRouteName();
  ?>

  <script type="text/javascript">

    function sort_book() {

        var expert_search_url   = "{{ route($current_route) }}";
        var start_date          = $("#start_date").val();
        var end_date            = $("#end_date").val();
        var staff        = $("#select_staff").val();

        if (start_date) {
            start_date = start_date;
        }
        else
        {
            start_date = "";
        }
        if (end_date) 
        {
            end_date = end_date;
            
        }
        else
        {
            end_date = "";
        }
        if (staff) 
        {
            staff = staff;
            
        }
        else
        {
            salesperson = "";
        }
        var str_search_request   = "&start_date="+start_date+"&end_date="+end_date+"&staff="+staff;
        
        if(str_search_request){
            // var pdf_url = expert_search_url1 + '?' + str_search_request
            // $('#image_url').attr('href', pdf_url);
            window.location.href = expert_search_url + '?' + str_search_request;
        }

        
        
    }

    function sort_book1() {

        var expert_search_url   = "{{ route($current_route) }}";
        var start_date          = $("#start_date").val();
        var end_date            = $("#end_date").val();
        var select_ex_staff     = $("#select_ex_staff").val();

        if (start_date) {
            start_date = start_date;
        }
        else
        {
            start_date = "";
        }
        if (end_date) 
        {
            end_date = end_date;
            
        }
        else
        {
            end_date = "";
        }
        if (select_ex_staff) 
        {
            select_ex_staff = select_ex_staff;
            
        }
        else
        {
            select_ex_staff = "";
        }
        var str_search_request   = "&start_date="+start_date+"&end_date="+end_date+"&select_ex_staff="+select_ex_staff;
        
        if(str_search_request){
            // var pdf_url = expert_search_url1 + '?' + str_search_request
            // $('#image_url').attr('href', pdf_url);
            window.location.href = expert_search_url + '?' + str_search_request;
        }

        
        
    }


  </script>

@endsection