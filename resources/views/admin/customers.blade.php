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
                    <h3 class="page-title">Customers11</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Customers</li>
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
                    <div class="corp-table-card">
                        @include('layouts.flash-message')
                        
                        <form action="{{ route('admin.customers.bulk.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Modern Action Bar -->
                            <div class="corp-action-bar">
                                
                                <!-- Left Side: Upload & Submit -->
                                <div class="corp-action-left">
                                    <div class="corp-upload-wrapper">
                                        <input type="file" name="bulk_upoad_file" accept=".xlsx,.csv">
                                        <a href="<?php echo url('');?>/public/admin_assets/images/customer_upload.xlsx" download class="corp-sample-link">Sample File</a>
                                    </div>
                                    <button type="submit" name="submit" value="submit" class="corp-btn corp-btn-primary" style="padding: 6px 12px; font-size: 0.85rem;">
                                        <i class="bi bi-upload"></i> Upload
                                    </button>
                                </div>
                                
                                <!-- Right Side: Search, Export, Add -->
                                <div class="corp-action-right">
                                    
                                    <!-- Clean Search Box -->
                                    <div class="corp-search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" name="text_value_search" id="text_value_search1" placeholder="Search customers..." onkeyup="TextValueSearch()">
                                    </div>
                                    
                                    <a href="{{ route('admin.customers.export') }}" class="corp-btn corp-btn-outline">
                                        <i class="bi bi-file-earmark-arrow-down"></i> Export
                                    </a>
                                    
                                    <span class="corp-btn corp-btn-primary" data-bs-toggle="modal" data-bs-target="#modal-default">
                                        <i class="bi bi-person-plus-fill"></i> Add Customer
                                    </span>
                                    
                                </div>
                            </div>
                        </form>

                        <!-- Modern Table Layout -->
                        <div class="creative-table-wrapper table-responsive">
                            <table id="example" class="creative-table">
                                <thead>
                                    <tr>
                                        <th width="5%">S.No</th>
                                        <th width="18%">Customer Name</th>
                                        <th width="15%">Contact Details</th>
                                        <th width="18%">Company Info</th>
                                        <th width="18%">Address Details</th>
                                        <th width="8%">GST No</th>
                                        <th width="8%">Status</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($client_details)
                                        @foreach($client_details as $key => $value)
                                        <tr>
                                            <!-- Serial Number -->
                                            <td class="text-muted fw-bold">{{ $key + 1}}</td>
                                            
                                            <!-- Name with Icon -->
                                            <td>
                                                <div class="icon-text-wrapper">
                                                    <div class="cell-icon icon-name"><i class="bi bi-person-fill"></i></div>
                                                    <div>
                                                        <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">{{ $value->fld_name }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Contact (Email & Phone stacked) with Icon -->
                                            <td>
                                                <div class="icon-text-wrapper mb-1">
                                                    <i class="bi bi-telephone-fill text-success" ></i>
                                                    <span class="fw-semibold">{{ $value->fld_phone }}</span>
                                                </div>
                                                @if($value->fld_email)
                                                <div class="icon-text-wrapper">
                                                    <i class="bi bi-envelope-fill text-muted" ></i>
                                                    <span class="text-muted" >{{ $value->fld_email }}</span>
                                                </div>
                                                @endif
                                            </td>
                                            
                                            <!-- Company with Icon -->
                                            <td>
                                                <div class="icon-text-wrapper">
                                                    <div class="cell-icon icon-company"><i class="bi bi-building"></i></div>
                                                    <span class="fw-semibold">{{ $value->fld_company_name }}</span>
                                                </div>
                                            </td>
                                            
                                            <!-- Address with Icon -->
                                            <td>
                                                <div class="icon-text-wrapper">
                                                    <div class="cell-icon icon-address"><i class="bi bi-geo-alt-fill"></i></div>
                                                    <?php
                                                        $cleanedString = strip_tags($value->fld_address);
                                                        $get_word = Str::limit($value->fld_address, 40, '...');
                                                    ?>
                                                    <span title="{{ $value->fld_address }}">{{ $get_word }}</span>
                                                </div>
                                            </td>
                                            
                                            <!-- GST No -->
                                            <td>
                                                <span class="fw-bold text-muted">{{ $value->fld_customer_gstno }}</span>
                                            </td>
                                            
                                            <!-- Status Badge -->
                                            <td>
                                                @if($value->fld_status == 1)
                                                    <span class="creative-badge creative-badge-active"><i class="bi bi-check-circle-fill"></i> Active</span>
                                                @elseif($value->fld_status == 0)
                                                    <span class="creative-badge creative-badge-inactive"><i class="bi bi-x-circle-fill"></i> Inactive</span>
                                                @endif
                                            </td>
                                            
                                            <!-- Action Buttons -->
                                            <td>
                                                <?php
                                                    $user_check = \App\Models\User::where('cus_id', $value->id)->first();
                                                ?>
                                                <div class="creative-action-box">
                                                    @if($user_check)
                                                        <a href="{{ route('admin.customers.details', $value->id) }}" title="Client Panel Created" class="creative-btn-action action-user">
                                                            <i class="ti-user"></i>
                                                        </a>
                                                    @else
                                                        <a onclick="ViewTaskModel1('{{ $value->id }}')" title="Create Client Panel" class="creative-btn-action action-user">
                                                            <i class="ti-user"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    <a onclick="ViewTaskModel('{{ $value->id }}')" title="Edit" class="creative-btn-action action-edit">
                                                        <i class="ion ion-edit"></i>
                                                    </a>
                                                    
                                                   <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.customers.delete', $value->id) }}')" title="Delete" class="creative-btn-action action-delete">
                                                        <i class="ti-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            
                            <!-- Pagination -->
                            <div class="row gy-4 align-items-center" id="seach_hide" style="margin-top: 20px; padding: 0 20px 20px 20px;">
                                <div class="col-12 d-flex justify-content-end">
                                    {!! $client_details->withQueryString()->links('pagination::bootstrap-5') !!}
                                </div>
                            </div>
                        </div>
                            
                    </div>
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
            
            <form class="form" action="{{ route('admin.customers.store') }}" method="post">
            @csrf
            <input type="hidden" name="conference_id" id="conference_id">

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Customers Name</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('fld_name') is-invalid @enderror" id="fld_name" name="fld_name" placeholder="Customers Name" value="{{ old('fld_name') }}" required>
                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
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
                    <div class="input-group mb-3">
                        <input type="email" class="form-control @error('fld_email') is-invalid @enderror" id="fld_email" name="fld_email" placeholder="Email Address" value="{{ old('fld_email') }}">
                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
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
                    <div class="input-group mb-3">
                        <input type="number" class="form-control @error('fld_phone') is-invalid @enderror" id="fld_phone" name="fld_phone" placeholder="Phone Number" value="{{ old('fld_phone') }}">
                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
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
                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('fld_address') is-invalid @enderror" id="fld_address" name="fld_address" placeholder="Address" value="{{ old('fld_address') }}">
                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
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
                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('fld_company_name') is-invalid @enderror" id="fld_company_name" name="fld_company_name" placeholder="Company Name" value="{{ old('fld_company_name') }}">
                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
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
                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('fld_customer_gstno') is-invalid @enderror" id="fld_customer_gstno" name="fld_customer_gstno" placeholder="GST Number" value="{{ old('fld_customer_gstno') }}">
                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
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
                    <div class="input-group mb-3">
                        <input type="date" class="form-control @error('fld_customer_dob') is-invalid @enderror" id="fld_customer_dob" name="fld_customer_dob" placeholder="Business Name" value="{{ old('fld_customer_dob') }}">
                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
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
                    <div class="input-group mb-3">
                        <select name="fld_status" class="form-control" id="fld_status">
                            <option value="1">Active</option>
                            <option value="0">In Active</option>
                        </select>
                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
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

  <div class="modal fade" id="modal-default1">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Customers</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body">
            
            <form class="form" action="{{ route('admin.customers.user.store') }}" method="post">
            @csrf
            <input type="hidden" name="conference_id1" id="conference_id1">

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Customers Name</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('fld_name1') is-invalid @enderror" id="fld_name1" name="fld_name1" placeholder="Customers Name" value="{{ old('fld_name1') }}" required>
                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                    </div>
                    @error('fld_name1')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control @error('fld_email1') is-invalid @enderror" id="fld_email1" name="fld_email1" placeholder="Email Address" value="{{ old('fld_email1') }}">
                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                    </div>
                    @error('fld_email1')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control @error('fld_phone1') is-invalid @enderror" id="fld_phone1" name="fld_phone1" placeholder="Phone Number" value="{{ old('fld_phone1') }}">
                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
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
                    <label class="form-label">Password</label>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" value="{{ old('password') }}">
                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                    </div>
                    @error('password')
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
            url: '{{ route('admin.customers.edit') }}',
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

    function ViewTaskModel1(ref) {
        var ele = ref;

        $.ajax({
            url: '{{ route('admin.customers.edit') }}',
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele
            },
            success: function (response) {
                console.log(response);
                $("#conference_id1").val(response.conference_id);
                $("#fld_name1").val(response.fld_name);
                $("#fld_email1").val(response.fld_email);
                $("#fld_phone1").val(response.fld_phone);
                $('#modal-default1').modal('show');
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
                    url: "{{ route('admin.customers.search') }}",
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