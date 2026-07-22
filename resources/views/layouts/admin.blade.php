<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="<?php echo url('');?>/public/admin_assets/images/favicon.png">
		<title>Task Tracker</title>
		
		<!-- Vendors Style-->
		<link rel="stylesheet" href="<?php echo url('');?>/public/admin_assets/css/vendors_css.css">
		  
		<!-- Style-->  
		<link rel="stylesheet" href="<?php echo url('');?>/public/admin_assets/css/style.css">
		<link rel="stylesheet" href="<?php echo url('');?>/public/admin_assets/css/skin_color.css">
		<link rel="stylesheet" href="<?php echo url('');?>/public/admin_assets/css/custom.css">
  	</head>

	<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
		
		<style>
		.notify-badge {
		    background: green;
		    width: 10px;
		    height: 10px;
		    border-radius: 50%;
		    position: absolute;
		    right: 3px;
		    z-index: 3;
		    top: 19px;
		    animation: zoom 1.2s linear infinite;
		}

		@keyframes zoom {
		    from {
		        transform: scale(0.8);
		        opacity: 0
		    }

		    to {
		        transform: scale(1.0);
		        opacity: 1
		    }
		}
		</style>
		
		<div class="wrapper">
		  	<!-- <div id="loader"></div> -->
		  	<header class="main-header">
				<div class="d-flex align-items-center logo-box justify-content-start">
					{{-- <a href="#" class="waves-effect waves-light nav-link d-none d-md-inline-block mx-10 push-btn bg-transparent" data-toggle="push-menu" role="button">
						<span class="icon-Align-left"><span class="path1"></span><span class="path2"></span><span class="path3"></span></span>
					</a> --}}   
					<!-- Logo -->
					<a href="{{ route('two.admin.index') }}" class="logo">
					  	<!-- logo-->
					  	<div class="logo-lg">
						  	<span class="light-logo"><img src="<?php echo url('');?>/public/admin_assets/images/logo.png" alt="logo"></span>
						  	<p style="font-size: 13px;">
						  		@if(Auth::user()->user_type == "super_admin")
						  		Super Admin
						  		@elseif(Auth::user()->user_type == "admin")
						  		Admin
						  		@elseif(Auth::user()->user_type == "sub_admin")
						  		Sub Admin
						  		@elseif(Auth::user()->user_type == "staff")
						  		Staff
						  		@endif
						  	</p>
						  	<span class="dark-logo"><img src="<?php echo url('');?>/public/admin_assets/images/logo.png" alt="logo"></span>
					  	</div>
					</a>    
				</div>  
				<!-- Header Navbar -->
				<nav class="navbar navbar-static-top">
				  	<!-- Sidebar toggle button-->
                    
                    <div class="app-menu">
						<ul class="header-megamenu nav">
							<li class="btn-group nav-item dis-b" style="display:none;">
								<a href="#" class="waves-effect waves-light nav-link push-btn" data-toggle="push-menu" role="button">
									<span class="icon-Align-left"><span class="path1"></span><span class="path2"></span><span class="path3"></span></span>
								</a>
							</li>
						</ul>
					</div>
				  	<?php
						$notification     = \App\Models\Notification::where('receiver_id', Auth::user()->id)->where('status', 0)->take(5)->get();
						$count_notifi = count($notification);
						// dd($count_notifi);
					?>
					
					<div class="navbar-custom-menu r-side">
						<ul class="nav navbar-nav">    
							
						   <!-- Notifications -->
						    <li class="dropdown notifications-menu">
						    	@if($count_notifi != 0)
						    	<div class="notify-badge"></div>
						    	@endif
							<a href="#" class="waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" title="Notifications">
							  <i class="icon-Notifications"><span class="path1"></span><span class="path2"></span></i>
							</a>
							<ul class="dropdown-menu animated bounceIn">

							  <li class="header">
								<div class="p-20">
									<div class="flexbox">
										<div>
											<h4 class="mb-0 mt-0">Notifications</h4>
										</div>
										<!-- <div>
											<a href="#" class="text-danger">Clear All</a>
										</div> -->
									</div>
								</div>
							  </li>

							  <li>
								<!-- inner menu: contains the actual data -->
								<ul class="menu sm-scrol">
								  
								  	@if($notification)
									@foreach($notification as $key => $message)
								  	<li id="notification_status" data-id='12'>
										<a href="{{ $message->url }}">
											<i class="fa fa-file-text text-info"></i> {{ $message->message }}
										</a>
								  	</li>
								  	@endforeach
								  	@endif

								</ul>
							  </li>
							  <li class="footer">
								  <a href="{{ route('admin.notification.view') }}">View all</a>
							  </li>
							</ul>
						  </li> 
						  
						  <!-- User Account-->
						  <li class="dropdown user user-menu">
							<a href="#" class="waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" title="User">
								<i class="icon-User"><span class="path1"></span><span class="path2"></span></i>
							</a>
							<ul class="dropdown-menu animated flipInX">
							  <li class="user-body">
								 <a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="ti-user text-muted me-2"></i> Profile</a>
								 <!-- <a class="dropdown-item" href="#"><i class="ti-wallet text-muted me-2"></i> My Wallet</a>
								 <a class="dropdown-item" href="#"><i class="ti-settings text-muted me-2"></i> Settings</a> -->
								 <div class="dropdown-divider"></div>
								 <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="ti-lock text-muted me-2"></i> Logout</a>
							  </li>
							</ul>
						  </li> 
						  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
			                    @csrf
			                </form>						
						</ul>
					</div>
				</nav>
		  	</header>
		  
		  	{{-- <aside class="main-sidebar">
				<!-- sidebar-->
				<section class="sidebar position-relative"> 
					<div class="multinav">
					  <div class="multinav-scroll" style="height: 100%;">   
						  <!-- sidebar menu-->
						  <ul class="sidebar-menu" data-widget="tree">
							<li>
							  <a href="{{ route('two.admin.index') }}">
								<i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
								<span>Dashboard</span>
							  </a>
							</li>
							
							<li class="treeview">
							  <a href="#">
								<i span class="icon-Layout-grid"><span class="path1"></span><span class="path2"></span></i>
								<span>Sub Admin</span>
								<span class="pull-right-container">
								  <i class="fa fa-angle-right pull-right"></i>
								</span>
							  </a>
							  <ul class="treeview-menu">
								<li><a href="{{ route('admin.sub.admin.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add Sub Admin</a></li>
								<li><a href="{{ route('admin.sub.admin.view') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>View Sub Admin</a></li>
							  </ul>
							</li>
							<li class="treeview">
							  <a href="#">
								<i span class="icon-Layout-grid"><span class="path1"></span><span class="path2"></span></i>
								<span>Staff</span>
								<span class="pull-right-container">
								  <i class="fa fa-angle-right pull-right"></i>
								</span>
							  </a>
							  <ul class="treeview-menu">
								<li><a href="{{ route('admin.sub.staff.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add Staff</a></li>
								<li><a href="{{ route('admin.sub.staff.view') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>View Staff</a></li>
							  </ul>
							</li> 
							<li class="treeview active">
							  <a href="#">
								<i span class="icon-File"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
								<span>Task</span>
								<span class="pull-right-container">
								  <i class="fa fa-angle-right pull-right"></i>
								</span>
							  </a>
							  <ul class="treeview-menu">
								<li><a href="{{ route('admin.task.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add Task</a></li>
								<li><a href="{{ route('second.admin.task.view') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>View Task</a></li>
								<li><a href="{{ route('second.admin.your.task.view') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>My Task</a></li>
							  </ul>
							</li> 

							<li>
							  <a href="{{ route('admin.staff.import') }}">
								<i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
								<span>Staff Import</span>
							  </a>
							</li>
							<!-- <li class="treeview">
							  <a href="">
								<i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
								<span>Settings</span>
							  </a>
							</li>  -->     
						  </ul>
					  </div>
					</div>
				</section>
				<div class="sidebar-footer">
					<a  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="link" data-bs-toggle="tooltip" title="Logout"><span class="icon-Lock-overturning"><span class="path1"></span><span class="path2"></span></span></a>
				</div>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
		  	</aside> --}}

		  	@yield('content')

		  	<!-- /.content-wrapper -->
			<footer class="main-footer">
				<di class="row">
					<div class="col-6">
						<p>Copyright © 2024 All rights reserved.</p>
					</div>
					<div class="col-6">
						<p style="text-align: right;">Hand-crafted & made with  <i class="ion ion-heart text-danger"></i>  Webbitech</p>
					</div>
				</di>
			</footer>
		  
		  	<div class="control-sidebar-bg"></div>
		</div>
		<!-- ./wrapper -->
		
		<!-- Vendor JS -->
		<script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/js/pages/chat-popup.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/icons/feather-icons/feather.min.js"></script>
	  	<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/jquery-knob/js/jquery.knob.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/raphael/raphael.min.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/datatable/datatables.min.js"></script>
		
		<!-- Etikto Admin App -->
		<script src="<?php echo url('');?>/public/admin_assets/js/template.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/js/pages/dashboard.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/icons/feather-icons/feather.min.js"></script>	
		<script src="<?php echo url('');?>/public/admin_assets/js/pages/data-table.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/ckeditor/ckeditor.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/js/pages/editor.js"></script>


		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/select2/dist/js/select2.full.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_plugins/input-mask/jquery.inputmask.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_plugins/input-mask/jquery.inputmask.extensions.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/moment/min/moment.min.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_plugins/timepicker/bootstrap-timepicker.min.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/assets/vendor_plugins/iCheck/icheck.min.js"></script>
		<script src="<?php echo url('');?>/public/admin_assets/js/pages/advanced-form-element.js"></script>
		<script>
	        $("document").ready(function(){
	            setTimeout(function(){
	            $("div.alert").remove();
	            }, 5000 ); 

	        });
	        $("#dismiss").click(function () {
	            $("div.alert").remove();
	        });

	        function message_hide(id)
        	{
        		// alert(id);
        		if (id) {
        			$.ajax({
	                    url: "{{ route('second.admin.notification.update') }}",
	                    type: "POST",
	                    data: {
	                        id: id,
	                        _token: '{{csrf_token()}}'
	                    },
	                    success: function (response) {
	                    	// console.log(response);
	                    	window.location.href = response;
	                        // window.location.reload();
	                    }
	                });
        		}
        	}
	    </script>
	    <script>
        $(document).ready(function () {
  
            
            $('#staff_select').on('change', function () {
                var idCountry = this.value;
                // alert(idCountry);
                $("#staff_id").html('');
                $.ajax({
                    url: "{{ route('admin.task.staff.fetch') }}",
                    type: "POST",
                    data: {
                        country_id: idCountry,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#staff_id').html('<option value="">-- Select User --</option>');
                        $.each(result.staff, function (key, value) {
                            $("#staff_id").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                    }
                });
            });

  
        });

		 function deleteProject(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to recover this project!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete It!',
                cancelButtonText: 'Cancel'
            }).then((result) => {


                if (result.isConfirmed) {


                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Project deleted successfully.',
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false
                    });


                    setTimeout(function() {
                        window.location.href = url;
                    }, 1000);
                }


            });
        }
    </script>
	 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	</body>
</html>