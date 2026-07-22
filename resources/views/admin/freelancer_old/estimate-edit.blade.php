@extends('layouts.dashboard')

@section('content')

<style>
	textarea {
	  padding: 0;
	  margin: 0;
	  white-space: pre-wrap; /* This helps maintain text formatting */
	}
</style>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Edit Task Estimate</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Task Estimate</li>
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
						<form class="form" action="{{ route('admin.freelancer.request.edit.store', $task_details->id) }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="box-body">
								
								<div class="row">

									<div class="col-md-4">

										<div class="form-group">
											<label class="form-label">Select Freelancer</label>
											<div class="input-group in-bord mb-3">
												<select class="form-control select2 @error('client_id') is-invalid @enderror" name="client_id" id="client_id">
													<option value=""> Select Freelancer</option>
													@foreach($client_details as $key => $value)
														<option value="{{ $value->id }}" @if($task_details->user_id == $value->id) selected @endif> {{ $value->name }} </option>
													@endforeach
												</select>
												<!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
											</div>
											@error('client_id')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Task Name</label>
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" value="{{ $task_details->name }}" required>
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
											<label class="form-label">Date</label>
											<div class="input-group in-bord mb-3">
												<input type="date" class="form-control @error('confirm_date') is-invalid @enderror" name="confirm_date" onfocus="'showPicker' in this && this.showPicker()" value="{{ $task_details->date }}" required>
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
											<label class="form-label">Amount</label>
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" placeholder="Amount" value="{{ number_format($task_details->amount,2) }}" required>
											</div>
											@error('amount')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Cost Type <span class="text-danger">*</span></label>
											<select name="cost_type" class="form-select @error('cost_type') is-invalid @enderror" required>
												<option value="">-- Select Type --</option>
												<option value="hourly" {{  $task_details->cost_type == 'hourly' ? 'selected' : '' }}>Hourly</option>
												<option value="fixed" {{ $task_details->cost_type == 'fixed' ? 'selected' : '' }}>Fixed</option>
											</select>
											@error('cost_type')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Task Hours</label>
											<input type="number" 
												class="form-control @error('hour') is-invalid @enderror" 
												name="hour" 
												placeholder="Task Hours" 
												value="{{ $task_details->total_hours }}" 
												required>
											@error('hour')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>
									
									@if(count($task_details->client_task_details) > 0)
                                    
									<div class="row">
										<h4 class="text-success">Key Point List</h4>
						                <div class="col-12 text-center" id="dynamicTable">
						                	@foreach($task_details->client_task_details as $key => $value)

						                    <div class="row mb-3 @if($key == 0) @else dynamicrow @endif">
						                        <div class="col-md-10">
						                        	<input type="hidden" name="addmore[{{ $key }}][id]" value="{{ $value->id }}">
									                <textarea name="addmore[{{ $key }}][points]" class="form-control" rows="2" style="width: 100%;">{{ $value->name }}</textarea>
									            </div>
									            @if($key == 0)
						                        <div class="col-md-2"><a href="javascript::void(0)" name="add" id="add" title="Add More"><i class="btn btn-success mdi mdi-plus"></i></a></div>
						                        @else
						                        <div class="col-md-2"><a href="javascript::void(0)" class="remove-tr" title="Remove"><i class="btn btn-danger mdi mdi-close"></i></a></div>
						                        @endif
						                    </div>
						                    @endforeach
						                </div>
									</div>
									
									@else
									<div class="row">
										<h4 class="text-success">Key Point List</h4>
						                <div class="col-12 text-center" id="dynamicTable">
						                    <div class="row mb-3">
						                        <div class="col-md-10">
									                <textarea name="addmore[0][points]" class="form-control" rows="2" style="width: 100%;"></textarea>
									            </div>
						                        <div class="col-md-2"><a href="javascript::void(0)" name="add" id="add" title="Add More"><i class="btn btn-success mdi mdi-plus"></i></a></div>
						                    </div>
						                </div>
									</div>

									@endif
									
									<div class="col-md-12">
										<div class="form-group">
											<label class="form-label">Description</label>
											<textarea id="editor1" name="editor1" rows="5" cols="80">
													{{ $task_details->description }}
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
    var i = 0;
    $("#add").click(function(){
        ++i;
        $("#dynamicTable").append('<div class="row dynamicrow mb-3">\
        <div class="col-md-10">\
            <textarea name="addmore['+i+'][points]" class="form-control" rows="2" style="width: 100%;"></textarea>\
        </div>\
        <div class="col-md-2"><a href="javascript::void(0)" class="remove-tr" title="Remove"><i class="btn btn-danger mdi mdi-close"></i></a></div>\
        </div>');
    });
    $(document).on('click', '.remove-tr', function(){  
        $(this).parents('.dynamicrow').remove();
    });
    
</script>
  

 <script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/ckeditor/ckeditor.js"></script>
        <!-- <script src="//cdn.ckeditor.com/4.5.6/standard/ckeditor.js"></script> -->
        <!-- <script src="<?php echo url('');?>/public/admin_assets/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js"></script> -->
        <script src="<?php echo url('');?>/public/admin_assets/js/pages/editor.js"></script>
@endsection