@extends('layouts.dashboard')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h3 class="page-title">Edit Staff</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i
                                                class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page">Staff</li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>
            <style type="text/css">
                /*switch*/
                .aiz-switch input:empty {
                    height: 0;
                    width: 0;
                    overflow: hidden;
                    position: absolute;
                    opacity: 0;
                }

                .aiz-switch input:empty~span {
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

                .aiz-switch input:empty~span:after,
                .aiz-switch input:empty~span:before {
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

                .aiz-switch input:empty~span:before {
                    background-color: #b0dcda;
                }

                .aiz-switch input:empty~span:after {
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

                .aiz-switch input:checked~span:after {
                    background-color: var(--primary);
                    margin-left: 20px;
                }

                .aiz-switch-secondary input:checked~span:after {
                    background-color: var(--secondary);
                }

                .aiz-switch-success input:checked~span:after {
                    background-color: #fff;
                }

                .aiz-switch-success input:checked~span:after {
                    background-color: #ff5900;
                }

                .aiz-switch-info input:checked~span:after {
                    background-color: var(--info);
                }

                .aiz-switch-warning input:checked~span:after {
                    background-color: var(--warning);
                }

                .aiz-switch-secondary-base input:checked~span:after {
                    background-color: var(--secondary-base);
                }

                .aiz-switch-danger input:checked~span:after {
                    background-color: var(--danger);
                }

                .aiz-switch-light input:checked~span:after {
                    background-color: var(--light);
                }

                .aiz-switch-dark input:checked~span:after {
                    background-color: var(--dark);
                }

                .aiz-switch-blue input:checked~span:after {
                    background-color: var(--blue);
                }

                .box_color {
                    text-align: center;
                    background: #00afef;
                    height: 90px;
                    border-radius: 10px;
                    padding: 22px !important;
                }
            </style>
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
                        <div class="box">
                            <!-- /.box-header -->
                            <form class="form" action="{{ route('sub.staff.update', $staff->id) }}" method="post">
                                @csrf
                                <div class="box-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Sub Admin</label>
                                                <div class="input-group in-bord mb-3">
                                                    <select class="form-control @error('sub_admin') is-invalid @enderror"
                                                        name="sub_admin">
                                                        <option value="">Select Sub Admin</option>
                                                        @if ($sub_admin)
                                                            @foreach ($sub_admin as $key => $admin)
                                                                <option value="{{ $admin->id }}"
                                                                    @if ($admin->id == $staff->sub_admin_id) selected @endif>
                                                                    {{ $admin->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <span class="input-group in-bord-text"><i class="ti-user"></i></span>
                                                </div>
                                                @error('sub_admin')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Name</label>
                                                <div class="input-group in-bord mb-3">
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" placeholder="Name" value="{{ $staff->name }}"
                                                        required>
                                                    <span class="input-group in-bord-text"><i class="ti-user"></i></span>
                                                </div>
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Email address</label>
                                                <div class="input-group in-bord mb-3">
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" placeholder="Email" value="{{ $staff->email }}"
                                                        required>
                                                    <span class="input-group in-bord-text"><i class="ti-email"></i></span>
                                                </div>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Phone Number</label>
                                                <div class="input-group in-bord mb-3">
                                                    <input type="text"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        name="phone" placeholder="Phone Number"
                                                        value="{{ $staff->phone }}" required>
                                                    <span class="input-group in-bord-text"><i class="ti-mobile"></i></span>
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
                                                <label class="form-label">Salary</label>
                                                <div class="input-group in-bord mb-3">
                                                    <input type="text"
                                                        class="form-control @error('salary') is-invalid @enderror"
                                                        name="salary" placeholder="Salary" value="{{ $staff->salary }}"
                                                        required>
                                                    <span class="input-group in-bord-text"><i class="ti-money"></i></span>
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
                                                <div class="input-group in-bord mb-3">
                                                    <input type="text"
                                                        class="form-control @error('role') is-invalid @enderror"
                                                        name="role" placeholder="Role" value="{{ $staff->role }}"
                                                        required>
                                                    <span class="input-group in-bord-text"><i class="ti-key"></i></span>
                                                </div>
                                                @error('role')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Joining Date</label>
                                                <div class="input-group in-bord mb-3">
                                                    <input type="date"
                                                        onfocus="'showPicker' in this && this.showPicker()"
                                                        class="form-control @error('join_date') is-invalid @enderror"
                                                        name="join_date" placeholder="Salary"
                                                        value="{{ $staff->join_date }}" required>
                                                    <!--<span class="input-group in-bord-text"><i class="ti-money"></i></span>-->
                                                </div>
                                                @error('join_date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Password</label>
                                                <div class="input-group in-bord mb-3">
                                                    <input type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        name="password" placeholder="Password">
                                                    <span class="input-group in-bord-text"><i class="ti-lock"></i></span>
                                                </div>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Confirm Password</label>
                                                <div class="input-group in-bord mb-3">
                                                    <input type="password"
                                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                                        name="password_confirmation" placeholder="Confirm Password">
                                                    <span class="input-group in-bord-text"><i class="ti-lock"></i></span>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Status</label>
                                                <div class="input-group in-bord mb-3">
                                                    <select class="form-control @error('status') is-invalid @enderror"
                                                        name="status">
                                                        <option value="active"
                                                            @if ($staff->status == 'active') selected @endif>Active
                                                        </option>
                                                        <option value="inactive"
                                                            @if ($staff->status == 'inactive') selected @endif>In Active
                                                        </option>
                                                    </select>
                                                </div>
                                                @error('status')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                    <style type="text/css">
                                        /* ==============================================================
           PREMIUM SEPARATED CARD DESIGN (Magic CSS Transformation)
        ============================================================== */

                                        /* Main Background Wrapper */
                                        .premium-role-section {
                                            background: #f8fafc;
                                            /* Soft gray background makes white cards pop */
                                            border-radius: 16px;
                                            padding: 35px 25px;
                                            box-shadow: inset 0 4px 20px rgba(0, 0, 0, 0.02);
                                            border: 1px solid #e2e8f0;
                                            margin-top: 10px;
                                        }

                                        .premium-role-section h4 {
                                            font-weight: 600;
                                            color: #1e2130;
                                            font-size: 1.4rem;
                                            margin-bottom: 25px;
                                            border-bottom: 2px dashed #cbd5e1;
                                            padding-bottom: 15px;
                                        }

                                        /* Convert <br> into physical spaces between cards */
                                        .premium-role-section br {
                                            display: block;
                                            content: "";
                                            height: 25px;
                                            /* THE SPACE FOR SEPARATE VIEWING */
                                            margin: 0;
                                        }

                                        /* Remove column backgrounds so cards float independently */
                                        .premium-role-section>.row>.col-md-4 {
                                            background: transparent !important;
                                            padding: 0 15px !important;
                                            border: none !important;
                                            box-shadow: none !important;
                                        }

                                        /* ----------------------------------------------------
           CARD HEADERS (Transforms col-md-10 rows)
        ---------------------------------------------------- */
                                        .premium-role-section .col-md-4>.row:has(> .col-md-10) {
                                            border-radius: 12px 12px 0 0;
                                            /* Rounded top only */
                                            padding: 14px 20px;
                                            margin: 0;
                                            display: flex;
                                            align-items: center;
                                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                                        }

                                        .premium-role-section .col-md-4>.row:has(> .col-md-10) .col-from-label {
                                            color: #ffffff;
                                            font-weight: 600;
                                            font-size: 0.95rem;
                                            text-transform: uppercase;
                                            letter-spacing: 0.5px;
                                            margin: 0;
                                        }

                                        /* ----------------------------------------------------
           CARD BODY ITEMS (Transforms col-md-1/9 rows)
        ---------------------------------------------------- */
                                        .premium-role-section .col-md-4>.row:has(> .col-md-1) {
                                            background: #ffffff;
                                            border-left: 1px solid #e2e8f0;
                                            border-right: 1px solid #e2e8f0;
                                            border-bottom: 1px solid #e2e8f0;
                                            padding: 12px 20px;
                                            margin: 0;
                                            display: flex;
                                            align-items: center;
                                            transition: background 0.2s ease;
                                        }

                                        .premium-role-section .col-md-4>.row:has(> .col-md-1):hover {
                                            background: #f1f5f9;
                                            /* Subtle hover effect on rows */
                                        }

                                        .premium-role-section .col-md-4>.row:has(> .col-md-1) .col-from-label {
                                            color: #475569;
                                            font-size: 0.9rem;
                                            font-weight: 600;
                                            margin: 0;
                                        }

                                        /* Round the bottom of the LAST item in every card */
                                        .premium-role-section .col-md-4>.row:has(> .col-md-1):has(+ br),
                                        .premium-role-section .col-md-4>.row:has(> .col-md-1):last-child {
                                            border-radius: 0 0 12px 12px;
                                            /* Rounded bottom */
                                            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
                                            /* Drop shadow for the whole card */
                                        }

                                        /* Clean up the grid alignment automatically */
                                        .premium-role-section .col-md-1 {
                                            display: none;
                                        }

                                        /* Hides empty space */
                                        .premium-role-section .col-md-9,
                                        .premium-role-section .col-md-10 {
                                            flex-grow: 1;
                                            padding: 0;
                                        }

                                        .premium-role-section .col-md-2 {
                                            flex-shrink: 0;
                                            padding: 0;
                                            display: flex;
                                            justify-content: flex-end;
                                            /* Pushes toggles to the right edge */
                                        }

                                        /* ----------------------------------------------------
           COLUMN THEMES (Webitech Brand Colors)
        ---------------------------------------------------- */
                                        /* COLUMN 1: Blue Theme */
                                        .premium-role-section>.row>.col-md-4:nth-child(1) .row:has(> .col-md-10) {
                                            background: linear-gradient(90deg, #00afef, #0284c7);
                                        }

                                        .premium-role-section>.row>.col-md-4:nth-child(1) .aiz-switch input:checked~span:before {
                                            background-color: #00afef !important;
                                        }

                                        /* COLUMN 2: Orange Theme */
                                        .premium-role-section>.row>.col-md-4:nth-child(2) .row:has(> .col-md-10) {
                                            background: linear-gradient(90deg, #ff6b00, #ea580c);
                                        }

                                        .premium-role-section>.row>.col-md-4:nth-child(2) .aiz-switch input:checked~span:before {
                                            background-color: #ff6b00 !important;
                                        }

                                        /* COLUMN 3: Dark Slate Theme */
                                        .premium-role-section>.row>.col-md-4:nth-child(3) .row:has(> .col-md-10) {
                                            background: linear-gradient(90deg, #1e293b, #334155);
                                        }

                                        .premium-role-section>.row>.col-md-4:nth-child(3) .aiz-switch input:checked~span:before {
                                            background-color: #334155 !important;
                                        }

                                        /* ----------------------------------------------------
           MODERN IOS-STYLE TOGGLE SWITCHES
        ---------------------------------------------------- */
                                        .aiz-switch input:empty {
                                            height: 0;
                                            width: 0;
                                            overflow: hidden;
                                            position: absolute;
                                            opacity: 0;
                                        }

                                        .aiz-switch input:empty~span {
                                            display: inline-block;
                                            position: relative;
                                            cursor: pointer;
                                            height: 24px;
                                            width: 44px;
                                            border-radius: 20px;
                                        }

                                        .aiz-switch input:empty~span:after,
                                        .aiz-switch input:empty~span:before {
                                            position: absolute;
                                            display: block;
                                            content: " ";
                                            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                        }

                                        /* Inactive Track */
                                        .aiz-switch input:empty~span:before {
                                            top: 0;
                                            bottom: 0;
                                            left: 0;
                                            right: 0;
                                            background-color: #e2e8f0 !important;
                                            border-radius: 20px;
                                            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
                                        }

                                        /* Knob */
                                        .aiz-switch input:empty~span:after {
                                            height: 18px;
                                            width: 18px;
                                            top: 3px;
                                            left: 3px;
                                            background-color: #ffffff !important;
                                            border-radius: 50%;
                                            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                                        }

                                        /* Knob Active Slide */
                                        .aiz-switch input:checked~span:after {
                                            margin-left: 20px;
                                        }
                                    </style>

                                    <style type="text/css">
                                        .role-section {
                                            background: #fff;
                                            box-shadow: 1px 3px 8px #e3e3e3;
                                            padding: 30px 20px;
                                        }
                                    </style>
                                    <?php
                                    $role_section = json_decode($staff->permissions);
                                    if ($role_section) {
                                        $role_section = $role_section;
                                    } else {
                                        $role_section = [];
                                    }
                                    // dd($role_section);
                                    ?>
                                    <div class="role-section premium-role-section">
                                        <h4>Permission Roles</h4>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <label class="col-from-label">Customer List</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="aiz-switch aiz-switch-success mb-0">
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="1"
                                                                @if (in_array('1', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="2"
                                                                @if (in_array('2', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="3"
                                                                @if (in_array('3', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="4"
                                                                @if (in_array('4', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="5"
                                                                @if (in_array('5', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="6"
                                                                @if (in_array('6', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="7"
                                                                @if (in_array('7', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="8"
                                                                @if (in_array('8', $role_section)) checked @endif>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                {{-- <div class="row">
					                                <div class="col-md-10">
					                                    <label class="col-from-label">Staff List</label>
					                                </div>
					                                <div class="col-md-2">
					                                    <label class="aiz-switch aiz-switch-success mb-0">
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="9" @if (in_array('9', $role_section)) checked @endif>
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
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="10" @if (in_array('10', $role_section)) checked @endif>
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
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="11" @if (in_array('11', $role_section)) checked @endif>
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
					                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="12" @if (in_array('12', $role_section)) checked @endif>
					                                        <span class="slider round"></span>
					                                    </label>
					                                </div>
					                            </div> --}}
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <label class="col-from-label">Project List</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="aiz-switch aiz-switch-success mb-0">
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="45"
                                                                @if (in_array('45', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="46"
                                                                @if (in_array('46', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="47"
                                                                @if (in_array('47', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="48"
                                                                @if (in_array('48', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="49"
                                                                @if (in_array('49', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="50"
                                                                @if (in_array('50', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="51"
                                                                @if (in_array('51', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="52"
                                                                @if (in_array('52', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="53"
                                                                @if (in_array('53', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="54"
                                                                @if (in_array('54', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="13"
                                                                @if (in_array('13', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="14"
                                                                @if (in_array('14', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="15"
                                                                @if (in_array('15', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="16"
                                                                @if (in_array('16', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="17"
                                                                @if (in_array('17', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="18"
                                                                @if (in_array('18', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="19"
                                                                @if (in_array('19', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="20"
                                                                @if (in_array('20', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="37"
                                                                @if (in_array('37', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="38"
                                                                @if (in_array('38', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="39"
                                                                @if (in_array('39', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="40"
                                                                @if (in_array('40', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="41"
                                                                @if (in_array('41', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="42"
                                                                @if (in_array('42', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="43"
                                                                @if (in_array('43', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="44"
                                                                @if (in_array('44', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="67"
                                                                @if (in_array('67', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="68"
                                                                @if (in_array('68', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="69"
                                                                @if (in_array('69', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="70"
                                                                @if (in_array('70', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="71"
                                                                @if (in_array('71', $role_section)) checked @endif>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
													<br>

                                                <div class="row">
                                                   
													 <div class="col-md-10">
                                                        <label class="col-from-label">Leads whatsapp</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="aiz-switch aiz-switch-success mb-0">
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="701"
                                                                @if (in_array('701', $role_section)) checked @endif>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
												
												
                                                <div class="row">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-9">
                                                        <label class="col-from-label">Leads whatsapp Edit</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="aiz-switch aiz-switch-success mb-0">
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="702"
                                                                @if (in_array('702', $role_section)) checked @endif>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-9">
                                                        <label class="col-from-label">Leads whatsapp Delete</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="aiz-switch aiz-switch-success mb-0">
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="703"
                                                                @if (in_array('703', $role_section)) checked @endif>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1"></div>
                                                    <div class="col-md-9">
                                                        <label class="col-from-label">Bulk Leads whatsapp Upload</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="aiz-switch aiz-switch-success mb-0">
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="704"
                                                                @if (in_array('704', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="21"
                                                                @if (in_array('21', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="22"
                                                                @if (in_array('22', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="23"
                                                                @if (in_array('23', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="24"
                                                                @if (in_array('24', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="25"
                                                                @if (in_array('25', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="26"
                                                                @if (in_array('26', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="27"
                                                                @if (in_array('27', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="28"
                                                                @if (in_array('28', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="29"
                                                                @if (in_array('29', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="30"
                                                                @if (in_array('30', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="31"
                                                                @if (in_array('31', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="32"
                                                                @if (in_array('32', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="33"
                                                                @if (in_array('33', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="34"
                                                                @if (in_array('34', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="35"
                                                                @if (in_array('35', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="36"
                                                                @if (in_array('36', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="63"
                                                                @if (in_array('63', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="64"
                                                                @if (in_array('64', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="65"
                                                                @if (in_array('65', $role_section)) checked @endif>
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
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="66"
                                                                @if (in_array('66', $role_section)) checked @endif>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-10">
                                                        <label class="col-from-label">Follow Up Project List</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="aiz-switch aiz-switch-success mb-0">
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="72"
                                                                @if (in_array('72', $role_section)) checked @endif>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-10">
                                                        <label class="col-from-label">Follow Up Payment List</label>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="aiz-switch aiz-switch-success mb-0">
                                                            <input type="checkbox" name="permissions[]"
                                                                class="form-control demo-sw" value="73"
                                                                @if (in_array('73', $role_section)) checked @endif>
                                                            <span class="slider round"></span>
                                                        </label>
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
