@extends('layouts.dashboard')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i
                                                class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page">Leads From</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <style type="text/css">
                .pagination li a {
                    padding: 5px;
                }
            </style>

            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            @include('layouts.flash-message')

                            <div class="box-body">

                                {{-- ============ FORM 1: Bulk Upload (now properly self-contained) ============ --}}
                                <form action="{{ route('admin.leads.from.bulk.upload') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row" style="margin-bottom: 40px;">
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.status.list.view') }}" class="btn btn-primary">
                                                Add Leads Status
                                            </a>
                                        </div>
                                        <div class="col-md-6"></div>
                                        <div class="col-md-3">
                                            <span class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modal-default" style="float: right;">
                                                Add Leads From
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-md-3">
                                            <div class="corp-action-bar">
                                                <div class="corp-action-left">
                                                    <div class="corp-upload-wrapper p-0">
                                                        <input type="File"
                                                            class="form-control @error('upload_file') is-invalid @enderror"
                                                            id="upload_file" name="upload_file" placeholder="Business Name"
                                                            value="{{ old('upload_file') }}">
                                                        @error('upload_file')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                        <a href="<?php echo url(''); ?>/public/admin_assets/images/bulk_lead_form.xlsx"
                                                            download class="corp-sample-link"
                                                            style="margin-right: 8px;">Sample File</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="mb-3 creative-input-group in-bord">
                                                    <select name="service_id" class="form-control select2" id="service_id">
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
                                        <div class="col-md-2">
                                            <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                                        </div>
                                    </div>
                                </form>
                                {{-- ============ FORM 1 CLOSED — everything below is NOT inside a form ============ --}}

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group in-bord mb-3">
                                                <input type="date"
                                                    class="form-control @error('start_date') is-invalid @enderror"
                                                    name="start_date" id="start_date" value="{{ $start_date ?? '' }}"
                                                    onfocus="'showPicker' in this && this.showPicker()">
                                            </div>
                                            @error('start_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group in-bord mb-3">
                                                <input type="date"
                                                    class="form-control @error('end_date') is-invalid @enderror"
                                                    name="end_date" id="end_date" value="{{ $end_date ?? '' }}"
                                                    onfocus="'showPicker' in this && this.showPicker()">
                                            </div>
                                            @error('end_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group in-bord mb-3">
                                                <select name="service_search" class="form-control select2"
                                                    id="service_search">
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
                                            @error('service_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group in-bord mb-3">
                                                <select name="salesperson" class="form-control select2" id="salesperson">
                                                    <option value="">Select Staff</option>
                                                    @if ($salesperson)
                                                        @foreach ($salesperson as $key => $value1)
                                                            <?php
                                                            $user_details = \App\Models\User::where('id', $value1->added_by)->where('status', 'Active')->first();
                                                            ?>
                                                            @if ($user_details)
                                                                <option value="{{ $value1->added_by }}"
                                                                    @if ($value1->added_by == $salesperson1) selected @endif>
                                                                    {{ $user_details->name }}
                                                                </option>
                                                            @endif
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
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group in-bord mb-3">
                                                <select name="status_id" class="form-control select2" id="status_id">
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
                                            @error('service_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <span class="btn btn-primary" onclick="sort_book()">Submit</span>
                                    </div>
                                    <div class="col-md-1">
                                        <a href="" id="image_url" class="btn btn-success"
                                            style="padding: 4px 10px;margin: 0px 2px;"><i class="fa fa-download"></i></a>
                                    </div>
                                </div>

                                <div class="table-responsive creative-table-wrapper">
                                    <table id="example_render"
                                        class=" creative-table table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                        <thead>
                                            <tr>
                                                <th>S No</th>
                                                @if (auth()->user()->user_type == 'super_admin')
                                                    <th>Added_by</th>
                                                @endif
                                                <th>Date</th>
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
                                                    <tr
                                                        @if ($value->status == 'Completed') style="background: #50f90733;" @endif>
                                                        <td class="text-muted fw-bold sorting_1">{{ $key + 1 }}</td>
                                                        <td>
                                                            <div class="icon-text-wrapper mb-1">
                                                                <i class="bi bi-person-fill text-success"></i>
                                                                <span
                                                                    class="fw-semibold">{{ $value->user_details->name ?? '' }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="icon-text-wrapper mb-1">
                                                                <i class="bi bi-calendar-fill text-success"></i>
                                                                <span
                                                                    class="fw-semibold">{{ date('d-m-Y', strtotime($value->created_at)) }}</span>
                                                            </div>
                                                        </td>
                                                        <td>{{ $value->name }}</td>
                                                        <td>
                                                            <div class="icon-text-wrapper mb-1">
                                                                <i class="bi bi-telephone-fill text-success"></i>
                                                                <span class="fw-semibold">{{ $value->contact_no }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="table-text" title="{{ $value->business_name }}">
                                                                {{ $value->business_name }}
                                                            </span>
                                                        </td>


                                                        <td>
                                                            <span class="table-text"
                                                                title="{{ $value->service_get->name ?? '' }}">
                                                                {{ $value->service_get->name ?? '' }}
                                                            </span>
                                                        </td>


                                                        <td>
                                                            <span class="table-text"
                                                                title="@if (is_numeric($value->status)) {{ optional($value->lead_status)->name }}@else{{ $value->status }} @endif">
                                                                @if (is_numeric($value->status))
                                                                    {{ optional($value->lead_status)->name }}
                                                                @else
                                                                    {{ $value->status }}
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a onclick="ViewTaskModel('{{ $value->id }}')"
                                                                class="creative-btn-action action-edit"
                                                                style="padding: 4px 12px;margin: 10px 4px;"><i
                                                                    class="ion ion-edit text-white"></i></a>
                                                            <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.leads.from.delete', $value->id) }}'}"
                                                                class="creative-btn-action action-delete"
                                                                style="padding: 4px 12px;margin: 10px 4px;"><i
                                                                    class="ti-trash text-white"></i></a>
                                                            @if ($value->status == 'Completed')
                                                            @else
                                                                <a onclick="ViewTaskModel1('{{ $value->id }}')"
                                                                    class="creative-btn-action action-user"
                                                                    style="padding: 4px 12px;margin: 10px 4px;"><i
                                                                        class="ti-eye text-white"></i></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="row gy-4 align-items-center" id="seach_hide" style="margin-top: 30px;">
                                        <div class="col-12">
                                            {!! $leads_from->withQueryString()->links('pagination::bootstrap-5') !!}
                                        </div>
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
        </div>
    </div>
    <!-- /.content-wrapper -->

    {{-- ============ Add / Edit Lead Modal ============ --}}
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Leads From</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">
                    <form class="form" action="{{ route('admin.leads.from.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="conference_id" id="conference_id">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Name</label>
                                <div class="input-group in-bord mb-3">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Name" value="{{ old('name') }}">
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
                                <div class="input-group in-bord mb-3">
                                    <input type="text" class="form-control @error('contact_no') is-invalid @enderror"
                                        id="contact_no" name="contact_no" placeholder="Contact No"
                                        value="{{ old('contact_no') }}">
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
                                <div class="input-group in-bord mb-3">
                                    <input type="text"
                                        class="form-control @error('business_name') is-invalid @enderror"
                                        id="business_name" name="business_name" placeholder="Business Name"
                                        value="{{ old('business_name') }}">
                                </div>
                                @error('business_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Service</label>
                                <div class="input-group in-bord mb-3">
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
                                <div class="input-group in-bord mb-3">
                                    <select name="status" class="form-control @error('status') is-invalid @enderror"
                                        id="status">
                                        <option value="">Select Status</option>
                                        @if ($lead_status)
                                            @foreach ($lead_status as $key => $value)
                                                <option value="{{ $value->id }}"
                                                    @if (old('status') == $value->id) selected @endif>
                                                    {{ $value->name }}
                                                </option>
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
        </div>
    </div>

    {{-- ============ Report / Change Status Modal ============ --}}
    <div class="modal fade" id="modal-default1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Leads From</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">
                    <form class="form" action="{{ route('admin.leads.from.report') }}" method="post">
                        @csrf
                        <input type="hidden" name="leads_from" id="leads_from1">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Service</label>
                                <div class="input-group in-bord mb-3">
                                    {{-- id changed from "service" to "service_report" to avoid duplicate-id clash with modal-default --}}
                                    <select name="service" class="form-control" id="service_report">
                                        <option value="">Select Status</option>
                                        @if ($lead_status)
                                            @foreach ($lead_status as $key => $value)
                                                <option value="{{ $value->id }}"
                                                    @if (old('service') == $value->id) selected @endif>
                                                    {{ $value->name }}
                                                </option>
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
        </div>
    </div>

    <?php
    $current_route = Route::currentRouteName();
    $current_route1 = route('admin.leads.from.download');
    ?>

    <script src="<?php echo url(''); ?>/public/admin_assets/js/vendors.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">
        function ViewTaskModel(ref) {
            var ele = ref;
            $.ajax({
                url: '{{ route('admin.leads.from.edit') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ele
                },
                success: function(response) {
                    console.log(response);
                    $("#conference_id").val(response.conference_id); // ✅ restored — needed for update vs insert
                    $("#name").val(response.name);
                    $("#business_name").val(response.business_name);
                    $("#contact_no").val(response.contact_no);
                    $("#status").val(response.status);
                    $("#service option[value='" + response.service + "']").attr("selected", "selected");
                    $('#modal-default').modal('show');
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Could not load lead details. Please try again.');
                }
            });
        }

        function ViewTaskModel1(ref) {
            $("#leads_from1").val(ref);
            $('#modal-default1').modal('show');
        }

        var deleteLinks = document.querySelectorAll('.delete');
        for (var i = 0; i < deleteLinks.length; i++) {
            deleteLinks[i].addEventListener('click', function(event) {
                event.preventDefault();
                var choice = confirm(this.getAttribute('data-confirm'));
                if (choice) {
                    window.location.href = this.getAttribute('href');
                }
            });
        }

        function sort_book() {
            var expert_search_url = "{{ route($current_route) }}";
            var start_date = $("#start_date").val() || "";
            var end_date = $("#end_date").val() || "";
            var service = $("#service_search").val() || "";
            var salesperson = $("#salesperson").val() || "";
            var status = $("#status_id").val() || "";

            var str_search_request = "&start_date=" + start_date + "&end_date=" + end_date + "&status=" + status +
                "&salesperson=" + salesperson + "&service=" + service;

            window.location.href = expert_search_url + '?' + str_search_request;
        }

        function PdfDownload() {
            var expert_search_url1 = "{{ $current_route1 }}";
            var start_date = $("#start_date").val() || "";
            var end_date = $("#end_date").val() || "";
            var service = $("#service_search").val() || "";
            var salesperson = $("#salesperson").val() || "";
            var status = $("#status_id").val() || "";

            var str_search_request = "&start_date=" + start_date + "&end_date=" + end_date + "&status=" + status +
                "&salesperson=" + salesperson + "&service=" + service;

            var pdf_url = expert_search_url1 + '?' + str_search_request;
            $('#image_url').attr('href', pdf_url);
        }

        PdfDownload();
    </script>
@endsection
