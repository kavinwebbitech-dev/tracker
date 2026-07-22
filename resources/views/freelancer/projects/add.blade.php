@extends('layouts.freelancer')

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
								<li class="breadcrumb-item"><a href="{{ route('freelancer.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
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
							<form class="form" action="{{ route('freelancer.projects.store') }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="box-body">
								
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Task Name</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" value="{{ old('name') }}" required>
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
											<div class="input-group mb-3">
												<input type="date" class="form-control @error('confirm_date') is-invalid @enderror" name="confirm_date" onfocus="'showPicker' in this && this.showPicker()" value="{{ date('Y-m-d') }}" required>
											</div>
											@error('confirm_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>

									{{-- <div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Task Hours</label>
											<div class="input-group mb-3">
												<input type="number" class="form-control @error('hour') is-invalid @enderror" name="hour" placeholder="Task Hours" value="{{ old('hour') }}" required>
											</div>
											@error('hour')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div> --}}
									{{-- <div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Amount</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" placeholder="Amount" value="{{ old('amount') }}" required>
											</div>
											@error('amount')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div> --}}

									<div class="row">
										<h4 class="text-success mb-3">Task List</h4>
										<div class="col-12" id="dynamicTable">
											<div class="row mb-3">
												<!-- Task Points -->
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-label">Task Points</label>
														<textarea name="addmore[0][points]" 
																class="form-control" 
																rows="2" 
																style="width: 100%;"></textarea>
													</div>
												</div>

												<!-- Task Hours -->
												<div class="col-md-4">
													<div class="form-group">
														<label class="form-label">Task Hours</label>
														<input type="number" 
															class="form-control @error('hour') is-invalid @enderror" 
															name="addmore[0][hour]" 
															placeholder="Task Hours" 
															value="{{ old('hour') }}" 
															required>
														@error('hour')
															<span class="invalid-feedback" role="alert">
																<strong>{{ $message }}</strong>
															</span>
														@enderror
													</div>
												</div>

												<!-- Add More Button -->
												<div class="col-md-2 text-start mt-4 p-2">
													<button type="button" name="add" id="add" class="btn btn-success">
														<i class="mdi mdi-plus"></i> Add
													</button>
												</div>
											</div>
										</div>
									</div>
									
									<div class="col-md-12">
										<div class="form-group">
											<label class="form-label">Description</label>
											<textarea id="editor1" name="editor1" rows="5" cols="80"></textarea>
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
        $("#dynamicTable").append(
			'<div class="col-12" id="dynamicTable">\
				<div class="row mb-3 dynamicrow">\
					<div class="col-md-6">\
						<div class="form-group">\
							<label class="form-label">Task Points</label>\
							<textarea name="addmore['+i+'][points]" class="form-control" rows="2" style="width: 100%;"></textarea>\
						</div>\
					</div>\
					<div class="col-md-4">\
						<div class="form-group">\
							<label class="form-label">Task Hours</label>\
							<input type="number" class="form-control" name="addmore['+i+'][hour]" placeholder="Task Hours" required>\
						</div>\
					</div>\
					<div class="col-md-2 text-start mt-4 p-2">\
						<a href="javascript:void(0)" class="btn btn-danger remove-tr"><i class="mdi mdi-close"></i> Remove</a>\
					</div>\
				</div>\
			</div>'
		);

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