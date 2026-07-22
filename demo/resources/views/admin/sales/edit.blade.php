@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Create Admin</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Admin</li>
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
				
				<div class="col-lg-6 col-12">
					
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
					  <div class="box">
						<!-- /.box-header -->
						<form class="form" action="{{ route('admin.sales.update', $sub_admin->id) }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="box-body">
								<div class="form-group">
									<label class="form-label">First Name</label>
									<div class="input-group mb-3">
										<input type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" placeholder="First Name" value="{{ $sub_admin->firstname }}" required>
									</div>
									@error('firstname')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
								</div>
								<div class="form-group">
									<label class="form-label">Last Name</label>
									<div class="input-group mb-3">
										<input type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" placeholder="Last Name" value="{{ $sub_admin->lastname }}" required>
									</div>
									@error('lastname')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
								</div>
								<div class="form-group">
									<label class="form-label">Email address</label>
									<div class="input-group mb-3">
										<input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ $sub_admin->email }}" required>
									</div>
									@error('email')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
								</div>
								<div class="form-group">
									<label class="form-label">Phone Number</label>
									<div class="input-group mb-3">
										<input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Phone Number" value="{{ $sub_admin->phone }}" required>
									</div>
									@error('phone')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
								</div>
								<div class="form-group">
									<label class="form-label">Color Code</label>
									<div class="input-group mb-3">
										<input type="color" class="form-control @error('color_code') is-invalid @enderror" name="color_code" placeholder="Color Code" value="{{ $sub_admin->color_code }}">
									</div>
									@error('color_code')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
								</div>
								<div class="form-group">
									<label class="form-label">Profile</label>
									<div class="input-group mb-3">
										<input type="file" class="form-control @error('profile') is-invalid @enderror" name="profile" placeholder="Password">
									</div>
									@error('profile')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
								</div>
								<div class="form-group">
									<label class="form-label">User Role</label>
									<div class="input-group mb-3">
										<select class="form-control @error('user_role') is-invalid @enderror" name="user_role">
											<option value="1" @if($sub_admin->type == 1) selected @endif>Admin</option>
											<option value="3" @if($sub_admin->type == 3) selected @endif>Employee</option>
										</select>
									</div>
									@error('user_role')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
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

@endsection