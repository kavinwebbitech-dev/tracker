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
                    <h3 class="page-title">Customers</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Customers</li>
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
                    <form action="{{ route('sub_admin.customers.bulk.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-5"></div>
                        <div class="col-md-3">
                            <input type="file" name="bulk_upoad_file" class="form-control">
                            <a href="<?php echo url('');?>/public/admin_assets/images/customer_upload.xlsx" download>Sample File</a>
                        </div>
                        <div class="col-md-1">
                            <input type="submit" name="submit" value="submit" class="btn btn-primary">
                        </div>
                        @if(in_array('2', $role_section))
                        <div class="col-md-3">
                            <span class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-default" style="float: right;margin-right: 10px;">
                                Add Customer
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    </form>
                    <div class="table-responsive">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Company Name</th>
                                <th>Address</th>
                                <th>GST No</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($client_details)
                            @foreach($client_details as $key => $value)
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $value->fld_name }}</td>
                                <td>{{ $value->fld_email }}</td>
                                <td>{{ $value->fld_phone }}</td>
                                <td>{{ $value->fld_company_name }}</td>
                                <td>{{ $value->fld_address }}</td>
                                <td>{{ $value->fld_customer_gstno }}</td>
                                <td>
                                    @if($value->fld_status == 1)
                                        Active
                                    @elseif($value->fld_status == 0)
                                        In Active
                                    @endif
                                </td>
                                <td>
                                    @if(in_array('3', $role_section))
                                    <a onclick="ViewTaskModel('{{ $value->id }}')" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                                    @endif
                                    @if(in_array('4', $role_section))
                                    <a href="{{ route('sub_admin.customers.delete', $value->id) }}" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
                                    @endif
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
  <!-- /.content-wrapper -->
  <div class="modal fade" id="modal-default">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Customers</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body">
            
            <form class="form" action="{{ route('sub_admin.customers.store') }}" method="post">
            @csrf
            <input type="hidden" name="conference_id" id="conference_id">

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Customers Name</label>
                    <div class="input-group in-bord mb-3">
                        <input type="text" class="form-control @error('fld_name') is-invalid @enderror" id="fld_name" name="fld_name" placeholder="Customers Name" value="{{ old('fld_name') }}" required>
                        <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                    </div>
                    @error('fld_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-group in-bord mb-3">
                        <input type="email" class="form-control @error('fld_email') is-invalid @enderror" id="fld_email" name="fld_email" placeholder="Email Address" value="{{ old('fld_email') }}">
                        <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                    </div>
                    @error('fld_email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <div class="input-group in-bord mb-3">
                        <input type="text" class="form-control @error('fld_phone') is-invalid @enderror" id="fld_phone" name="fld_phone" placeholder="Phone Number" value="{{ old('fld_phone') }}">
                        <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                    </div>
                    @error('fld_phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <div class="input-group in-bord mb-3">
                        <input type="text" class="form-control @error('fld_address') is-invalid @enderror" id="fld_address" name="fld_address" placeholder="Address" value="{{ old('fld_address') }}">
                        <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                    </div>
                    @error('fld_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Company Name</label>
                    <div class="input-group in-bord mb-3">
                        <input type="text" class="form-control @error('fld_company_name') is-invalid @enderror" id="fld_company_name" name="fld_company_name" placeholder="Company Name" value="{{ old('fld_company_name') }}">
                        <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                    </div>
                    @error('fld_company_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">GST Number</label>
                    <div class="input-group in-bord mb-3">
                        <input type="text" class="form-control @error('fld_customer_gstno') is-invalid @enderror" id="fld_customer_gstno" name="fld_customer_gstno" placeholder="GST Number" value="{{ old('fld_customer_gstno') }}">
                        <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                    </div>
                    @error('fld_customer_gstno')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Date of Birth</label>
                    <div class="input-group in-bord mb-3">
                        <input type="date" class="form-control @error('fld_customer_dob') is-invalid @enderror" id="fld_customer_dob" name="fld_customer_dob" placeholder="Business Name" value="{{ old('fld_customer_dob') }}">
                        <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                    </div>
                    @error('service')
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
                        <select name="fld_status" class="form-control" id="fld_status">
                            <option value="1">Active</option>
                            <option value="0">In Active</option>
                        </select>
                        <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                    </div>
                    @error('fld_status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="box-footer text-end">
                    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
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
            url: '{{ route('sub_admin.customers.edit') }}',
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele
            },
            success: function (response) {
                console.log(response);
                $("#conference_id").val(response.conference_id);
                $("#fld_name").val(response.fld_name);
                $("#fld_email").val(response.fld_email);
                $("#fld_phone").val(response.fld_phone);
                $("#fld_address").val(response.fld_address);
                $("#fld_company_name").val(response.fld_company_name);
                $("#fld_customer_gstno").val(response.fld_customer_gstno);
                $("#fld_customer_dob").val(response.fld_customer_dob);
                $("#fld_status option[value='"+response.fld_status+"']").attr("selected","selected");
                $('#modal-default').modal('show');
            }
        });
    }
  </script>
@endsection