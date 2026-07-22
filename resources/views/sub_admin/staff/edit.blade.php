@extends('layouts.sub_admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Edit Staff</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Staff</li>
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
				
				<div class="col-lg-6 col-12">
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
					  <div class="box">
						<!-- /.box-header -->
						<form class="form" action="{{ route('staff.update', $staff->id) }}" method="post">
							@csrf
							<div class="box-body">
								{{-- <div class="form-group">
									<label class="form-label">Sub Admin</label>
									<div class="input-group mb-3">
										<select class="form-control @error('sub_admin') is-invalid @enderror" name="sub_admin">
											<option value="">Select Sub Admin</option>
											@if($sub_admin)
											@foreach($sub_admin as $key => $admin)
											<option value="{{ $admin->id }}" @if($admin->id == $staff->admin_id) selected @endif>{{ $admin->name }}</option>
											@endforeach
											@endif
										</select>
										<span class="input-group-text"><i class="ti-user"></i></span>
									</div>
									@error('sub_admin')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
								</div> --}}
								<div class="form-group">
									<label class="form-label">Name</label>
									<div class="input-group mb-3">
										<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" value="{{ $staff->name }}" required>
										<span class="input-group-text"><i class="ti-user"></i></span>
									</div>
									@error('name')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
								</div>
								<div class="form-group">
									<label class="form-label">Email address</label>
									<div class="input-group mb-3">
										<input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ $staff->email }}" required>
										<span class="input-group-text"><i class="ti-email"></i></span>
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
										<input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Phone Number" value="{{ $staff->phone }}" required>
										<span class="input-group-text"><i class="ti-mobile"></i></span>
									</div>
									@error('phone')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
								</div>
								<div class="form-group">
									<label class="form-label">Password</label>
									<div class="input-group mb-3">
										<input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">
										<span class="input-group-text"><i class="ti-lock"></i></span>
									</div>
									@error('password')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
								</div>
								<div class="form-group">
									<label class="form-label">Confirm Password</label>
									<div class="input-group mb-3">
										<input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Confirm Password">
										<span class="input-group-text"><i class="ti-lock"></i></span>
									</div>
									
								</div>

								<div class="form-group">
									<label class="form-label">Status</label>
									<div class="input-group mb-3">
										<select class="form-control @error('status') is-invalid @enderror" name="status">
											<option value="active" @if($staff->status == "active") selected @endif>Active</option>
											<option value="inactive" @if($staff->status == "inactive") selected @endif>In Active</option>
										</select>
									</div>
									@error('status')
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