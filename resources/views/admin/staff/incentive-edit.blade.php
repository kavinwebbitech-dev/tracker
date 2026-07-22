@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Edit Staff Incentive</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Staff Incentive</li>
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
						<form class="form" action="{{ route('sub.staff.incentive.update') }}" method="post">
							@csrf
							<div class="box-body">
								
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label class="form-label">Select Staff</label>
											<div class="creative-input-group mb-3">
												<select class="select2 form-control @error('staff_id') is-invalid @enderror" name="staff_id" style="width: 100%;">
													<option value="">Select Staff</option>
													@if(count($sub_admin) > 0)
						                            @foreach($sub_admin as $key => $value)
						                            <option value="{{ $value->id }}" @if($user_details->id == $value->id) selected @endif>{{ $value->name }}</option>
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
									</div>
									<div class="col-12 text-center" id="dynamicTable">
					              	@if(count($incentive_details) > 0)
					              	@foreach($incentive_details as $key => $value)
					                  <div class="row mb-3 @if($key != 0) dynamicrow @endif">
					                  		<input type="hidden" name="addmore[{{ $key }}][id]" value="{{ $value->id }}">
					                      <div class="col-md-4">
											<div class="creative-input-group">
												<input type="text" name="addmore[{{ $key }}][start_amount]" value="{{ $value->start_amount }}" placeholder="Start Amount" class="form-control">
											</div> 
					                      </div>
					                      <div class="col-md-4">
											<div class="creative-input-group">
												<input type="text" name="addmore[{{ $key }}][end_amount]" value="{{ $value->end_amount }}" placeholder="End Amount" class="form-control" />
											</div>
					                          
					                      </div>
					                      <div class="col-md-3">
											<div class="creative-input-group">
												 <input type="text" name="addmore[{{ $key }}][incentive]" value="{{ $value->amount }}" placeholder="Incentive Amount" class="form-control" />
											</div>
					                         
					                      </div>
					                      @if($key == 0)
					                      	<div class="col-md-1"><a href="javascript::void(0)" name="add" id="add" title="Add More"><i class="btn btn-success mdi mdi-plus"></i></a></div>
					                      @else
					                      	<div class="col-md-1"><a href="javascript::void(0)" class="remove-tr" title="Remove"><i class="btn btn-danger mdi mdi-close"></i></a></div>
					                      @endif
					                      
					                  </div>
					                @endforeach
					                @else
					                <div class="row mb-3">
				                      	<div class="col-md-4">
				                          	<input type="text" name="addmore[0][start_amount]" value="" placeholder="Start Amount" class="form-control">
				                      	</div>
				                      	<div class="col-md-4">
				                          	<input type="text" name="addmore[0][end_amount]" value="" placeholder="End Amount" class="form-control" />
				                      	</div>
				                      	<div class="col-md-3">
				                          	<input type="text" name="addmore[{{ 0 }}][incentive]" value="" placeholder="Incentive Amount" class="form-control" />
				                      	</div>
				                      	<div class="col-md-1">
				                      		<a href="javascript::void(0)" name="add" id="add" title="Add More"><i class="btn btn-success mdi mdi-plus"></i></a>
				                      	</div>
					                 </div>
					                @endif
				              		</div>
									{{-- <div class="col-md-12">
										<div class="form-group">
						                    <label class="form-label">Status</label>
						                    <div class="input-group mb-3">
						                        <select name="status" class="form-control" id="status">
						                            <option value="Active">Active</option>
						                            <option value="In Active">In Active</option>
						                        </select>
						                    </div>
						                    @error('link')
						                        <span class="invalid-feedback" role="alert">
						                            <strong>{{ $message }}</strong>
						                        </span>
						                    @enderror
						                </div>
									</div> --}}
								</div>

							</div>
							<!-- /.box-body -->
							<div class="box-footer text-end">
								<input type="submit" name="submit" class="btn btn-primary" value="Submit">
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

  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  	<script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>

	<script type="text/javascript">
	    var i = "{{ count($incentive_details) }}";

	    $("#add").click(function(){
	        ++i;
	        $("#dynamicTable").append(
            '<div class="row dynamicrow mb-3">' +

				'<div class="col-md-4">' +
					'<div class="creative-input-group">' +
						'<input type="text" name="addmore[' + i + '][start_amount]" placeholder="Start Amount" class="form-control" />' +
					'</div>' +
				'</div>' +

				'<div class="col-md-4">' +
					'<div class="creative-input-group">' +
						'<input type="text" name="addmore[' + i + '][end_amount]" placeholder="End Amount" class="form-control" />' +
					'</div>' +
				'</div>' +

				'<div class="col-md-3">' +
					'<div class="creative-input-group">' +
						'<input type="text" name="addmore[' + i + '][incentive]" placeholder="Incentive Amount" class="form-control" />' +
					'</div>' +
				'</div>' +

				'<div class="col-md-1">' +
					'<a href="javascript:void(0)" class="remove-tr" title="Remove">' +
						'<i class="btn btn-danger mdi mdi-close"></i>' +
					'</a>' +
				'</div>' +

			'</div>'
		);
	    });
	    $(document).on('click', '.remove-tr', function(){  
	        $(this).parents('.dynamicrow').remove();
	    });

	</script>

@endsection