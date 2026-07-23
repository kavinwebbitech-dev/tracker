@extends('layouts.sub_admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Freelancer</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Freelancer</li>
                                <li class="breadcrumb-item active" aria-current="page">View</li>
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
                            <a href="{{ route('sub_admin.freelancer.create') }}" class="btn btn-primary btn-sm add_button" style="float: right;">Add Freelancer</a>
                        </div>
                    </div>
                    <style type="text/css">
                        .add_button
                        {
                            margin-bottom: 10px;
                        }
                    </style>
                    <div class="table-responsive">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S No</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($sub_admin)
                            @foreach($sub_admin as $key => $value)
                            <tr>
                                <td class="text-muted fw-bold sorting_1">{{ $key + 1}}</td>
                                <td>
                                    <div class="icon-text-wrapper">
                                        <div class="cell-icon icon-name"><i class="bi bi-person-fill"></i></div>
                                        <div>
                                            <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">{{ $value->name }}</span>
                                        </div>
                                    </div>
                                </td> 
                                <td>
                                    <div class="icon-text-wrapper mb-1">
                                        <i class="bi bi-telephone-fill text-success"></i>
                                        <span class="fw-semibold">{{ $value->phone }}</span>
                                    </div>
                                </td> 
                                <td>
                                    <div class="icon-text-wrapper mb-1">
                                        <i class="bi bi-envelope-fill text-success"></i>
                                        <span class="fw-semibold">{{ $value->email }}</span>
                                    </div>
                                </td>  
                                <td>
                                    @if($value->status == "active")
                                        <span class="creative-badge creative-badge-active"><i class="bi bi-check-circle-fill"></i> Active</span>
                                    @else
                                        <span class="creative-badge creative-badge-inactive"><i class="bi bi-x-circle-fill"></i> Inactive</span>
                                    @endif
                                </td> 
                                <td>
                                    <p style="display: flex;">
                                        <a href="{{ route('sub_admin.freelancer.edit', $value->id) }}" class="creative-btn-action action-edit" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                                        <a href="javascript:void(0);" onclick="deleteProject('{{ route('sub_admin.freelancer.delete', $value->id) }}')" class="creative-btn-action action-delete" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
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
  <!-- /.content-wrapper -->

@endsection