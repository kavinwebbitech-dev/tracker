@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Edit Staff Group</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Edit Group</li>
								<li class="breadcrumb-item active" aria-current="page">Create</li>
							</ol>
						</nav>
					</div>
				</div>
				
			</div>
		</div>	  

		<?php
			$role_section = json_decode($group_staff->group_user);
			if ($role_section) {
				$role_section = $role_section;
			}
			else
			{
				$role_section = [];
			}
			// dd($role_section);
		?>

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
						<form class="form" action="{{ route('sub.staff.group.update', $group_staff->id) }}" method="post">
							@csrf
							<div class="box-body">
								
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="creative-label">Select Staff</label>
											<div class="creative-input-group mb-3">
												<select class="select2 form-control @error('staff_id') is-invalid @enderror" name="staff_id[]" multiple style="width: 100%;">
													<option value="">Select Staff</option>
													@if(count($sub_admin) > 0)
						                            @foreach($sub_admin as $key => $value)
						                            <option value="{{ $value->id }}" @if(in_array($value->id, $role_section)) selected @endif>{{ $value->name }}</option>
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
									<div class="col-md-4">
										<div class="form-group">
											<label class="creative-label">Group Name</label>
											<div class="creative-input-group mb-3">
												<input type="text" class="form-control @error('group_name') is-invalid @enderror" name="group_name" placeholder="Name" value="{{ $group_staff->group_name }}" required>
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
						                    <label class="creative-label">Status</label>
						                    <div class="creative-input-group mb-3">
						                        <select name="status" class="form-control" id="status">
						                            <option value="Active" @if($group_staff->status == "Active") selected @endif>Active</option>
						                            <option value="In Active" @if($group_staff->status == "In Active") selected @endif>In Active</option>
						                        </select>
						                        <!-- <span class="input-group-text"><i class="ti-user"></i></span> -->
						                    </div>
						                    @error('link')
						                        <span class="invalid-feedback" role="alert">
						                            <strong>{{ $message }}</strong>
						                        </span>
						                    @enderror
						                </div>
									</div>
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

@endsection