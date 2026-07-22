@extends('layouts.dashboard')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h3 class="page-title">Create Task</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">Task</li>
                                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    @include('layouts.flash-message')
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-lg-12 col-12">

                        <div class="box">
                            <!-- /.box-header -->
                            <form class="form" action="{{ route('task.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="simple_task_id"
                                    value="{{ old('simple_task_id', $simple_task_id ?? '') }}">
                                <div class="box-body">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Task Type</label>
                                                <div class="input-group mb-3">
                                                    <select class="form-control @error('task_type') is-invalid @enderror"
                                                        name="task_type" id="date_type" onchange="cusom_date()">
                                                        <option value="">Select Task Type</option>
                                                        <option value="custom">One time Task</option>
                                                        <option value="recurring">Recurring</option>
                                                    </select>
                                                    <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                                </div>
                                                @error('date_type')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">User Type</label>
                                                <div class="input-group mb-3">
                                                    <select class="form-control @error('user_type') is-invalid @enderror"
                                                        name="user_type" id="user_type" onchange="cusom_date()">
                                                        <option value=""> Select User</option>
                                                        <option value="sub_admin"> Sub Admin</option>
                                                        <option value="staff"> Staff</option>
                                                    </select>
                                                    <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                                </div>
                                                @error('user_type')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6" id="check_dynamic3" style="display:none;">

                                            <div class="form-group">
                                                <label class="form-label">Select Sub Admin</label>
                                                <div class="input-group mb-3">
                                                    <select
                                                        class="form-control @error('task_sub_admin') is-invalid @enderror"
                                                        name="task_sub_admin" id="task_sub_admin">
                                                        <option value=""> Select Sub Admin</option>
                                                        @foreach ($sub_admin as $key => $value)
                                                            <option value="{{ $value->id }}"> {{ $value->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                                </div>
                                                @error('task_sub_admin')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6" id="check_dynamic" style="display:none;">
                                            <div class="form-group">
                                                <label class="form-label">Select User</label>
                                                <div class="input-group mb-3">
                                                    <select class="form-control @error('staff_id') is-invalid @enderror"
                                                        name="staff_id_1" id="staff_id_1">
                                                        <option value=""> Select User</option>

                                                    </select>
                                                    <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                                </div>
                                                @error('staff_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="check_dynamic2" style="display:none;">
                                            <div class="form-group">
                                                <label class="form-label">Select Staff</label>
                                                <div class="input-group mb-3">
                                                    <select
                                                        class="form-control multiple_staff select2 @error('staff_id') is-invalid @enderror"
                                                        id="multiple_staff" name="multiple_staff[]" style="width: 100%;"
                                                        multiple>
                                                        <!-- <select class="selectpicker form-control multiple_staff"name="multiple_staff[]" multiple> -->

                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6" id="check_dynamic1" style="display:none;">

                                            <div class="form-group">
                                                <label class="form-label">Select Sub Admin</label>
                                                <div class="input-group mb-3">
                                                    <select
                                                        class="form-control @error('sub_admin_id') is-invalid @enderror"
                                                        name="sub_admin_id" id="sub_admin_id"
                                                        onchange="sub_admin_check()">
                                                        <option value=""> Select Sub Admin</option>
                                                        @foreach ($sub_admin as $key => $value)
                                                            <option value="{{ $value->id }}"> {{ $value->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                                </div>
                                                @error('sub_admin_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-md-6" id="check_dynamic" style="display:none;">
                                            <div class="form-group">
                                                <label class="form-label">Select User</label>
                                                <div class="input-group mb-3">
                                                    <select class="form-control @error('staff_id') is-invalid @enderror"
                                                        name="staff_id" id="staff_id">
                                                        @foreach ($sub_admin as $key => $value)
                                                            <option value="{{ $value->id }}"> {{ $value->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                                </div>
                                                @error('staff_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Select Project</label>
                                                <div class="input-group mb-3">
                                                    <select
                                                        class="form-control select2 @error('project_id') is-invalid @enderror"
                                                        name="project_id" id="project_id">
                                                        <option value="">Select Project</option>
                                                        @if ($projects)
                                                            @foreach ($projects as $key => $project)
                                                                <option value="{{ $project->id }}">{{ $project->name }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                                </div>
                                                @error('date_type')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Task Name</label>
                                                <div class="input-group mb-3">
                                                    <input type="text"
                                                        class="form-control @error('task_name') is-invalid @enderror"
                                                        name="task_name" placeholder="Task Name"
                                                        value="{{ old('task_name', $prefill_task_name ?? '') }}" required>
                                                    <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                                </div>
                                                @error('task_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row" id="customer_choice_options">

                                    </div>

                                    <div class="row" style="display: none;" id="drop_display">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Period of Task</label>
                                                <div class="input-group mb-3">
                                                    <select class="form-control @error('date_type') is-invalid @enderror"
                                                        name="date_type">
                                                        <option value="7">Weekly</option>
                                                        <option value="15">15 Days</option>
                                                        <option value="30">Monthly</option>
                                                        <option value="90">Quarterly</option>
                                                        <option value="365">Yearly</option>
                                                    </select>
                                                    <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
                                                </div>
                                                @error('date_type')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Start Date</label>
                                                <div class="input-group mb-3">
                                                    <input type="date"
                                                        class="form-control @error('recurring_start_date') is-invalid @enderror"
                                                        name="recurring_start_date" placeholder="Task Start Date"
                                                        value="{{ old('recurring_start_date') }}">
                                                </div>
                                                @error('recurring_start_date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row" style="display: none;" id="date_display">

                                        {{-- <div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Start Date</label>

											<div class="input-group mb-3">
												<input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror">
											</div>
											@error('start_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">End Date</label>

											<div class="input-group mb-3">
												<input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror">
											</div>
											@error('end_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div> --}}

                                    </div>

                                    <div class="row">

                                        {{-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Payment Follow Up</label>
                                                <div class="input-group mb-3">
                                                    <select
                                                        class="form-control select2 @error('payment_follow_up') is-invalid @enderror"
                                                        id="payment_follow_up" name="payment_follow_up"
                                                        style="width: 100%;">
                                                        <option value="">Select Person</option>
                                                        @foreach ($sub_admin1 as $key => $value)
                                                            <option value="{{ $value->id }}"> {{ $value->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Project Work Follow Up</label>
                                                <div class="input-group mb-3">
                                                    <select
                                                        class="form-control select2 @error('project_follow_up') is-invalid @enderror"
                                                        id="project_follow_up" name="project_follow_up"
                                                        style="width: 100%;">
                                                        <option value="">Select Person</option>
                                                        @foreach ($sub_admin1 as $key => $value)
                                                            <option value="{{ $value->id }}"> {{ $value->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Description</label>
                                                <textarea id="editor1" name="editor1" rows="10" cols="80" required>
													
											</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- <div class="col-md-6"></div> -->
                                        <div class="col-md-12">
                                            <div class="box-footer text-end">
                                                <input type="submit" name="submit" class="btn btn-primary"
                                                    value="Submit">
                                                <!-- <button type="submit" class="btn btn-primary">
                                                                       <i class="ti-save-alt"></i> Save
                                                                       </button> -->
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                            </form>
                        </div>
                        <!-- /.box -->
                    </div>

                </div>

        </div>
        <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>
    </div>
    <!-- /.content-wrapper -->
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <script type="text/javascript">
        const roleHourMap = {
            'PHP Developer': ['backend'],
            'Senior PHP Developer': ['backend'],
            'React Native Developer': ['backend'],
            'Frontend Developer': ['frontend'],
            'Creative Head': ['frontend'],
            'SEO Developer': ['seo'],
            'Graphic Designer': ['seo'],
            'Testing': ['testing'],
        };

        const hourFields = {
            frontend: {
                label: 'Frontend Hours',
                name: 'assigned_frontend_hours'
            },
            backend: {
                label: 'Backend Hours',
                name: 'assigned_backend_hours'
            },
            seo: {
                label: 'SEO Hours',
                name: 'assigned_seo_hours'
            },
            testing: {
                label: 'Testing Hours',
                name: 'assigned_testing_hours'
            },
        };

        function buildHourFields(i, role) {
            const trimmedRole = (role ?? '').trim();
            // If role found in map → show only those fields; else show all 4
            const keys = roleHourMap.hasOwnProperty(trimmedRole) ?
                roleHourMap[trimmedRole] :
                Object.keys(hourFields);

            return keys.map(function(key) {
                const f = hourFields[key];
                return `<div class="col-md-3 mt-2">
                        <label class="form-label">${f.label}</label>
                        <input type="number" step="0.5" min="0" class="form-control"
                            name="addmore[${i}][${f.name}]" value="0" placeholder="0">
                    </div>`;
            }).join('');
        }

        function add_more_customer_choice_option(i, text, role = '') {
            $('#customer_choice_options').show().append(`
            <div class="form-group row border-bottom pb-3 mb-3" id="staff_row_${i}">
                <div class="col-md-3">
                    <label class="form-label">Name</label>
                    <input type="hidden" name="addmore[${i}][id]" value="${i}">
                    <input type="text" class="form-control" name="addmore[${i}][name]" value="${text}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="addmore[${i}][start_date]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" name="addmore[${i}][end_date]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Task Priority</label>
                    <select class="form-control" name="addmore[${i}][priority]">
                        <option value="">Select Priority</option>
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
                ${buildHourFields(i, role)}
            </div>`);
        }


        function cusom_date() {

            var date_check = $("#date_type").val();
            var user_type = $("#user_type").val();

            if (user_type == "sub_admin" && date_check == "custom") {
                $("#date_display").show();
                $("#check_dynamic2").hide();
                $("#check_dynamic1").hide();
                $("#check_dynamic3").show();
                $("#drop_display").hide();
                $("#check_dynamic").hide();
                $("#customer_choice_options").hide();
            } else if (user_type == "staff" && date_check == "custom") {
                $("#drop_display").hide();
                $("#date_display").show();
                $("#check_dynamic2").show();
                $("#check_dynamic1").show();
                $("#check_dynamic3").hide();
                $("#customer_choice_options").hide();
            } else if (user_type == "sub_admin" && date_check == "recurring") {
                $("#date_display").hide();
                $("#drop_display").show();
                $("#check_dynamic2").hide();
                $("#check_dynamic1").hide();
                $("#check_dynamic").show();
                $("#check_dynamic3").hide();
                $("#customer_choice_options").hide();
                var user_type = 'sub_admin';
                $("#staff_id_1").html('');
                $.ajax({
                    url: "{{ route('admin.staff.get') }}",
                    type: "POST",
                    data: {
                        user_type: user_type,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);
                        $("#staff_id_1").append(response);

                    }
                });
            } else if (user_type == "staff" && date_check == "recurring") {
                $("#date_display").hide();
                $("#drop_display").show();
                $("#check_dynamic2").hide();
                $("#check_dynamic1").hide();
                $("#check_dynamic").show();
                $("#check_dynamic3").hide();
                $("#customer_choice_options").hide();
                var user_type = 'staff';
                $("#staff_id_1").html('');
                $.ajax({
                    url: "{{ route('admin.staff.get') }}",
                    type: "POST",
                    data: {
                        user_type: user_type,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);
                        $("#staff_id_1").append(response);

                    }
                });
            } else {
                // alert("Hi");
                $("#date_display").hide();
                $("#drop_display").hide();
                $("#check_dynamic2").hide();
                $("#check_dynamic1").hide();
                $("#check_dynamic").hide();
                $("#customer_choice_options").hide();
                $("#check_dynamic3").hide();
            }

        }

        // function sub_admin_check() {

        // }

        // function sub_admin_check() {

        //     var sub_admin_id = $("#sub_admin_id").val();

        //     if (sub_admin_id) {
        //         $('.inner li').remove();
        //         $.ajax({
        //             url: "{{ route('admin.staff.check') }}",
        //             type: "POST",
        //             data: {
        //                 id: sub_admin_id,
        //                 _token: '{{ csrf_token() }}'
        //             },
        //             success: function(response) {
        //                 console.log(response);
        //                 // $('#multiple_staff').empty();

        //                 var obj = JSON.parse(response.html);
        //                 var obj1 = JSON.parse(response.staff);

        //                 if (obj) {
        //                     $("#check_dynamic2").show();
        //                     $('#multiple_staff').append(obj);
        //                     $('.inner').append(obj1);
        //                 }

        //             }
        //         });
        //     }
        // }


        // ─── Role → hour field mapping ───────────────────────────────────────────────

        // ─── When multiple_staff selection changes ────────────────────────────────────
        // $(document).ready(function() {

        //     $('#multiple_staff').on('change', function() {
        //         $('#customer_choice_options').html('');
        //         $("#multiple_staff option:selected").each(function() {
        //             const id = $(this).val();
        //             const name = $(this).text();
        //             const role = $(this).data('role') ?? ''; // ← read from data-role attribute
        //             add_more_customer_choice_option(id, name, role);
        //         });
        //     });

        //     // ─── Sub admin single select ──────────────────────────────────────────────
        //     $('#task_sub_admin').on('change', function() {
        //         $('#customer_choice_options').html('');
        //         const id = $(this).val();
        //         const name = $(this).find("option:selected").text();
        //         // Sub admins don't have a role field — show all hours
        //         add_more_customer_choice_option(id, name, '');
        //     });

        // });
        $(document).ready(function() {

            // Multiple staff multiselect (staff + custom task)
            $('#multiple_staff').on('change', function() {
                $('#customer_choice_options').html('');
                $("#multiple_staff option:selected").each(function() {
                    const id = $(this).val();
                    const name = $(this).text().trim();
                    const role = $(this).attr('data-role') ?? ''; // ← data-role from option
                    console.log('Staff:', name, '| Role:', role); // debug — remove later
                    add_more_customer_choice_option(id, name, role);
                });
            });

            // Sub admin single select (sub_admin + custom task)
            $('#task_sub_admin').on('change', function() {
                $('#customer_choice_options').html('');
                const id = $(this).val();
                const name = $(this).find("option:selected").text().trim();
                add_more_customer_choice_option(id, name, ''); // no role → show all hours
            });

        });



        $('#task_sub_admin').on('change', function() {
            $('#customer_choice_options').html(null);
            var admin_name = $(this).find("option:selected").text();

            add_more_customer_choice_option($(this).val(), admin_name);
        });

        $(document).ready(function() {
            $('#multiple_staff').on('change', function() {
                var selectedValue = $(this).val();
                $('#customer_choice_options').html(null);
                $.each($("#multiple_staff option:selected"), function() {
                    add_more_customer_choice_option($(this).val(), $(this).text());
                });
                console.log('Selected Value on Open: ' + selectedValue);
            });
        });

        // $('#multiple_staff').on('change', function() {
        //     $('#customer_choice_options').html(null);
        //     $.each($("#multiple_staff option:selected"), function(){
        //         add_more_customer_choice_option($(this).val(), $(this).text());
        //     });
        // });
    </script> --}}

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <script type="text/javascript">
    
        const prefillStartDate = @json(old('start_date', $prefill_start_date ?? ''));
        const prefillEndDate = @json(old('end_date', $prefill_end_date ?? ''));

        // ─── Role → hour field mapping ────────────────────────────────────────────
        const roleHourMap = {
            'PHP Developer': ['backend'],
            'Senior PHP Developer': ['backend'],
            'React Native Developer': ['backend'],
            'Frontend Developer': ['frontend'],
            'Front End Developer': ['frontend'],
            'Creative Head': ['frontend'],
            'SEO Developer': ['seo'],
            'Off Page Seo': ['seo'],
            'Graphic Designer': ['designer'],
            'Designer': ['designer'],
            'UI/UX Designer': ['designer'],
            'Digital Marketer/Software Testing': ['testing'],
            'Digital Marketer': ['testing'],
        };

        const hourFields = {
            frontend: {
                label: 'Frontend Hours',
                name: 'assigned_frontend_hours'
            },
            backend: {
                label: 'Backend Hours',
                name: 'assigned_backend_hours'
            },
            seo: {
                label: 'SEO Hours',
                name: 'assigned_seo_hours'
            },
            testing: {
                label: 'Testing Hours',
                name: 'assigned_testing_hours'
            },
            designer: {
                label: 'Designer Hours',
                name: 'assigned_designer_hours'
            },
        };

        function buildHourFields(i, role) {
            const trimmedRole = (role || '').trim();
            const keys = roleHourMap.hasOwnProperty(trimmedRole) ?
                roleHourMap[trimmedRole] :
                Object.keys(hourFields);

            return keys.map(function(key) {
                const f = hourFields[key];
                return '<div class="col-md-3 mt-2">' +
                    '<label class="form-label">' + f.label + '</label>' +
                    '<input type="number" step="0.5" min="0" class="form-control"' +
                    ' name="addmore[' + i + '][' + f.name + ']" value="0" placeholder="0">' +
                    '</div>';
            }).join('');
        }

        function add_more_customer_choice_option(i, text, role) {
            role = role || '';
            $('#customer_choice_options').show().append(
                '<div class="form-group row border-bottom pb-3 mb-3" id="staff_row_' + i + '">' +
                '<div class="col-md-3">' +
                '<label class="form-label">Name</label>' +
                '<input type="hidden" name="addmore[' + i + '][id]" value="' + i + '">' +
                '<input type="text" class="form-control" name="addmore[' + i + '][name]" value="' + text + '">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label class="form-label">Start Date</label>' +
                '<input type="date" class="form-control" name="addmore[' + i + '][start_date]" value="' +
                prefillStartDate + '">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label class="form-label">End Date</label>' +
                '<input type="date" class="form-control" name="addmore[' + i + '][end_date]" value="' + prefillEndDate +
                '">' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label class="form-label">Task Priority</label>' +
                '<select class="form-control" name="addmore[' + i + '][priority]">' +
                '<option value="">Select Priority</option>' +
                '<option value="High">High</option>' +
                '<option value="Medium">Medium</option>' +
                '<option value="Low">Low</option>' +
                '</select>' +
                '</div>' +
                buildHourFields(i, role) +
                '</div>'
            );
        }

        // ─── Task Type / User Type toggle ─────────────────────────────────────────
        // ─── Task Type / User Type toggle ─────────────────────────────────────────
        function cusom_date() {
            var date_check = $("#date_type").val();
            var user_type = $("#user_type").val();

            // Reset all conditional sections first
            $("#date_display, #drop_display, #check_dynamic, #check_dynamic1, #check_dynamic2, #check_dynamic3").hide();
            $("#customer_choice_options").hide().html('');

            if (user_type === "sub_admin" && date_check === "custom") {
                $("#date_display").show();
                $("#check_dynamic3").show();

            } else if (user_type === "staff" && date_check === "custom") {
                $("#date_display").show();
                $("#check_dynamic1").show();
                $("#check_dynamic2").show();

                // Auto-load staff into the multi-select as soon as "Staff" is picked
                $('#multiple_staff').empty();
                $('#sub_admin_id').val('');

                $.ajax({
                    url: "{{ route('admin.staff.get') }}",
                    type: "POST",
                    data: {
                        user_type: 'staff',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#multiple_staff').append(response);
                    }
                });

            } else if (date_check === "recurring") {
                $("#drop_display").show();
                $("#check_dynamic").show();
                $("#staff_id_1").html('<option value="">Select User</option>');

                $.ajax({
                    url: "{{ route('admin.staff.get') }}",
                    type: "POST",
                    data: {
                        user_type: user_type,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $("#staff_id_1").append(response);
                    }
                });
            }
        }

        // ─── Sub admin → load their staff into #multiple_staff ───────────────────
        function sub_admin_check() {
            var sub_admin_id = $("#sub_admin_id").val();
            if (!sub_admin_id) return;

            $('.inner li').remove();
            $('#multiple_staff').empty();
            $('#customer_choice_options').html('').hide();

            $.ajax({
                url: "{{ route('admin.staff.check') }}",
                type: "POST",
                data: {
                    id: sub_admin_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var optionsHtml = JSON.parse(response.html);
                    var listHtml = JSON.parse(response.staff);

                    if (optionsHtml) {
                        $("#check_dynamic2").show();
                        // Append raw HTML string — data-role is inside each <option>
                        $('#multiple_staff').append(optionsHtml);
                        $('.inner').append(listHtml);
                    }
                }
            });
        }

        // ─── All event bindings ───────────────────────────────────────────────────
        $(document).ready(function() {

            $(document).on('change', '#multiple_staff', function() {
                $('#customer_choice_options').html('').hide();

                var lastSubAdminId = '';

                $('#multiple_staff option:selected').each(function() {
                    var id = $(this).val();
                    var name = $(this).text().trim();
                    var role = $(this).attr('data-role') || '';
                    var subAdminId = $(this).attr('data-subadmin') || '';
                    if (subAdminId && subAdminId !== 'null') lastSubAdminId = subAdminId;

                    add_more_customer_choice_option(id, name, role);
                });

                // Auto-select Select Sub Admin based on the picked staff — no manual pick needed
                $('#sub_admin_id').val(lastSubAdminId);
            });

            // Sub Admin single select (One time Task → Sub Admin flow)
            $(document).on('change', '#task_sub_admin', function() {
                $('#customer_choice_options').html('').hide();
                var id = $(this).val();
                var name = $(this).find('option:selected').text().trim();
                // Sub admins have no role restriction → show all hours
                add_more_customer_choice_option(id, name, '');
            });

        });
    </script>
@endsection
