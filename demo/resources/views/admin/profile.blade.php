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

		<!-- Main content -->
		<section class="content">

		  <div class="row">
		  	<div class="col-12 col-lg-7 col-xl-8">
				  <div class="box">
					<div class="box-header with-border">
					  <h4 class="box-title">Profile & Master Details</h4>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!-- Nav tabs -->
						<div class="vtabs">
							<ul class="nav nav-tabs tabs-vertical" role="tablist">
								<li class="nav-item"><a class="nav-link active" href="#usertimeline" data-bs-toggle="tab" role="tab">Profile Update</a></li>
							  <!-- <li class="nav-item"><a class="nav-link" href="#activity" data-bs-toggle="tab" role="tab">Incentive Amount List</a></li> -->
							  <li class="nav-item"><a class="nav-link" href="#celebration" data-bs-toggle="tab" role="tab">Event Details</a></li>
							  <!--<li class="nav-item"><a class="nav-link" href="#group_amount" data-bs-toggle="tab" role="tab">Group Incentive Amount List</a></li>-->
							  <li class="nav-item"><a class="nav-link" href="#g_suide" data-bs-toggle="tab" role="tab">G suide Details</a></li>
							</ul>
							<!-- Tab panes -->
							<div class="tab-content">

						  	    <div class="active tab-pane" id="usertimeline" role="tabpanel">
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
            		                		<h4>Profile Update</h4>
                								<form class="form-horizontal form-element col-12" action="{{ route('admin.profile.upload', $user->id) }}" method="post" enctype="multipart/form-data">
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
                										  	<option value="male">Male</option>
                										  	<option value="female">Female</option>
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
						  		<div class="tab-pane" id="activity" role="tabpanel">
						  			<h4>Incentive Amount List</h4>
						  			<form action="{{ route('admin.incentive.save') }}" method="post">
						  				@csrf
				              	        <div class="col-12 text-center" id="dynamicTable">
					              	@if(count($incentive_details) > 0)
					              	@foreach($incentive_details as $key => $value)
					                  <div class="row mb-3 @if($key != 0) dynamicrow @endif">
					                  		<input type="hidden" name="addmore[{{ $key }}][id]" value="{{ $value->id }}">
					                      <div class="col-md-4">
					                          <input type="text" name="addmore[{{ $key }}][start_amount]" value="{{ $value->start_amount }}" placeholder="Start Amount" class="form-control">
					                      </div>
					                      <div class="col-md-4">
					                          <input type="text" name="addmore[{{ $key }}][end_amount]" value="{{ $value->end_amount }}" placeholder="End Amount" class="form-control" />
					                      </div>
					                      <div class="col-md-3">
					                          <input type="text" name="addmore[{{ $key }}][incentive]" value="{{ $value->amount }}" placeholder="Incentive Amount" class="form-control" />
					                      </div>
					                      @if($key == 0)
					                      	<div class="col-md-1"><a href="javascript::void(0)" name="add" id="add" title="Add More"><i class="btn btn-success mdi mdi-plus"></i></a></div>
					                      @else
					                      	<div class="col-md-1"><a href="javascript::void(0)" class="remove-tr" title="Remove"><i class="btn btn-danger mdi mdi-close"></i></a></div>
					                      @endif
					                      
					                  </div>
					                @endforeach
					                @endif
				              	</div>
		              			        <div class="box-footer text-end">
											<input type="submit" name="submit" class="btn btn-primary" value="Submit">
										</div>
		              		        </form>
						  		</div>

							  	<div class="tab-pane" id="celebration" role="tabpanel">
							  		<h4>Event Details</h4>
							  		<div class="row">
							  			<div class="col-md-6"></div>
							  			<div class="col-md-6" style="text-align: right;">
							  				<span class="btn btn-primary" onclick="CelebrationAdd()">Add Event</span>
							  			</div>
							  		</div>

							  		<table id="example1" class="table table-bordered table-striped">
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
        				                <style type="text/css">
        				                    .circle
        				                    {
        				                        border-radius: 50px;
        				                        width: 35px;
        				                        height: 35px;
        				                        background: #ff5900;
        				                    }
        				                </style>
        				                <tbody>
        
        				                		@if(count($event_details) > 0)
        				                		@foreach($event_details as $key => $event)
        				                    <tr>
        				                    	<td>
        				                    		{{ $key + 1 }}
        				                    	</td>
        				                    	<td>
        				                    		{{  $event->event_name }}
        				                    		<br>
        				                    		<span><a href="{{ url('/') }}/public/event_image/{{ $event->event_image }}" target="_blank">Image Url</a></span>
        				                    	</td>
        				                    	<td>{{  $event->start_date }}</td>
        				                    	<td>{{  $event->end_date }}</td>
        				                    	<td>
        				                    		@if($event->user_type == "Telecaller")
        				                    			Telecaller
        				                    		@else
        				                    			All Staff
        				                    		@endif
        				                    	</td>
        				                    	<td>{{  $event->description }}</td>
        				                    	<td>{{  $event->status }}</td>
        				                    	<td>
        				                    		<p style="display:flex;">
        				                    			<a onclick="ViewTaskModel('{{ $event->id }}')" class="btn btn-primary" style="padding: 4px 12px;margin: 0px 4px;"><i class="ion ion-edit text-white"></i></a>
        				                        	<a href="{{ route('admin.event.details.delete', $event->id) }}" class="btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;"><i class="ti-trash text-white"></i></a>
        				                    		</p>
        				                    	</td>
        				                    </tr>
        				                    @endforeach
        				                    @endif
        
        				                </tbody>
        				            </table>
							  	</div>

						  		<div class="tab-pane" id="group_amount" role="tabpanel">
						  			<h4>Group Incentive Amount List</h4>
							  		<form action="{{ route('admin.incentive.group.save') }}" method="post">
							  			@csrf
							  			
			              	            <div class="col-12 text-center" id="dynamicTable1">
				              	@if(count($group_incentive_details) > 0)
				              	@foreach($group_incentive_details as $key => $value)
				                  <div class="row mb-3 @if($key != 0) dynamicrow1 @endif">
				                  		<input type="hidden" name="addmore1[{{ $key }}][id]" value="{{ $value->id }}">
				                      <div class="col-md-4">
				                          <input type="text" name="addmore1[{{ $key }}][start_amount]" value="{{ $value->start_amount }}" placeholder="Start Amount" class="form-control">
				                      </div>
				                      <div class="col-md-4">
				                          <input type="text" name="addmore1[{{ $key }}][end_amount]" value="{{ $value->end_amount }}" placeholder="End Amount" class="form-control" />
				                      </div>
				                      <div class="col-md-3">
				                          <input type="text" name="addmore1[{{ $key }}][incentive]" value="{{ $value->amount }}" placeholder="Incentive Amount" class="form-control" />
				                      </div>
				                      @if($key == 0)
				                      	<div class="col-md-1"><a href="javascript::void(0)" name="add1" id="add1" title="Add More"><i class="btn btn-success mdi mdi-plus"></i></a></div>
				                      @else
				                      	<div class="col-md-1"><a href="javascript::void(0)" class="remove-tr1" title="Remove"><i class="btn btn-danger mdi mdi-close"></i></a></div>
				                      @endif
				                      
				                  </div>
				                @endforeach
				                @endif
			              	</div>
		            			        <div class="box-footer text-end">
											<input type="submit" name="submit" class="btn btn-primary" value="Submit">
										</div>
		          			        </form>
						  		</div>
						  		<div class="tab-pane" id="g_suide" role="tabpanel">
						  			<h4>G suide Details</h4>
						  			<form action="{{ route('admin.gsuide.save') }}" method="post">
							  			@csrf
							  			<div class="row mb-3">
        			                      <div class="col-md-2">
        			                          <label>Start Number of Mail</label>
        			                      </div>
        			                      <div class="col-md-2">
        			                          <label>End Number of Mail</label>
        			                      </div>
        			                      <div class="col-md-3">
        			                      	<label>Gsuide Type</label>
        			                      </div>
        			                      <div class="col-md-2">
        			                          <label>Original Amount</label>
        			                      </div>
        			                      <div class="col-md-2">
        			                          <label>Actual Amount</label>
        			                      	</divClient
        			                      <div class="col-md-1"></div>
        			                  </div>
			              	            <div class="col-12 text-center" id="dynamicTable4">
        				              	@if(count($gsuide_details) > 0)
        				              	@foreach($gsuide_details as $key4 => $value4)
        				                  <div class="row mb-3 @if($key4 != 0) dynamicrow4 @endif">
        				                  		<div class="col-md-2">
        				                          <input type="text" name="addmore4[{{ $key4 }}][start_email]" placeholder="Start Email" class="form-control" value="{{ $value4->start_email }}">
        				                      </div>
        				                      <div class="col-md-2">
        				                          <input type="text" name="addmore4[{{ $key4 }}][end_email]" placeholder="End Email" class="form-control" value="{{ $value4->end_email }}">
        				                      </div>
        				                      <div class="col-md-3">
        				                      	<div class="form-group">
													<div class="input-group mb-3">
														<select class="form-control" name="addmore4[{{ $key4 }}][email_type]">
															<option value="New" @if($value4->email_type == "New") selected @endif>New</option>
															<option value="Renewal" @if($value4->email_type == "Renewal") selected @endif>Renewal</option>
														</select>
													</div>
												</div>
        				                      </div>
        				                      <div class="col-md-2">
        			                          <input type="text" name="addmore4[{{ $key4 }}][amount]" placeholder="Amount" class="form-control"  value="{{ $value4->amount }}">
        			                      	</div>
        			                      	<div class="col-md-2">
        			                          <input type="text" name="addmore4[{{ $key4 }}][actual_price]" placeholder="Actual Amount" class="form-control"  value="{{ $value4->actual_price }}">
        			                      	</div>
        				                      @if($key4 == 0)
        				                      	<div class="col-md-1"><a href="javascript::void(0)" name="add4" id="add4" title="Add More"><i class="btn btn-success mdi mdi-plus"></i></a></div>
        				                      @else
        				                      	<div class="col-md-1"><a href="javascript::void(0)" class="remove-tr4" title="Remove"><i class="btn btn-danger mdi mdi-close"></i></a></div>
        				                      @endif
        				                  </div>
        				                @endforeach
        				                @else
        				                <div class="row mb-3">
        			                      <div class="col-md-2">
        			                          <input type="text" name="addmore4[0][start_email]" placeholder="Start Email" class="form-control">
        			                      </div>
        			                      <div class="col-md-2">
        			                          <input type="text" name="addmore4[0][end_email]" placeholder="End Email" class="form-control" />
        			                      </div>
        			                      <div class="col-md-3">
        			                      	<div class="form-group">
												<div class="input-group mb-3">
													<select class="form-control" name="addmore4[0][email_type]">
														<option value="New">New</option>
														<option value="Renewal">Renewal</option>
													</select>
												</div>
											</div>
        			                      </div>
        			                      <div class="col-md-2">
        			                          <input type="text" name="addmore4[0][amount]" placeholder="Amount" class="form-control" />
        			                      </div>
        			                      <div class="col-md-2">
        			                          <input type="text" name="addmore4[0}][actual_price]" placeholder="Actual Amount" class="form-control">
        			                      	</div>
        			                      <div class="col-md-1"><a href="javascript::void(0)" name="add4" id="add4" title="Add More"><i class="btn btn-success mdi mdi-plus"></i></a></div>
        			                  </div>
        				                @endif
        			              	</div>
		            			        <div class="box-footer text-end">
											<input type="submit" name="submit" class="btn btn-primary" value="Submit">
										</div>
		          			        </form>

						  		</div>

							</div>
						</div>
					</div>
					<!-- /.box-body -->
				  </div>
				  <!-- /.box -->
				</div>
				<!-- /.col -->
			
			  
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
					<img class="rounded-circle" src="<?php echo url('');?>/public/admin/images/user3-128x128.jpg" alt="User Avatar">
					@endif
					</div>
					<div class="box-footer">
					  <div class="row">
						<div class="col-sm-4">
						  <div class="description-block">
							<h5 class="description-header">{{ count($total_sub) }}</h5>
							<span class="description-text">Total Sub Admin</span>
						  </div>
						  <!-- /.description-block -->
						</div>
						<!-- /.col -->
						<div class="col-sm-4 be-1 bs-1">
						  <div class="description-block">
							<h5 class="description-header">{{ count($total_staff) }}</h5>
							<span class="description-text">Total Staff</span>
						  </div>
						  <!-- /.description-block -->
						</div>
						<!-- /.col -->
						<div class="col-sm-4">
						  <div class="description-block">
							<h5 class="description-header">{{ count($total_task) }}</h5>
							<span class="description-text">Total Task</span>
						  </div>
						  <!-- /.description-block -->
						</div>
						<!-- /.col -->
					  </div>
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
        $("#dynamicTable4").append('<div class="row dynamicrow4 mb-3">\
        	<div class="col-md-2">\
              <input type="text" name="addmore4['+z+'][start_email]" placeholder="Start Email" class="form-control">\
          </div>\
          <div class="col-md-2">\
              <input type="text" name="addmore4['+z+'][end_email]" placeholder="End Email" class="form-control" />\
          </div>\
          <div class="col-md-3">\
          	<div class="form-group">\
							<div class="input-group mb-3">\
								<select class="form-control" name="addmore4['+z+'][email_type]">\
									<option value="New">New</option>\
									<option value="Renewal">Renewal</option>\
								</select>\
							</div>\
						</div>\
          </div>\
          <div class="col-md-2">\
              <input type="text" name="addmore4['+z+'][amount]" placeholder="Amount" class="form-control" />\
          </div>\
          <div class="col-md-2">\
              <input type="text" name="addmore4['+z+'][actual_price]" placeholder="Actual Amount" class="form-control" />\
          </div>\
        	<div class="col-md-1"><a href="javascript::void(0)" class="remove-tr4" title="Remove"><i class="btn btn-danger mdi mdi-close"></i></a></div>\
        </div>');
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