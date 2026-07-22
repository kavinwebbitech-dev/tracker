    @extends('layouts.dashboard')

    @section('content')
    <style>
        .tooltip-inner {
            background: #333 !important;
            color: #fff !important;
            white-space: normal !important;
        }

        input[type="checkbox"] {
            opacity: 1 !important;
            position: static !important;
        }

        .custom-checkbox {
            opacity: 1 !important;
            position: static !important;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h3 class="page-title">Credentials</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page">Credentials</li>
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
                        <form action="{{ route('admin.credentials.bulk.upload') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-6 "></div>
                            <div class="col-6 text-end">
                                <button id="bulkDeleteBtn" class="btn btn-danger me-4" style="display:none;"><i class="fa fa-trash"></i> Delete Selected </button>
                                <span class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-default" style="float: right;">
                                    Add Credentials
                                </span>
                            </div>
                            
                            <div class="col-md-3" id="input_file_col" style="display: none;">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control @error('bulk_upoad_file') is-invalid @enderror" id="bulk_upoad_file" name="bulk_upoad_file" placeholder="Email Address" value="{{ old('email') }}" required>
                                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                    </div>
                                    <a href="<?php echo url('');?>/public/admin_assets/images/credentials_bulk_uploads.xlsx" download>Download Sample File</a>
                                    @error('bulk_upoad_file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3" id="button_col" style="display: none;">
                                <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                            </div>
                           
                        </div>
                        </form>
                        <div class="table-responsive">
                        <table  class="table creative-table table-bordered table-hover display nowrap margin-top-10 w-p100">
                            <thead>
                                <tr>
                                    <th style="width:5%"><input type="checkbox" id="checkAll"></th>
                                    <th style="width:5%">S No</th>
                                    <th style="width:15%">Name</th>
                                    <th style="width:15%">Username</th>
                                    <th style="width:15%">Password</th>
                                    <th style="width:35%">Description</th> {{-- big space --}}
                                    <th style="width:10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>

    @if($credential_details)
    @foreach($credential_details as $key => $value)

    <tr>

        <td>
            <input class="checkbox-item custom-checkbox"
                type="checkbox"
                name="ids[]"
                value="{{ $value->id }}">
        </td>

        <td class="text-muted fw-bold sorting_1">
            {{ $key + 1}}
        </td>

        <td>
            <div class="icon-text-wrapper">

                <div class="cell-icon icon-name">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>

                <span class="fw-semibold">
                    {{ $value->name }}
                </span>

            </div>
        </td>

        <td>
            <div class="icon-text-wrapper">

                <i class="bi bi-person-fill text-primary"></i>

                <span class="fw-semibold">
                    {{ $value->username }}
                </span>

            </div>
        </td>

        <td data-export="{{ $value->password }}">

            <div id="viewCr{{ $value->id }}">

                <div class="icon-text-wrapper">

                    <i class="bi bi-key-fill text-warning"></i>

                    <p style="display:flex;margin-bottom:0px;align-items:center;">

                        <span>#######</span>

                        <span style="margin-left:10px;cursor:pointer;"
                            onclick="DisplayView('{{ $value->id }}')">
                            👁️
                        </span>

                    </p>

                </div>

            </div>

            <div id="hide{{ $value->id }}" style="display:none;">

                <div class="icon-text-wrapper">

                    <i class="bi bi-unlock-fill text-success"></i>

                    <p style="display:flex;margin-bottom:0px;align-items:center;">

                        <span>{{ $value->password }}</span>

                        <span style="margin-left:10px;cursor:pointer;"
                            onclick="DisplayHide('{{ $value->id }}')">
                            🙈
                        </span>

                    </p>

                </div>

            </div>

        </td>

        <td data-export="{!! $value->description !!}">

            @php
                $desc = $value->description ?? '-';
            @endphp

            <div class="icon-text-wrapper align-items-start">

                <i class="bi bi-chat-left-text-fill text-info mt-1"></i>

                <span class="fw-semibold">

                    {{ Str::limit($desc, 50) }}

                    @if(strlen($desc) > 45)

                        <i class="fa fa-info-circle text-primary"
                            data-bs-toggle="tooltip"
                            data-bs-placement="left"
                            data-bs-container="body"
                            title="{{ $desc }}">
                        </i>

                    @endif

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

                <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.credentials.delete', $value->id) }}')"
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
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Credentials</h4>
                <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
            </div>
            <div class="modal-body">
                
                <form class="form" action="{{ route('admin.credentials.store') }}" method="post">
                @csrf
                <input type="hidden" name="conference_id" id="conference_id">

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="firstname" name="name" placeholder="Name" value="{{ old('name') }}" required>
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
                        <label class="form-label">User Name</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="User Name" value="{{ old('username') }}">
                            <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                        </div>
                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" value="{{ old('password') }}">
                            <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <div class="input-group mb-3">
                            <textarea name="description" id="description" rows="5" cols="80"></textarea>
                        </div>
                        @error('editor1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box-footer text-end">
                        <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                        <!-- <button type="submit" class="btn btn-primary">
                        <i class="ti-save-alt"></i> Save
                        </button> -->
                    </div> 
                </div>
                </form>

            </div>
            
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- DataTables core -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

    <script type="text/javascript">
        function ViewTaskModel(ref) {
            var ele = ref;
            // alert(ele);
            $.ajax({
                url: '{{ route('admin.credentials.edit') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: ele
                },
                success: function (response) {
                    console.log(response);
                    $("#conference_id").val(response.conference_id);
                    $("#firstname").val(response.title);
                    $("#username").val(response.username);
                    $("#password").val(response.password);
                    $("#description").val(response.description);
                    $('#modal-default').modal('show');
                }
            });
        }
        </script>
        <script type="text/javascript">
            function BulkCheck() {
                $("#input_file_col").show();
                $("#button_col").show();
                $("#bul_upload_col").hide();
            }
        </script>
        <script>
            function DisplayView(ref)
            {
                
                var ref1 = '#hide'+ref;
                var viewCr = '#viewCr'+ref;
                $(ref1).show();
                $(viewCr).hide();
            }
        </script>
        <script>
            function DisplayHide(ref)
            {
                
                var ref1 = '#hide'+ref;
                var viewCr = '#viewCr'+ref;
                $(ref1).hide();
                $(viewCr).show();
            }
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                    new bootstrap.Tooltip(el, {
                    placement: 'left',       // force left side
                    container: 'body',
                    fallbackPlacements: []   // prevent auto-flip
                    });
                });
            });
        </script>
    <script>
        $(document).ready(function () {

            if (!$.fn.DataTable) {
                console.error('DataTables not loaded');
                return;
            }
            setTimeout(function () {
                $('#example1').DataTable().columns.adjust();
            }, 200);

            $('#example1').DataTable({
                scrollX: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                dom: 'Bfrtip',
            
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel"></i> Download Excel',
                        title: 'Credentials Report',
                        exportOptions: {
                            columns: [1,2,3,4,5], // ❗ skip checkbox & action
                            format: {
                                body: function (data, row, column, node) {
                                    return $(node).data('export') ?? data;
                                }
                            }
                        },
                        customize: function (xlsx) {
                            let sheet = xlsx.xl.worksheets['sheet1.xml'];
            
                            // 🔥 Make header BLACK & BOLD
                            $('row:first c', sheet).each(function () {
                                $(this).attr('s', '2');
                            });
                        }
                    }
                ],
            
                columnDefs: [
                    { orderable: false, targets: [0,6] }
                ],
            
                initComplete: function () {
                    this.api().columns.adjust().draw(false);
                }
            });


        });

         $('#checkAll').on('change', function() {
            $('.checkbox-item').prop('checked', this.checked);
            toggleBulkDelete();
        });
        $(document).on('change', '.checkbox-item', function() {
            toggleBulkDelete();
        });
        function toggleBulkDelete() {
            let checkedCount = $('.checkbox-item:checked').length;
            if (checkedCount > 0) {
                $('#bulkDeleteBtn').show();
            } else {
                $('#bulkDeleteBtn').hide();
            }
        }
        $('#bulkDeleteBtn').on('click', function() {

            let ids = [];
            $('.checkbox-item:checked').each(function() {
                ids.push($(this).val());
            });

            if (ids.length === 0) {
                alert('Please select at least one record.');
                return;
            }

            if (!confirm('Are you sure you want to delete selected career?')) {
                return;
            }

            $.ajax({
                url: "{{ route('credentials.bulk.delete') }}",
                type: "POST",
                data: {
                    ids: ids,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    alert(res.message);

                    // ✅ FULL PAGE RELOAD
                    location.reload();
                },
                error: function() {
                    alert('Something went wrong!');
                }
            });
        });
    </script>

    @endsection