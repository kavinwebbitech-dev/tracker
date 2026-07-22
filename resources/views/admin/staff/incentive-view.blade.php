@extends('layouts.dashboard')

@section('content')

    <style type="text/css">
        .theme-primary .select2-container--default.select2-container--open {
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
                        <h3 class="page-title">Staff Incentive</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i
                                                class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page">Staff Incentive</li>
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
                                        <a href="{{ route('sub.task.incentive.add') }}"
                                            class="corp-btn corp-btn-primary add_button" style="float: right;">Add
                                            Incentive</a>
                                    </div>
                                </div>
                                <style type="text/css">
                                    .add_button {
                                        margin-bottom: 10px;
                                    }
                                </style>
                                <div class="table-responsive creative-table-wrapper">
                                    <table id="example1" class="table table-bordered table-striped creative-table">
                                        <thead>
                                            <tr>
                                                <th>S No</th>
                                                <th>Staff Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($group_staff)
                                                @foreach ($group_staff as $key => $value)
                                                    <tr>
                                                        <td class="text-muted fw-bold sorting_1">{{ $key + 1 }}</td>
                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <div class="cell-icon icon-name"><i
                                                                        class="bi bi-person-fill"></i></div>
                                                                <div>
                                                                    <span class="fw-bold text-dark d-block"
                                                                        style="font-size: 0.95rem;">{{ $value->user_details->name ?? '' }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <p style="display: flex;">
                                                                <a href="{{ route('sub.staff.incentive.edit', $value->user_details->id ?? '') }}"
                                                                    class="creative-btn-action action-edit"
                                                                    style="padding: 4px 12px;margin: 0px 4px;"><i
                                                                        class="ion ion-edit text-white"></i></a>
                                                                <a href="javascript:void(0);"
                                                                    onclick="deleteProject('{{ route('sub.staff.incentive.delete', $value->user_details->id ?? '') }}')"
                                                                    class="creative-btn-action action-delete"
                                                                    style="padding: 4px 12px;margin: 0px 4px;"><i
                                                                        class="ti-trash text-white"></i></a>
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
