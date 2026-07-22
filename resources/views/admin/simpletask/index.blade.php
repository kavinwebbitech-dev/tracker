@extends('layouts.dashboard')

@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h3 class="page-title">Simple Task</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i
                                                class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item">Simple Task</li>
                                    <li class="breadcrumb-item active" aria-current="page">View</li>
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

                                <div class="st-header d-flex justify-content-between align-items-end flex-wrap gap-3 mb-3">

                                    <form action="{{ route('admin.simpletask.view') }}" method="GET"
                                        class="d-flex align-items-end gap-2 flex-wrap">

                                        <input type="date" name="start_date" class="form-control" style="width:180px;"
                                            value="{{ request('start_date') }}"
                                            onfocus="'showPicker' in this && this.showPicker()">

                                        <input type="date" name="end_date" class="form-control" style="width:180px;"
                                            value="{{ request('end_date') }}"
                                            onfocus="'showPicker' in this && this.showPicker()">

                                        <button type="submit" class="btn btn-primary">
                                            Submit
                                        </button>

                                        <button type="button" class="btn btn-success" title="Download Excel"
                                            onclick="triggerExport('excel')">
                                            <i class="fa fa-download"></i>
                                        </button>

                                    </form>

                                    <button type="button" class="btn btn-primary" onclick="openAddModal()">
                                        Add Simple-Task
                                    </button>

                                </div>
                                <div class="table-responsive">
                                    <table id="simpleTaskTable" class="table table-bordered table-striped nowrap-table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="st-table-body">
                                            @forelse($simple_tasks as $key => $task)
                                                <tr id="st-row-{{ $task->id }}">
                                                    <td class="text-muted fw-bold">
                                                        {{ $key + 1 }}
                                                    </td>
                                                    <td>
                                                        <div class="icon-text-wrapper">
                                                            <i class="bi bi-calendar-fill text-success"></i>
                                                            <span class="fw-semibold">
                                                                {{ $task->start_date ? date('d-m-Y', strtotime($task->start_date)) : '' }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="icon-text-wrapper">
                                                            <i class="bi bi-calendar-check-fill text-danger"></i>
                                                            <span class="fw-semibold">
                                                                {{ $task->end_date ? date('d-m-Y', strtotime($task->end_date)) : '' }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="icon-text-wrapper">
                                                            <i class="bi bi-list-task text-warning"></i>
                                                            <span class="fw-semibold">{{ $task->title }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="fw-semibold">
                                                            {{ \Illuminate\Support\Str::limit($task->description, 50) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <p style="display: flex; margin: 0;">

                                                            <a href="{{ route('task.create', [
                                                                'simple_task_id' => $task->id,
                                                                'task_name' => $task->title,
                                                                'start_date' => $task->start_date,
                                                                'end_date' => $task->end_date,
                                                            ]) }}"
                                                                class="creative-btn-action action-user"
                                                                title="Assign as Task" style="margin-right: 6px;">
                                                                <i class="ion ion-clipboard text-white"></i>
                                                            </a>

                                                            <a href="javascript:void(0);"
                                                                class="creative-btn-action action-edit"
                                                                style="margin-right: 6px;" data-id="{{ $task->id }}"
                                                                data-title="{{ $task->title }}"
                                                                data-description="{{ $task->description }}"
                                                                data-start_date="{{ $task->start_date }}"
                                                                data-end_date="{{ $task->end_date }}"
                                                                onclick="openEditModal(this)">
                                                                <i class="ion ion-edit text-white"></i>
                                                            </a>

                                                            <a href="javascript:void(0);"
                                                                onclick="deleteProject('{{ route('admin.simpletask.delete', $task->id) }}')"
                                                                class="creative-btn-action action-delete">
                                                                <i class="ti-trash text-white"></i>
                                                            </a>
                                                        </p>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr id="st-empty-row">
                                                    <td colspan="6" class="text-center">No tasks found.</td>
                                                </tr>
                                            @endforelse
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

    <!-- ── Add / Edit Modal ─────────────────────────────────────────────── -->
    <!-- Plain form, real submit, no JS/ajax. Action + method are switched by
             openAddModal()/openEditModal() before the modal is shown. -->
    <div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="taskModalTitle">Add Task</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>

                <form id="taskForm" method="POST" action="{{ route('admin.simpletask.store') }}">
                    @csrf
                    <input type="hidden" id="task_method" name="_method" value="">

                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <label class="form-label">Task Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="title" name="title" placeholder="Task Title" value="{{ old('title') }}">
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                        id="start_date" name="start_date" value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                        id="end_date" name="end_date" value="{{ old('end_date') }}">
                                    @error('end_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <span class="btn btn-secondary" data-bs-dismiss="modal">Close</span>
                        <button type="submit" class="btn btn-primary" id="taskSubmitBtn">Save Task</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- jQuery must be available before anything below runs. If layouts.dashboard
                     already loads jQuery globally, this tag is harmless (browsers won't
                     re-download/re-init a script whose src it already has cached/loaded),
                     but if it doesn't, this is what was causing "$ is not defined". -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- DataTables core + Buttons extension (Copy / Excel / CSV / Print) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script type="text/javascript">
        $(function() {
            "use strict";

            $('#simpleTaskTable').DataTable({
                paging: true,
                pageLength: 10,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                dom: "<'row align-items-center mb-3'<'col-sm-3'l><'col-sm-5'B><'col-sm-4'f>>" +
                    "rt" +
                    "<'row align-items-center mt-3'<'col-sm-6'i><'col-sm-6'p>>",
                buttons: [{
                        extend: 'copy',
                        text: 'Copy',
                        className: 'btn'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn'
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'btn'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn'
                    },
                ],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }]
            });

            // If validation failed server-side (redirect back with errors),
            // re-open the modal automatically so the user sees what to fix.
            @if ($errors->any())
                $('#taskModal').modal('show');
            @endif
        });

        function triggerExport(type) {
            $('.buttons-' + type).trigger('click');
        }

        function openAddModal() {
            $('#taskForm')[0].reset();
            $('#taskForm').attr('action', "{{ route('admin.simpletask.store') }}");
            $('#task_method').val('');
            $('#taskModalTitle').text('Add Task');
            $('#taskSubmitBtn').text('Save Task');
            $('#taskModal').modal('show');
        }

        // Reads task data straight from the clicked link's data-* attributes
        // (rendered server-side in the table above) - no AJAX round-trip.
        function openEditModal(link) {
            var id = $(link).data('id');
            var updateUrl = "{{ route('admin.simpletask.update', ':id') }}".replace(':id', id);

            $('#taskForm').attr('action', updateUrl);
            $('#task_method').val('PUT');

            $('#title').val($(link).data('title'));
            $('#description').val($(link).data('description'));
            $('#start_date').val($(link).data('start_date'));
            $('#end_date').val($(link).data('end_date'));

            $('#taskModalTitle').text('Edit Task');
            $('#taskSubmitBtn').text('Update Task');
            $('#taskModal').modal('show');
        }
    </script>
@endsection
