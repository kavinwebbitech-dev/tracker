@extends('layouts.dashboard')

@section('content')

<style type="text/css">
    
    table.dataTable.nowrap th, table.dataTable.nowrap td
    {
        white-space: normal;
    }
</style>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Follow Up</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Follow Up</li>
                                <!-- <li class="breadcrumb-item active" aria-current="page">View</li> -->
                            </ol>
                        </nav>
                    </div>
                </div>
                
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
        <!-- Main content -->
        <section class="content">
          <div class="row">

            <div class="col-12">

             <div class="box">
                @include('layouts.flash-message')
                <!-- /.box-header -->
                <div class="box-body">
                    <form action="{{ route('admin.follow.up.bulk.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row" style="margin-bottom: 10px;align-items: center;"> 
                        <div class="col-md-3">
                            <!-- Modern Action Bar -->
                            <div class="corp-action-bar b-0 p-0 m-0"> 
                                <!-- Left Side: Upload & Submit -->
                                <div class="corp-action-left">
                                    <div class="corp-upload-wrapper">
                                        <input type="file" name="bulk_upoad_file" class="form-control">
                                        <a href="<?php echo url('');?>/public/admin_assets/images/follow_up_upload.xlsx" download class="corp-sample-link">Sample File</a>
                                    </div> 
                                </div> 
                            </div>   
                        </div>
                        <div class="col-md-1">
                            <input type="submit" name="submit" value="submit" class="btn btn-primary">
                        </div>
                        <div class="col-md-8">
                            <span class="btn btn-primary" style="float: right;">
                                <a href="{{ route('admin.follow.up.export') }}" style="color: #fff;">Export Customers</a>
                            </span>
                            <span class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-default" style="float: right;margin-right: 10px;">
                                Add Follow Up
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <input type="text" name="text_value_search" id="text_value_search1" class="form-control" onkeyup="TextValueSearch()" style="position: absolute;width: 200px;z-index: 999;">
                        </div>
                    </div>
                    </form>
                    <div class="table-responsive">
                      <table id="example" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
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

        <td class="text-muted fw-bold sorting_1" style="width: 20px">
            {{ $key + 1}}
        </td>

        <td>
            <div class="icon-text-wrapper">

                <div class="cell-icon icon-name">
                    <i class="bi bi-person-fill"></i>
                </div>

                <div>
                    <span class="fw-bold text-dark d-block">
                        {{ $value->fld_name }}
                    </span>
                </div>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-envelope-fill text-primary"></i>

                <span class="fw-semibold">
                    {{ $value->fld_email }}
                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-telephone-fill text-success"></i>

                <span class="fw-semibold">
                    {{ $value->fld_phone }}
                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-building text-warning"></i>

                <span class="fw-semibold">
                    {{ $value->fld_company_name }}
                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-geo-alt-fill text-danger"></i>

                <span class="fw-semibold">

                    <?php
                        $cleanedString = strip_tags($value->fld_address);
                        $get_word = Str::limit($value->fld_address, 50, '...');
                    ?>

                    {{ $get_word }}

                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-receipt text-info"></i>

                <span class="fw-semibold">
                    {{ $value->fld_customer_gstno }}
                </span>

            </div>
        </td>

        <td>
           @if($value->fld_status == 1)
                <span class="creative-badge creative-badge-active"><i class="bi bi-check-circle-fill"></i> Active</span>
            @elseif($value->fld_status == 0)
                <span class="creative-badge creative-badge-inactive"><i class="bi bi-x-circle-fill"></i> Inactive</span>
            @endif
        </td>  

        <td>
            <p style="display: flex;">

                <a href="{{ route('admin.follow.up.project', $value->id) }}"
                    class="creative-btn-action btn-success"
                    style="padding: 4px 12px;margin: 0px 4px;">
                    <i class="ti-eye text-white"></i>
                </a>

                <a onclick="ViewTaskModel('{{ $value->id }}')"
                    class="creative-btn-action action-edit"
                    style="padding: 4px 12px;margin: 0px 4px;">
                    <i class="ion ion-edit text-white"></i>
                </a>

                <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.follow.up.delete', $value->id) }}')"
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
                      </table>
                      <style type="text/css">
                          .pagination li a
                          {
                            padding: 6px;
                          }
                        </style>
                        <div class="row gy-4 align-items-center" id="seach_hide" style="margin-top: 30px;">
                            <div class="col-12">
                                {!! $client_details->withQueryString()->links('pagination::bootstrap-5') !!}
                                
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
            <h4 class="modal-title">Follow Up</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body">
            
            <form class="form" action="{{ route('admin.follow.up.store') }}" method="post">
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
                        <input type="text" class="form-control @error('fld_email') is-invalid @enderror" id="fld_email" name="fld_email" placeholder="Email Address" value="{{ old('fld_email') }}">
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
            url: '{{ route('admin.follow.up.edit') }}',
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
                $("#fld_status option[value='"+response.fld_status+"']").attr("selected","selected");
                $('#modal-default').modal('show');
            }
        });
    }
  </script>
    <script type="text/javascript">

        function TextValueSearch()
        {
            var text_value_search = $("#text_value_search1").val();

            if (text_value_search) {
                $.ajax({
                    url: "{{ route('admin.follow.up.search') }}",
                    type: "POST",
                    data: {
                        text_value_search: text_value_search,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        
                        if (response.status) 
                        {
                            $("#example").html(response.project);
                            $("#seach_hide").hide();
                        }
                    }
                });
            }

        }

        
    </script>
@endsection