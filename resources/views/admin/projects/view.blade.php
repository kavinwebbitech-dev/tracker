@extends('layouts.dashboard')

<?php
$page_user_tile = 'Project List - ' . $user_title_name;
?>

@section('meta_name') {{ $page_user_tile }} @stop

@section('content')


    <style type="text/css">
        .count-icons {
            text-align: center;
        }

        .count-icons p {
            font-size: 16px;
            margin-bottom: 5px;
            font-weight: 400;
        }

        .count-icons .count-right-icon {
            margin-right: 10px;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h3 class="page-title">{{ $page_title }} Project List</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i
                                                class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page">Admin</li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ $page_title }} Project List
                                    </li>
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
                                @if ($recordCount != 0)
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.projects.renewal.view') }}"
                                                class="btn btn-primary-light btn-sm add_button">View Notification <span
                                                    style="border-radius: 50px;background: #3762EA;padding: 1px 5px 2px;color: #fff;">{{ $recordCount }}</span></a>
                                        </div>
                                    </div>
                                @endif

                                @if ($start_date || $end_date || $service_get1 || $status || $salesperson1)
                                    <div class="row">
                                        <div class="col-md-2">
                                            <a href="{{ route('admin.projects.view') }}"
                                                style="width: 100%;padding: 10px;font-size: 15px;display: block;margin-bottom: 4px;">Clear
                                                x</a>
                                        </div>
                                        <div class="col-md-10"></div>
                                    </div>
                                @endif
                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-2">
                                        <a class="creative-btn-action action-edit"
                                            href="{{ route('admin.projects.create') }}"
                                            style="    width: 100%;padding: 10px;">Add Projects</a>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="creative-btn-action action-user"
                                            href="{{ route('admin.projects.renewal') }}"
                                            style="    width: 100%;padding: 10px">Renewal Projects</a>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="creative-btn-action btn-success" href="" id="image_url1"
                                            style="width: 100%;padding: 10px">Completed Payment Projects</a>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="creative-btn-action btn-danger" href="" id="image_url2"
                                            style="width: 100%;padding: 10px;background: red !important;">Cancel Payment
                                            Projects</a>
                                    </div>
                                    <!-- <div class="col-md-2"></div> -->
                                    <div class="col-md-2">
                                        <a class="creative-btn-action action-delete" href="" id="image_url"
                                            style="width: 100%;padding: 10px;">Report Download</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="date"
                                                    class="form-control @error('start_date') is-invalid @enderror"
                                                    name="start_date" id="start_date" value="{{ $start_date ?? '' }}"
                                                    required>
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
                                            <div class="input-group mb-3">
                                                <input type="date"
                                                    class="form-control @error('end_date') is-invalid @enderror"
                                                    name="end_date" id="end_date" value="{{ $end_date ?? '' }}" required>
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
                                            <div class="input-group mb-3">
                                                <select
                                                    class="form-control select2 @error('salesperson') is-invalid @enderror"
                                                    name="salesperson" id="salesperson">
                                                    <option value="">Select Sales Person</option>
                                                    @if ($salesperson)
                                                        @foreach ($salesperson as $person)
                                                            @if ($person->added_by)
                                                                @php
                                                                    $user_details = \App\Models\User::where(
                                                                        'id',
                                                                        $person->added_by,
                                                                    )
                                                                        ->where('status', 'active')
                                                                        ->first();
                                                                @endphp

                                                                @if ($user_details)
                                                                    <option value="{{ $user_details->id }}"
                                                                        {{ $salesperson1 == $user_details->id ? 'selected' : '' }}>
                                                                        {{ $user_details->name }}
                                                                    </option>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @error('salesperson')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <select class="form-control select2 @error('service') is-invalid @enderror"
                                                    name="service" id="service">
                                                    <option value="">Select Service</option>
                                                    @if ($service_get)
                                                        @foreach ($service_get as $key => $service)
                                                            <option value="{{ $service->id }}"
                                                                @if ($service_get1 == $service->id) selected @endif>
                                                                {{ $service->name }}</option>
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

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <select class="form-control select2 @error('status') is-invalid @enderror"
                                                    name="status" id="status">
                                                    <option value="all"
                                                        @if ($status == '' || $status == null) selected @endif>All</option>
                                                    <option value="0"
                                                    {{-- @dd($status == 0) --}}
                                                        @if ($status != null && $status == 0) selected @endif>Pending</option>
                                                    <option value="1"
                                                        @if ($status != null && $status == 1) selected @endif>On Progress
                                                    </option>
                                                    <option value="3"
                                                        @if ($status != null && $status == 3) selected @endif>On Hold</option>
                                                    <option value="5"
                                                        @if ($status != null && $status == 5) selected @endif>Done</option>
                                                    <option value="6"
                                                        @if ($status != null && $status == 6) selected @endif>Cancel</option>
                                                </select>
                                            </div>
                                            @error('status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="submit" name="submit" class="btn btn-primary"
                                            onclick="sort_book()" value="Submit">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <input type="text"
                                                    class="form-control @error('add_year') is-invalid @enderror"
                                                    name="add_year" id="add_year" placeholder="Move Year Wise Project">
                                            </div>
                                            @error('add_year')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="btn btn-primary" onclick="text_value_search1()">Submit</span>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-2">
                                        <input type="text" name="text_value_search" id="text_value_search"
                                            class="form-control" onkeyup="text_value_search()"
                                            style="margin-bottom: 10px;">
                                    </div>
                                </div>

                                <style type="text/css">
                                    .add_button {
                                        margin-bottom: 10px;
                                    }
                                </style>
                                <form action="{{ route('admin.projects.year.wise.store') }}" method="post"
                                    id="year_wise_project">
                                    @csrf
                                    <input type="hidden" name="year_wise_year" id="year_wise_year">
                                    <div class="table-responsive creative-table-wrapper">
                                        <table id="example_render"
                                            class="table creative-table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50px;"><input type="checkbox" name="checkAll"
                                                            id="checkAll"></th>
                                                    <th>S No</th>
                                                    <th>Assigned Staff</th>
                                                    <th>Project</th>
                                                    <th>Sales User Name</th>
                                                    <th>Total Amount</th>
                                                    <th>Pending</th>
                                                    <th>Confirm Date</th>
                                                    <th class="noExport">Status</th>
                                                    <th class="noExport">Action</th>
                                                </tr>
                                            </thead>
                                            <?php
                                            $total_amount1 = 0;
                                            $total_pening = 0;
                                            ?>
                                            <tbody>
                                                @if ($users)
                                                    @foreach ($users as $key => $value)
                                                        <?php
                                                        $total_amount = 0;
                                                        $total_penc = 0;
                                                        
                                                        $bit_amounts = $value->bit_amounts;
                                                        
                                                        if (count($bit_amounts) > 0) {
                                                            foreach ($bit_amounts as $key1 => $value1) {
                                                                $total_amount += $value1->fld_project_amount;
                                                            }
                                                        }
                                                        
                                                        $total_penc = $value->bid_amount - $total_amount;
                                                        
                                                        $total_amount1 += $value->bid_amount;
                                                        $total_pening += $total_penc;
                                                        ?>

                                                        <tr @if ($value->status == 6) style="background: #dc3545;" @endif
                                                            @if ($total_penc == 0) style="background: #f6e2f7;" @endif
                                                            @if ($value->refunded == 'refunded') style="background: #ff6464;" @endif>

                                                            <td>
                                                                <input type="checkbox" name="select_invoice[]"
                                                                    value="{{ $value->id }}">
                                                            </td>

                                                            <td class="text-muted fw-bold sorting_1">
                                                                {{ $key + 1 }}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    // Collect all unique staff IDs from all tasks of this project
                                                                    $allStaffIds = collect($value->tasks)
                                                                        ->flatMap(function ($task) {
                                                                            return json_decode($task->staff_id, true) ??
                                                                                [];
                                                                        })
                                                                        ->unique()
                                                                        ->values();
                                                                @endphp

                                                                @forelse($allStaffIds as $staffId)
                                                                    @php $staff = $staffs->find($staffId); @endphp
                                                                    @if ($staff)
                                                                        <span
                                                                            class="badge bg-success">{{ $staff->name }}</span>
                                                                    @endif
                                                                @empty
                                                                    <span class="text-muted"
                                                                        style="font-size:12px;">—</span>
                                                                @endforelse
                                                            </td>
                                                            <td>
                                                                <div class="icon-text-wrapper">
                                                                    <div class="cell-icon icon-name">
                                                                        <i class="bi bi-person-fill"></i>
                                                                    </div>

                                                                    <div>
                                                                        <span class="fw-bold text-dark d-block">
                                                                            {{ $value->name }}
                                                                        </span>

                                                                        @if ($value->refunded == 'refunded')
                                                                            <span style="font-size: 10px;color: #fff;">
                                                                                (Refunded)
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <div class="icon-text-wrapper">
                                                                    <i class="bi bi-person-workspace text-primary"></i>

                                                                    <span class="fw-semibold">
                                                                        {{ $value->added_user_details->name ?? '' }}
                                                                    </span>
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <div
                                                                    class="count-icons d-flex align-items-center justify-content-center gap-2">

                                                                    <p class="m-0 fw-semibold">
                                                                        {{ $value->bid_amount }}
                                                                    </p>

                                                                    <i class="fa fa-add count-right-icon"
                                                                        style="cursor: pointer;font-size: 20px;"
                                                                        onclick='ViewTaskModel1("{{ $value->id }}")'>
                                                                    </i>

                                                                    <i class="fa fa-eye"
                                                                        style="cursor: pointer;font-size: 17px;"
                                                                        onclick='ViewTaskModel("{{ $value->id }}")'>
                                                                    </i>

                                                                </div>
                                                            </td>

                                                            <td>
                                                                <div class="icon-text-wrapper">
                                                                    <i class="bi bi-currency-rupee text-success"></i>

                                                                    <span class="fw-semibold">
                                                                        {{ $total_penc }}
                                                                    </span>
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <div class="icon-text-wrapper">

                                                                    <i class="bi bi-calendar-fill text-success"></i>

                                                                    <span class="fw-semibold">
                                                                        @if ($value->sales_user_date == '0000-00-00')
                                                                        @else
                                                                            {{ date('d-m-Y', strtotime($value->sales_user_date)) }}
                                                                        @endif
                                                                    </span>

                                                                </div>
                                                            </td>

                                                            <td>
                                                                <div class="icon-text-wrapper">

                                                                    <i class="bi bi-flag-fill text-warning"></i>

                                                                    <span class="fw-semibold">

                                                                        @if ($value->status == '0')
                                                                            Pending
                                                                        @elseif($value->status == '1')
                                                                            On Progress
                                                                        @elseif($value->status == '3')
                                                                            On Hold
                                                                        @elseif($value->status == '5')
                                                                            Done
                                                                        @elseif($value->status == '6')
                                                                            Cancel
                                                                        @endif

                                                                    </span>

                                                                </div>
                                                            </td>

                                                            <td>
                                                                <p style="display: flex;margin-bottom: 0px;">

                                                                    <a href="{{ route('admin.projects.view.details', $value->id) }}"
                                                                        class="creative-btn-action btn-success"
                                                                        style="padding: 4px 12px;margin: 0px 4px;">
                                                                        <i class="ti-eye text-white"></i>
                                                                    </a>

                                                                    <a href="{{ route('admin.projects.edit', $value->id) }}"
                                                                        class="creative-btn-action action-edit"
                                                                        style="padding: 4px 12px;margin: 0px 4px;">
                                                                        <i class="ion ion-edit text-white"></i>
                                                                    </a>

                                                                    <a onclick="CustomerDetails('{{ $value->customer_id }}')"
                                                                        title="Customer Details"
                                                                        class="creative-btn-action action-user"
                                                                        style="padding: 4px 12px;margin: 0px 4px;">
                                                                        <i class="ti-user text-white"></i>
                                                                    </a>

                                                                    <a href="javascript:void(0);"
                                                                        onclick="deleteProject('{{ route('admin.projects.delete', $value->id) }}')"
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

                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        {{ $total_amount1 }}
                                                    </td>
                                                    <td>
                                                        {{ $total_pening }}
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <style type="text/css">
                                            .pagination li a {
                                                padding: 6px;
                                            }
                                        </style>
                                        <div class="row gy-4 align-items-center" id="seach_hide"
                                            style="margin-top: 30px;">
                                            <div class="col-12">
                                                {!! $users->withQueryString()->links('pagination::bootstrap-5') !!}

                                            </div>
                                        </div>
                                    </div>
                                </form>
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
    <style type="text/css">
        #example_filter label {
            display: none;
        }

        #text_search_value {
            display: none;
        }
    </style>
    <!-- /.content-wrapper -->
    <script src="<?php echo url(''); ?>/public/admin_assets/js/vendors.min.js"></script>
    <?php
    $current_route = Route::currentRouteName();
    $current_route1 = route('admin.download.projects.view');
    $current_route2 = route('admin.completed.payment.project');
    $current_route3 = route('admin.projects.cancel');
    ?>

    <script type="text/javascript">
        function text_value_search1() {

            var add_year = $("#add_year").val();
            $("#year_wise_year").val(add_year);

            document.getElementById("year_wise_project").submit();


        }

        function text_value_search() {
            var text_value_search = $("#text_value_search").val();
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            var salesperson = $("#salesperson").val();
            var service = $("#service").val();
            var status = $("#status").val();

            if (text_value_search) {
                $.ajax({
                    url: "{{ route('admin.projects.search.view') }}",
                    type: "POST",
                    data: {
                        text_value_search: text_value_search,
                        start_date: start_date,
                        end_date: end_date,
                        salesperson: salesperson,
                        status: status,
                        service: service,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {

                        if (response.status) {
                            $("#example_render").html(response.project);
                            $("#seach_hide").hide();
                        }
                    }
                });
            }

        }

        function sort_book() {

            var expert_search_url = "{{ route($current_route) }}";
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            var status = $("#status").val();
            var salesperson = $("#salesperson").val();
            var service = $("#service").val();

            if (start_date) {
                start_date = start_date;
            } else {
                start_date = "";
            }
            if (end_date) {
                end_date = end_date;

            } else {
                end_date = "";
            }
            if (status) {
                status = status;

            } else {
                status = "";
            }
            if (salesperson) {
                salesperson = salesperson;

            } else {
                salesperson = "";
            }
            if (service) {
                service = service;

            } else {
                service = "";
            }
            var str_search_request = "&start_date=" + start_date + "&end_date=" + end_date + "&status=" + status +
                "&salesperson=" + salesperson + "&service=" + service;

            if (str_search_request) {
                // var pdf_url = expert_search_url1 + '?' + str_search_request
                // $('#image_url').attr('href', pdf_url);
                window.location.href = expert_search_url + '?' + str_search_request;
            }



        }

        function PdfDownload() {
            var expert_search_url1 = "{{ $current_route1 }}";
            var expert_search_url2 = "{{ $current_route2 }}";
            var expert_search_url3 = "{{ $current_route3 }}";

            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            var status = $("#status").val();
            var salesperson = $("#salesperson").val();
            var service = $("#service").val();

            if (start_date) {
                start_date = start_date;
            } else {
                start_date = "";
            }
            if (end_date) {
                end_date = end_date;

            } else {
                end_date = "";
            }
            if (status) {
                status = status;

            } else {
                status = "";
            }
            if (salesperson) {
                salesperson = salesperson;

            } else {
                salesperson = "";
            }
            if (service) {
                service = service;

            } else {
                service = "";
            }
            var str_search_request = "&start_date=" + start_date + "&end_date=" + end_date + "&status=" + status +
                "&salesperson=" + salesperson + "&service=" + service;

            if (str_search_request) {
                var pdf_url = expert_search_url1 + '?' + str_search_request
                var pdf_url1 = expert_search_url2 + '?' + str_search_request
                var pdf_url2 = expert_search_url3 + '?' + str_search_request
                $('#image_url').attr('href', pdf_url);
                $('#image_url1').attr('href', pdf_url1);
                $('#image_url2').attr('href', pdf_url2);
            }

        }

        function ViewTaskModel1(id) {
            var project_id = id;

            if (project_id) {
                $('#modal-default1').modal('show');
                $("#project_id").val(project_id);
            }
        }

        function ViewTaskModel(id) {
            var project_id = id;

            if (project_id) {
                $.ajax({
                    url: "{{ route('admin.projects.payment.details') }}",
                    type: "POST",
                    data: {
                        id: project_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#modal-default').modal('show');
                        $("#load_html1").html(response.html);
                    }
                });
            }

        }

        function CustomerDetails(id) {
            var project_id = id;

            if (project_id) {
                $.ajax({
                    url: "{{ route('admin.domain.hosting.user.details') }}",
                    type: "POST",
                    data: {
                        id: project_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#modal-default2').modal('show');
                        $("#load_html2").html(response.html);
                    }
                });
            }

        }

        PdfDownload();
    </script>

    <div class="modal fade" id="modal-default1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Payment Update</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body" id="load_html">
                    <form action="{{ route('admin.projects.payment.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="project_id" id="project_id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Payment Date</label>
                                    <div class="input-group mb-3">
                                        <input type="date"
                                            class="form-control @error('payment_date') is-invalid @enderror"
                                            name="payment_date" value="{{ old('payment_date') }}" required>
                                    </div>
                                    @error('payment_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Payment Amount</label>
                                    <div class="input-group mb-3">
                                        <input type="text"
                                            class="form-control @error('payment_amount') is-invalid @enderror"
                                            name="payment_amount" value="{{ old('payment_amount') }}" required>
                                    </div>
                                    @error('payment_amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>
                <!-- <div class="modal-footer">
                    <span class="btn btn-danger" data-bs-dismiss="modal">Close</span>
                  </div> -->
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Payment Details</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body" id="load_html1">

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-default2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Customer Details</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body" id="load_html2">

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <script type="text/javascript">
        $('#checkAll').click(function() {
            $('input:checkbox').prop('checked', this.checked);
        });
    </script>

    <style type="text/css">
        [type="checkbox"]:not(:checked),
        [type="checkbox"]:checked {
            left: 23px;
            opacity: 99999;
        }
    </style>

@endsection
