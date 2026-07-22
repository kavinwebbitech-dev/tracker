@extends('layouts.dashboard')

@section('content')

	<?php
        $total_task     = \App\Models\Task::get();
        $total_staff    = \App\Models\User::where('user_type', 'staff')->get();
        $total_sub      = \App\Models\User::where('user_type', 'sub_admin')->get();
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
								<li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Extra</li>
								<li class="breadcrumb-item active" aria-current="page">Profile</li>
							</ol>
						</nav>
					</div>
				</div>
				
			</div>
		</div>
        <style>
            .tab-content .active
            {
                border: 2px solid #3762ea;
                border-radius: 10px;
                padding: 10px;
            }
        </style>
		<style type="text/css">
			.vtabs .tabs-vertical
			{
				width: 18%;
			}
			.vtabs .tab-content
			{
				width: 75%;
			}
			.box-body ul li {
			  line-height: 24px;
			}
			.vtabs .tabs-vertical li .nav-link {
		    color: #262626;
		    margin-bottom: 10px;
		    border: 0px;
		    border-radius: 5px 0 0 5px;
			}
		</style>

		<style>
			/* =========================================================
   PREMIUM PROFILE & MASTER DETAILS THEME
========================================================= */

/* --- Profile Widget Card (Right Side) --- */
.premium-profile-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
    border: 1px solid #f1f5f9;
    overflow: hidden;
    margin-bottom: 24px;
}

.profile-cover-bg {
    background: linear-gradient(135deg, #1e2130, #00afef); /* Brand Dark to Blue */
    height: 110px;
    position: relative;
}

.profile-avatar-wrapper {
    text-align: center;
    margin-top: -55px; /* Pulls avatar up into the cover */
    position: relative;
    z-index: 2;
}

.profile-avatar-img {
    width: 110px;
    height: 110px;
    border-radius: 20px; /* Modern Squircle */
    border: 4px solid #ffffff;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    object-fit: cover;
    background: #fff;
}

.profile-user-name {
    font-size: 1.35rem;
    font-weight: 800;
    color: #1e293b;
    margin-top: 12px;
    margin-bottom: 0;
}

/* Stats Grid inside Profile */
.profile-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    text-align: center;
    border-top: 1px dashed #e2e8f0;
    border-bottom: 1px dashed #e2e8f0;
    background: #f8fafc;
    padding: 16px 0;
    margin-top: 25px;
}

.profile-stat-val {
    font-size: 1.25rem;
    font-weight: 900;
    color: #00afef;
    display: block;
}

.profile-stat-label {
    font-size: 0.7rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Contact Info List */
.profile-contact-list { padding: 24px; }

.profile-contact-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 18px;
}

.profile-contact-item:last-child { margin-bottom: 0; }

.profile-contact-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, rgba(0, 175, 239, 0.1), rgba(255, 107, 0, 0.1));
    color: #00afef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.profile-contact-text p {
    margin: 0;
    font-size: 0.95rem;
    color: #1e293b;
    font-weight: 600;
}

.profile-contact-text small {
    font-size: 0.75rem;
    color: #94a3b8;
    text-transform: uppercase;
    font-weight: 700;
    display: block;
    margin-bottom: 4px;
}

/* --- Premium Vertical Tabs (Left Side) --- */
.premium-vtabs-container {
    display: flex;
    gap: 24px;
}

@media (max-width: 991px) {
    .premium-vtabs-container { flex-direction: column; }
}

.premium-vtabs-nav {
    flex: 0 0 230px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.premium-vtabs-nav .nav-link {
    border-radius: 12px;
    padding: 14px 20px;
    color: #64748b;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    background: #ffffff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 12px;
}

.premium-vtabs-nav .nav-link:hover {
    background: #f8fafc;
    color: #00afef;
    transform: translateX(3px);
}

.premium-vtabs-nav .nav-link.active {
    background: linear-gradient(90deg, #00afef, #0284c7);
    color: #ffffff !important;
    box-shadow: 0 6px 15px rgba(0, 175, 239, 0.3);
    transform: translateX(5px);
}

/* Tab Content Card */
.premium-tab-content {
    flex-grow: 1;
    background: #ffffff;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
    border: 1px solid #f1f5f9;
	overflow: auto;
}

.premium-tab-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px dashed #e2e8f0;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* --- Form & Button Polish --- */
.premium-tab-content .form-control, 
.premium-tab-content .form-select {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 16px;
    color: #1e293b;
    font-weight: 500;
    transition: all 0.2s;
}

.premium-tab-content .form-control:focus, 
.premium-tab-content .form-select:focus {
    background: #ffffff;
    border-color: #00afef;
    box-shadow: 0 0 0 3px rgba(0, 175, 239, 0.1);
}

.premium-tab-content .form-label {
    font-weight: 700;
    color: #475569;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

/* Buttons */
.btn-premium {
    background: linear-gradient(90deg, #00afef, #0284c7);
    color: #ffffff !important;
    border: none;
    padding: 12px 28px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    box-shadow: 0 6px 15px rgba(0, 175, 239, 0.25);
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 175, 239, 0.4);
}

.btn-icon-sq {
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: #fff !important;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

.btn-icon-sq:hover { transform: translateY(-2px); }
.btn-bg-success { background: linear-gradient(135deg, #10b981, #059669); }
.btn-bg-danger { background: linear-gradient(135deg, #ef4444, #dc2626); }
.btn-bg-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
.btn-bg-primary { background: linear-gradient(135deg, #3b82f6, #2563eb); }

/* Dynamic Row Spacing */
.dynamic-row-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 12px;
}
		</style>

		<!-- Main content -->
		 <section class="content">
			<div class="row">
				
				<div class="col-12 col-lg-7 col-xl-8">
					<div class="premium-vtabs-container">
						
						<ul class="nav premium-vtabs-nav" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" href="#usertimeline" data-bs-toggle="tab" role="tab">
									<i class="bi bi-person-badge"></i> Profile Update
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#celebration" data-bs-toggle="tab" role="tab">
									<i class="bi bi-calendar-event"></i> Event Details
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#g_suide" data-bs-toggle="tab" role="tab">
									<i class="bi bi-google"></i> G Suite Details
								</a>
							</li>
						</ul>

						<div class="tab-content premium-tab-content">

							<div class="active tab-pane" id="usertimeline" role="tabpanel">
								
								@include('layouts.flash-message')
								@if ($errors->any())
									<div class="alert alert-danger rounded-3 shadow-sm">
										<strong>Whoops!</strong> There were some problems with your input.<br><br>
										<ul class="mb-0">
											@foreach ($errors->all() as $error)
												<li>{{ $error }}</li>
											@endforeach
										</ul>
									</div>
								@endif

								<h4 class="premium-tab-title"><i class="bi bi-person-gear text-primary"></i> Edit Profile Information</h4>
								
								<form action="{{ route('admin.profile.upload', $user->id) }}" method="post" enctype="multipart/form-data">
									@csrf
									<div class="row g-4">
										<div class="col-md-6">
											<label class="form-label">Full Name</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control" name="name" value="{{ $user->name }}">
											</div> 
										</div>
										<div class="col-md-6">
											<label class="form-label">Profile Picture</label>
											<div class="input-group mb-3">
												<input type="file" class="form-control" name="profile_picture">
											</div> 
										</div>
										<div class="col-md-6">
											<label class="form-label">Phone Number</label>
											<div class="input-group mb-3">
												<input type="tel" class="form-control" name="phone" value="{{ $user->phone }}">
											</div> 
										</div>
										<div class="col-md-6">
											<label class="form-label">Date of Birth</label>
											<div class="input-group mb-3">
												<input type="date" class="form-control" name="dob" value="{{ $user->date_of_birth }}">
											</div> 
										</div>
										<div class="col-md-6">
											<label class="form-label">Gender</label>
											<div class="input-group mb-3">
												<select class="form-select" name="gender">
												<option value="male" @if($user->gender == 'male') selected @endif>Male</option>
												<option value="female" @if($user->gender == 'female') selected @endif>Female</option>
											</select>
											</div> 
										</div>
										<div class="col-md-6">
											<label class="form-label">Address</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control" name="address" value="{{ $user->address }}">
											</div> 
										</div>
										<div class="col-md-4">
											<label class="form-label">City</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control" name="city" value="{{ $user->city }}">
											</div> 
										</div>
										<div class="col-md-4">
											<label class="form-label">State</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control" name="state" value="{{ $user->state }}">
											</div> 
										</div>
										<div class="col-md-4">
											<label class="form-label">Country</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control" name="country" value="{{ $user->country }}">
											</div> 
										</div>
										<div class="col-md-4">
											<label class="form-label">Zip Code</label>
											<div class="input-group mb-3">
												<input type="text" class="form-control" name="zip_code" value="{{ $user->zip_code }}">
											</div> 
										</div>
										<div class="col-md-4">
											<label class="form-label">New Password</label>
											<div class="input-group mb-3">
												<input type="password" class="form-control" name="password" placeholder="Leave blank to keep">
											</div> 
										</div>
										<div class="col-md-4">
											<label class="form-label">Confirm Password</label>
											<div class="input-group mb-3">
												<input type="password" class="form-control" name="password_confirmation">
											</div> 
										</div>
										<div class="col-12 text-end mt-4">
											<button type="submit" name="submit" class="btn-premium">
												<i class="bi bi-save"></i> Save Profile Changes
											</button>
										</div>
									</div>
								</form>
							</div>

							<div class="tab-pane" id="celebration" role="tabpanel">
								<div class="d-flex justify-content-between align-items-center mb-4">
									<h4 class="premium-tab-title mb-0 border-0"><i class="bi bi-calendar-star text-warning"></i> Event Details</h4>
									<button class="btn-premium" onclick="CelebrationAdd()"><i class="bi bi-plus-lg"></i> Add Event</button>
								</div>

								<div class="table-responsive">
									<table id="example1" class="creative-table">
										<thead>
											<tr>
												<th>S No</th>
												<th>Event Name</th>
												<th>Start Time</th>
												<th>End Time</th>
												<th>User Type</th>
												<th>Description</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											@if(count($event_details) > 0)
												@foreach($event_details as $key => $event)
												<tr>
													<td>{{ $key + 1 }}</td>
													<td class="fw-bold text-dark">
														{{ $event->event_name }}<br>
														<a href="{{ url('/') }}/public/event_image/{{ $event->event_image }}" target="_blank" class="badge bg-primary text-decoration-none mt-1">View Image</a>
													</td>
													<td>{{ $event->start_date }}</td>
													<td>{{ $event->end_date }}</td>
													<td>
														@if($event->user_type == "Telecaller")
															<span class="badge bg-info">Telecaller</span>
														@else
															<span class="badge bg-secondary">All Staff</span>
														@endif
													</td>
													<td>{{ Str::limit($event->description, 30) }}</td>
													<td>{{ $event->status }}</td>
													<td>
														<div class="d-flex gap-2">
															<a onclick="ViewTaskModel('{{ $event->id }}')" class="btn-icon-sq btn-bg-primary"><i class="ion ion-edit"></i></a>
															<a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.event.details.delete', $event->id) }}')" class="btn-icon-sq btn-bg-danger"><i class="ti-trash"></i></a>
														</div>
													</td>
												</tr>
												@endforeach
											@endif
										</tbody>
									</table>
								</div>
							</div>

							<div class="tab-pane" id="g_suide" role="tabpanel">
								<h4 class="premium-tab-title"><i class="bi bi-google text-success"></i> G Suite Settings</h4>
								
								<form action="{{ route('admin.gsuide.save') }}" method="post">
									@csrf
									<div class="row mb-2">
										<div class="col-md-2"><label class="form-label">Start Mail #</label></div>
										<div class="col-md-2"><label class="form-label">End Mail #</label></div>
										<div class="col-md-3"><label class="form-label">Type</label></div>
										<div class="col-md-2"><label class="form-label">Original Amt</label></div>
										<div class="col-md-2"><label class="form-label">Actual Amt</label></div>
									</div>

									<div id="dynamicTable4">
										@if(count($gsuide_details) > 0)
											@foreach($gsuide_details as $key4 => $value4)
											<div class="row align-items-center dynamic-row-box @if($key4 != 0) dynamicrow4 @endif">
												<div class="col-md-2">
													<div class="input-group mb-3">
														<input type="text" name="addmore4[{{ $key4 }}][start_email]" placeholder="Start Email" class="form-control" value="{{ $value4->start_email }}">
													</div> 
												</div>
												<div class="col-md-2">
													<div class="input-group mb-3">
														<input type="text" name="addmore4[{{ $key4 }}][end_email]" placeholder="End Email" class="form-control" value="{{ $value4->end_email }}">
													</div> 
												</div>
												<div class="col-md-3">
													<div class="input-group mb-3">
														<select class="form-select" name="addmore4[{{ $key4 }}][email_type]">
														<option value="New" @if($value4->email_type == "New") selected @endif>New</option>
														<option value="Renewal" @if($value4->email_type == "Renewal") selected @endif>Renewal</option>
													</select>
													</div> 
												</div>
												<div class="col-md-2">
													<div class="input-group mb-3">
														<input type="text" name="addmore4[{{ $key4 }}][amount]" placeholder="Amount" class="form-control" value="{{ $value4->amount }}">
													</div>  
												</div>
												<div class="col-md-2">
													<div class="input-group mb-3">
														<input type="text" name="addmore4[{{ $key4 }}][actual_price]" placeholder="Actual" class="form-control" value="{{ $value4->actual_price }}">
													</div> 
												</div>
												<div class="col-md-1 text-center">
													@if($key4 == 0)
														<a href="javascript::void(0)" name="add4" id="add4" class="btn-icon-sq btn-bg-success"><i class="mdi mdi-plus"></i></a>
													@else
														<a href="javascript::void(0)" class="remove-tr4 btn-icon-sq btn-bg-danger"><i class="mdi mdi-close"></i></a>
													@endif
												</div>
											</div>
											@endforeach
										@else
											<div class="row align-items-center dynamic-row-box">
												<div class="col-md-2"><input type="text" name="addmore4[0][start_email]" placeholder="Start" class="form-control"></div>
												<div class="col-md-2"><input type="text" name="addmore4[0][end_email]" placeholder="End" class="form-control"></div>
												<div class="col-md-3">
													<select class="form-select" name="addmore4[0][email_type]">
														<option value="New">New</option>
														<option value="Renewal">Renewal</option>
													</select>
												</div>
												<div class="col-md-2"><input type="text" name="addmore4[0][amount]" placeholder="Orig Amt" class="form-control"></div>
												<div class="col-md-2"><input type="text" name="addmore4[0][actual_price]" placeholder="Act Amt" class="form-control"></div>
												<div class="col-md-1 text-center"><a href="javascript::void(0)" name="add4" id="add4" class="btn-icon-sq btn-bg-success"><i class="mdi mdi-plus"></i></a></div>
											</div>
										@endif
									</div>

									<div class="text-end mt-4">
										<button type="submit" name="submit" class="btn-premium"><i class="bi bi-save"></i> Save G Suite Data</button>
									</div>
								</form>
							</div>
							
							<div class="tab-pane" id="activity" role="tabpanel">
								<h4>Incentive Amount List</h4>
								<form action="{{ route('admin.incentive.save') }}" method="post">
									@csrf
									<div class="col-12" id="dynamicTable">
										@if(count($incentive_details) > 0)
											@foreach($incentive_details as $key => $value)
												<div class="row align-items-center dynamic-row-box @if($key != 0) dynamicrow @endif">
													<input type="hidden" name="addmore[{{ $key }}][id]" value="{{ $value->id }}">
													<div class="col-md-4"><input type="text" name="addmore[{{ $key }}][start_amount]" value="{{ $value->start_amount }}" class="form-control"></div>
													<div class="col-md-4"><input type="text" name="addmore[{{ $key }}][end_amount]" value="{{ $value->end_amount }}" class="form-control"></div>
													<div class="col-md-3"><input type="text" name="addmore[{{ $key }}][incentive]" value="{{ $value->amount }}" class="form-control"></div>
													<div class="col-md-1">
														@if($key == 0)<a href="javascript::void(0)" id="add" class="btn-icon-sq btn-bg-success"><i class="mdi mdi-plus"></i></a>
														@else<a href="javascript::void(0)" class="remove-tr btn-icon-sq btn-bg-danger"><i class="mdi mdi-close"></i></a>@endif
													</div>
												</div>
											@endforeach
										@endif
									</div>
									<div class="text-end mt-3"><button type="submit" name="submit" class="btn-premium">Submit</button></div>
								</form>
							</div>

							<div class="tab-pane" id="group_amount" role="tabpanel">
								<h4>Group Incentive Amount List</h4>
								<form action="{{ route('admin.incentive.group.save') }}" method="post">
									@csrf
									<div class="col-12" id="dynamicTable1">
										@if(count($group_incentive_details) > 0)
											@foreach($group_incentive_details as $key => $value)
												<div class="row align-items-center dynamic-row-box @if($key != 0) dynamicrow1 @endif">
													<input type="hidden" name="addmore1[{{ $key }}][id]" value="{{ $value->id }}">
													<div class="col-md-4"><input type="text" name="addmore1[{{ $key }}][start_amount]" value="{{ $value->start_amount }}" class="form-control"></div>
													<div class="col-md-4"><input type="text" name="addmore1[{{ $key }}][end_amount]" value="{{ $value->end_amount }}" class="form-control"></div>
													<div class="col-md-3"><input type="text" name="addmore1[{{ $key }}][incentive]" value="{{ $value->amount }}" class="form-control"></div>
													<div class="col-md-1">
														@if($key == 0)<a href="javascript::void(0)" id="add1" class="btn-icon-sq btn-bg-success"><i class="mdi mdi-plus"></i></a>
														@else<a href="javascript::void(0)" class="remove-tr1 btn-icon-sq btn-bg-danger"><i class="mdi mdi-close"></i></a>@endif
													</div>
												</div>
											@endforeach
										@endif
									</div>
									<div class="text-end mt-3"><button type="submit" name="submit" class="btn-premium">Submit</button></div>
								</form>
							</div>

						</div>
					</div>
				</div>

				<div class="col-12 col-lg-5 col-xl-4">
					
					<div class="premium-profile-card">
						<div class="profile-cover-bg"></div>
						
						<div class="profile-avatar-wrapper">
							@if($user->profile_picture)
								<img class="profile-avatar-img" src="<?php echo url('');?>/public/profile/{{$user->profile_picture}}" alt="User Avatar">
							@else
								<img class="profile-avatar-img" src="<?php echo url('');?>/public/admin/images/user3-128x128.jpg" alt="User Avatar">
							@endif
							<h3 class="profile-user-name">{{ $user->name }}</h3>
						</div>

						<div class="profile-stats-grid">
							<div>
								<span class="profile-stat-val">{{ count($total_sub) }}</span>
								<span class="profile-stat-label">Sub Admins</span>
							</div>
							<div>
								<span class="profile-stat-val">{{ count($total_staff) }}</span>
								<span class="profile-stat-label">Total Staff</span>
							</div>
							<div>
								<span class="profile-stat-val" style="color: #ff6b00;">{{ count($total_task) }}</span>
								<span class="profile-stat-label">Total Tasks</span>
							</div>
						</div>

						<div class="profile-contact-list">
							<div class="profile-contact-item">
								<div class="profile-contact-icon"><i class="bi bi-envelope-at-fill"></i></div>
								<div class="profile-contact-text">
									<small>Email Address</small>
									<p>{{ $user->email }}</p>
								</div>
							</div>
							<div class="profile-contact-item">
								<div class="profile-contact-icon"><i class="bi bi-telephone-fill"></i></div>
								<div class="profile-contact-text">
									<small>Phone Number</small>
									<p>{{ $user->phone }}</p>
								</div>
							</div>
							<div class="profile-contact-item">
								<div class="profile-contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
								<div class="profile-contact-text">
									<small>Location Details</small>
									<p>
										{{ $user->address }} 
										@if($user->city), @endif {{ $user->city }}
										@if($user->state), @endif {{ $user->state }} 
										@if($user->country), @endif {{ $user->country }} 
										@if($user->zip_code) - @endif {{ $user->zip_code }}
									</p>
								</div>
							</div>
						</div>
						
					</div>
					
				</div>

			</div>
		</section>
		<!-- /.content -->
	  </div>
  </div>
  <!-- /.content-wrapper -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>

  <script type="text/javascript">
    var i = "{{ count($incentive_details) }}";
    var j = "{{ count($group_incentive_details) }}";
    var z = "{{ count($gsuide_details) }}";
    $("#add").click(function(){
        ++i;
        $("#dynamicTable").append('<div class="row dynamicrow mb-3">\
        	<div class="col-md-4">\
              <input type="text" name="addmore['+i+'][start_amount]" placeholder="Start Amount" class="form-control">\
          </div>\
          <div class="col-md-4">\
              <input type="text" name="addmore['+i+'][end_amount]" placeholder="End Amount" class="form-control" />\
          </div>\
          <div class="col-md-3">\
              <input type="text" name="addmore['+i+'][incentive]" placeholder="Incentive Amount" class="form-control" />\
          </div>\
        <div class="col-md-1"><a href="javascript::void(0)" class="remove-tr" title="Remove"><i class="btn btn-danger mdi mdi-close"></i></a></div>\
        </div>');
    });
    $(document).on('click', '.remove-tr', function(){  
        $(this).parents('.dynamicrow').remove();
    });

    $("#add1").click(function(){
        ++j;
        $("#dynamicTable1").append('<div class="row dynamicrow1 mb-3">\
        	<div class="col-md-4">\
              <input type="text" name="addmore1['+j+'][start_amount]" placeholder="Start Amount" class="form-control">\
          </div>\
          <div class="col-md-4">\
              <input type="text" name="addmore1['+j+'][end_amount]" placeholder="End Amount" class="form-control" />\
          </div>\
          <div class="col-md-3">\
              <input type="text" name="addmore1['+j+'][incentive]" placeholder="Incentive Amount" class="form-control" />\
          </div>\
        <div class="col-md-1"><a href="javascript::void(0)" class="remove-tr1" title="Remove"><i class="btn btn-danger mdi mdi-close"></i></a></div>\
        </div>');
    });
    $(document).on('click', '.remove-tr1', function(){  
        $(this).parents('.dynamicrow1').remove();
    });

    $("#add4").click(function(){
        ++z;
         $("#dynamicTable4").append(

    '<div class="row dynamicrow4 mb-3">' +

        '<div class="col-md-2">' +
            '<div class="input-group mb-3">' +
                '<input type="text" name="addmore4[' + z + '][start_email]" placeholder="Start Email" class="form-control" />' +
            '</div>' +
        '</div>' +

        '<div class="col-md-2">' +
            '<div class="input-group mb-3">' +
                '<input type="text" name="addmore4[' + z + '][end_email]" placeholder="End Email" class="form-control" />' +
            '</div>' +
        '</div>' +

        '<div class="col-md-3">' +
            '<div class="form-group">' +
                '<div class="input-group mb-3">' +
                    '<select class="form-control" name="addmore4[' + z + '][email_type]">' +
                        '<option value="New">New</option>' +
                        '<option value="Renewal">Renewal</option>' +
                    '</select>' +
                '</div>' +
            '</div>' +
        '</div>' +

        '<div class="col-md-2">' +
            '<div class="input-group mb-3">' +
                '<input type="text" name="addmore4[' + z + '][amount]" placeholder="Amount" class="form-control" />' +
            '</div>' +
        '</div>' +

        '<div class="col-md-2">' +
            '<div class="input-group mb-3">' +
                '<input type="text" name="addmore4[' + z + '][actual_price]" placeholder="Actual Amount" class="form-control" />' +
            '</div>' +
        '</div>' +

        '<div class="col-md-1">' +
            '<a href="javascript:void(0)" class="remove-tr4" title="Remove">' +
                '<i class="btn btn-danger mdi mdi-close"></i>' +
            '</a>' +
        '</div>' +

    '</div>'

);
    });
    $(document).on('click', '.remove-tr4', function(){  
        $(this).parents('.dynamicrow4').remove();
    });
    
    function CelebrationAdd() {
    	$('#modal-default').modal('show');
    }

    function ViewTaskModel(ref) {
        var ele = ref;
        // alert(ele);
        $.ajax({
            url: '{{ route('admin.event.details.edit') }}',
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele
            },
            success: function (response) {
                console.log(response);
                $("#conference_id").val(response.conference_id);
                $("#event_name").val(response.event_name);
                $("#start_date").val(response.start_date);
                $("#end_date").val(response.end_date);
                $("#description").val(response.description);
                if (response.old_image) 
                {
                    $('#old_image').val(response.old_image);
                    $('#image_url').attr('src', response.image_url);
                }
                $("#user_type option[value='"+response.user_type+"']").attr("selected","selected");
                $("#status option[value='"+response.status+"']").attr("selected","selected");
                $('#modal-default').modal('show');
            }
        });
    }
	</script>

		<div class="modal fade" id="modal-default">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Event Details</h4>
            <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
          </div>
          <div class="modal-body">
          	<form action="{{ route('admin.event.details.store') }}" method="post" enctype="multipart/form-data">
          		@csrf
          	<input type="hidden" name="conference_id" id="conference_id">
          	<div class="row">
          		<div class="form-group">
								<label class="form-label">Event Name</label>
			    				<div class="input-group mb-3">
			    					<input type="text" class="form-control @error('event_name') is-invalid @enderror" name="event_name" id="event_name" placeholder="Event Name">
								</div>
							</div>
          	</div>
          	<div class="row">
          		<div class="form-group">
								<label class="form-label">Upload Image</label>
			    				<div class="input-group mb-3">
			    					<input type="file" class="form-control @error('event_image') is-invalid @enderror" name="event_image" id="event_image">
								</div>
								<input type="hidden" name="old_image" id="old_image">
                <img src="" alt="" id="image_url" style="width: 100px;">
							</div>
          	</div>
          	<div class="row">
          		<div class="form-group">
          			<label class="form-label">Start Date</label>
                  <div class="input-group mb-3">
                      <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" id="start_date" onfocus="'showPicker' in this && this.showPicker()">
                  </div>
              </div>
          	</div>
          	<div class="row">
          		<div class="form-group">
          			<label class="form-label">End Date</label>
                  <div class="input-group mb-3">
                      <input type="date" class="form-control @error('end_date') is-invalid @enderror" name="end_date" id="end_date" onfocus="'showPicker' in this && this.showPicker()">
                  </div>
              </div>
          	</div>
          	<div class="row">
								<div class="form-group">
									<label class="form-label">Select User Type </label>
									<div class="input-group mb-3">
										<select class="form-control @error('user_type') is-invalid @enderror" name="user_type" id="user_type">
											<option value="">Select Task Type</option>
											<option value="Telecaller">Telecaller</option>
											<option value="all_staff">All Staff</option>
										</select>
									</div>
								</div>
						</div>
          	<div class="row">
          		<div class="form-group">
								<label class="form-label">Description</label>
			    				<div class="input-group mb-3">
			    					<input type="text" class="form-control @error('description') is-invalid @enderror" name="description" id="description" placeholder="Description">
								</div>
							</div>
          	</div>
          	<div class="row">
								<div class="form-group">
									<label class="form-label">Status</label>
									<div class="input-group mb-3">
										<select class="form-control @error('status') is-invalid @enderror" name="status" id="status">
											<option value="">Select Status</option>
											<option value="Active">Active</option>
											<option value="InActive">InActive</option>
										</select>
									</div>
								</div>
						</div>
						<div class="row">
								<!-- <div class="col-md-6"></div> -->
								<div class="col-md-12">
									<div class="box-footer text-end">
										<input type="submit" name="submit" class="btn btn-primary" value="Submit">
										<!-- <button type="submit" class="btn btn-primary">
										  <i class="ti-save-alt"></i> Save
										</button> -->
									</div> 
								</div>
						</div>
						</form>
          </div>
        </div>
      </div>
    </div>
@endsection