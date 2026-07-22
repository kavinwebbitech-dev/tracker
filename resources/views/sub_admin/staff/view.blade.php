@extends('layouts.sub_admin')

@section('content')
    <?php
    $role_section = json_decode(Auth::user()->permissions);
    if ($role_section) {
        $role_section = $role_section;
    } else {
        $role_section = [];
    }
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h3 class="page-title">Staff</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i
                                                class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page">Staff</li>
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
                                <div class="table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>S No</th>
                                                <!-- <th>Sub Admin Name</th> -->
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($sub_admin)
                                                @foreach ($sub_admin as $key => $value)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $value->name }}</td>
                                                        <td>{{ $value->phone }}</td>
                                                        <td>{{ $value->email }}</td>
                                                        <td>
                                                            @if (in_array('11', $role_section))
                                                                <a href="{{ route('staff.edit', $value->id) }}"
                                                                    class="btn btn-primary"
                                                                    style="padding: 4px 12px;margin: 0px 4px;"><i
                                                                        class="ion ion-edit text-white"></i></a>
                                                            @endif
                                                            @if (in_array('12', $role_section))
                                                               <a href="javascript:void(0);"onclick="deleteProject('{{ route('staff.delete', $value->id) }}')"
                                                                    class="btn btn-danger"
                                                                    style="padding: 4px 12px;margin: 0px 4px;"><i
                                                                        class="ti-trash text-white"></i></a>
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
