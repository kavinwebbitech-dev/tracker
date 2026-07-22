@extends('layouts.freelancer')

@section('content')

<?php
		
		

?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->	  
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Profile</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('freelancer.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Extra</li>
								<li class="breadcrumb-item active" aria-current="page">Profile</li>
							</ol>
						</nav>
					</div>
				</div>
				
			</div>
		</div>

		<!-- Main content -->
		<section class="content">

		  <div class="row">
			<div class="col-12 col-lg-7 col-xl-8">

				<div class="box">
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
	                <!-- /.box-header -->
	                <div class="box-body">
	                	<div class="box no-shadow">		
											<form class="form-horizontal form-element col-12" action="{{ route('freelancer.profile.upload', $user->id) }}" method="post" enctype="multipart/form-data">
												@csrf
												  <div class="form-group row">
													<label for="inputName" class="col-sm-2 form-label">Name</label>

													<div class="col-sm-10">
													  <input type="text" class="form-control" id="inputName" name="name" placeholder="" value="{{ $user->name }}">
													</div>
												  </div>
												  <div class="form-group row">
													<label for="inputEmail" class="col-sm-2 form-label">Profile Picture</label>

													<div class="col-sm-10">
													  <input type="file" class="form-control" id="inputEmail" name="profile_picture" placeholder="">
													</div>
												  </div>
												  <div class="form-group row">
													<label for="inputPhone" class="col-sm-2 form-label">Phone</label>

													<div class="col-sm-10">
													  <input type="tel" class="form-control" id="inputPhone" name="phone" placeholder="" value="{{ $user->phone }}">
													</div>
												  </div>
												  <div class="form-group row">
													<label for="inputPhone" class="col-sm-2 form-label">Date of Birth</label>

													<div class="col-sm-10">
													  <input type="date" class="form-control" id="inputPhone" name="dob" placeholder="" value="{{ $user->date_of_birth }}">
													</div>
												  </div>
												  
												  <div class="form-group row">
													<label for="inputExperience" class="col-sm-2 form-label">Gender</label>

													<div class="col-sm-10">
													  <select class="form-control" name="gender">
													  	<option value="male" @if($user->gender == "male") selected @endif>Male</option>
													  	<option value="female" @if($user->gender == "female") selected @endif>Female</option>
													  </select>
													</div>
												  </div>
												  <div class="form-group row">
													<label for="inputSkills" class="col-sm-2 form-label">Address</label>

													<div class="col-sm-10">
													  <input type="text" class="form-control" id="inputSkills" name="address" placeholder="" value="{{ $user->address }}">
													</div>
												  </div>
												  <div class="form-group row">
													<label for="inputSkills" class="col-sm-2 form-label">City</label>

													<div class="col-sm-10">
													  <input type="text" class="form-control" id="inputSkills" name="city" placeholder="" value="{{ $user->city }}">
													</div>
												  </div>
												  <div class="form-group row">
													<label for="inputSkills" class="col-sm-2 form-label">State</label>

													<div class="col-sm-10">
													  <input type="text" class="form-control" id="inputSkills" name="state" placeholder="" value="{{ $user->state }}">
													</div>
												  </div>
												  <div class="form-group row">
													<label for="inputSkills" class="col-sm-2 form-label">Country</label>

													<div class="col-sm-10">
													  <input type="text" class="form-control" id="inputSkills" name="country" placeholder="" value="{{ $user->country }}">
													</div>
												  </div>
												  <div class="form-group row">
													<label for="inputSkills" class="col-sm-2 form-label">Zip Code</label>

													<div class="col-sm-10">
													  <input type="text" class="form-control" id="inputSkills" name="zip_code" placeholder="" value="{{ $user->zip_code }}">
													</div>
												  </div>
												  <div class="form-group row">
													<label for="inputSkills" class="col-sm-2 form-label">Password</label>

													<div class="col-sm-10">
													  <input type="password" class="form-control" id="inputSkills" name="password" placeholder="">
													</div>
												  </div>
												  <div class="form-group row">
													<label for="inputSkills" class="col-sm-2 form-label">Confirm Password</label>

													<div class="col-sm-10">
													  <input type="password" class="form-control" id="inputSkills" name="password_confirmation" placeholder="">
													</div>
												  </div>
												  <!-- <div class="form-group row">
													<div class="ms-auto col-sm-10">
													  <div class="checkbox">
														<input type="checkbox" id="basic_checkbox_1" checked="">
														<label for="basic_checkbox_1"> I agree to the</label>
														  &nbsp;&nbsp;&nbsp;&nbsp;<a href="#">Terms and Conditions</a>
													  </div>
													</div>
												  </div> -->
												  <div class="form-group row">
													<div class="col-sm-10">
														<input type="submit" name="submit" class="btn btn-primary" value="Submit">
													  <!-- <button type="submit" class="btn btn-success">Submit</button> -->
													</div>
												  </div>
												</form>
											</div>
										</div>
	                </div>
	            </div>
				
			  
			<!-- /.col -->		

			  <div class="col-12 col-lg-5 col-xl-4">
				 <div class="box box-widget widget-user">
					<!-- Add the bg color to the header using any of the bg-* classes -->
					<div class="widget-user-header bg-img bbsr-0 bber-0" style="background: url('../admin/images/gallery/full/10.jpg') center center;" data-overlay="5">
					  <h3 class="widget-user-username text-white">{{ $user->name }}</h3>
					  <!-- <h6 class="widget-user-desc text-white">Designer</h6> -->
					</div>
					<div class="widget-user-image">
					@if($user->profile_picture)
					  <img class="rounded-circle" src="<?php echo url('');?>/public/profile/{{$user->profile_picture}}" alt="User Avatar">
					@else
					<img class="rounded-circle" src="<?php echo url('');?>/public/admin_assets/images/avatar/3.jpg" alt="User Avatar">
					@endif
					</div>
					<div class="box-footer">
					  
					  <!-- /.row -->
					</div>
				  </div>
				  <div class="box">
					<div class="box-body box-profile">            
					  <div class="row">
						<div class="col-12">
							<div>
								<p>Email :<span class="text-gray ps-10">{{ $user->email }}</span> </p>
								<p>Phone :<span class="text-gray ps-10">{{ $user->phone }}</span></p>
								<p>Address :<span class="text-gray ps-10">{{ $user->address }} @if($user->city), @endif {{ $user->city }}@if($user->state), @endif {{ $user->state }} @if($user->country), @endif {{ $user->country }} @if($user->zip_code) - @endif {{ $user->zip_code }}</span></p>
							</div>
						</div>
					  </div>
					</div>
					<!-- /.box-body -->
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