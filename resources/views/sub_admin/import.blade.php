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
								<li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
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
						<form class="form" action="{{ route('sub_admin.bulk.store') }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="box-body">
								<div class="row">
									<div class="col-md-6">
										
										<div class="col-md-12">
											<div class="form-group">
												<label class="form-label d-block">Excel File <span class="float-end"><a href="<?php echo url('');?>/public/admin_assets/images/sample_file.xlsx" download><i class="fa fa-download"></i> Sample File</a> </span></label>
												<div class="input-group mb-3">
													<input type="file" class="form-control @error('excel_file') is-invalid @enderror" name="excel_file" placeholder="Excel File" value="{{ old('excel_file') }}" required>
												</div>
												@error('excel_file')
				                                    <span class="invalid-feedback" role="alert">
				                                        <strong>{{ $message }}</strong>
				                                    </span>
				                                @enderror
											</div>
										</div>
									</div>

								</div>
								

								<div class="row">
									<!-- <div class="col-md-6"></div> -->
									<div class="col-md-12">
										<div class="box-footer text-start">
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
  <!-- /.content-wrapper -->

@endsection