<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo url('');?>/public/admin_assets/images/favicon.png">

    <title>Webbitech Task Tracker</title>
  
    <!-- Vendors Style-->
    <link rel="stylesheet" href="<?php echo url('');?>/public/admin_assets/css/vendors_css.css">
      
    <!-- Style-->  
    <link rel="stylesheet" href="<?php echo url('');?>/public/admin_assets/css/style.css">
    <link rel="stylesheet" href="<?php echo url('');?>/public/admin_assets/css/skin_color.css">   
    @laravelPWA
</head>
    
<body class="hold-transition theme-primary bg-img" style="background-image: url(public/admin_assets/images/auth-bg/bg-1.jpg)">
    
    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">   
            
            <div class="col-12">
                @include('layouts.flash-message')
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                <h2 class="text-primary">Let's Get Started</h2>
                                <p class="mb-0">Sign in to continue to Webbitech.</p>                         
                            </div>
                            <div class="p-40">
                                <form method="POST" action="{{ route('user.login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent"><i class="ti-user"></i></span>
                                            <input type="text" class="form-control ps-15 bg-transparent @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Username">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text  bg-transparent"><i class="ti-lock"></i></span>
                                            <input type="password" class="form-control ps-15 bg-transparent @error('password') is-invalid @enderror" name="password" required placeholder="Password">
                                        </div>
                                    </div>
                                      <div class="row">
                                        <!-- <div class="col-6">
                                          <div class="checkbox">
                                            <input type="checkbox" id="basic_checkbox_1" >
                                            <label for="basic_checkbox_1">Remember Me</label>
                                          </div>
                                        </div> -->
                                        <!-- /.col -->
                                        {{-- <div class="col-6">
                                         <div class="fog-pwd">
                                            <a href="javascript:void(0)" class="hover-warning"><i class="ion ion-locked"></i> Forgot Password?</a><br>
                                          </div>
                                        </div> --}}
                                        <!-- /.col -->
                                        <div class="col-12 text-center">
                                          <button type="submit" class="btn btn-danger mt-10">SIGN IN</button>
                                        </div>
                                        <!-- /.col -->
                                      </div>
                                </form>
                            </div>                      
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Vendor JS -->
    <script src="<?php echo url('');?>/public/admin_assets/js/vendors.min.js"></script>
    <script src="<?php echo url('');?>/public/admin_assets/js/pages/chat-popup.js"></script>
    <script src="<?php echo url('');?>/public/admin_assets/assets/icons/feather-icons/feather.min.js"></script>    

</body>
</html>
