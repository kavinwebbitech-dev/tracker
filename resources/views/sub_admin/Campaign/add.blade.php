@extends('layouts.sub_admin')

@section('content')
	
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Create Campaign</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('sub.admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Campaign</li>
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
						<form class="form" action="{{ route('sub_admin.campaign.details.store') }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="box-body">
								
								<div class="row">

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Template Name</label>
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('template_name') is-invalid @enderror" name="template_name" placeholder="Template Name" value="{{ old('template_name') }}" required>
											</div>
											@error('template_name')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<div class="row">
												<div class="col-md-6">
													<label class="form-label"><label class="form-label">Upload CSV</label></label>
												</div>
												<div class="col-md-6">
													<a href="<?php echo url('');?>/public/admin_assets/images/new_campaign.xlsx" download style="float: right;">Sample File</a>
												</div>
											</div>
											<div class="input-group in-bord mb-3">
												<input type="file" class="form-control @error('upload_csv') is-invalid @enderror" name="upload_csv" placeholder="Email" value="{{ old('upload_csv') }}" required>
											</div>
											@error('upload_csv')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>

									{{-- <div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Select User</label>
											<div class="input-group in-bord mb-3">
												<select class="form-control select2 @error('project_id') is-invalid @enderror" name="project_id[]" id="project_id" multiple="multiple" data-placeholder="Select User">
													@if($leads_details)
													@foreach($leads_details as $key => $admin)
													<option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->contact_no }})</option>
													@endforeach
													@endif
												</select>
											</div>
											@error('campaign_user')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div> --}}

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Campaign Name</label>
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('campaign_name') is-invalid @enderror" name="campaign_name" placeholder="Campaign Name" value="{{ old('campaign_name') }}" required>
											</div>
											@error('campaign_name')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Campaign Image</label>
											<div class="input-group in-bord mb-3">
												<input type="file" class="form-control @error('campaign_image') is-invalid @enderror" name="campaign_image" placeholder="Email" value="{{ old('campaign_image') }}" required>
											</div>
											@error('campaign_image')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="form-label">Description</label>
											<div class="input-group in-bord mb-3">
												<textarea name="description" rows="5" cols="80" style="width: 100%;border: 1px solid #dcd9d9;border-radius: 10px;padding: 10px;"></textarea>
											</div>
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