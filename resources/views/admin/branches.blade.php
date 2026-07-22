@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Branches</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Branches</li>
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
                    <form action="{{ route('admin.client.bulk.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <span class="corp-btn corp-btn-primary" data-bs-toggle="modal" data-bs-target="#modal-default" style="float: right;">
                                Add Branches
                            </span>
                        </div>
                    </div>
                    </form>
                    <div class="creative-table-wrapper table-responsive">
                      <table id="example1" class="table table-bordered table-striped creative-table">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Branch Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($client_details)
                            @foreach($client_details as $key => $value)
                            <tr>
                                <td class="text-muted fw-bold sorting_1">{{ $key + 1}}</td>
                                <td>
                                    <div class="icon-text-wrapper">
                                        <div class="cell-icon icon-company"><i class="bi bi-building"></i></div>
                                        <span class="fw-semibold">{{ $value->fld_branch_name }}</span>
                                    </div>
                                </td>  
                                <td>
                                    <span class="creative-badge creative-badge-active"><i class="bi bi-check-circle-fill"></i> 
                                        @if($value->fld_status == 1)
                                            Active
                                        @elseif($value->fld_status == 0)
                                            In Active
                                        @endif
                                    </span>
                                </td>  
                                <td>
                                    <div class="creative-action-box">
                                        <a onclick="ViewTaskModel('{{ $value->id }}')" class="creative-btn-action action-edit"  ><i class="ion ion-edit text-white"></i></a>
                                        <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.branches.delete', $value->id) }}')" class="creative-btn-action action-delete"  ><i class="ti-trash text-white"></i></a>
                                    </div> 
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
            <h4 class="modal-title">Branch</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body">
            
            <form class="form" action="{{ route('admin.branches.store') }}" method="post">
            @csrf
            <input type="hidden" name="conference_id" id="conference_id">

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Branch Name</label>
                    <div class="input-group in-bord mb-3">
                        <input type="text" class="form-control @error('fld_branch_name') is-invalid @enderror" id="fld_branch_name" name="fld_branch_name" placeholder="Branch Name" value="{{ old('fld_branch_name') }}" required>
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
            url: '{{ route('admin.branches.edit') }}',
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele
            },
            success: function (response) {
                console.log(response);
                $("#conference_id").val(response.conference_id);
                $("#fld_branch_name").val(response.fld_branch_name);
                $("#fld_status option[value='"+response.fld_status+"']").attr("selected","selected");
                $('#modal-default').modal('show');
            }
        });
    }
  </script>
@endsection