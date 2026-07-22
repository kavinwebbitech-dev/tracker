@extends('layouts.sub_admin')

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
								<li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
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
				<div class="col-lg-12 col-12">
					  <div class="box">
						<!-- /.box-header -->
						<form class="form" action="{{ route('sub.task.update', $task->id) }}" method="post">
							@csrf
							<div class="box-body">
								<div class="row">
									<?php
										$userCheck = $TaskStaff;
										$user_task_check = [];
										foreach ($userCheck as $key => $value1) {
											$user_task_check[] = $value1->staff_id;
										}
										// dd($user_task_check);
									?>
									<div class="col-md-6">
										<div class="form-group"><label class="form-label">Select Staff</label>
						    				<div class="input-group mb-3">
												<select class="selectpicker form-control multiple_staff" id="multiple_staff" name="multiple_staff[]" multiple>
														@if($sub_admin)
														@foreach($sub_admin as $key => $staff_details)
															<option value="{{ $staff_details->id }}" @if(in_array($staff_details->id, $user_task_check)) selected @endif>{{ $staff_details->name }}</option>
														@endforeach
														@endif
												</select>
											</div>
										</div>
									</div>

									{{-- <div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Assign Staff</label>
											<div class="input-group mb-3">
												<select class="form-control @error('staff_id') is-invalid @enderror" name="staff_id" id="staff_dropdown">
													<option value="">Select Staff</option>
													@if($sub_admin)
													@foreach($sub_admin as $key => $staff_details)
													<option value="{{ $staff_details->id }}">{{ $staff_details->name }}</option>
													@endforeach
													@endif
												</select>
											</div>
											@error('staff_id')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div> --}}

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Select Project</label>
											<div class="input-group mb-3">
												<select class="form-control select2 @error('project_id') is-invalid @enderror" name="project_id" id="project_id">
													<option value="">Select Project</option>
													@if($projects)
													@foreach($projects as $key => $project)
													<option value="{{ $project->id }}" @if($task->project_id == $project->id) selected @endif>{{ $project->name }}</option>
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
												<input type="text" class="form-control @error('task_name') is-invalid @enderror" name="task_name" placeholder="Task Name" value="{{ $task->name }}" required>
												<!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
											</div>
											@error('task_name')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Freequency of Task Type</label>
											<div class="input-group mb-3">
												<select class="form-control @error('task_type') is-invalid @enderror" name="task_type" onchange="cusom_date()" id="date_type">
													<!-- <option value="">Select Task Type</option> -->
													<!-- <option value="recurring">Recurring</option> -->
													<option value="custom">One time Task</option>
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

									{{-- <div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Task Amount</label>
											<div class="input-group mb-3">
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

								<div class="row" id="customer_choice_options">
								@if($task->task_staff)
								@foreach($task->task_staff as $key => $task_staff)
									<div class="form-group row">
						                <div class="col-md-3">
						                	<label class="form-label">Name</label>
						                	<input type="hidden" name="addmore[{{$task_staff->staff_id}}][id]" value="{{ $task_staff->staff_details->id }}">
						                    <input type="text" class="form-control" name="addmore[{{$task_staff->staff_id}}][name]" value="{{ $task_staff->staff_details->name }}">
						                </div>
						                <div class="col-md-3">
						                	<label class="form-label">Start Date</label>
						                	<input type="date" class="form-control" name="addmore[{{$task_staff->staff_id}}][start_date]" placeholder="" value="{{ $task_staff->start_date }}">
						                </div>
						                <div class="col-md-3">
						                	<label class="form-label">End Date</label>
						                	<input type="date" class="form-control" name="addmore[{{$task_staff->staff_id}}][end_date]" placeholder="test1" value="{{ $task_staff->end_date }}">
						                </div>
						                <div class="col-md-3">
						                    <div class="form-group">
						                        <label class="form-label">Task Priority </label>
						                        <div class="input-group mb-3">
						                            <select class="form-control" name="addmore[{{$task_staff->staff_id}}][priority]">
						                                <option value="">Select Priority</option>
						                                <option value="High" @if($task_staff->priority == "High") selected @endif>High</option>\
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
								{{-- <div class="form-group">
									<label class="form-label">Confirm Password</label>
									<div class="input-group mb-3">
										<input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Confirm Password">
										<span class="input-group-text"><i class="ti-lock"></i></span>
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <!-- /.content-wrapper -->
    <script type="text/javascript">
  	function cusom_date()
    {
    	// alert("hi");
    	var date_check = $("#date_type").val();
    	// alert(date_check);
    	if (date_check == "custom") {
    		$("#date_display").show();
    		$("#drop_display").hide();
    	}
    	else if (date_check == "recurring") {
    		$("#drop_display").show();
    		$("#date_display").hide();
    	}
    	else
    	{
    		$("#date_display").hide();
    		$("#drop_display").hide();
    	}
    }

    function add_more_customer_choice_option(i, text) {
    	var user_id = i;
    	var task_id = "{{ $task->id }}";
    	var text = text;
    	$.ajax({
            url: "{{ route('sub_admin.staff.task.check') }}",
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


    $('#multiple_staff').on('change', function() {
    	// alert("hi");
        $('#customer_choice_options').html(null);
        $.each($("#multiple_staff option:selected"), function(){
            add_more_customer_choice_option($(this).val(), $(this).text());
        });

    });

  </script>
@endsection