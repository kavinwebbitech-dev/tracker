<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo url(''); ?>/public/admin_assets/images/favicon.png">

    <title>@yield('meta_name')</title>


    <!-- Vendors Style-->
    <link rel="stylesheet" href="<?php echo url(''); ?>/public/admin_assets/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="<?php echo url(''); ?>/public/admin_assets/css/style.css">
    <link rel="stylesheet" href="<?php echo url(''); ?>/public/admin_assets/css/skin_color.css">
    <link rel="stylesheet" href="<?php echo url(''); ?>/public/admin_assets/css/custom.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/public/admin_assets/css/bootstrap-icons.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/public/admin_assets/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/public/admin_assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/public/admin_assets/css/meanmenu.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo url(''); ?>/public/admin_assets/css/tracker-header.css" />
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/ckeditor/ckeditor.js"></script>
    <!-- <script src="//cdn.ckeditor.com/4.5.6/standard/ckeditor.js"></script> -->
    <!-- <script
        src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js">
    </script> -->
    <script src="<?php echo url(''); ?>/public/admin_assets/js/pages/editor.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

    <!-- Optional: Include the libraries for exporting to Excel, CSV, PDF, etc. -->
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.17/jspdf.plugin.autotable.min.js"></script>

    @laravelPWA
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

    <?php
    // $to_date = date('Y-m-d');
    $date = \Carbon\Carbon::now()->addDays(30);
    $to_date = $date->format('Y-m-d');
    
    $domainhosting = \App\Models\DomainHosting::where('fld_status', 1)->where('fld_domain_end_date', '<', $to_date)->get();
    $domainhosting1 = \App\Models\GSuite::where('fld_status', 1)->where('fld_gsuite_end_date', '<', $to_date)->get();
    $domainhosting2 = \App\Models\AdminAmc::where('fld_status', 1)->where('fld_amc_end_date', '<', $to_date)->get();
    
    $total_domain = count($domainhosting) + count($domainhosting1) + count($domainhosting2);
    
    $estimate_list = \App\Models\ClientTask::whereNot('status', 'Approved')->get();
    $estimate_client_list = \App\Models\ClientTask::where('user_type', 'client')->whereNot('status', 'Approved')->get();
    $estimate__freelance_list = \App\Models\ClientTask::where('user_type', 'freelancer')->whereNot('status', 'Approved')->get();
    
    $adddedd_year = \App\Models\Project::groupBy('added_year')->get(['added_year']);
    
    $added_year = $adddedd_year;
    // dd($added_year);
    ?>

    <div class="wrapper">
        <!-- <div id="loader"></div> -->
        <div class="mobile-header mobile-view">
            <div class="container">
                <div class="row gy-3 align-items-center">
                    <div class="col-12 order-0 order-md-0 col-md-6">
                        <div class="logo text-center text-md-start">
                            <a href="{{ route('admin.index') }}"><img
                                    src="<?php echo url(''); ?>/public/admin_assets/images/logo.png" alt=""></a>
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
                                    <a href="{{ route('admin.index') }}" class="logo"><img
                                            src="<?php echo url(''); ?>/public/admin_assets/images/logo.png" /></a>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>

                            </div>
                            <div class="offcanvas-body mean-container">
                                <div class="main-menu ">
                                    <nav class="mean-menulist">
                                        <ul>
                                            <li>
                                                <a class=" active" href="{{ route('admin.index') }}"><i
                                                        class="bi bi-grid-fill"></i>Dashboard</a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="bi bi-collection-fill"></i>Master
                                                </a>
                                                <ul>
                                                    <li><a href="{{ route('admin.customers.view') }}"><i
                                                                class="bi bi-indent"></i>Customer List</a></li>
                                                    <li><a href="{{ route('admin.branches.view') }}"><i
                                                                class="bi bi-indent"></i>Branches</a></li>
                                                    <li><a href="#"><i class="bi bi-indent"></i>Sub Admin</a>
                                                        <ul>
                                                            <ul>
                                                                <li><a href="{{ route('sub.admin.create') }}"><i
                                                                            class="bi bi-indent"></i>Add Sub Admin</a>
                                                                </li>
                                                                <li><a href="{{ route('sub.admin.view') }}"><i
                                                                            class="bi bi-indent"></i>View Sub Admin</a>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </li>
                                                    <li><a href="#"><i class="bi bi-indent"></i>Staff</a>
                                                        <ul>
                                                            <ul>
                                                                <li><a href="{{ route('sub.staff.create') }}"><i
                                                                            class="bi bi-indent"></i>Add Staff</a></li>
                                                                <li><a href="{{ route('sub.staff.view') }}"><i
                                                                            class="bi bi-indent"></i>View Staff</a>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </li>
                                                    <li><a href="#"><i class="bi bi-indent"></i>Freelancer</a>
                                                        <ul>
                                                            <ul>
                                                                <li><a href="{{ route('admin.freelancer.create') }}"><i
                                                                            class="bi bi-indent"></i>Add Freelancer</a>
                                                                </li>
                                                                <li><a href="{{ route('admin.freelancer.view') }}"><i
                                                                            class="bi bi-indent"></i>View
                                                                        Freelancer</a>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </li>
                                                    {{-- <li><a href="{{ route('admin.sales.view') }}"><i class="bi bi-indent"></i>Sales User</a></li> --}}
                                                    <li><a href="{{ route('admin.service.view') }}"><i
                                                                class="bi bi-indent"></i>Service</a></li>
                                                    <li><a href="{{ route('admin.projects.employee.report') }}"><i
                                                                class="bi bi-indent"></i>Employee Project Report</a>
                                                    </li>
                                                    <li><a href="{{ route('admin.hosting_server.view') }}"><i
                                                                class="bi bi-indent"></i>Hosting Server</a>
                                                    </li>
                                                    <li><a href="{{ route('admin.domain_server.view') }}"><i
                                                                class="bi bi-indent"></i>Domain_Server</a>
                                                    </li>

                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#" role="button">
                                                    <i class="bi bi-stickies-fill"></i> Renewal(s)
                                                </a>
                                                <ul>
                                                    <li><a href="{{ route('admin.domain.hosting.view') }}"><i
                                                                class="bi bi-indent"></i>Domain Hosting</a></li>
                                                    <li><a href="{{ route('admin.gsuide.view') }}"><i
                                                                class="bi bi-indent"></i>Gsuite</a></li>
                                                    <li><a href="{{ route('admin.amc.view') }}"><i
                                                                class="bi bi-indent"></i>AMC</a></li>
                                                    <li><a href="{{ route('admin.domain.hosting.view.interest') }}"><i
                                                                class="bi bi-indent"></i>Not Interested DH</a></li>
                                                    <li><a href="{{ route('admin.amc.view.interest') }}"><i
                                                                class="bi bi-indent"></i>Not Interested AMC</a></li>
                                                    <li><a href="{{ route('admin.gsuide.view.interest') }}"><i
                                                                class="bi bi-indent"></i>Not Interested Gsuite</a></li>
                                                </ul>
                                            </li>

                                            <li>
                                                <a href="#">
                                                    <i class="bi bi-file-earmark-text-fill"></i>Projects
                                                </a>
                                                <ul>
                                                    <li><a href="{{ route('admin.projects.create') }}"><i
                                                                class="bi bi-indent"></i>Add Projects</a></li>
                                                    <li><a href="{{ route('admin.projects.view') }}"><i
                                                                class="bi bi-indent"></i>View Projects</a></li>
                                                    <li><a href="{{ route('admin.projects.renewal') }}"><i
                                                                class="bi bi-indent"></i>Renewal Projects</a></li>
                                                    @if (count($added_year))
                                                        <li><a><i class="bi bi-indent"></i>Years</a>
                                                            <ul>
                                                                @foreach ($added_year as $key => $year)
                                                                    @if ($year->added_year)
                                                                        <li><a
                                                                                href="{{ route('admin.projects.year.wise', $year->added_year) }}"><i
                                                                                    class="bi bi-indent"></i>
                                                                                {{ $year->added_year }}</a></li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    @endif
                                                    <li><a href="{{ route('admin.follow.up.view') }}"><i
                                                                class="bi bi-indent"></i>Follow Up</a></li>
                                                    <li><a href="{{ route('admin.projects.going') }}"><i
                                                                class="bi bi-indent"></i>On Progress Projects</a></li>
                                                    <li><a href="{{ route('admin.projects.pending') }}"><i
                                                                class="bi bi-indent"></i>Pending Projects</a></li>
                                                    <li><a href="{{ route('admin.projects.hold') }}"><i
                                                                class="bi bi-indent"></i>On Hold Projects</a></li>
                                                    <li><a href="{{ route('admin.projects.completed') }}"><i
                                                                class="bi bi-indent"></i>Completed Projects</a></li>
                                                    <li><a href="{{ route('admin.leads.from.view') }}"><i
                                                                class="bi bi-indent"></i>Leads Form</a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="bi bi-ubuntu"></i>Task Request
                                                </a>
                                                <ul>
                                                    <li><a><i class="bi bi-indent"></i>Years</a>
                                                        <ul>
                                                            <li><a href=""><i class="bi bi-indent"></i> Client
                                                                    Request <span
                                                                        style="float: right;background: #00afef;width: 21px;height: 20px;border-radius: 50%;color: #fff;font-size: 10px;text-align: center;margin-left: 13px;padding: 2px;margin-top: 0px;">{{ count($estimate_client_list) }}</span></a>
                                                            </li>
                                                            <li><a href=""><i class="bi bi-indent"></i>
                                                                    Freelancer Request <span
                                                                        style="float: right;background: #00afef;width: 21px;height: 20px;border-radius: 50%;color: #fff;font-size: 10px;text-align: center;margin-left: 13px;padding: 2px;margin-top: 0px;">{{ count($estimate__freelance_list) }}</span></a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li><a href="{{ route('task.create') }}"><i
                                                                class="bi bi-indent"></i>Add Task</a></li>
                                                    <li><a href="{{ route('task.view') }}"><i
                                                                class="bi bi-indent"></i>Single Task</a></li>
                                                    <li><a href="{{ route('recurring.task.view') }}"><i
                                                                class="bi bi-indent"></i>Recurring Task</a></li>
                                                    <li><a href="{{ route('admin.recommand.task.view') }}"><i
                                                                class="bi bi-indent"></i>Recommand Task</a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="bi bi-ubuntu"></i>Proposal
                                                </a>
                                                <ul>
                                                    <li><a href="{{ route('admin.proposal.create') }}"><i
                                                                class="bi bi-indent"></i>Add
                                                            Proposal</a></li>
                                                    <li><a href="{{ route('admin.proposal.view') }}"><i
                                                                class="bi bi-indent"></i>View
                                                            Proposal</a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="bi bi-currency-rupee"></i>Accounts
                                                </a>
                                                <ul>
                                                    <li><a href="{{ route('admin.imcome.amount.view') }}"><i
                                                                class="bi bi-indent"></i>Income Amount</a></li>
                                                    <li><a href="{{ route('admin.expenses.amount.view') }}"><i
                                                                class="bi bi-indent"></i>Expenses Amount</a></li>
                                                    <li><a href="{{ route('admin.my.bills.view') }}"><i
                                                                class="bi bi-indent"></i>My Bills</a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.credentials.view') }}"><i
                                                        class="bi bi-key"></i>Credentials</a>
                                            </li>
                                        </ul>

                                    </nav>


                                </div>


                            </div>


                        </div>
                    </div>
                    <div class="col-10 col-md-5 order-2 order-md-1">
                        <form class="d-flex justify-content-end " role="search">
                            <!-- <div class="dropdown">
                                    <a class="btn btn-dropdown" href="#">
                                        <i class="fa-solid fa-expand"></i>
                                    </a>

                                </div> -->
                            <?php
                            $notification = \App\Models\Notification::where('receiver_id', Auth::user()->id)
                                ->where('status', 0)
                                ->take(5)
                                ->latest()
                                ->get();
                            $count_notifi = count($notification);
                            ?>
                            <div class="dropdown">
                                @if ($count_notifi != 0)
                                    <div class="notify-badge"></div>
                                @endif
                                <a class="btn btn-dropdown dropdown-toggle" href="#" role="button"
                                    data-bs-offset="10,20" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-bell"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    @if ($notification)
                                        @foreach ($notification as $key => $message)
                                            <li id="notification_status" data-id='12'><a class="dropdown-item"
                                                    href="{{ $message->url }}">{{ $message->message }}</a></li>
                                        @endforeach
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('admin.notification.view') }}">View
                                            all</a></li>
                                </ul>
                            </div>
                            <div class="dropdown">
                                <a class="btn btn-dropdown dropdown-toggle" href="#" role="button"
                                    data-bs-offset="10,20" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>

        <style>
            .main-header .nav-item {
                padding: 0px 10px !important;
            }

            /* Premium Corporate Navbar Wrapper */
            .corporate-navbar {
                background: #ffffff;
                box-shadow: unset !important;
                /* Deep, soft shadow */
                padding: 12px 30px;
                position: relative;
                border-bottom: 1px solid rgba(0, 0, 0, 0.02);
                z-index: 1000;
            }

            /* Creative Accent Line - Matches Webitech Logo Colors */
            .corporate-navbar::before {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 2px;
                background: linear-gradient(90deg, #00afef 0%, #ff6b00 100%);
            }

            .corporate-navbar .nav-link {
                color: #000000 !important;
                font-weight: 600 !important;
                font-size: 0.9rem;
                display: flex;
                align-items: center;
                border-radius: 8px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                padding: 10px 10px !important;
            }

            .corporate-navbar .nav-link:hover,
            .corporate-navbar .nav-link.active {
                color: #00afef !important;
                background-color: rgba(0, 175, 239, 0.08);
            }

            .corporate-navbar .nav-link:hover i,
            .corporate-navbar .nav-link.active i {
                color: #00afef !important;
            }

            .corporate-navbar .btn-dropdown {
                background: #ff5900 !important;
                color: rgb(255 255 255) !important;
            }

            /* .corp-badge {
    background: linear-gradient(135deg, #00afef, #007bb5);
    color: #ffffff;
    font-size: 0.7rem;
    font-weight: 700;
    height: 22px;
    min-width: 22px;
    border-radius: 11px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
    margin-left: 8px;
    box-shadow: 0 3px 8px rgba(0, 175, 239, 0.3);
    border: 2px solid #fff;
} */

            .main-header .dropdown .dropdown-menu .dropdown-item {
                margin: 0;
                border-radius: 10px;
                border: 0;
                color: #000000 !important;
                font-weight: 600 !important;
            }

            .main-header .dropdown .dropdown-menu .dropdown-item:hover {
                color: #00afef !important;
                background-color: rgba(0, 175, 239, 0.08);
            }

            .main-header .navbar-nav>li.dropdown>.dropdown-menu {
                padding: 10px !important;
                border-radius: 14px !important;
            }

            /* Chrome, Safari, Edge, Opera */
            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            /* Firefox */
            input[type="number"] {
                -moz-appearance: textfield;
                appearance: textfield;
            }
        </style>

        <header class="main-header web-view">
            <nav class="navbar navbar-expand-lg bg-body-tertiary corporate-navbar">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('admin.index') }}"><img
                            src="<?php echo url(''); ?>/public/admin_assets/images/logo.png" alt=""></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="{{ route('admin.index') }}"><i
                                        class="bi bi-grid-fill"></i>Dashboard</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-collection-fill"></i>Master
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.customers.view') }}">Customer
                                            List</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('admin.branches.view') }}">Branches</a></li>
                                    <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"
                                            href="#"> Sub
                                            Admin</a>
                                        <ul class="dropdown-menu">
                                            <ul class="treeview-menu" style="display: block;">
                                                <li><a href="{{ route('sub.admin.create') }}"
                                                        class="dropdown-item">Add Sub
                                                        Admin</a></li>
                                                <li><a href="{{ route('sub.admin.view') }}"
                                                        class="dropdown-item">View Sub
                                                        Admin</a></li>
                                            </ul>
                                        </ul>
                                    </li>
                                    <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"
                                            href="#"> Staff</a>
                                        <ul class="dropdown-menu">
                                            <ul class="treeview-menu" style="display: block;">
                                                <li><a href="{{ route('sub.staff.create') }}"
                                                        class="dropdown-item">Add
                                                        Staff</a></li>
                                                <li><a href="{{ route('sub.staff.view') }}"
                                                        class="dropdown-item">View
                                                        Staff</a></li>
                                                {{-- <li><a href="{{ route('sub.task.group') }}" class="dropdown-item">Group
                                                            Staff</a></li> --}}
                                                <li><a href="{{ route('sub.task.incentive') }}"
                                                        class="dropdown-item">Incentive List</a></li>
                                            </ul>
                                        </ul>
                                    </li>
                                    <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"
                                            href="#"> Freelancer</a>
                                        <ul class="dropdown-menu">
                                            <ul class="treeview-menu" style="display: block;">
                                                <li><a href="{{ route('admin.freelancer.create') }}"
                                                        class="dropdown-item">Add
                                                        Freelancer</a></li>
                                                <li><a href="{{ route('admin.freelancer.view') }}"
                                                        class="dropdown-item">View
                                                        Freelancer</a></li>
                                            </ul>
                                        </ul>
                                    </li>
                                    {{-- <li><a class="dropdown-item" href="{{ route('admin.sales.view') }}">Sales User</a> --}}
                                    <li><a class="dropdown-item" href="{{ route('admin.service.view') }}">Service</a>
                                    </li>
                                    <li><a href="{{ route('admin.leads.from.view') }}" class="dropdown-item">Leads
                                            Form</a></li>
                                    <li><a href="{{ route('admin.campaign.details.view') }}"
                                            class="dropdown-item">Campaign</a></li>
                                    <li><a href="{{ route('admin.hosting_server.view') }}" class="dropdown-item">
                                            Hosting Server</a></li>
                                    <li><a href="{{ route('admin.domain_server.view') }}"class="dropdown-item">Domain_Server</a>
                                    </li>

                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-ubuntu"></i>Renewal(s) <span class="corp-badge"
                                        style="float: right;background: #00afef;width: 21px;height: 20px;border-radius: 50%;color: #fff;font-size: 10px;text-align: center;margin-left: 13px;padding: 2px;margin-top: 0px;">{{ $total_domain }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.domain.hosting.view') }}"
                                            class="dropdown-item">Domain Hosting <span class="corp-badge"
                                                style="float: right;background: #00afef;width: 21px;height: 20px;border-radius: 50%;color: #fff;font-size: 10px;text-align: center;margin-left: 13px;padding: 2px;margin-top: 0px;">{{ count($domainhosting) }}</span></a>
                                    </li>
                                    <li><a href="{{ route('admin.gsuide.view') }}" class="dropdown-item">Gsuite <span
                                                class="corp-badge"
                                                style="float: right;background: #00afef;width: 22px;height: 22px;padding: 2px 8px;border-radius: 50%;color: #fff;font-size: 11px;">{{ count($domainhosting1) }}</span></a>
                                    </li>
                                    <li><a href="{{ route('admin.amc.view') }}" class="dropdown-item">AMC <span
                                                class="corp-badge"
                                                style="float: right;background: #00afef;width: 22px;height: 22px;padding: 2px 8px;border-radius: 50%;color: #fff;font-size: 11px;">{{ count($domainhosting2) }}</span></a>
                                    </li>
                                    <li><a href="{{ route('admin.domain.hosting.view.interest') }}"
                                            class="dropdown-item">Not
                                            Interested DH</a></li>
                                    <li><a href="{{ route('admin.amc.view.interest') }}" class="dropdown-item">Not
                                            Interested AMC</a></li>
                                    <li><a href="{{ route('admin.gsuide.view.interest') }}" class="dropdown-item">Not
                                            Interested Gsuite</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-stickies-fill"></i> Projects
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.projects.create') }}" class="dropdown-item">Add
                                            Projects</a></li>
                                    <li><a href="{{ route('admin.projects.view') }}" class="dropdown-item">View
                                            Projects</a></li>
                                    <li><a href="{{ route('admin.projects.renewal') }}" class="dropdown-item">Renewal
                                            Projects</a></li>
                                    @if (count($added_year))
                                        <li class="dropdown-submenu"><a class="dropdown-item">Years</a>
                                            <ul class="dropdown-menu">
                                                @foreach ($added_year as $key => $year1)
                                                    @if ($year1->added_year)
                                                        <li><a href="{{ route('admin.projects.year.wise', $year1->added_year) }}"
                                                                class="dropdown-item"> {{ $year1->added_year }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                    <li><a href="{{ route('admin.follow.up.view') }}" class="dropdown-item">Follow
                                            Up</a></li>
                                    {{-- <li><a href="{{ route('admin.projects.going') }}" class="dropdown-item">On
                                            Progress Projects</a>
                                    </li>
                                    <li><a href="{{ route('admin.projects.pending') }}" class="dropdown-item">Pending
                                            Projects</a></li>
                                    <li><a href="{{ route('admin.projects.hold') }}" class="dropdown-item">On Hold
                                            Projects</a></li>
                                    <li><a href="{{ route('admin.projects.completed') }}"
                                            class="dropdown-item">Completed Projects</a></li> --}}
                                    <li><a href="{{ route('admin.projects.employee.report') }}"
                                            class="dropdown-item">Employee Project Report</a></li>

                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-file-earmark-text-fill"></i>Task <span class="corp-badge"
                                        style="float: right;background: #00afef;width: 21px;height: 20px;border-radius: 50%;color: #fff;font-size: 10px;text-align: center;margin-left: 13px;padding: 2px;margin-top: 0px;">{{ count($estimate_list) }}</span>
                                </a>
                                <ul class="dropdown-menu">

                                    <li class="dropdown-submenu"><a class="dropdown-item">Task Request</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('task.request') }}" class="dropdown-item">Client
                                                    Request <span class="corp-badge"
                                                        style="float: right;background: #00afef;width: 21px;height: 20px;border-radius: 50%;color: #fff;font-size: 10px;text-align: center;">{{ count($estimate_client_list) }}</span></a>
                                            </li>
                                            <li><a href="{{ route('admin.freelancer.request') }}"
                                                    class="dropdown-item">Freelancer Request <span class="corp-badge"
                                                        style="float: right;background: #00afef;width: 21px;height: 20px;border-radius: 50%;color: #fff;font-size: 10px;text-align: center;">{{ count($estimate__freelance_list) }}</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('task.create') }}">Add
                                            Task</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.simpletask.view') }}">Simple
                                            Task</a></li>
                                    <li><a class="dropdown-item" href="{{ route('task.view') }}">Single
                                            Task</a></li>
                                    <li><a class="dropdown-item" href="{{ route('recurring.task.view') }}">Recurring
                                            Task</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('admin.recommand.task.view') }}">Recommand
                                            Task</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-ubuntu"></i>Proposal
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.proposal.create') }}">Add
                                            Proposal</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.proposal.view') }}">View
                                            Proposal</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-currency-rupee"></i>Accounts
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item"
                                            href="{{ route('admin.imcome.amount.view') }}">Income Amount</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('admin.expenses.amount.view') }}">Expenses Amount</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.my.bills.view') }}">My
                                            Bills</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page"
                                    href="{{ route('admin.credentials.view') }}"><i
                                        class="bi bi-key"></i>Credentials</a>
                            </li>
                        </ul>
                        <div class="d-flex">
                            <!-- <div class="dropdown">
                                    <a class="btn btn-dropdown" href="#">
                                        <i class="fa-solid fa-expand"></i>
                                    </a>

                                </div> -->
                            <?php
                            $notification = \App\Models\Notification::where('receiver_id', Auth::user()->id)
                                ->where('status', 0)
                                ->take(5)
                                ->latest()
                                ->get();
                            $count_notifi = count($notification);
                            ?>
                            <div class="dropdown">
                                @if ($count_notifi != 0)
                                    <div class="notify-badge"></div>
                                @endif
                                <a class="btn btn-dropdown dropdown-toggle" href="#" role="button"
                                    data-bs-offset="10,20" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-bell"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    @if ($notification)
                                        @foreach ($notification as $key => $message)
                                            <li id="notification_status" data-id='12'><a class="dropdown-item"
                                                    href="{{ $message->url }}">{{ $message->message }}</a></li>
                                        @endforeach
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('admin.notification.view') }}">View
                                            all</a></li>
                                </ul>
                            </div>
                            <div class="dropdown">
                                <a class="btn btn-dropdown dropdown-toggle" href="#" role="button"
                                    data-bs-offset="10,20" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
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
                    <p style="text-align: right;">Hand-crafted & made with <i class="ion ion-heart text-danger"></i>
                        Webbitech</p>
                </div>
            </di>
        </footer>

        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <!-- Vendor JS -->
    <script src="<?php echo url(''); ?>/public/admin_assets/js/vendors.min.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/js/pages/chat-popup.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/icons/feather-icons/feather.min.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/jquery-knob/js/jquery.knob.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/raphael/raphael.min.js"></script>

    <!-- DataTables Buttons CSS and JS -->

    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/datatable/datatables.min.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/js/pages/data-table.js"></script>

    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/ckeditor/ckeditor.js"></script>
    <!-- <script src="//cdn.ckeditor.com/4.5.6/standard/ckeditor.js"></script> -->
    <!-- <script
        src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js">
    </script> -->
    <script src="<?php echo url(''); ?>/public/admin_assets/js/pages/editor.js"></script>

    <!-- Etikto Admin App -->
    <script src="<?php echo url(''); ?>/public/admin_assets/js/template.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/js/pages/dashboard.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/icons/feather-icons/feather.min.js"></script>


    <script
        src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/bootstrap-select/dist/js/bootstrap-select.js">
    </script>
    <script
        src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.js">
    </script>
    <script
        src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js">
    </script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/select2/dist/js/select2.full.js">
    </script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_plugins/input-mask/jquery.inputmask.js"></script>
    <script
        src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_plugins/input-mask/jquery.inputmask.date.extensions.js">
    </script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_plugins/input-mask/jquery.inputmask.extensions.js">
    </script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/moment/min/moment.min.js"></script>
    <script
        src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js">
    </script>
    <script
        src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js">
    </script>
    <script
        src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js">
    </script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_plugins/timepicker/bootstrap-timepicker.min.js">
    </script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_plugins/iCheck/icheck.min.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/js/pages/advanced-form-element.js"></script>

    <script type="text/javascript" src="<?php echo url(''); ?>/public/admin_assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="<?php echo url(''); ?>/public/admin_assets/js/jquery.meanmenu.min.js"></script>

    <script>
        $('.mean-menulist').meanmenu({
            meanMenuContainer: '.mobile-menu',

        });
    </script>

    <script>
        $("document").ready(function() {
            setTimeout(function() {
                $("div.alert").remove();
            }, 5000);

        });
        $("#dismiss").click(function() {
            $("div.alert").remove();
        });

        function message_hide(id) {
            // alert(id);
            if (id) {
                $.ajax({
                    url: "{{ route('admin.notification.update') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // console.log(response);
                        window.location.href = response;
                        // window.location.reload();
                    }
                });
            }
        }
    </script>
    <script>
        $(document).ready(function() {


            $('#staff_select').on('change', function() {
                var idCountry = this.value;
                // alert(idCountry);
                if (idCountry == "sub_admin") {
                    $('#check_dynamic').show();
                    $('#check_dynamic1').hide();
                } else if (idCountry == "staff") {
                    $('#check_dynamic').hide();
                    $('#check_dynamic1').show();
                } else {
                    $('#check_dynamic').hide();
                    $('#check_dynamic1').hide();
                }
                // $("#staff_id").html('');
                // $.ajax({
                //     url: "{{ route('task.staff.fetch') }}",
                //     type: "POST",
                //     data: {
                //         country_id: idCountry,
                //         _token: '{{ csrf_token() }}'
                //     },
                //     // success: function (result) {
                //     	// console.log(result);
                //     	// var obj = JSON.parse(result);
                //     	// console.log(obj);
                //     	// alert("hi");
                //         // $('#check_dynamic').append('<label class="form-label">User Type</label>\
                //         // 				<div class="input-group mb-3">\
                // 		// 				<select class="selectpicker form-control" id="multiple_staff" name="multiple_staff" multiple>\
                // 		// 					'+result+'\
                // 		// 				</select>\</div>');
                //         // $('#multiple_staff').selectpicker('refresh');
                //     // }
                // });
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
