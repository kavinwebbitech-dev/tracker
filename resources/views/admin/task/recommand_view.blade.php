@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Recommend Task</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Recommend Task</li>
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
                        <div class="col-lg-12">
                            <p style="display:flex;float: right;">
                                <a href="{{ route('admin.recommand.task.create') }}" class="btn btn-primary btn-sm add_button" style="float: right;padding: 8px 35px;">Create Recommend Task</a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Task Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

    @if($test_details)
    @foreach($test_details as $key => $value)

    <tr>

        <td class="text-muted fw-bold sorting_1">
            {{ $key + 1}}
        </td>

        <td>
            <div class="icon-text-wrapper">

                <div class="cell-icon icon-name">
                    <i class="bi bi-list-task"></i>
                </div>

                <span class="fw-semibold">
                    {{ $value->name }}
                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper"> 

                <span class="creative-badge creative-badge-active"><i class="bi bi-check-circle-fill"></i> Recommend</span>

            </div>
        </td>

        <td>

            <p style="display: flex;margin-bottom: 0px;">

                <a href="{{ route('task.edit', $value->id) }}"
                    class="creative-btn-action action-edit"
                    style="padding: 4px 12px;margin: 5px 4px;">
                    <i class="ion ion-edit text-white"></i>
                </a>

                @if($value->status != "completed")

                 <a href="javascript:void(0);"onclick="deleteProject('{{ route('admin.task.delete', $value->id) }}')"
                    class="creative-btn-action action-delete"
                    style="padding: 4px 12px;margin: 5px 4px;">
                    <i class="ti-trash text-white"></i>
                </a>

                @endif

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