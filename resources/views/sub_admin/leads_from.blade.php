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
                        <h3 class="page-title">Leads From</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i
                                                class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page">Leads From</li>
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
                                <form action="{{ route('sub_admin.leads.from.bulk.upload') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row" style="margin-bottom: 10px;">
                                        @if (in_array('72', $role_section))
                                            <div class="col-md-3">
                                                <a class="btn btn-primary"
                                                    href="{{ route('sub_admin.status.list.view') }}">Leads Status</a>
                                            </div>
                                            <div class="col-md-6"></div>
                                        @endif
                                        @if (in_array('68', $role_section))
                                            <div class="col-md-3">
                                                <span class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modal-default" style="float: right;">
                                                    Add Leads From
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row" style="margin-bottom: 10px;">
                                        @if (in_array('71', $role_section))
                                            {{-- <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="input-group mb-3">
                                                        <select name="service_id" class="form-control" id="service_id">
                                                            <option value="">Select Service</option>
                                                            @if ($service_id)
                                                                @foreach ($service_id as $key => $value)
                                                                    <option value="{{ $value->id }}">{{ $value->name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    @error('service_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div> --}}
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="input-group mb-3">
                                                        <input type="File"
                                                            class="form-control @error('upload_file') is-invalid @enderror"
                                                            id="upload_file" name="upload_file" placeholder="Business Name"
                                                            value="{{ old('upload_file') }}">
                                                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                                    </div>
                                                    @error('upload_file')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <a href="<?php echo url(''); ?>/public/admin_assets/images/bulk_lead_form.xlsx"
                                                    download>Sample File</a>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                                            </div>
                                        @endif
                                    </div>
                                </form>
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="date"
                                                    class="form-control @error('start_date') is-invalid @enderror"
                                                    name="start_date" id="start_date" value="{{ $start_date ?? '' }}"
                                                    onfocus="'showPicker' in this && this.showPicker()">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="date"
                                                    class="form-control @error('end_date') is-invalid @enderror"
                                                    name="end_date" id="end_date" value="{{ $end_date ?? '' }}"
                                                    onfocus="'showPicker' in this && this.showPicker()">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <select name="service_search" class="form-control" id="service_search">
                                                    <option value="">Select Service</option>
                                                    @if ($service_id)
                                                        @foreach ($service_id as $key => $value)
                                                            <option value="{{ $value->id }}"
                                                                @if ($value->id == $service_get1) selected @endif>
                                                                {{ $value->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <select name="salesperson" class="form-control" id="salesperson">
                                                    <option value="">Select Staff</option>
                                                    @if ($salesperson)
                                                        @foreach ($salesperson as $key => $value1)
                                                            <?php $user_details = \App\Models\User::where('id', $value1->added_by)->where('status', 'Active')->first(); ?>
                                                            @if ($user_details)
                                                                <option value="{{ $value1->added_by }}"
                                                                    @if ($value1->added_by == $salesperson1) selected @endif>
                                                                    {{ $user_details->name ?? 'Unknown' }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <select name="status_id" class="form-control" id="status_id">
                                                    <option value="">Select Status</option>
                                                    @if ($lead_status)
                                                        @foreach ($lead_status as $key => $value2)
                                                            <option value="{{ $value2->id }}"
                                                                @if ($value2->id == $status) selected @endif>
                                                                {{ $value2->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <span class="btn btn-primary" onclick="sort_book()">Submit</span>
                                    </div>
                                    <div class="col-md-1">
                                        <a href="" id="image_url" class="btn btn-success"
                                            style="padding: 4px 10px;"><i class="fa fa-download"></i></a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>S No</th>
                                                <th>Added By</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Business Name</th>
                                                <th>Service</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($leads_from)
                                                @foreach ($leads_from as $key => $value)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $value->user_details->name ?? '' }}</td>
                                                        <td>{{ $value->name }}</td>
                                                        <td>{{ $value->contact_no }}</td>
                                                        <td>{{ $value->business_name }}</td>
                                                        <td>{{ $value->service_get->name ?? '' }}</td>
                                                        <td>
                                                            @if (is_numeric($value->status))
                                                                {{ optional($value->lead_status)->name }}
                                                            @else
                                                                {{ $value->status }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (in_array('69', $role_section))
                                                                <a onclick="ViewTaskModel('{{ $value->id }}')"
                                                                    class="btn btn-primary"
                                                                    style="padding: 4px 12px;margin: 10px 4px;"><i
                                                                        class="ion ion-edit text-white"></i></a>
                                                            @endif
                                                            @if (in_array('70', $role_section))
                                                                <a href="javascript:void(0);"onclick="deleteProject('{{ route('sub_admin.leads.from.delete', $value->id) }}')"
                                                                    class="btn btn-danger"
                                                                    style="padding: 4px 12px;margin: 10px 4px;"><i
                                                                        class="ti-trash text-white"></i></a>
                                                            @endif
                                                            <a onclick="ViewTaskModel1('{{ $value->id }}')"
                                                                class="btn btn-success"
                                                                style="padding: 4px 12px;margin: 10px 4px;"><i
                                                                    class="ti-eye text-white"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row gy-4 align-items-center" style="margin-top: 30px;">
                                    <div class="col-12">
                                        {!! $leads_from->withQueryString()->links('pagination::bootstrap-5') !!}
                                    </div>
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
                    <h4 class="modal-title">Leads From</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">

                    <form class="form" action="{{ route('sub_admin.leads.from.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="conference_id" id="conference_id">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Name</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Name" value="{{ old('name') }}">
                                    <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                </div>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Contact No</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('contact_no') is-invalid @enderror"
                                        id="contact_no" name="contact_no" placeholder="Contact No"
                                        value="{{ old('contact_no') }}">
                                    <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                </div>
                                @error('contact_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Business Name</label>
                                <div class="input-group mb-3">
                                    <input type="text"
                                        class="form-control @error('business_name') is-invalid @enderror"
                                        id="business_name" name="business_name" placeholder="Business Name"
                                        value="{{ old('business_name') }}">
                                    <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                </div>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Service</label>
                                <div class="input-group mb-3">
                                    <select name="service" class="form-control" id="service">
                                        <option value="">Select Service</option>
                                        @if ($service)
                                            @foreach ($service as $key => $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
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
                                    <select name="status" class="form-control" id="status">
                                        <option value="">Select Status</option>
                                        @if ($lead_status)
                                            @foreach ($lead_status as $key => $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('status')
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
                    <h4 class="modal-title">Leads Status</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">

                    <form class="form" action="{{ route('sub_admin.leads.from.report') }}" method="post">
                        @csrf
                        <input type="hidden" name="leads_from" id="leads_from1">


                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Service</label>
                                <div class="input-group mb-3">
                                    <select name="service" class="form-control" id="service">
                                        <option value="">Select Service</option>
                                        @if ($lead_status)
                                            @foreach ($lead_status as $key => $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('service')
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

    <script src="<?php echo url(''); ?>/public/admin_assets/js/vendors.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    
    <script type="text/javascript">
       

        function ViewTaskModel(ref) {
            var ele = ref;
            // alert(ele);
            $.ajax({
                url: '{{ route('sub_admin.leads.from.edit') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ele
                },
                success: function(response) {
                    console.log(response);
                    $("#conference_id").val(response.conference_id);
                    $("#name").val(response.name);
                    $("#business_name").val(response.business_name);
                    $("#contact_no").val(response.contact_no);
                    $("#status").val(response.status);
                    $("#service option[value='" + response.service + "']").attr("selected", "selected");
                    $('#modal-default').modal('show');
                }
            });
        }

        function ViewTaskModel1(ref) {
            $("#leads_from1").val(ref);
            $('#modal-default1').modal('show');
        }

        function sort_book() {
            var expert_search_url =
                "{{ route('sub_admin.leads.from.view') }}"; // replace with your actual sub_admin route name
            var start_date = $("#start_date").val() || "";
            var end_date = $("#end_date").val() || "";
            var service = $("#service_search").val() || "";
            var salesperson = $("#salesperson").val() || "";
            var status = $("#status_id").val() || "";
            var str = "&start_date=" + start_date + "&end_date=" + end_date + "&status=" + status +
                "&salesperson=" + salesperson + "&service=" + service;
            window.location.href = expert_search_url + '?' + str;
        }
    </script>
    <script>
          $(document).ready(function() {
            $('#example1').DataTable({
                paging: false, // Laravel's paginator already handles paging server-side
                info: false, // avoid duplicate "showing X of Y" info clashing with Laravel's links
                searching: true, // enables the search box, filters only the current page's rows
                ordering: true,
                columnDefs: [{
                        orderable: false,
                        targets: [0, 7]
                    } // disable sorting on checkbox column and action column
                ]
            });
        });
        </script>
@endsection
