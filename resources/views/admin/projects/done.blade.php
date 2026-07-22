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
                    <h3 class="page-title">Completed Project List</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">Completed Project List</li>
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
                    {{-- <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.proposal.create') }}" class="btn btn-primary-light btn-sm add_button" style="float: right;">Add Proposals</a>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="input-group in-bord mb-3">
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" id="start_date" value="{{ $start_date ?? '' }}" required>
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
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" id="end_date" value="{{ $end_date ?? '' }}" required>
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
                                    <select class="form-control @error('salesperson') is-invalid @enderror" name="salesperson" id="salesperson">
                                        <option value="">Select Sales Person</option>
                                        @if($salesperson)
                                        @foreach($salesperson as $key => $person)
                                        @if($person->added_by)
                                        <?php
                                            $user_details = \App\Models\User::where('id', $person->added_by)->first();
                                            // dd($user_details);
                                        ?>
                                        <option value="{{ $user_details->id }}" @if($salesperson1 == $user_details->id) selected @endif>{{ $user_details->name ?? '' }}</option>
                                        @endif
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('salesperson')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="input-group in-bord mb-3">
                                    <select class="form-control @error('service') is-invalid @enderror" name="service" id="service">
                                        <option value="">Select Service</option>
                                        @if($service_get)
                                        @foreach($service_get as $key => $service)
                                        <option value="{{ $service->id }}" @if($service_get1 == $service->id) selected @endif>{{ $service->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('service')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="input-group in-bord mb-3">
                                    <select class="form-control @error('status') is-invalid @enderror" name="status" id="status">
                                        <option value="all" @if($status == "" || $status == null) selected @endif>All</option>
                                        <option value="0" @if($status != null && $status == 0) selected @endif>Pending</option>
                                        <option value="1" @if($status != null && $status == 1) selected @endif>On Progress</option>
                                        <option value="3" @if($status != null && $status == 3) selected @endif>On Hold</option>
                                        <option value="5" @if($status != null && $status == 5) selected @endif>Done</option>
                                        <option value="6" @if($status != null && $status == 6) selected @endif>Cancel</option>
                                    </select>
                                </div>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input type="submit" name="submit" class="btn btn-primary" onclick="sort_book()" value="Submit">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <!-- <a href="" id="image_url" class="btn btn-success" target="_blank">Report Download</a> -->
                        </div>
                        <div class="col-md-8"></div>
                        <div class="col-md-2">
                            <input type="text" name="text_value_search" id="text_value_search" class="form-control" onkeyup="text_value_search()" style="margin-bottom: 10px;">
                        </div>
                    </div>
                    <style type="text/css">
                        .add_button
                        {
                            margin-bottom: 10px;
                        }
                    </style>
                    <div class="table-responsive creative-table-wrapper">
                      <table id="example_render" class="table table-bordered table-hover display nowrap margin-top-10 w-p100 creative-table">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Project</th>
                                <th>Sales User Name</th>
                                <th>Total Amount</th>
                                <th>Received</th>
                                <th>Confirm Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php
                            $total_amount1 = 0;
                            $total_pening = 0;
                        ?>
                         <tbody>
    @if($users)
    @foreach($users as $key => $value)

    <?php
        $total_amount = 0;
        $total_penc = 0;

        $bit_amounts = $value->bit_amounts;

        if (count($bit_amounts) > 0) {
            foreach ($bit_amounts as $key1 => $value1) {
                $total_amount += $value1->fld_project_amount;
            }
        }

        $total_penc = $value->bid_amount - $total_amount;

        $total_amount1 += $value->bid_amount;
        $total_pening += $total_amount;
    ?>

    <tr 
        @if($value->bid_amount == $total_amount) style="background: #f6e2f7;" @endif
        @if($value->refunded == "refunded") style="background: #ff6464;" @endif
    >

        <td class="text-muted fw-bold sorting_1">
            {{ $key + 1}}
        </td>

        <td>
            <div class="icon-text-wrapper">

                <div class="cell-icon icon-name">
                    <i class="bi bi-folder-fill"></i>
                </div>

                <div>
                    <span class="fw-bold text-dark d-block">
                        {{ $value->name }}
                    </span>

                    @if($value->refunded == "refunded")
                        <span style="font-size: 10px;color: #fff;">
                            (Refunded)
                        </span>
                    @endif
                </div>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-person-workspace text-primary"></i>

                <span class="fw-semibold">
                    {{ $value->added_user_details->name ?? '' }}
                </span>

            </div>
        </td>

        <td>
            <div class="count-icons icon-text-wrapper">

                <i class="bi bi-currency-rupee text-success"></i>

                <p class="mb-0 fw-semibold">
                    {{ $value->bid_amount }}
                </p>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-currency-rupee text-success"></i>

                <span class="fw-semibold">
                    {{ $total_amount }}
                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-calendar-fill text-success"></i>

                <span class="fw-semibold">

                    @if($value->sales_user_date == "0000-00-00")
                    @else
                        {{ date('d-m-Y', strtotime($value->sales_user_date)) }}
                    @endif

                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-flag-fill text-warning"></i>

                <span class="fw-semibold">

                    @if($value->status == "0")
                        Pending
                    @elseif($value->status == "1")
                        On Progress
                    @elseif($value->status == "3")
                        On Hold
                    @elseif($value->status == "5")
                        Done
                    @elseif($value->status == "6")
                        Cancel
                    @endif

                </span>

            </div>
        </td>

        <td>
            <p style="display: flex;">

                <a href="{{ route('admin.projects.view.details', $value->id) }}"
                    class="creative-btn-action btn-success"
                    style="padding: 4px 12px;margin: 0px 4px;">
                    <i class="ti-eye text-white"></i>
                </a>

                <a href="{{ route('admin.projects.edit', $value->id) }}"
                    class="creative-btn-action action-edit"
                    style="padding: 4px 12px;margin: 0px 4px;">
                    <i class="ion ion-edit text-white"></i>
                </a>

                <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.projects.delete', $value->id) }}')"
                    class="creative-btn-action action-delete"
                    style="padding: 4px 12px;margin: 0px 4px;">
                    <i class="ti-trash text-white"></i>
                </a>

            </p>
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
                                <td>
                                    <div class="icon-text-wrapper mb-1">
                                        <i class="bi bi-currency-rupee text-success"></i>
                                        <span class="fw-semibold">  {{ $total_amount1 }}</span>
                                    </div>
                                </td> 
                                <td>
                                    <div class="icon-text-wrapper mb-1">
                                        <i class="bi bi-currency-rupee text-success"></i>
                                        <span class="fw-semibold">  {{ $total_pening }}</span>
                                    </div>
                                </td>  
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                        </table>
                        <style type="text/css">
                          .pagination li a
                          {
                            padding: 6px;
                          }
                        </style>
                        <div class="row gy-4 align-items-center" id="seach_hide" style="margin-top: 30px;">
                            <div class="col-12">
                                {!! $users->withQueryString()->links('pagination::bootstrap-5') !!}
                                
                            </div>
                        </div>
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
  <?php
    $current_route = Route::currentRouteName();
  ?>
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
  <script type="text/javascript">
      function sort_book() {

        var expert_search_url   = "{{ route($current_route) }}";
        var start_date          = $("#start_date").val();
        var end_date            = $("#end_date").val();
        var status              = $("#status").val();
        var salesperson         = $("#salesperson").val();
        var service             = $("#service").val();

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
        if (status) 
        {
            status = status;
            
        }
        else
        {
            status = "";
        }
        if (salesperson) 
        {
            salesperson = salesperson;
            
        }
        else
        {
            salesperson = "";
        }
        if (service) 
        {
            service = service;
            
        }
        else
        {
            service = "";
        }
        var str_search_request   = "&start_date="+start_date+"&end_date="+end_date+"&status="+status+"&salesperson="+salesperson+"&service="+service;
        
  
        if(str_search_request){
            window.location.href = expert_search_url + '?' + str_search_request;
        }
        
    }

    function text_value_search()
    {
        var text_value_search = $("#text_value_search").val();
        var start_date        = $("#start_date").val();
        var end_date          = $("#end_date").val();
        var salesperson       = $("#salesperson").val();
        var service           = $("#service").val();
        var status            = $("#status").val();

        if (text_value_search) {
            $.ajax({
                url: "{{ route('admin.projects.completed.search.view') }}",
                type: "POST",
                data: {
                    text_value_search: text_value_search,
                    start_date: start_date,
                    end_date: end_date,
                    salesperson: salesperson,
                    status: status,
                    service: service,
                    _token: '{{csrf_token()}}'
                },
                success: function (response) {
                    
                    if (response.status) 
                    {
                        $("#example_render").html(response.project);
                        $("#seach_hide").hide();
                    }
                }
            });
        }

    }

  </script>
@endsection