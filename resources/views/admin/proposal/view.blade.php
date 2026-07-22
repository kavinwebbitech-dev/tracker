@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h3 class="page-title">Proposal</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">Proposal</li>
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
                            <a href="{{ route('admin.proposal.create') }}" class="corp-btn corp-btn-primary add_button" style="float: right;">Add Proposals</a>
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
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

    @if($users)
    @foreach($users as $key => $value)

    <tr>

        <td class="text-muted fw-bold sorting_1">
            {{ $key + 1}}
        </td>

        <td>
            <div class="icon-text-wrapper">

                <div class="cell-icon icon-name">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>

                <span class="fw-semibold">
                    {{ $value->name }}
                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-calendar-fill text-success"></i>

                <span class="fw-semibold">
                    {{ date('d-m-Y', strtotime($value->proposal_date)) }}
                </span>

            </div>
        </td>

        <td>

            <p style="display: flex;">

                <a href="{{ route('admin.proposal.edit', $value->id) }}"
                    class="creative-btn-action action-edit"
                    style="padding: 4px 12px;margin: 0px 4px;">
                    <i class="ion ion-edit text-white"></i>
                </a>

                <a href="{{ route('admin.proposal.status', $value->id) }}"
                    class="creative-btn-action action-view"
                    style="padding: 4px 12px;margin: 0px 4px;">
                    <i class="ti-eye text-white"></i>
                </a>

                <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.proposal.delete', $value->id) }}')"
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