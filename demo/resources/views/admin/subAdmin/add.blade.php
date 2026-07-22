@extends('layouts.dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<h3 class="page-title">Create Sub Admin</h3>
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="mdi mdi-home-outline"></i></a></li>
								<li class="breadcrumb-item" aria-current="page">Sub Admin</li>
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
				
				<div class="col-lg-12 col-12">
					
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
                    <style type="text/css">
                    	/*switch*/
						.aiz-switch input:empty {
						    height: 0;
						    width: 0;
						    overflow: hidden;
						    position: absolute;
						    opacity: 0;
						}
						.aiz-switch input:empty ~ span {
						    display: inline-block;
						    position: relative;
						    text-indent: 0;
						    cursor: pointer;
						    -webkit-user-select: none;
						    -moz-user-select: none;
						    -ms-user-select: none;
						    user-select: none;
						    line-height: 24px;
						    height: 21px;
						    width: 40px;
						    border-radius: 12px;
						}
						.aiz-switch input:empty ~ span:after,
						.aiz-switch input:empty ~ span:before {
						    position: absolute;
						    display: block;
						    top: 0;
						    bottom: 0;
						    left: 0;
						    content: " ";
						    -webkit-transition: all 0.1s ease-in;
						    transition: all 0.1s ease-in;
						    width: 40px;
						    border-radius: 12px;
						}
						.aiz-switch input:empty ~ span:before {
						    background-color: #b0dcda;
						}
						.aiz-switch input:empty ~ span:after {
						    height: 17px;
						    width: 17px;
						    line-height: 20px;
						    top: 2px;
						    bottom: 2px;
						    margin-left: 2px;
						    font-size: 0.8em;
						    text-align: center;
						    vertical-align: middle;
						    color: #f8f9fb;
						    background-color: #fff;
						}
						.aiz-switch input:checked ~ span:after {
						    background-color: var(--primary);
						    margin-left: 20px;
						}
						.aiz-switch-secondary input:checked ~ span:after {
						    background-color: var(--secondary);
						}
						.aiz-switch-success input:checked ~ span:after {
						    background-color: #fff;
						}
						.aiz-switch-success input:checked ~ span:after {
						    background-color: #ff5900;
						}
						.aiz-switch-info input:checked ~ span:after {
						    background-color: var(--info);
						}
						.aiz-switch-warning input:checked ~ span:after {
						    background-color: var(--warning);
						}
						.aiz-switch-secondary-base input:checked ~ span:after {
						    background-color: var(--secondary-base);
						}
						.aiz-switch-danger input:checked ~ span:after {
						    background-color: var(--danger);
						}
						.aiz-switch-light input:checked ~ span:after {
						    background-color: var(--light);
						}
						.aiz-switch-dark input:checked ~ span:after {
						    background-color: var(--dark);
						}
						.aiz-switch-blue input:checked ~ span:after {
						    background-color: var(--blue);
						}
						.box_color{
							text-align: center;
						    background: #00afef;
						    height: 90px;
						    border-radius: 10px;
						    padding: 22px !important;
						}
                    </style>

                    <!-- New Role Start 95 - kesavan -->
					  <div class="box">
						<!-- /.box-header -->
						<form class="form" action="{{ route('sub.admin.store') }}" method="post">
							@csrf
							<div class="box-body">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Name</label>
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" value="{{ old('name') }}" required>
												<span class="input-group in-bord-text"><i class="ti-user"></i></span>
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
											<div class="input-group in-bord mb-3">
												<input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ old('email') }}" required>
												<span class="input-group in-bord-text"><i class="ti-email"></i></span>
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
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Phone Number" value="{{ old('phone') }}" required>
												<span class="input-group in-bord-text"><i class="ti-mobile"></i></span>
											</div>
											@error('phone')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Role</label>
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('role') is-invalid @enderror" name="role" placeholder="Role" value="{{ old('role') }}" required>
												<span class="input-group in-bord-text"><i class="ti-key"></i></span>
											</div>
											@error('role')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Salary</label>
											<div class="input-group in-bord mb-3">
												<input type="text" class="form-control @error('salary') is-invalid @enderror" name="salary" placeholder="Salary" value="{{ old('salary') }}" required>
												<span class="input-group in-bord-text"><i class="ti-money"></i></span>
											</div>
											@error('phone')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Joining Date</label>
											<div class="input-group in-bord mb-3">
												<input type="date" onfocus="'showPicker' in this && this.showPicker()" class="form-control @error('join_date') is-invalid @enderror" name="join_date" placeholder="Salary" value="{{ old('join_date') }}" required>
												<!--<span class="input-group in-bord-text"><i class="ti-money"></i></span>-->
											</div>
											@error('join_date')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="form-label">Password</label>
											<div class="input-group in-bord mb-3">
												<input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">
												<span class="input-group in-bord-text"><i class="ti-lock"></i></span>
											</div>
											@error('password')
			                                    <span class="invalid-feedback" role="alert">
			                                        <strong>{{ $message }}</strong>
			                                    </span>
			                                @enderror
										</div>
									</div>
									<style type="text/css">
										.role-section
										{
											background: #fff;
										    box-shadow: 1px 3px 8px #e3e3e3;
										    padding: 30px 20px;
										}
									</style>
									<div class="role-section">
										<h4>Permission Roles</h4>
										<div class="row">
											<div class="col-md-4">
												<div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Customer List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="1">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Customer Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="2">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Customer Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="3">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Customer Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="4">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <br>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Branches List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="5">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Branches Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="6">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Branches Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="7">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Branches Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="8">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <br>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Staff List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="9">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Staff Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="10">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Staff Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="11">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Staff Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="12">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <br>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Project List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="45">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Project Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="46">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Project Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="47">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Project Details</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="85">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Project Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="48">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">On Progress Project</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="49">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Pending Project</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="50">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">On Hold Project</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="51">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Completed Project</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="52">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Renewal Project</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="53">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Completed Payment Project</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="54">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Cancel Payment Project</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="84">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <br>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Leads Status List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="72">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Leads Status Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="73">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Leads Status Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="74">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Leads Status Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="75">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>

					                            <br>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Campaign List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="95">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Campaign Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="96">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Campaign Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="97">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            
											</div>
											
											<div class="col-md-4">
												<div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Sales List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="13">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Sales Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="14">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Sales Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="15">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Sales Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="16">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <br>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Services List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="17">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Services Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="18">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Services Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="19">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Services Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="20">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <br>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">AMC List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="37">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">AMC Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="38">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">AMC Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="39">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">AMC Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="40">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">AMC Invoice</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="41">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">AMC Not interest</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="42">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">AMC interest</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="43">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">AMC Customer</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="44">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <br>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Task List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="55">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Task Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="56">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Single Task Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="57">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Single Task Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="58">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Single Task View</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="59">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Recurring Task Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="60">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Recurring Task Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="61">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Recurring Task View</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="62">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Project Follow</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="76">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Payment Follow</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="77">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Task Complete Rights</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="83">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Edit Admin Hours</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="93">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Delete Comments</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="94">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Customer Follow Up</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="86">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Customer Follow Up Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="87">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Customer Follow Up Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="88">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Customer Follow Up Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="89">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Follow Up Create Project</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="90">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Follow Up Bulk Upload</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="91">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Follow Up Import</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="92">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
											</div>
											<div class="col-md-4">
												<div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Domain Hosting List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="21">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Domain Hosting Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="22">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Domain Hosting Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="23">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Domain Hosting Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="24">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Domain Hosting Invoice</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="25">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Domain Hosting Not interest</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="26">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Domain Hosting interest</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="27">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Domain Hosting Customer</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="28">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <br>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">GSuite List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="29">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">GSuite Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="30">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">GSuite Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="31">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">GSuite Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="32">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">GSuite Invoice</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="33">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">GSuite Not interest</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="34">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">GSuite interest</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="35">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">GSuite Customer</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="36">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <br>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Proposal List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="63">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Proposal Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="64">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Proposal Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="65">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Proposal Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="66">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <br>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Leads From List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="67">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Leads From Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="68">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Leads From Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="69">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Leads From Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="70">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Bulk Leads From Upload</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="71">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>

					                            <br>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Credentials List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="78">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Credentials Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="79">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Credentials Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="80">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Credentials Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="81">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">Bulk Credentials Upload</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="82">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>

					                            <br>
					                            <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">My Bills List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="98">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">My Bills Add</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="99">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                                <div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">My Bills Edit</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="100">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>
					                            <div class="row">
					                            	<div class="col-md-1"></div>
					                                <div class="col-md-9">
					                                    <label class="col-from-label">My Bills Delete</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="101">
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div>

											</div>
										</div>
									</div>

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