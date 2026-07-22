@extends('layouts.staff')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Create Project</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('staff.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Project</li>
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
				
				<div class="col-lg-12 col-12">
					
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
                    <style type="text/css">
                    	.icheckbox_minimal-blue
                    	{
                    		margin-top: -6px;
                    	}
                    	[type="radio"]:not(:checked), [type="radio"]:checked
                    	{
                    		position: relative;
                    		opacity: 1;
                    		left: 0px;
                    	}
                    </style>
					  <div class="box">
						<!-- /.box-header -->
						<form class="form" action="{{ route('staff.projects.update', $sub_admin->id) }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="box-body">
								<div class="row" style="margin-bottom: 20px;">
									<div class="col-md-4">
										<label for="chkPassport" style="font-size: 18px;font-weight: 700;">
										    <input type="radio" id="normal_projects" name="project_type" onclick="ShowHideDiv('normal_projects')" value="normal_projects" @if($sub_admin->is_renewal== 0) checked @endif/>
										    Regular Project
										</label>
									</div>
									<div class="col-md-4">
										<label for="chkPassport" style="font-size: 18px;font-weight: 700;color: red;">
										    <input type="radio" id="renewal_projects" name="project_type" onclick="ShowHideDiv('renewal_projects')" value="renewal_projects" @if($sub_admin->is_renewal== 1) checked @endif/>
										    Renewal Project
										</label>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Customer</label>
											<div class="input-group mb-3">
												<select class="form-control select2 @error('customer') is-invalid @enderror" name="customer">
													<option value="">Select Customer</option>
													@if($customers)
													@foreach($customers as $key => $users)
					                              	<option value="{{ $users->id }}" @if($sub_admin->customer_id== $users->id) selected @endif>{{ $users->fld_name }} ({{ $users->fld_phone }}) - {{ $users->fld_company_name }}</option>
					                              	@endforeach
					                              	@endif
												</select>
											</div>
											@error('customer')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Name</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" value="{{ $sub_admin->name }}">
											</div>
											@error('name')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Status</label>
											<div class="input-group mb-3">
												<select class="form-control @error('status') is-invalid @enderror" name="status">
													<option value="0" @if($sub_admin->status== 0) selected @endif>Pending</option>
													<option value="1" @if($sub_admin->status== 1) selected @endif>On Progress</option>
													<option value="3" @if($sub_admin->status== 3) selected @endif>On Hold</option>
													<option value="5" @if($sub_admin->status== 5) selected @endif>Done</option>
													<option value="6" @if($sub_admin->status== 6) selected @endif>Cancel</option>
												</select>
											</div>
											@error('status')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Sales Person</label>
											<div class="input-group mb-3">
												<select class="form-control select2 @error('sales_person') is-invalid @enderror" name="sales_person">
													<option value="">Select Person</option>
													@if($salesperson)
														@foreach($salesperson as $key => $users)
															@php
																$user_details = \App\Models\User::find($users->added_by);
															@endphp

															@if(
																$user_details &&
																$user_details->user_type === 'staff' &&
																auth()->user()->id == $user_details->id
															)
																<option value="{{ $user_details->id }}" selected>
																	{{ $user_details->name }}
																</option>
															@endif
														@endforeach
													@endif
												</select>
											</div>
											@error('sales_person')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Order Confirm Date</label>
											<div class="input-group mb-3">
												<input type="date" class="form-control @error('confirm_date') is-invalid @enderror" name="confirm_date" value="{{ $sub_admin->sales_user_date }}">
											</div>
											@error('confirm_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Start Date</label>
											<div class="input-group mb-3">
												<input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ $sub_admin->start_date }}">
											</div>
											@error('start_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4"  @if($sub_admin->is_renewal== 0) @else style="display: none;" @endif  id="end_day_display">
										<div class="form-group">
											<label class="form-label">End Date</label>
											<div class="input-group mb-3">
												<input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" value="{{ $sub_admin->end_date }}">
											</div>
											@error('end_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4" @if($sub_admin->is_renewal== 1) @else style="display: none;" @endif  id="end_day_display" id="renewal_days">
										<div class="form-group">
											<label class="form-label">Renewal Days</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('renewal_days') is-invalid @enderror" name="renewal_days" value="{{ $sub_admin->renewal_days }}">
											</div>
											@error('renewal_days')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Amount</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" placeholder="Amount" value="{{ $sub_admin->bid_amount }}" required>
											</div>
											@error('amount')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4" @if($sub_admin->is_renewal== 1) @else style="display: none;" @endif  id="end_day_display" id="alert_days">
										<div class="form-group">
											<label class="form-label">Alert Days</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('alert_days') is-invalid @enderror" name="alert_days" placeholder="Days" value="{{ $sub_admin->total_days }}">
											</div>
											@error('alert_days')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Service</label>
											<div class="input-group mb-3">
												<select class="form-control select2 @error('services') is-invalid @enderror" name="services">
													<option value="">Select Service</option>
													@if($services)
													@foreach($services as $key => $ser)
					                              	<option value="{{ $ser->id }}" @if($sub_admin->services_id== $ser->id) selected @endif>{{ $ser->name }}</option>
					                              	@endforeach
					                              	@endif
												</select>
											</div>
											@error('services')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label class="form-label">Description</label>
											<textarea id="editor1" name="editor1" rows="5" cols="80">
													{{ $sub_admin->description }}
											</textarea>
										</div>
									</div>
								</div>
								
							</div>
							<!-- /.box-body -->
							<div class="box-footer text-end">
								<input type="submit" name="submit" class="btn btn-primary" value="Submit">
								<!-- <button type="submit" class="btn btn-primary">
								  <i class="ti-save-alt"></i> Save
								</button> -->
							</div>  
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
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script type="text/javascript">
  	function ShowHideDiv(chkPassport) {
  		
  		var text = document.getElementById("end_day_display");
  		var renewal_days = document.getElementById("renewal_days");
  		var alert_days = document.getElementById("alert_days");
  		if (chkPassport == "normal_projects") 
  		{
  			var checkBox = document.getElementById("normal_projects");
		
			if (checkBox.checked == true){
			    text.style.display = "block";
			    renewal_days.style.display = "none";
			    alert_days.style.display = "none";
			} else {
			    text.style.display = "none";
			    renewal_days.style.display = "none";
			    alert_days.style.display = "none";
			}
  		}
  		else if(chkPassport == "renewal_projects")
  		{
  			var checkBox = document.getElementById("renewal_projects");

			if (checkBox.checked == true){
			    text.style.display = "none";
			    renewal_days.style.display = "block";
			    alert_days.style.display = "block";
			} else {
			    text.style.display = "none";
			    renewal_days.style.display = "none";
			    alert_days.style.display = "none";
			}
  		}
  		
  	}
  </script>
@endsection