@extends('layouts.dashboard')

@section('content')
	
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Create Freelancer</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Freelancer</li>
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
					
					  <div class="box">
						<!-- /.box-header -->
						<form class="form" action="{{ route('admin.freelancer.store') }}" method="post">
							@csrf
							<div class="box-body">
								
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Name</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" value="{{ old('name') }}" required>
												<span class="input-group-text"><i class="ti-user"></i></span>
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
											<label class="form-label">Email address</label>
											<div class="input-group mb-3">
												<input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ old('email') }}" required>
												<span class="input-group-text"><i class="ti-email"></i></span>
											</div>
											@error('email')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Phone Number</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Phone Number" value="{{ old('phone') }}" required>
												<span class="input-group-text"><i class="ti-mobile"></i></span>
											</div>
											@error('phone')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									
									{{-- <div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Salary</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('salary') is-invalid @enderror" name="salary" placeholder="Salary" value="{{ old('salary') }}" required>
												<span class="input-group-text"><i class="ti-money"></i></span>
											</div>
											@error('phone')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Role</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control @error('role') is-invalid @enderror" name="role" placeholder="Role" value="{{ old('role') }}" required>
												<span class="input-group-text"><i class="ti-key"></i></span>
											</div>
											@error('role')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div> --}}
									
									<div class="col-md-4">
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
									</div>
									<div class="col-md-4">
										<div class="form-group">
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
										</div>
									</div>
								</div>

								
							</div>
							<!-- /.box-body -->
							<div class="box-footer text-center">
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

@endsection