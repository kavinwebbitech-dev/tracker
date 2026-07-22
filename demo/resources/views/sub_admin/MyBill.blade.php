@extends('layouts.sub_admin')

@section('content')
<?php
    $role_section = json_decode(Auth::user()->permissions);
    if ($role_section) {
        $role_section = $role_section;
    }
    else
    {
        $role_section = [];
    }
?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">My Bills</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">My Bills</li>
                                <!-- <li class="breadcrumb-item active" aria-current="page">View</li> -->
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
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            @if(in_array('99', $role_section))
                            <span class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-default" style="float: right;">
                                Add Bills
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                      <table id="example_render" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($MyBill)
                            @foreach($MyBill as $key => $value)
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->bill_amount }}</td>
                                <td>{{ date('d-m-Y', strtotime($value->start_date)) }}</td>
                                <td>{{ date('d-m-Y', strtotime($value->end_date)) }}</td>
                                <td>{{ $value->status }}</td>
                                <td>
                                    @if(in_array('100', $role_section))
                                    <a onclick="ViewTaskModel('{{ $value->id }}')" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                                    @endif
                                    @if(in_array('101', $role_section))
                                    <a href="{{ route('sub_admin.my.bills.delete', $value->id) }}" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        </table>
                        <style type="text/css">
                            .pagination li a
                            {
                                padding: 6px;
                            }
                        </style>
                        <div class="row gy-4 align-items-center" id="seach_hide" style="margin-top: 30px;">
                            <div class="col-12">
                                {!! $MyBill->withQueryString()->links('pagination::bootstrap-5') !!}
                                
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
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">My Bills</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body">
            
            <form class="form" action="{{ route('sub_admin.my.bills.store') }}" method="post">
            @csrf
            <input type="hidden" name="conference_id" id="conference_id">

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <div class="input-group in-bord mb-3">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ old('name') }}" required>
                    </div>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Bill Amount</label>
                    <div class="input-group in-bord mb-3">
                        <input type="text" class="form-control @error('bill_amount') is-invalid @enderror" id="bill_amount" name="bill_amount" placeholder="Bill Amount" value="{{ old('bill_amount') }}" required>
                    </div>
                    @error('bill_amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Bill Date</label>
                    <div class="input-group in-bord mb-3">
                        <input type="date" class="form-control @error('bill_date') is-invalid @enderror" id="bill_date" name="bill_date" placeholder="Bill Date" value="{{ old('bill_date') }}" onfocus="'showPicker' in this && this.showPicker()" required>
                    </div>
                    @error('bill_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Recurring Days</label>
                    <div class="input-group in-bord mb-3">
                        <input type="text" class="form-control @error('recurring_type') is-invalid @enderror" id="recurring_type" name="recurring_type" placeholder="Recurring Days" value="{{ old('recurring_type') }}" required>
                    </div>
                    @error('recurring_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div class="input-group in-bord mb-3">
                        <select name="status" class="form-control" id="status">
                            <option value="Active">Active</option>
                            <option value="In Active">In Active</option>
                        </select>
                    </div>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="box-footer text-end">
                    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                    <!-- <button type="submit" class="btn btn-primary">
                      <i class="ti-save-alt"></i> Save
                    </button> -->
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
            <h4 class="modal-title">My Bills</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body">
            
            <form class="form" action="{{ route('sub_admin.my.bills.store') }}" method="post">
            @csrf
            <input type="hidden" name="conference_id" id="conference_id1">

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <div class="input-group in-bord mb-3">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name1" name="name" placeholder="Name" value="{{ old('name') }}" required>
                    </div>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Bill Amount</label>
                    <div class="input-group in-bord mb-3">
                        <input type="text" class="form-control @error('bill_amount') is-invalid @enderror" id="bill_amount1" name="bill_amount" placeholder="Bill Amount" value="{{ old('bill_amount') }}" required>
                    </div>
                    @error('bill_amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Start Date</label>
                    <div class="input-group in-bord mb-3">
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" placeholder="Bill Date" value="{{ old('start_date') }}" onfocus="'showPicker' in this && this.showPicker()" required>
                    </div>
                    @error('start_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">End Date</label>
                    <div class="input-group in-bord mb-3">
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date1" name="end_date" placeholder="Recurring Days" value="{{ old('end_date') }}" onfocus="'showPicker' in this && this.showPicker()" required>
                    </div>
                    @error('end_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div class="input-group in-bord mb-3">
                        <select name="status" class="form-control" id="status">
                            <option value="Active">Active</option>
                            <option value="In Active">In Active</option>
                        </select>
                    </div>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="box-footer text-end">
                    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                    <!-- <button type="submit" class="btn btn-primary">
                      <i class="ti-save-alt"></i> Save
                    </button> -->
                </div> 
            </div>
            </form>

          </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script type="text/javascript">
    function ViewTaskModel(ref) {
        var ele = ref;
        // alert(ele);
        $.ajax({
            url: '{{ route('sub_admin.my.bills.edit') }}',
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele
            },
            success: function (response) {
                console.log(response);
                $("#conference_id1").val(response.conference_id);
                $("#name1").val(response.name);
                $("#start_date").val(response.start_date);
                $("#end_date1").val(response.end_date);
                $("#bill_amount1").val(response.bill_amount);
                $("#status option[value='"+response.status+"']").attr("selected","selected");
                $('#modal-default1').modal('show');
            }
        });
    }
  </script>
  <script type="text/javascript">
        function BulkCheck() {
            $("#input_file_col").show();
            $("#button_col").show();
            $("#bul_upload_col").hide();
        }
    </script>
@endsection