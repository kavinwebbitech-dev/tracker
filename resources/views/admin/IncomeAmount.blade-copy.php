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
                        <h3 class="page-title">Income Amount</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i
                                                class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page">Income Amount</li>
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
                                <form action="{{ route('admin.imcome.amount.export') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6 d-flex justify-content-end gap-2">
                                            <span class="btn btn-primary" style="float: right; cursor: pointer;"
                                                onclick="window.location='{{ route('admin.expenses.amount.view') }}'">
                                                Expense
                                            </span>
                                            <span class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modal-category" style="float: right;">
                                                Add Income category
                                            </span>
                                            <span class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modal-default" style="float: right;">
                                                Add Income Amount
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom: 10px;">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <input type="date"
                                                        class="form-control @error('upload_file') is-invalid @enderror"
                                                        id="start_date" onfocus="'showPicker' in this && this.showPicker()"
                                                        name="start_date" placeholder="Business Name"
                                                        value="{{ $start_date }}">
                                                </div>
                                                @error('start_date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <input type="date"
                                                        class="form-control @error('upload_file') is-invalid @enderror"
                                                        id="end_date" onfocus="'showPicker' in this && this.showPicker()"
                                                        name="end_date" placeholder="Business Name"
                                                        value="{{ $end_date }}">
                                                </div>
                                                @error('end_date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group mb-3">
                                                <select class="form-control @error('upload_file') is-invalid @enderror"
                                                    id="category_filter" name="category_filter">
                                                    <option value="">All Categories</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ request('category_filter') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <span class="btn btn-primary" onclick="sort_book()">Submit</span>
                                        </div>
                                        <div class="col-md-1">
                                            <input type="submit" name="submit" class="btn btn-primary" value="Download">
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table id="example"
                                        class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
                                        <thead>
                                            <tr>
                                                <th>S No</th>
                                                <th>Name</th>
                                                <th>Category</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total_amount1 = 0;
                                            $total_pening = 0;
                                            ?>

                                            @if ($IncomeAmount)
                                                @foreach ($IncomeAmount as $key => $value)
                                                    <?php
                                                    $total_amount1 += $value->amount;
                                                    ?>
                                                    <tr>
                                                        <td class="text-muted fw-bold sorting_1">{{ $key + 1 }}</td>
                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <div class="cell-icon icon-name">
                                                                    <i class="bi bi-kanban"></i>
                                                                </div>
                                                                <span class="fw-semibold">{{ $value->name }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <i class="bi bi-tag-fill text-info"></i>
                                                                <span class="fw-semibold">
                                                                    {{ $value->category ? $value->category->name : '' }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <i class="bi bi-currency-rupee text-success"></i>
                                                                <span class="fw-bold">{{ $value->amount }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="icon-text-wrapper">
                                                                <i class="bi bi-calendar-fill text-primary"></i>
                                                                <span class="fw-semibold">
                                                                    {{ date('d-m-Y', strtotime($value->income_date)) }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="icon-text-wrapper align-items-start">
                                                                <i class="bi bi-chat-left-text-fill text-warning mt-1"></i>
                                                                <span
                                                                    class="fw-semibold">{{ $value->description ?? '' }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <p style="display: flex;">
                                                                <a onclick="ViewTaskModel('{{ $value->id }}')"
                                                                    class="creative-btn-action action-edit"
                                                                    style="padding: 4px 12px;margin: 10px 4px;">
                                                                    <i class="ion ion-edit text-white"></i>
                                                                </a>
                                                                <a href="{{ route('admin.imcome.amount.delete', $value->id) }}"
                                                                    class="creative-btn-action action-delete"
                                                                    style="padding: 4px 12px;margin: 10px 4px;">
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
                                                <td>
                                                    <div class="icon-text-wrapper mb-1">
                                                        <span class="fw-semibold"> Total</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="icon-text-wrapper mb-1">
                                                        <i class="bi bi-currency-rupee text-success"></i>
                                                        <span class="fw-semibold"> {{ $total_amount1 }}</span>
                                                    </div>
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
                                    <div class="row gy-4 align-items-center" style="margin-top: 30px;">
                                        <div class="col-12">
                                            {!! $IncomeAmount->withQueryString()->links('pagination::bootstrap-5') !!}
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

    <!-- Category Modal: form + inline list with edit/delete, no separate page needed -->
    <div class="modal fade" id="modal-category">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="category_modal_title">Add Income Category</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">

                    <form id="category_form" action="{{ route('admin.income.category.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="conference_id" id="category_conference_id">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Category Name</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="category_name" name="name" placeholder="Category Name"
                                        value="{{ old('name') }}" required>
                                </div>
                                @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="input-group mb-3">
                                    <select class="form-control" id="category_status" name="status" required>
                                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                                @error('status')
                                    <span class="invalid-feedback d-block"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="box-footer text-end">
                                <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                            </div>
                        </div>
                    </form>

                    <hr>

                    <!-- Inline category list, right inside the same modal -->
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-bordered table-hover w-p100">
                            <thead>
                                <tr>
                                    <th>S No</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($all_categories) && count($all_categories) > 0)
                                    @foreach ($all_categories as $key => $cat)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $cat->name }}</td>
                                            <td>
                                                @if ($cat->status == 'Active')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <p style="display: flex;">
                                                    <a onclick="ViewCategoryModel('{{ $cat->id }}')"
                                                        class="creative-btn-action action-edit"
                                                        style="padding: 4px 12px;margin: 4px;">
                                                        <i class="ion ion-edit text-white"></i>
                                                    </a>
                                                    <a href="{{ route('admin.income.category.delete', $cat->id) }}"
                                                        class="creative-btn-action action-delete delete"
                                                        data-confirm="Are you sure you want to delete this category?"
                                                        style="padding: 4px 12px;margin: 4px;">
                                                        <i class="ti-trash text-white"></i>
                                                    </a>
                                                </p>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No categories added yet</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Income Amount</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.imcome.amount.store') }}" method="post">
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
                                <label class="form-label">Category</label>
                                <div class="input-group mb-3">
                                    <select class="form-control" id="category_id" name="category_id">
                                        <option value="">-- Select Category --</option>
                                        @if (isset($income_categories) && count($income_categories) > 0)
                                            @foreach ($income_categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('category_id')
                                    <span class="invalid-feedback d-block"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Amount</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                        id="amount" name="amount" placeholder="Amount" value="{{ old('amount') }}">
                                </div>
                                @error('amount')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Date</label>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control @error('income_date') is-invalid @enderror"
                                        id="income_date" name="income_date"
                                        onfocus="'showPicker' in this && this.showPicker()" placeholder="Business Name"
                                        value="{{ old('income_date') }}">
                                </div>
                                @error('income_date')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" placeholder="Description"
                                        value="{{ old('description') }}">
                                </div>
                                @error('description')
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

    <?php
    $current_route = Route::currentRouteName();
    ?>
    <script src="<?php echo url(''); ?>/public/admin_assets/js/vendors.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">
        // Edit an existing category (fills the same form inside the modal)
        function ViewCategoryModel(ref) {
            $.ajax({
                url: '{{ route('admin.income.category.edit') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ref
                },
                success: function(response) {
                    $("#category_conference_id").val(response.conference_id);
                    $("#category_name").val(response.name);
                    $("#category_status").val(response.status);
                    $("#category_modal_title").text("Edit Income Category");
                }
            });
        }

        // Reset the form to "Add" mode only when opened via the Add category button,
        // not when opened programmatically from ViewCategoryModel
        document.querySelectorAll('[data-bs-target="#modal-category"]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                $('#category_form')[0].reset();
                $('#category_conference_id').val('');
                $('#category_modal_title').text('Add Income Category');
            });
        });

        function ViewTaskModel(ref) {
            var ele = ref;
            $.ajax({
                url: '{{ route('admin.imcome.amount.edit') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: ele
                },
                success: function(response) {
                    console.log(response);
                    $("#conference_id").val(response.conference_id);
                    $("#name").val(response.name);
                    $("#amount").val(response.amount);
                    $("#category_id").val(response.category_id);
                    $("#income_date").val(response.income_date);
                    $("#description").val(response.description);
                    $('#modal-default').modal('show');
                }
            });
        }

        function ViewTaskModel1(ref) {
            $("#leads_from1").val(ref);
            $('#modal-default1').modal('show');
        }

        document.getElementById("whatsapp-id").addEventListener("click", function() {
            $("#report_type").val('whatsapp')
            form.submit();
        });

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
            var category_filter = $("#category_filter").val() || "";

            var str_search_request = "?start_date=" + start_date + "&end_date=" + end_date + "&category_filter=" +
                category_filter;

            window.location.href = expert_search_url + str_search_request;
        }
    </script>
@endsection
