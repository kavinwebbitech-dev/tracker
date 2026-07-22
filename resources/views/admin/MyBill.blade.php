@extends('layouts.dashboard')

@section('content')

    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h3 class="page-title">My Bills</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i
                                                class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page">My Bills</li>
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
                                <div class="row">
                                    <div class="col-md-9"></div>
                                    <div class="col-md-3">
                                        <span class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-default"
                                            style="float: right;">
                                            Add Bills
                                        </span>
                                    </div>
                                </div>

                                <div class="table-responsive creative-table-wrapper">
                                    <table id="example_render"
                                        class="table creative-table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                        <thead>
                                            <tr>
                                                <th>S No</th>
                                                <th>Name</th>
                                                <th>Amount</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Repeat</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        {{-- <tbody>
                                            @if ($MyBill)
                                                @foreach ($MyBill as $key => $value)
                                                    <?php
                                                    $daysLeft = intval((strtotime($value->end_date) - strtotime(date('Y-m-d'))) / 86400);
                                                    $rowClass = '';
                                                    $badgeText = '';
                                                    $badgeClass = '';
                                                    
                                                    if ($daysLeft < 0) {
                                                        $rowClass = 'table-danger';
                                                        $badgeText = 'Overdue';
                                                        $badgeClass = 'bg-danger';
                                                    } elseif ($daysLeft <= 2) {
                                                        $rowClass = 'table-warning';
                                                        $badgeText = $daysLeft == 0 ? 'Due Today' : $daysLeft . ' day(s) left';
                                                        $badgeClass = 'bg-warning text-dark';
                                                    }
                                                    ?>
                                                    <tr class="{{ $rowClass }}">
                                                        <td class="text-muted fw-bold sorting_1">{{ $key + 1 }}</td>

                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <div class="cell-icon icon-name">
                                                                    <i class="bi bi-receipt-cutoff"></i>
                                                                </div>
                                                                <span class="fw-semibold">{{ $value->name }}</span>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <i class="bi bi-currency-rupee text-success"></i>
                                                                <span class="fw-bold">{{ $value->bill_amount }}</span>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <i class="bi bi-calendar-event-fill text-primary"></i>
                                                                <span class="fw-semibold">
                                                                    {{ date('d-m-Y', strtotime($value->start_date)) }}
                                                                </span>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <i class="bi bi-calendar-check-fill text-warning"></i>
                                                                <span class="fw-semibold">
                                                                    {{ date('d-m-Y', strtotime($value->end_date)) }}
                                                                </span>
                                                                @if ($badgeText)
                                                                    <span class="badge {{ $badgeClass }} ms-1">{{ $badgeText }}</span>
                                                                @endif
                                                            </div>
                                                        </td>

                                                        <td>
                                                            @if ($value->repeat_type)
                                                                <span class="badge bg-info text-dark">
                                                                    Every {{ $value->repeat_count }} {{ ucfirst($value->repeat_type) }}(s)
                                                                </span>
                                                            @else
                                                                <span class="text-muted">No Repeat</span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <span class="creative-badge creative-badge-active">
                                                                    <i class="bi bi-check-circle-fill"></i>
                                                                    {{ $value->status }}
                                                                </span>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <p style="display: flex;">
                                                                <a onclick="ViewTaskModel('{{ $value->id }}')"
                                                                    class="creative-btn-action action-edit"
                                                                    style="padding: 4px 12px;margin: 0px 4px;">
                                                                    <i class="ion ion-edit text-white"></i>
                                                                </a>
                                                                <a href="{{ route('admin.my.bills.delete', $value->id) }}"
                                                                    class="creative-btn-action action-delete"
                                                                    style="padding: 4px 12px;margin: 0px 4px;">
                                                                    <i class="ti-trash text-white"></i>
                                                                </a>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody> --}}
                                        <tbody id="billsTableBody">
                                            @if ($MyBill)
                                                @foreach ($MyBill as $key => $value)
                                                    <?php
                                                    $daysLeft = intval((strtotime($value->end_date) - strtotime(date('Y-m-d'))) / 86400);
                                                    $rowClass = '';
                                                    $badgeText = '';
                                                    $badgeClass = '';
                                                    $rowCategory = 'other';
                                                    
                                                    if ($daysLeft < 0) {
                                                        $rowClass = 'table-danger';
                                                        $badgeText = 'Overdue';
                                                        $badgeClass = 'bg-danger';
                                                        $rowCategory = 'overdue';
                                                    } elseif ($daysLeft == 0) {
                                                        $rowClass = 'table-warning';
                                                        $badgeText = 'Due Today';
                                                        $badgeClass = 'bg-warning text-dark';
                                                        $rowCategory = 'today';
                                                    } elseif ($daysLeft == 1) {
                                                        $rowClass = 'table-warning';
                                                        $badgeText = '1 day(s) left';
                                                        $badgeClass = 'bg-warning text-dark';
                                                        $rowCategory = 'oneday';
                                                    } elseif ($daysLeft == 2) {
                                                        $rowClass = 'table-warning';
                                                        $badgeText = '2 day(s) left';
                                                        $badgeClass = 'bg-warning text-dark';
                                                        $rowCategory = 'twoday';
                                                    }
                                                    ?>
                                                    <tr class="{{ $rowClass }}" data-category="{{ $rowCategory }}">
                                                        <td class="text-muted fw-bold sorting_1">{{ $key + 1 }}</td>

                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <div class="cell-icon icon-name">
                                                                    <i class="bi bi-receipt-cutoff"></i>
                                                                </div>
                                                                <span class="fw-semibold">{{ $value->name }}</span>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <i class="bi bi-currency-rupee text-success"></i>
                                                                <span class="fw-bold">{{ $value->bill_amount }}</span>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <i class="bi bi-calendar-event-fill text-primary"></i>
                                                                <span class="fw-semibold">
                                                                    {{ date('d-m-Y', strtotime($value->start_date)) }}
                                                                </span>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <i class="bi bi-calendar-check-fill text-warning"></i>
                                                                <span class="fw-semibold">
                                                                    {{ date('d-m-Y', strtotime($value->end_date)) }}
                                                                </span>
                                                                @if ($badgeText)
                                                                    <span
                                                                        class="badge {{ $badgeClass }} ms-1">{{ $badgeText }}</span>
                                                                @endif
                                                            </div>
                                                        </td>

                                                        <td>
                                                            @if ($value->repeat_type)
                                                                <span class="badge bg-info text-dark">
                                                                    Every {{ $value->repeat_count }}
                                                                    {{ ucfirst($value->repeat_type) }}(s)
                                                                </span>
                                                            @else
                                                                <span class="text-muted">No Repeat</span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <span class="creative-badge creative-badge-active">
                                                                    <i class="bi bi-check-circle-fill"></i>
                                                                    {{ $value->status }}
                                                                </span>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <p style="display: flex;">
                                                                <a onclick="ViewTaskModel('{{ $value->id }}')"
                                                                    class="creative-btn-action action-edit"
                                                                    style="padding: 4px 12px;margin: 0px 4px;">
                                                                    <i class="ion ion-edit text-white"></i>
                                                                </a>
                                                                <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.my.bills.delete', $value->id) }}')"
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
                                    <style type="text/css">
                                        .pagination li a {
                                            padding: 6px;
                                        }

                                        .repeat-every-wrapper .repeat-row {
                                            display: flex;
                                            align-items: center;
                                            justify-content: space-between;
                                            padding: 10px 4px;
                                            border-bottom: 1px solid #eee;
                                        }

                                        .repeat-every-wrapper .repeat-row:last-child {
                                            border-bottom: none;
                                        }

                                        .repeat-value-input:disabled {
                                            background: #f2f2f2;
                                            color: #aaa;
                                        }
                                    </style>
                                    <div class="row gy-4 align-items-center" id="seach_hide" style="margin-top: 30px;">
                                        <div class="col-12">
                                            {!! $MyBill->withQueryString()->links('pagination::bootstrap-5') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- ================= ADD BILL MODAL ================= --}}
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">My Bills</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">
                    <form class="form" action="{{ route('admin.my.bills.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="conference_id" id="conference_id">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Name</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Name" value="{{ old('name') }}"
                                        required>
                                </div>
                                @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Bill Amount</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('bill_amount') is-invalid @enderror"
                                        id="bill_amount" name="bill_amount" placeholder="Bill Amount"
                                        value="{{ old('bill_amount') }}" required>
                                </div>
                                @error('bill_amount')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Bill Date</label>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control @error('bill_date') is-invalid @enderror"
                                        id="bill_date" name="bill_date" placeholder="Bill Date"
                                        value="{{ old('bill_date') }}"
                                        onfocus="'showPicker' in this && this.showPicker()" required>
                                </div>
                                @error('bill_date')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- ===== Repeat Every: radio + numeric input per unit ===== --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Repeat Every</label>
                                <div class="repeat-every-wrapper border rounded p-2">

                                    <div class="repeat-row">
                                        <div class="form-check">
                                            <input class="form-check-input repeat-radio" type="radio"
                                                name="repeat_type_radio" id="unit_none" value="none" checked>
                                            <label class="form-check-label" for="unit_none">No Repeat</label>
                                        </div>
                                    </div>

                                    <div class="repeat-row">
                                        <div class="form-check">
                                            <input class="form-check-input repeat-radio" type="radio"
                                                name="repeat_type_radio" id="unit_day" value="day">
                                            <label class="form-check-label" for="unit_day">Day(s)</label>
                                        </div>
                                        <input type="number" min="1" class="form-control repeat-value-input"
                                            id="value_day" placeholder="e.g. 1" style="width:100px;" disabled>
                                    </div>

                                    <div class="repeat-row">
                                        <div class="form-check">
                                            <input class="form-check-input repeat-radio" type="radio"
                                                name="repeat_type_radio" id="unit_week" value="week">
                                            <label class="form-check-label" for="unit_week">Week(s)</label>
                                        </div>
                                        <input type="number" min="1" class="form-control repeat-value-input"
                                            id="value_week" placeholder="e.g. 1" style="width:100px;" disabled>
                                    </div>

                                    <div class="repeat-row">
                                        <div class="form-check">
                                            <input class="form-check-input repeat-radio" type="radio"
                                                name="repeat_type_radio" id="unit_month" value="month">
                                            <label class="form-check-label" for="unit_month">Month(s)</label>
                                        </div>
                                        <input type="number" min="1" class="form-control repeat-value-input"
                                            id="value_month" placeholder="e.g. 6" style="width:100px;" disabled>
                                    </div>

                                    <div class="repeat-row">
                                        <div class="form-check">
                                            <input class="form-check-input repeat-radio" type="radio"
                                                name="repeat_type_radio" id="unit_year" value="year">
                                            <label class="form-check-label" for="unit_year">Year(s)</label>
                                        </div>
                                        <input type="number" min="1" class="form-control repeat-value-input"
                                            id="value_year" placeholder="e.g. 1" style="width:100px;" disabled>
                                    </div>

                                </div>
                                @error('repeat_count')
                                    <span class="invalid-feedback d-block"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror

                                {{-- actual values submitted to the controller --}}
                                <input type="hidden" name="repeat_type" id="repeat_type_final" value="none">
                                <input type="hidden" name="repeat_count" id="repeat_count_final" value="0">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="input-group mb-3">
                                    <select name="status" class="form-control" id="status">
                                        <option value="Active">Active</option>
                                        <option value="In Active">In Active</option>
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

    {{-- ================= EDIT BILL MODAL ================= --}}
    <div class="modal fade" id="modal-default1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">My Bills</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">
                    <form class="form" action="{{ route('admin.my.bills.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="conference_id" id="conference_id1">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Name</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name1" name="name" placeholder="Name" value="{{ old('name') }}"
                                        required>
                                </div>
                                @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Bill Amount</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('bill_amount') is-invalid @enderror"
                                        id="bill_amount1" name="bill_amount" placeholder="Bill Amount"
                                        value="{{ old('bill_amount') }}" required>
                                </div>
                                @error('bill_amount')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Start Date</label>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                        id="start_date" name="start_date" placeholder="Start Date"
                                        value="{{ old('start_date') }}"
                                        onfocus="'showPicker' in this && this.showPicker()" required>
                                </div>
                                @error('start_date')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">End Date</label>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                        id="end_date1" name="end_date" placeholder="End Date"
                                        value="{{ old('end_date') }}" onfocus="'showPicker' in this && this.showPicker()"
                                        required>
                                </div>
                                @error('end_date')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- ===== Repeat Every (edit) ===== --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Repeat Every</label>
                                <div class="repeat-every-wrapper border rounded p-2">

                                    <div class="repeat-row">
                                        <div class="form-check">
                                            <input class="form-check-input repeat-radio-edit" type="radio"
                                                name="repeat_type_radio_edit" id="unit_none_e" value="none" checked>
                                            <label class="form-check-label" for="unit_none_e">No Repeat</label>
                                        </div>
                                    </div>

                                    <div class="repeat-row">
                                        <div class="form-check">
                                            <input class="form-check-input repeat-radio-edit" type="radio"
                                                name="repeat_type_radio_edit" id="unit_day_e" value="day">
                                            <label class="form-check-label" for="unit_day_e">Day(s)</label>
                                        </div>
                                        <input type="number" min="1" class="form-control repeat-value-input-edit"
                                            id="value_day_e" placeholder="e.g. 1" style="width:100px;" disabled>
                                    </div>

                                    <div class="repeat-row">
                                        <div class="form-check">
                                            <input class="form-check-input repeat-radio-edit" type="radio"
                                                name="repeat_type_radio_edit" id="unit_week_e" value="week">
                                            <label class="form-check-label" for="unit_week_e">Week(s)</label>
                                        </div>
                                        <input type="number" min="1" class="form-control repeat-value-input-edit"
                                            id="value_week_e" placeholder="e.g. 1" style="width:100px;" disabled>
                                    </div>

                                    <div class="repeat-row">
                                        <div class="form-check">
                                            <input class="form-check-input repeat-radio-edit" type="radio"
                                                name="repeat_type_radio_edit" id="unit_month_e" value="month">
                                            <label class="form-check-label" for="unit_month_e">Month(s)</label>
                                        </div>
                                        <input type="number" min="1" class="form-control repeat-value-input-edit"
                                            id="value_month_e" placeholder="e.g. 6" style="width:100px;" disabled>
                                    </div>

                                    <div class="repeat-row">
                                        <div class="form-check">
                                            <input class="form-check-input repeat-radio-edit" type="radio"
                                                name="repeat_type_radio_edit" id="unit_year_e" value="year">
                                            <label class="form-check-label" for="unit_year_e">Year(s)</label>
                                        </div>
                                        <input type="number" min="1" class="form-control repeat-value-input-edit"
                                            id="value_year_e" placeholder="e.g. 1" style="width:100px;" disabled>
                                    </div>

                                </div>

                                <input type="hidden" name="repeat_type" id="repeat_type_final_edit" value="none">
                                <input type="hidden" name="repeat_count" id="repeat_count_final_edit" value="0">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="input-group mb-3">
                                    <select name="status" class="form-control" id="status">
                                        <option value="Active">Active</option>
                                        <option value="In Active">In Active</option>
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

    <script src="<?php echo url(''); ?>/public/admin_assets/js/vendors.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            // ---- ADD modal: enable/disable numeric input per selected radio ----
            $('.repeat-radio').on('change', function() {
                $('.repeat-value-input').prop('disabled', true).val('');
                var unit = $(this).val();
                if (unit !== 'none') {
                    var $input = $('#value_' + unit);
                    $input.prop('disabled', false);
                    if (!$input.val()) $input.val(1);
                }
            });

            // ---- EDIT modal: same behavior ----
            $('.repeat-radio-edit').on('change', function() {
                $('.repeat-value-input-edit').prop('disabled', true).val('');
                var unit = $(this).val();
                if (unit !== 'none') {
                    var $input = $('#value_' + unit + '_e');
                    $input.prop('disabled', false);
                    if (!$input.val()) $input.val(1);
                }
            });

            // Reset ADD modal when opened fresh (not via edit)
            $('#modal-default').on('show.bs.modal', function(e) {
                if ($(e.relatedTarget).is('[data-bs-target="#modal-default"]')) {
                    $('.repeat-radio').prop('checked', false);
                    $('#unit_none').prop('checked', true);
                    $('.repeat-value-input').prop('disabled', true).val('');
                }
            });

            // Push correct values into hidden fields before submit (ADD modal)
            $('#modal-default form').on('submit', function() {
                var unit = $('input[name="repeat_type_radio"]:checked').val();
                var value = 0;
                if (unit && unit !== 'none') {
                    value = $('#value_' + unit).val() || 1;
                }
                $('#repeat_type_final').val(unit);
                $('#repeat_count_final').val(value);
            });

            // Push correct values into hidden fields before submit (EDIT modal)
            $('#modal-default1 form').on('submit', function() {
                var unit = $('input[name="repeat_type_radio_edit"]:checked').val();
                var value = 0;
                if (unit && unit !== 'none') {
                    value = $('#value_' + unit + '_e').val() || 1;
                }
                $('#repeat_type_final_edit').val(unit);
                $('#repeat_count_final_edit').val(value);
            });

        });

        function ViewTaskModel(ref) {
            var ele = ref;
            $.ajax({
                url: '{{ route('admin.my.bills.edit') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ele
                },
                success: function(response) {
                    $("#conference_id1").val(response.conference_id);
                    $("#name1").val(response.name);
                    $("#start_date").val(response.start_date);
                    $("#end_date1").val(response.end_date);
                    $("#bill_amount1").val(response.bill_amount);
                    $("#status option[value='" + response.status + "']").attr("selected", "selected");

                    // Reset edit repeat UI first
                    $('.repeat-radio-edit').prop('checked', false);
                    $('.repeat-value-input-edit').prop('disabled', true).val('');

                    // Populate repeat fields from server response
                    if (response.repeat_type) {
                        $('#unit_' + response.repeat_type + '_e').prop('checked', true);
                        var $input = $('#value_' + response.repeat_type + '_e');
                        $input.prop('disabled', false).val(response.repeat_count || 1);
                    } else {
                        $('#unit_none_e').prop('checked', true);
                    }

                    $('#modal-default1').modal('show');
                }
            });
        }

        function BulkCheck() {
            $("#input_file_col").show();
            $("#button_col").show();
            $("#bul_upload_col").hide();
        }

        function filterBillsTable(filterKey, el) {
            var $el = $(el);
            var alreadyActive = $el.hasClass('active-filter');

            // Clear active state from all boxes
            $('.reminder-box[data-filter]').removeClass('active-filter');

            if (alreadyActive) {
                // Clicking the same box again -> show all rows
                $('#billsTableBody tr').show();
                return;
            }

            $el.addClass('active-filter');

            $('#billsTableBody tr').each(function() {
                var rowCategory = $(this).data('category');
                $(this).toggle(rowCategory === filterKey);
            });
        }
    </script>
@endsection
