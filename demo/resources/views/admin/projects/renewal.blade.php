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
                    <h3 class="page-title">{{ $page_title }} Project List</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $page_title }} Project List</li>
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
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-2">
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.projects.create') }}" style="    width: 100%;">Add Projects</a>
                        </div>
                        {{-- <div class="col-md-3">
                            <a class="btn btn-success btn-sm" href="{{ route('admin.completed.payment.project') }}" style="width: 100%;">Completed Payment Projects</a>
                        </div> --}}
                    </div>
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
                                    <select class="form-control select2 @error('salesperson') is-invalid @enderror" name="salesperson" id="salesperson">
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
                                    <select class="form-control select2 @error('service') is-invalid @enderror" name="service" id="service">
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
                                    <select class="form-control select2 @error('status') is-invalid @enderror" name="status" id="status">
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
                    <!-- </form> -->
                    <style type="text/css">
                        .add_button
                        {
                            margin-bottom: 10px;
                        }
                    </style>
                    <div class="table-responsive">
                      <table id="example" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Project</th>
                                <th>Sales User Name</th>
                                <th>Total Amount</th>
                                <th>Pending</th>
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
                                $total_pening += $total_penc;
                                $date_check = 0;
                                if ($value->end_date <= date('Y-m-d')) {
                                    $date_check = 1;
                                }
                                // dd($value->stop_project);
                            ?>
                            
                            <tr @if($value->stop_project) style="background: #ff0404b3;" @endif>
                                <td @if($value->stop_project && $value->renew_start == 1) style="background: #04a08beb;" @endif>{{ $key + 1 }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->added_user_details->name ?? '' }}</td>
                                <td>
                                    
                                    <div class="count-icons">
                                        <p>{{ $value->bid_amount }}</p>
                                        <i class="fa fa-add count-right-icon" style="cursor: pointer;font-size: 20px;" onclick='ViewTaskModel1("{{ $value->id }}")'></i> <i class="fa fa-eye" style="cursor: pointer;font-size: 17px;" onclick='ViewTaskModel("{{ $value->id }}")'></i>
                                    </div>
                                </td>
                                <td>
                                    {{ $total_penc }}
                                </td>
                                <td>
                                    @if($value->sales_user_date == "0000-00-00")
                                    @else
                                        {{ date('d-m-Y', strtotime($value->sales_user_date)) }}
                                    @endif
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    <p style="display: flex;">
                                        @if($value->stop_project && $value->renew_start == 0)
                                        <a onclick="TaskComments('{{ $value->id }}')" class="btn btn-warning" style="padding: 4px 12px;margin: 0px 4px;"><i class="bi bi-ubuntu text-white"></i></a>
                                        @endif
                                        <a href="{{ route('admin.projects.view.details', $value->id) }}" class="btn btn-success" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-eye text-white"></i></a>
                                        <a href="{{ route('admin.projects.edit', $value->id) }}" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                                        <a href="{{ route('admin.projects.delete', $value->id) }}" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
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
                                <td style="text-align: center;">
                                    {{ $total_amount1 }}
                                </td>
                                <td>
                                    {{ $total_pening }}
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
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
  <!-- /.content-wrapper -->
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
  <?php
    $current_route = Route::currentRouteName();
  ?>
  
  <script type="text/javascript">

    function sort_book() {

        var expert_search_url   = "{{ route($current_route) }}";
        var start_date      = $("#start_date").val();
        var end_date    = $("#end_date").val();
        var status           = $("#status").val();
        var salesperson         = $("#salesperson").val();

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
        var str_search_request   = "&start_date="+start_date+"&end_date="+end_date+"&status="+status+"&salesperson="+salesperson;
        
  
        if(str_search_request){
            window.location.href = expert_search_url + '?' + str_search_request;
        }
        
    }

    function ViewTaskModel1(id) {
        var project_id = id;

        if (project_id) {
            $('#modal-default1').modal('show');
            $("#project_id").val(project_id);
        }
    }

    function ViewTaskModel(id) {
        var project_id = id;

        if (project_id) {
            $.ajax({
                url: "{{ route('admin.projects.payment.details') }}",
                type: "POST",
                data: {
                    id: project_id,
                    _token: '{{csrf_token()}}'
                },
                success: function (response) {
                    $('#modal-default').modal('show');
                    $("#load_html1").html(response.html);
                }
            });
        }

    }
    
    function TaskComments(id) {
    var project_id = id;

    $('#modal-default5').modal('show');
    $('#estimate_id').val(project_id);
    
}

  </script>
  
  <div class="modal fade" id="modal-default5">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Renewal Project Restart</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html1">

            <form action="{{ route('admin.start.new.recuring') }}" method="post">
            @csrf
            <input type="hidden" name="estimate_id" id="estimate_id">
            <div class="row">

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Restart Date</label>
                        <div class="input-group in-bord mb-3">
                            <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" placeholder="Task Name" onfocus="'showPicker' in this && this.showPicker()" value="{{ old('date') }}" required>
                            <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                        </div>
                        @error('task_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Total Amount</label>
                        <div class="input-group in-bord mb-3">
                            <input type="text" class="form-control @error('total_amount') is-invalid @enderror" name="total_amount" placeholder="Task Amount" value="{{ old('total_amount') }}" required>
                            
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
                </div> --}}
                
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
            <h4 class="modal-title">Payment Update</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html">
            <form action="{{ route('admin.projects.payment.update') }}" method="post">
                @csrf
                <input type="hidden" name="project_id" id="project_id">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Payment Date</label>
                            <div class="input-group in-bord mb-3">
                                <input type="date" class="form-control @error('payment_date') is-invalid @enderror" name="payment_date" value="{{ old('payment_date') }}" required>
                            </div>
                            @error('payment_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Payment Amount</label>
                            <div class="input-group in-bord mb-3">
                                <input type="text" class="form-control @error('payment_amount') is-invalid @enderror" name="payment_amount" value="{{ old('payment_amount') }}" required>
                            </div>
                            @error('payment_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
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
            <h4 class="modal-title">Payment Details</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body" id="load_html1">
            
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

@endsection