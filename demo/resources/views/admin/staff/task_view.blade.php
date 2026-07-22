@extends('layouts.dashboard')

@section('content')

<style type="text/css">
    .theme-primary .select2-container--default.select2-container--open
    {
        z-index: 9999;
    }
</style>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Staff Group</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Staff Group</li>
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
                            <a href="{{ route('sub.task.group.add') }}" class="btn btn-primary btn-sm add_button" style="float: right;">Add Group</a>
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
                                <th>Group Id</th>
                                <th>Group Name</th>
                                <th>Group Staff</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($group_staff)
                            @foreach($group_staff as $key => $value)
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $value->group_id }}</td>
                                <td>{{ $value->group_name }}</td>
                                <td>
                                    @if($value->group_user)
                                    @php $user_name = json_decode($value->group_user); @endphp
                                    @foreach($user_name as $key1 => $user)
                                    @if($user)
                                        <?php
                                            $user_details = \App\Models\User::where('id', $user)->first();
                                        ?>
                                        {{ $user_details->name }},
                                    @endif
                                    @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if($value->status == "Active")
                                        Active
                                    @else
                                        In Active
                                    @endif
                                </td>
                                <td>
                                    <p style="display: flex;">
                                        <a href="{{ route('sub.staff.group.edit', $value->id) }}" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a><a href="{{ route('sub.staff.group.delete', $value->id) }}" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
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