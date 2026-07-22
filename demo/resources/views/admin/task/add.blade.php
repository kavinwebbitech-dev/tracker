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
								<li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
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
							<div class="box-body">
								<div class="row">

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Task Type</label>
											<div class="input-group mb-3">
												<select class="form-control @error('task_type') is-invalid @enderror" name="task_type" id="date_type" onchange="cusom_date()">
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
												<select class="form-control @error('user_type') is-invalid @enderror" name="user_type" id="user_type" onchange="cusom_date()">
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
												<select class="form-control @error('task_sub_admin') is-invalid @enderror" name="task_sub_admin" id="task_sub_admin">
													<option value=""> Select Sub Admin</option>
													@foreach($sub_admin as $key => $value)
														<option value="{{ $value->id }}"> {{ $value->name }}</option>
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
												<select class="form-control @error('staff_id') is-invalid @enderror" name="staff_id_1" id="staff_id_1">
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
									
									<div class="col-md-6" id="check_dynamic1" style="display:none;">

										<div class="form-group">
											<label class="form-label">Select Sub Admin</label>
											<div class="input-group mb-3">
												<select class="form-control @error('sub_admin_id') is-invalid @enderror" name="sub_admin_id" id="sub_admin_id" onchange="sub_admin_check()">
													<option value=""> Select Sub Admin</option>
													@foreach($sub_admin as $key => $value)
														<option value="{{ $value->id }}"> {{ $value->name }}</option>
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
												<select class="form-control @error('staff_id') is-invalid @enderror" name="staff_id" id="staff_id">
													@foreach($sub_admin as $key => $value)
														<option value="{{ $value->id }}"> {{ $value->name }}</option>
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

									<div class="col-md-6" id="check_dynamic2" style="display:none;">
										<div class="form-group">
											<label class="form-label">Select Staff</label>
						    				<div class="input-group mb-3">
						    					<select class="form-control multiple_staff select2 @error('staff_id') is-invalid @enderror" id="multiple_staff"  name="multiple_staff[]" style="width: 100%;" multiple>
												<!-- <select class="selectpicker form-control multiple_staff"name="multiple_staff[]" multiple> -->
													
												</select>
											</div>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Select Project</label>
											<div class="input-group mb-3">
												<select class="form-control select2 @error('project_id') is-invalid @enderror" name="project_id" id="project_id">
													<option value="">Select Project</option>
													@if($projects)
													@foreach($projects as $key => $project)
													<option value="{{ $project->id }}">{{ $project->name }}</option>
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
												<input type="text" class="form-control @error('task_name') is-invalid @enderror" name="task_name" placeholder="Task Name" value="{{ old('task_name') }}" required>
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
												<select class="form-control @error('date_type') is-invalid @enderror" name="date_type">
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
												<input type="date" class="form-control @error('recurring_start_date') is-invalid @enderror" name="recurring_start_date" placeholder="Task Start Date" value="{{ old('recurring_start_date') }}">
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

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Payment Follow Up</label>
						    				<div class="input-group mb-3">
						    					<select class="form-control select2 @error('payment_follow_up') is-invalid @enderror" id="payment_follow_up"  name="payment_follow_up" style="width: 100%;">
													<option value="">Select Person</option>	
													@foreach($sub_admin1 as $key => $value)
														<option value="{{ $value->id }}"> {{ $value->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Project Work Follow Up</label>
						    				<div class="input-group mb-3">
						    					<select class="form-control select2 @error('project_follow_up') is-invalid @enderror" id="project_follow_up"  name="project_follow_up" style="width: 100%;">
													<option value="">Select Person</option>	
													@foreach($sub_admin1 as $key => $value)
														<option value="{{ $value->id }}"> {{ $value->name }}</option>
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
											<input type="submit" name="submit" class="btn btn-primary" value="Submit">
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <script type="text/javascript">
  	function cusom_date()
    {
    	
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
    	}
    	else if (user_type == "staff" && date_check == "custom") {
    		$("#drop_display").hide();
    		$("#date_display").show();
    		$("#check_dynamic2").show();
    		$("#check_dynamic1").show();
    		$("#check_dynamic3").hide();
    		$("#customer_choice_options").hide();
    	}
    	else if (user_type == "sub_admin" && date_check == "recurring") {
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
                    _token: '{{csrf_token()}}'
                },
                success: function (response) {
                	console.log(response);
                	$("#staff_id_1").append(response);

                }
            });
    	}
    	else if (user_type == "staff" && date_check == "recurring") {
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
                    _token: '{{csrf_token()}}'
                },
                success: function (response) {
                	console.log(response);
                	$("#staff_id_1").append(response);

                }
            });
    	}
    	else
    	{
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

    function sub_admin_check() 
    {

    	var sub_admin_id = $("#sub_admin_id").val();

    	if (sub_admin_id) {
    		$('.inner li').remove();
			$.ajax({
                url: "{{ route('admin.staff.check') }}",
                type: "POST",
                data: {
                    id: sub_admin_id,
                    _token: '{{csrf_token()}}'
                },
                success: function (response) {
                	console.log(response);
                	// $('#multiple_staff').empty();

                	var obj = JSON.parse(response.html);
                	var obj1 = JSON.parse(response.staff);
                	
                	if (obj) {
                		$("#check_dynamic2").show();
	                	$('#multiple_staff').append(obj);
	                	$('.inner').append(obj1);
                	}

                }
            });
		}
    }

    function add_more_customer_choice_option(i, text) {
    	$('#customer_choice_options').show();
    	$('#customer_choice_options').append('\
            <div class="form-group row">\
                <div class="col-md-3">\
                	<label class="form-label">Name</label>\
                	<input type="hidden" name="addmore['+i+'][id]" value="'+i+'">\
                    <input type="text" class="form-control" name="addmore['+i+'][name]" value="'+text+'">\
                </div>\
                <div class="col-md-3">\
                	<label class="form-label">Start Date</label>\
                	<input type="date" class="form-control" name="addmore['+i+'][start_date]" placeholder="test">\
                </div>\
                <div class="col-md-3">\
                	<label class="form-label">End Date</label>\
                	<input type="date" class="form-control" name="addmore['+i+'][end_date]" placeholder="test1">\
                </div>\
                <div class="col-md-3">\
					<div class="form-group">\
						<label class="form-label">Task Priority </label>\
						<div class="input-group mb-3">\
							<select class="form-control" name="addmore['+i+'][priority]">\
								<option value="">Select Priority</option>\
								<option value="High">High</option>\
								<option value="Medium">Medium</option>\
								<option value="Low">Low</option>\
							</select>\
						</div>\
					</div>\
				</div>\
            </div>');
    }

    
    
    $('#task_sub_admin').on('change', function() {
        $('#customer_choice_options').html(null);
        var admin_name = $(this).find("option:selected").text();

        add_more_customer_choice_option($(this).val(), admin_name);
    });

    $(document).ready(function() {
	    $('#multiple_staff').on('change', function() {
	        var selectedValue = $(this).val();
	        $('#customer_choice_options').html(null);
	        $.each($("#multiple_staff option:selected"), function(){
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


  </script>
@endsection