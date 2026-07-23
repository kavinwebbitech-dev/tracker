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

    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h3 class="page-title">Leads Whatsapp</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i
                                                class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page">Leads Whatsapp</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            @include('layouts.flash-message')
                            <div class="box-body">

                                {{-- Bulk Upload Form (permission 704) --}}
                                @if (in_array('904', $role_section))
                                    <form action="{{ route('sub_admin.leads.whatsapp.bulk.upload') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row" style="margin-bottom: 10px;">
                                            {{-- <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="input-group in-bord mb-3">
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
                                                    <div class="input-group in-bord mb-3">
                                                        <input type="File"
                                                            class="form-control @error('upload_file') is-invalid @enderror"
                                                            id="upload_file" name="upload_file" placeholder="Upload Excel"
                                                            value="{{ old('upload_file') }}">
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
                                                <input type="submit" name="submit" class="btn btn-primary" value="Upload">
                                            </div>
                                        </div>
                                    </form>
                                @endif

                                {{-- Filter row - STATUS DROPDOWN REMOVED. Always "Phone not picked", controller enforces it --}}
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="input-group in-bord mb-3">
                                                <input type="date"
                                                    class="form-control @error('start_date') is-invalid @enderror"
                                                    name="start_date" id="start_date" value="{{ $start_date ?? '' }}"
                                                    onfocus="'showPicker' in this && this.showPicker()">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="input-group in-bord mb-3">
                                                <input type="date"
                                                    class="form-control @error('end_date') is-invalid @enderror"
                                                    name="end_date" id="end_date" value="{{ $end_date ?? '' }}"
                                                    onfocus="'showPicker' in this && this.showPicker()">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="input-group in-bord mb-3">
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
                                    <div class="col-md-1">
                                        <span class="btn btn-primary" onclick="sort_book()">Submit</span>
                                    </div>

                                </div>
                                {{-- Download Excel: single button, auto-switches between All / Filtered based on active filters --}}
                                {{-- <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-12 text-end">
                                        @if ($start_date || $end_date || $service_get1)
                                            <a href="{{ route('staff.leads.whatsapp.download', ['start_date' => $start_date, 'end_date' => $end_date, 'service' => $service_get1]) }}"
                                                class="btn btn-outline-success btn-sm">
                                                <i class="ti-filter"></i> Download Excel (Filtered)
                                            </a>
                                        @else
                                            <a href="{{ route('staff.leads.whatsapp.download') }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="ti-download"></i> Download Excel (All)
                                            </a>
                                        @endif
                                    </div>
                                </div> --}}
                                {{-- Floating Bulk Action Bar - only visible when checkboxes are selected --}}
                                <div id="bulkActionBar"
                                    style="display:none; position:sticky; top:10px; z-index:999; text-align:right; margin-bottom:10px;">
                                    <span class="badge bg-primary" style="font-size:14px; padding:8px 12px;">
                                        <span id="bulkSelectedCount">0</span> Selected
                                    </span>
                                    <button type="button" class="btn btn-info btn-sm" onclick="openBulkStatusModal()"
                                        style="padding: 4px 12px;margin: 10px 4px;"><i class="ti-eye text-white"></i></a>
                                    </button>
                                    @if (in_array('903', $role_section))
                                        <button type="button" class="btn btn-danger btn-sm" onclick="bulkDelete()">
                                            <i class="ti-trash"></i> Delete Selected
                                        </button>
                                    @endif
                                    {{-- <button type="button" class="btn btn-success btn-sm" onclick="bulkSend()">
                                        <i class="ti-share"></i> Send Whatsapp
                                    </button> --}}
                                </div>

                                <div class="table-responsive">
                                    <table id="whatsapp_leads_table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width:40px;">
                                                    <input type="checkbox" id="checkAll" class="lead-checkbox"
                                                        style="width:18px;height:18px;opacity:1 !important;position:static !important;display:inline-block !important;-webkit-appearance:checkbox !important;appearance:checkbox !important;">
                                                </th>
                                                <th>S No</th>
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
                                                        <td>
                                                            <input type="checkbox" name="lead_ids[]"
                                                                value="{{ $value->id }}"
                                                                class="row-check lead-checkbox"
                                                                style="width:18px;height:18px;opacity:1 !important;position:static !important;display:inline-block !important;-webkit-appearance:checkbox !important;appearance:checkbox !important;">
                                                        </td>
                                                        <td>{{ $key + 1 }}</td>
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
                                                            @if (in_array('902', $role_section))
                                                                <a onclick="ViewTaskModel('{{ $value->id }}')"
                                                                    class="btn btn-primary"
                                                                    style="padding: 4px 12px;margin: 0px 4px;"><i
                                                                        class="ion ion-edit text-white"></i></a>
                                                            @endif
                                                            @if (in_array('903', $role_section))
                                                                <a href="javascript:void(0);"onclick="deleteProject('{{ route('sub_admin.leads.whatsapp.delete', $value->id) }}')"
                                                                    
                                                                    class="btn btn-danger"
                                                                    style="padding: 4px 12px;margin: 0px 4px;"><i
                                                                        class="ti-trash text-white"></i></a>
                                                            @endif
                                                            {{-- Eye icon now opens the SINGLE-row status view modal --}}
                                                            <a onclick="ViewSingleStatus('{{ $value->id }}')"
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
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- Edit Modal (name/contact/business/service/status - unchanged) --}}
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Leads Whatsapp</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">
                    <form class="form" action="{{ route('sub_admin.leads.whatsapp.store') }}" method="post">
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
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
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
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
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
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
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
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="input-group in-bord mb-3">
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
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
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


    {{-- Bulk Status Update Modal (works on selected checkboxes) --}}
    <div class="modal fade" id="modal-default1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Leads Status</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">
                    <p><strong><span id="bulk_status_count">0</span></strong> lead(s) selected.</p>

                    <form class="form" action="{{ route('sub_admin.leads.whatsapp.bulk.report') }}" method="post">
                        @csrf
                        <div id="bulk_status_ids"></div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="input-group in-bord mb-3">
                                    <select name="service" class="form-control" id="report_status" required>
                                        <option value="">Select Status</option>
                                        @if ($lead_status)
                                            @foreach ($lead_status as $key => $value)
                                                <option value="{{ $value->name }}">{{ $value->name }}</option>
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
    {{-- Single Row View/Status Modal --}}
    <div class="modal fade" id="modal-single-status">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Lead Status</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 mb-3">
                        <p><strong>Name:</strong> <span id="single_view_name"></span></p>
                        <p><strong>Contact No:</strong> <span id="single_view_contact_no"></span></p>
                        <p><strong>Business Name:</strong> <span id="single_view_business_name"></span></p>
                    </div>

                    <form class="form" action="{{ route('sub_admin.leads.whatsapp.bulk.report') }}" method="post">
                        @csrf
                        <input type="hidden" name="leads_from[]" id="single_leads_from">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="input-group in-bord mb-3">
                                    <select name="service" class="form-control" id="single_report_status" required>
                                        <option value="">Select Status</option>
                                        @if ($lead_status)
                                            @foreach ($lead_status as $key => $value)
                                                <option value="{{ $value->name }}">{{ $value->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
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

    {{-- Hidden bulk forms --}}
    <form id="bulkDeleteForm" action="{{ route('sub_admin.leads.whatsapp.bulk.delete') }}" method="post"
        style="display:none;">
        @csrf
        <div id="bulkDeleteIds"></div>
    </form>

    <form id="bulkSendForm" action="{{ route('sub_admin.leads.whatsapp.bulk.send') }}" method="post"
        style="display:none;">
        @csrf
        <div id="bulkSendIds"></div>
    </form>

    <script src="<?php echo url(''); ?>/public/admin_assets/js/vendors.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#whatsapp_leads_table').DataTable({
                paging: true, // Laravel's paginator already handles paging server-side
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

        function ViewTaskModel(ref) {
            $.ajax({
                url: '{{ route('sub_admin.leads.whatsapp.edit') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ref
                },
                success: function(response) {
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

        // SINGLE ROW: eye icon fetches lead details, opens single modal, pre-fills that one id
        function ViewSingleStatus(ref) {
            $.ajax({
                url: '{{ route('sub_admin.leads.whatsapp.edit') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ref
                },
                success: function(response) {
                    $("#single_view_name").text(response.name ?? '-');
                    $("#single_view_contact_no").text(response.contact_no ?? '-');
                    $("#single_view_business_name").text(response.business_name ?? '-');
                    $("#single_leads_from").val(ref);
                    $("#single_report_status").val('');
                    $('#modal-single-status').modal('show');
                }
            });
        }

        // BULK: opens the multi-row status modal using currently checked rows
        function openBulkStatusModal() {
            var ids = getSelectedIds();

            if (ids.length === 0) {
                alert('Please select at least one lead using the checkboxes.');
                return;
            }

            $('#bulk_status_count').text(ids.length);

            var container = $('#bulk_status_ids').empty();
            ids.forEach(function(id) {
                container.append('<input type="hidden" name="leads_from[]" value="' + id + '">');
            });

            $('#report_status').val('');
            $('#modal-default1').modal('show');
        }

        function sort_book() {
            var expert_search_url = "{{ route('sub_admin.leads.whatsapp.view') }}";
            var start_date = $("#start_date").val() || "";
            var end_date = $("#end_date").val() || "";
            var service = $("#service_search").val() || "";
            var str = "&start_date=" + start_date + "&end_date=" + end_date + "&service=" + service;
            window.location.href = expert_search_url + '?' + str;
        }

        // Show/hide bulk action bar + update count whenever checkboxes change
        function refreshBulkBar() {
            var count = $('.row-check:checked').length;
            if (count > 0) {
                $('#bulkActionBar').show();
                $('#bulkSelectedCount').text(count);
            } else {
                $('#bulkActionBar').hide();
            }
        }

        $(document).on('change', '#checkAll', function() {
            $('.row-check').prop('checked', $(this).is(':checked'));
            refreshBulkBar();
        });

        $(document).on('change', '.row-check', function() {
            refreshBulkBar();
        });

        function getSelectedIds() {
            var ids = [];
            $('.row-check:checked').each(function() {
                ids.push($(this).val());
            });
            return ids;
        }

        function bulkDelete() {
            var ids = getSelectedIds();
            if (ids.length === 0) {
                alert('Please select at least one lead.');
                return;
            }
            if (!confirm('Delete ' + ids.length + ' selected lead(s)?')) return;
            var container = $('#bulkDeleteIds').empty();
            ids.forEach(function(id) {
                container.append('<input type="hidden" name="lead_ids[]" value="' + id + '">');
            });
            $('#bulkDeleteForm').submit();
        }

        function bulkSend() {
            var ids = getSelectedIds();
            if (ids.length === 0) {
                alert('Please select at least one lead.');
                return;
            }
            if (!confirm('Send Whatsapp message to ' + ids.length + ' selected lead(s)?')) return;
            var container = $('#bulkSendIds').empty();
            ids.forEach(function(id) {
                container.append('<input type="hidden" name="lead_ids[]" value="' + id + '">');
            });
            $('#bulkSendForm').submit();
        }
    </script>
@endsection
