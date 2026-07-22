@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Sales</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Sales</li>
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
                            <a href="{{ route('admin.sales.create') }}" class="btn btn-primary-light btn-sm add_button" style="float: right;">Add Sales</a>
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
                                <th>Email</th>
                                <th>Profile</th>
                                <th>Color Code</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <style type="text/css">
                            .circle
                            {
                                border-radius: 50px;
                                width: 35px;
                                height: 35px;
                                background: #ff5900;
                            }
                        </style>
                        <tbody>
                            @if($users)
                            @foreach($users as $key => $value)
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $value->firstname }} {{ $value->lastname }}</td>
                                <td>{{ $value->email }}</td>
                                <td><img src="<?php echo url('');?>/public/uploads/{{ $value->profile }}" style="width: 80px;"></td>
                                <td>
                                    <div class="circle" @if($value->color_code) style="background: {{ $value->color_code }};" @endif></div>
                                </td>
                                <td>
                                    @if($value->type == 1)
                                        Admin
                                    @elseif($value->type == 2)
                                        Staff
                                    @else
                                        Employee
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.sales.edit', $value->id) }}" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
                                    @if(Auth::user()->user_type == "super_admin")
                                    <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.sales.delete', $value->id) }}')" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
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

@endsection