@extends('layouts.sub_admin')

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
								<li class="breadcrumb-item"><a href="{{ route('staff.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
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
					<?php
						$form_date = (new \Carbon\Carbon($task->start_date))->format('m/d/Y') ?? '';
        				$to_date   = (new \Carbon\Carbon($task->end_date))->format('m/d/Y') ?? '';
        				$date_range = $form_date .' - '.$to_date;
						// dump($date_range);

					?>
					  <div class="box">
						<!-- /.box-header -->
						<form class="form" action="{{ route('sub.task.update', $task->id) }}" method="post">
							@csrf
							<div class="box-body">
								<div class="row">
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Assign Staff</label>
											<div class="input-group in-bord mb-3">
												<select class="form-control @error('staff_id') is-invalid @enderror" name="staff_id" id="staff_dropdown">
													<option value="">Select Staff</option>
													@if($staff)
													@foreach($staff as $key => $staff_details)
													<option value="{{ $staff_details->id }}" @if($task->staff_id == $staff_details->id) selected @endif>{{ $staff_details->name }}</option>
													@endforeach
													@endif
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
								</div>

								<div class="row">
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
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Date Range</label>
											<div class="input-group in-bord">
											  <div class="input-group in-bord-addon">
												<i class="fa fa-calendar"></i>
											  </div>
											  <input type="text" class="form-control pull-right" name="date_range_task" id="reservation" value="{{ $date_range }}">
											</div>
											@error('start_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
								</div>
								
								<div class="row">
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Status</label>

											<div class="input-group in-bord mb-3">
												<select class="form-control @error('status') is-invalid @enderror" name="status">
													<option value="pending" @if($task->status == "pending") selected @endif>Pending</option>
													<option value="progress" @if($task->status == "progress") selected @endif>Progress</option>
													<option value="completed" @if($task->status == "completed") selected @endif>Completed</option>
													<option value="rejected" @if($task->status == "rejected") selected @endif>Rejected</option>
												</select>
												<!-- <span class="input-group in-bord-text"><i class="ti-lock"></i></span> -->
											</div>
											@error('status')
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
@endsection