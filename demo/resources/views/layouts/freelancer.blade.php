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
		
		<link rel="stylesheet" href="<?php echo url('');?>/public/admin_assets/css/vendors_css.css">
          
        <!-- Style-->  
        <link rel="stylesheet" href="<?php echo url('');?>/public/admin_assets/css/style.css">
        <link rel="stylesheet" href="<?php echo url('');?>/public/admin_assets/css/skin_color.css">
        <link rel="stylesheet" href="<?php echo url('');?>/public/admin_assets/css/custom.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo url('');?>/public/admin_assets/css/bootstrap-icons.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo url('');?>/public/admin_assets/css/all.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo url('');?>/public/admin_assets/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo url('');?>/public/admin_assets/css/meanmenu.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo url('');?>/public/admin_assets/css/tracker-header.css" />
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

        <script src="<?php echo url('');?>/public/admin_assets/assets/vendor_components/ckeditor/ckeditor.js"></script>
        <!-- <script src="//cdn.ckeditor.com/4.5.6/standard/ckeditor.js"></script> -->
        <!-- <script src="<?php echo url('');?>/public/admin_assets/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js"></script> -->
        <script src="<?php echo url('');?>/public/admin_assets/js/pages/editor.js"></script>
        
		@laravelPWA
		<?php
            $role_section = json_decode(Auth::user()->permissions);
            if ($role_section) {
                $role_section = $role_section;
            }
            else
            {
                $role_section = [];
            }
        ?>
  	</head>

	<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
		
		<style>
		.notify-badge {
		    background: green;
		    width: 10px;
		    height: 10px;
		    border-radius: 50%;
		    position: absolute;
		    right: 0px;
		    z-index: 3;
		    top: 0px;
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
		.content a {
            text-decoration: auto;
        }
		</style>

		<div class="wrapper">
		  	
		  	<!-- <div id="loader"></div> -->
		  	<div class="mobile-header mobile-view">
                <div class="container">
                    <div class="row gy-3 align-items-center">
                        <div class="col-12 order-0 order-md-0 col-md-6">
                            <div class="logo text-center text-md-start">
                                <a href="{{ route('freelancer.index') }}"><img src="<?php echo url('');?>/public/admin_assets/images/logo.png" alt=""></a>
                            </div>
                        </div>
                        <div class="col-2 col-md-1 order-1 order-md-2">
                            <p class="text-start text-md-end">

                                <a class="btn toggler" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button"
                                    aria-controls="offcanvasExample">
                                    <i class="bi bi-list"></i>
                                </a>
                            </p>
                            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample"
                                aria-labelledby="offcanvasExampleLabel">
                                <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="offcanvasExampleLabel">
                                        <a href="{{ route('freelancer.index') }}" class="logo"><img src="<?php echo url('');?>/public/admin_assets/images/logo.png" /></a>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                        aria-label="Close"></button>

                                </div>
                                <div class="offcanvas-body mean-container">
                                    <div class="main-menu ">
                                        <nav class="mean-menulist">
                                            <ul>
                                                <li>
                                                    <a class=" active" href="{{ route('freelancer.index') }}"><i class="bi bi-grid-fill"></i>Dashboard</a>
                                                </li>

                                                <li>
                                                    <a class=" active" href="{{ route('freelancer.projects.task.view') }}"><i class="bi bi-ubuntu"></i>Task Estimate</a>
                                                </li>
                                                
                                            </ul>

                                        </nav>


                                    </div>


                                </div>


                            </div>
                        </div>
                        <div class="col-10 col-md-5 order-2 order-md-1">
                            <form class="d-flex justify-content-end " role="search">
                                
                                <?php
        							$notification     = \App\Models\Notification::where('receiver_id', Auth::user()->id)->where('status', 0)->take(5)->latest()->get();
        							$count_notifi = count($notification);
        						?>
                                <div class="dropdown">
                                    @if($count_notifi != 0)
                                    <div class="notify-badge"></div>
                                    @endif
                                    <a class="btn btn-dropdown dropdown-toggle" href="#" role="button" data-bs-offset="10,20"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-bell"></i>
                                    </a>

                                    <ul class="dropdown-menu">
                                        @if($notification)
        								@foreach($notification as $key => $message)
                                        <li id="notification_status" data-id='12'><a class="dropdown-item" href="{{ $message->url }}">{{ $message->message }}</a></li>
                                        @endforeach
        								@endif
        								<li><a class="dropdown-item" href="#">View all</a></li>
                                    </ul>
                                </div>

                                <div class="dropdown">
                                    <a class="btn btn-dropdown dropdown-toggle" href="#" role="button" data-bs-offset="10,20"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-user"></i>
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('freelancer.profile') }}">Profile</a></li>
                                        <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                                    </ul>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        			                    @csrf
        			                </form>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
            </div>


            <header class="main-header web-view">
                <nav class="navbar navbar-expand-lg bg-body-tertiary">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="{{ route('freelancer.index') }}"><img src="<?php echo url('');?>/public/admin_assets/images/logo.png" alt=""></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="{{ route('freelancer.index') }}"><i
                                            class="bi bi-grid-fill"></i>Dashboard</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="{{ route('freelancer.projects.task.view') }}"><i
                                            class="bi bi-grid-fill"></i>Task Estimate</a>
                                </li>
                                
                            </ul>
                            <div class="d-flex">
                                <!-- <div class="dropdown">
                                    <a class="btn btn-dropdown" href="#">
                                        <i class="fa-solid fa-expand"></i>
                                    </a>

                                </div> -->
                                <?php
        							$notification     = \App\Models\Notification::where('receiver_id', Auth::user()->id)->where('status', 0)->take(5)->latest()->get();
        							$count_notifi = count($notification);
        						?>
                                <div class="dropdown">
                                    @if($count_notifi != 0)
                                    <div class="notify-badge"></div>
                                    @endif
                                    <a class="btn btn-dropdown dropdown-toggle" href="#" role="button" data-bs-offset="10,20"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-bell"></i>
                                    </a>

                                    <ul class="dropdown-menu">
                                    	@if($notification)
        								@foreach($notification as $key => $message)
                                        <li id="notification_status" data-id='12'><a class="dropdown-item" href="{{ $message->url }}">{{ $message->message }}</a></li>
                                        @endforeach
        								@endif
        								<li><a class="dropdown-item" href="#">View all</a></li>
                                    </ul>
                                </div>
                                <div class="dropdown">
                                    <a class="btn btn-dropdown dropdown-toggle" href="#" role="button" data-bs-offset="10,20"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-user"></i>
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('freelancer.profile') }}">Profile</a></li>
                                        <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                                    </ul>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        			                    @csrf
        			                </form>	
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

		  	@yield('content')

		  	<!-- /.content-wrapper -->
			<footer class="main-footer">
				<di class="row">
					<div class="col-6">
						<p>Copyright © 2025 All rights reserved.</p>
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
        <!-- <script src="//cdn.ckeditor.com/4.5.6/standard/ckeditor.js"></script> -->
        <!-- <script src="<?php echo url('');?>/public/admin_assets/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js"></script> -->
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

		<script type="text/javascript" src="<?php echo url('');?>/public/admin_assets/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="<?php echo url('');?>/public/admin_assets/js/jquery.meanmenu.min.js"></script>

        <script>
            $('.mean-menulist').meanmenu({
                meanMenuContainer: '.mobile-menu',

            });
        </script>

		<script>

	        $("document").ready(function(){
	            setTimeout(function(){
	            $("div.alert").remove();
	            }, 5000 ); 
	        });

	        $("#dismiss").click(function () {
	            $("div.alert").remove();
	        });
            
	        ckeditor.replace('editor1');

	        function message_hide(id)
        	{
        		// alert(id);
        		if (id) {
        			$.ajax({
	                    url: "{{ route('notification.update') }}",
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
	</body>
</html>