@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Edit Task</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Task</li>
								<li class="breadcrumb-item active" aria-current="page">Edit</li>
							</ol>
						</nav>
					</div>
				</div>
				
			</div>
		</div>	  

		<!-- Main content -->
		<section class="content">
			<div class="row">			  
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
                @include('layouts.flash-message')
				<div class="col-lg-12 col-12">
					<?php
						// $form_date = (new \Carbon\Carbon($task->start_date))->format('m/d/Y') ?? '';
        				// $to_date   = (new \Carbon\Carbon($task->end_date))->format('m/d/Y') ?? '';
        				// $date_range = $form_date .' - '.$to_date;
					?>
					  <div class="box">
						<!-- /.box-header -->
						<form class="form" action="{{ route('task.update', $task->id) }}" method="post">
							@csrf
							
							<div class="box-body">
								<div class="row">

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Task Type</label>
											<div class="input-group in-bord mb-3">
												<select class="form-control @error('task_type') is-invalid @enderror" name="task_type" id="date_type" onchange="cusom_date()">
													<option value="">Select Task Type</option>
													<option value="custom" @if($task->task_type == "custom") selected @endif>One time Task</option>
													{{-- <option value="recurring" @if($task->task_type == "recurring") selected @endif>Recurring</option> --}}
												</select>
												<!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
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
											<div class="input-group in-bord mb-3">
												<select class="form-control @error('user_type') is-invalid @enderror" name="user_type" id="user_type" onchange="cusom_date()">
													<option value=""> Select User</option>
														<option value="sub_admin" @if($task->sub_admin_id == "") selected @endif> Sub Admin</option>
														<option value="staff" @if($task->sub_admin_id != "") selected @endif> Staff</option>
												</select>
												<!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
											</div>
											@error('user_type')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>

									@if($task->sub_admin_id == "")
									<div class="col-md-6" id="check_dynamic3">
										<div class="form-group">
											<label class="form-label">Select Sub Admin</label>
											<div class="input-group in-bord mb-3">
												<select class="form-control @error('task_sub_admin') is-invalid @enderror" name="task_sub_admin" id="task_sub_admin">
													<option value=""> Select Sub Admin</option>
													@foreach($sub_admin as $key => $value)
														<option value="{{ $value->id }}" @if($task->staff_id == $value->id) selected @endif> {{ $value->name }}</option>
													@endforeach
												</select>
												<!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
											</div>
											@error('task_sub_admin')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									@endif

									<div class="col-md-6" id="check_dynamic" style="display:none;">
										<div class="form-group">
											<label class="form-label">Select User</label>
											<div class="input-group in-bord mb-3">
												<select class="form-control @error('staff_id') is-invalid @enderror" name="staff_id" id="staff_id">
													<option value=""> Select User</option>
													@foreach($sub_admin as $key => $value)
														<option value="{{ $value->id }}"> {{ $value->name }}</option>
													@endforeach
												</select>
												<!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
											</div>
											@error('staff_id')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									
									<div class="col-md-6" id="check_dynamic1" @if($task->sub_admin_id != "") style="display:block;" @else  style="display:none;"  @endif>

										<div class="form-group">
											<label class="form-label">Select Sub Admin</label>
											<div class="input-group in-bord mb-3">
												<select class="form-control @error('sub_admin_id') is-invalid @enderror" name="sub_admin_id" id="sub_admin_id" onchange="sub_admin_check()">
													<option value=""> Select Sub Admin</option>
													@foreach($sub_admin as $key => $value)
														<option value="{{ $value->id }}" @if($task->sub_admin_id == $value->id) selected  @endif> {{ $value->name }}</option>
													@endforeach
												</select>
												<!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
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
											<div class="input-group in-bord mb-3">
												<select class="form-control @error('staff_id') is-invalid @enderror" name="staff_id" id="staff_id">
													@foreach($sub_admin as $key => $value)
														<option value="{{ $value->id }}"> {{ $value->name }}</option>
													@endforeach
												</select>
												<!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
											</div>
											@error('staff_id')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>

									<div class="col-md-6" id="check_dynamic2" @if($task->sub_admin_id != "") style="display:block;" @else  style="display:none;"  @endif>
										<div class="form-group"><label class="form-label">Select Staff</label>
						    				<div class="input-group in-bord mb-3">
						    					<select class="form-control multiple_staff select2 @error('staff_id') is-invalid @enderror" id="multiple_staff"  name="multiple_staff[]" style="width: 100%;" multiple>
												<!-- <select class="selectpicker form-control multiple_staff" id="multiple_staff" name="multiple_staff[]" multiple> -->
													@foreach($staff as $key => $value1)
														<?php
															$task_details = \App\Models\TaskStaff::where('task_id', $task->id)->where('staff_id', $value1->id)->first();
														?>
														@if($task_details)
															<option value="{{ $value1->id }}" @if($task_details->staff_id == $value1->id) selected @endif> {{ $value1->name }}</option>
														@else
															<option value="{{ $value1->id }}"> {{ $value1->name }}</option>
														@endif
														
													@endforeach
												</select>
											</div>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Select Project</label>
											<div class="input-group in-bord mb-3">
												<select class="form-control select2 @error('project_id') is-invalid @enderror" name="project_id" id="project_id">
													<option value="">Select Project</option>
													@if($projects)
													@foreach($projects as $key => $project)
													<option value="{{ $project->id }}" @if($task->project_id == $project->id) selected @endif>{{ $project->name }}</option>
													@endforeach
													@endif
												</select>
												<!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
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
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('task_name') is-invalid @enderror" name="task_name" placeholder="Task Name" value="{{ $task->name }}" required>
												<!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
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
								@if($task->task_staff)
								@foreach($task->task_staff as $key => $task_staff)
									<div class="form-group row">
						                <div class="col-md-3">
						                	<label class="form-label">Name</label>
						                	<input type="hidden" name="addmore[{{$task_staff->staff_id}}][id]" value="{{ $task_staff->staff_details->id ?? '' }}">
						                    <input type="text" class="form-control" name="addmore[{{$task_staff->staff_id}}][name]" value="{{ $task_staff->staff_details->name ?? '' }}">
						                </div>
						                <div class="col-md-3">
						                	<label class="form-label">Start Date</label>
						                	<input type="date" class="form-control" name="addmore[{{$task_staff->staff_id}}][start_date]" placeholder="" value="{{ $task_staff->start_date ?? '' }}">
						                </div>
						                <div class="col-md-3">
						                	<label class="form-label">End Date</label>
						                	<input type="date" class="form-control" name="addmore[{{$task_staff->staff_id}}][end_date]" placeholder="test1" value="{{ $task_staff->end_date ?? '' }}">
						                </div>
						                <div class="col-md-3">
											<div class="form-group">
												<label class="form-label">Task Priority </label>
												<div class="input-group in-bord mb-3">
													<select class="form-control" name="addmore[{{$task_staff->staff_id}}][priority]">
														<option value="">Select Priority</option>
														<option value="High" @if($task_staff->priority == "High") selected @endif>High</option>
														<option value="Medium" @if($task_staff->priority == "Medium") selected @endif>Medium</option>
														<option value="Low" @if($task_staff->priority == "Low") selected @endif>Low</option>
													</select>
												</div>
											</div>
										</div>
						            </div>
						        @endforeach
								@endif
								</div>

								<div class="row" style="display: none;" id="drop_display">
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Period of Task</label>
											<div class="input-group in-bord mb-3">
												<select class="form-control @error('date_type') is-invalid @enderror" name="date_type">
													<option value="7">Weekly</option>
													<option value="15">15 Days</option>
													<option value="30">Monthly</option>
													<option value="90">Quarterly</option>
													<option value="365">Yearly</option>
												</select>
												<!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
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
											<div class="input-group in-bord mb-3">
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
								
								<div class="row">
									
									{{-- <div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Start Date</label>

											<div class="input-group in-bord mb-3">
												<input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ $task->start_date }}">
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

											<div class="input-group in-bord mb-3">
												<input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ $task->end_date }}">
											</div>
											@error('end_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div> --}}

									{{-- <div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Task Amount</label>
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('task_amount') is-invalid @enderror" name="task_amount" placeholder="Task Amount" value="{{ $task->task_amount }}">
											</div>
											@error('task_amount')
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
						    				<div class="input-group in-bord mb-3">
						    					<select class="form-control select2 @error('payment_follow_up') is-invalid @enderror" id="payment_follow_up"  name="payment_follow_up" style="width: 100%;">
													<option value="">Select Person</option>	
													@foreach($sub_admin1 as $key => $value)
														<option value="{{ $value->id }}" @if($value->id == $task->payment_follow_up) selected @endif> {{ $value->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Project Work Follow Up</label>
						    				<div class="input-group in-bord mb-3">
						    					<select class="form-control select2 @error('project_follow_up') is-invalid @enderror" id="project_follow_up"  name="project_follow_up" style="width: 100%;">
													<option value="">Select Person</option>	
													@foreach($sub_admin1 as $key => $value)
														<option value="{{ $value->id }}" @if($value->id == $task->project_follow_up) selected @endif> {{ $value->name }}</option>
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
											<textarea id="editor1" name="editor1" rows="10" cols="80">
													{{ $task->description }}
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
								{{-- <div class="form-group">
									<label class="form-label">Confirm Password</label>
									<div class="input-group in-bord mb-3">
										<input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Confirm Password">
										<span class="input-group in-bord-text"><i class="ti-lock"></i></span>
									</div>
									@error('password_confirmation')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
								</div> --}}
								
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

    	// alert(sub_admin_id);
    }

    function add_more_customer_choice_option(i, text) {

    	var user_id = i;
    	var task_id = "{{ $task->id }}";
    	var text = text;
    	$.ajax({
            url: "{{ route('admin.staff.task.check') }}",
            type: "POST",
            data: {
                id: user_id,
                task_id: task_id,
                text: text,
                _token: '{{csrf_token()}}'
            },
            success: function (response) {
            	$('#customer_choice_options').show();
    			$('#customer_choice_options').append(response);
            }
        });
    	
    }

    // var check_staff = $('#task_sub_admin').val();
    // alert(check_staff);
    // if (check_staff) {
    // 	$('#customer_choice_options').html(null);
	//     var admin_name = check_staff.find("option:selected").text();
	//     add_more_customer_choice_option($(this).val(), admin_name);
    // }

    
    

    $('#task_sub_admin').on('change', function() {
    	alert($(this));
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


  </script>
@endsection