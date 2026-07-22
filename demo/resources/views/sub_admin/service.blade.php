@extends('layouts.sub_admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Services</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Services</li>
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
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <span class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-default" style="float: right;">
                                Add Service
                            </span> 
                        </div>
                    </div>
                    <div class="table-responsive">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($service_details)
                            @foreach($service_details as $key => $value)
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $value->name }}</td>
                                <td>
                                    @if($value->status == "Active")
                                        Active
                                    @elseif($value->status == "In Active")
                                        In Active
                                    @endif
                                </td>
                                <td>
                                    <a onclick="ViewTaskModel('{{ $value->id }}')" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                                    <a href="{{ route('sub_admin.service.delete', $value->id) }}" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="fa fa-trash-o text-white"></i></a>
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
            <h4 class="modal-title">Services</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body">
            
            <form class="form" action="{{ route('sub_admin.service.store') }}" method="post">
            @csrf
            <input type="hidden" name="conference_id" id="conference_id">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <div class="input-group in-bord mb-3">
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Title" value="{{ old('title') }}" required>
                        <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                    </div>
                    @error('title')
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
                        <select name="status" class="form-control">
                            <option value="Active">Active</option>
                            <option value="In Active">In Active</option>
                        </select>
                        <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                    </div>
                    @error('link')
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
            url: '{{ route('sub_admin.service.edit') }}',
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele
            },
            success: function (response) {
                console.log(response);
                $("#conference_id").val(response.conference_id);
                $("#title").val(response.title);
                $("select option[value='"+response.status+"']").attr("selected","selected");
                $('#modal-default').modal('show');
            }
        });
    }
  </script>
@endsection