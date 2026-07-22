<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo url(''); ?>/public/admin_assets/images/favicon.png">
    <title>Task Tracker</title>

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
    $role_section = json_decode(Auth::user()->permissions);
    if ($role_section) {
        $role_section = $role_section;
    } else {
        $role_section = [];
    }
    ?>
    <div class="wrapper">

        <!-- <div id="loader"></div> -->
        {{-- <div class="mobile-header mobile-view">
            <div class="container">
                <div class="row gy-3 align-items-center">
                    <div class="col-12 order-0 order-md-0 col-md-6">
                        <div class="logo text-center text-md-start">
                            <a href="{{ route('sub.admin.index') }}"><img
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
                                    <a href="{{ route('sub.admin.index') }}" class="logo"><img
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
                                                <a class=" active" href="{{ route('sub.admin.index') }}"><i
                                                        class="bi bi-grid-fill"></i>Dashboard</a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="bi bi-collection-fill"></i>Master
                                                </a>
                                                <ul>
                                                    @if (in_array('1', $role_section))
                                                        <li><a href="{{ route('sub_admin.customers.view') }}"><i
                                                                    class="bi bi-indent"></i>Customer List</a></li>
                                                    @endif
                                                    @if (in_array('5', $role_section))
                                                        <li><a href="{{ route('sub_admin.branches.view') }}"><i
                                                                    class="bi bi-indent"></i>Branches</a></li>
                                                    @endif
                                                    @if (in_array('9', $role_section))
                                                        <li><a href="#"><i class="bi bi-indent"></i>Staff</a>
                                                            <ul>
                                                                <ul>
                                                                    @if (in_array('10', $role_section))
                                                                        <li><a href="{{ route('staff.create') }}"><i
                                                                                    class="bi bi-indent"></i>Add
                                                                                Staff</a></li>
                                                                    @endif
                                                                    <li><a href="{{ route('staff.view') }}"><i
                                                                                class="bi bi-indent"></i>View Staff</a>
                                                                    </li>
                                                                </ul>
                                                            </ul>
                                                        </li>
                                                    @endif
                                                    <li><a href="#"><i class="bi bi-indent"></i>Freelancer</a>
                                                        <ul>
                                                            <ul>
                                                                <li><a href="{{ route('admin.freelancer.create') }}"><i
                                                                            class="bi bi-indent"></i>Add Freelancer</a>
                                                                </li>
                                                                <li><a href="{{ route('admin.freelancer.view') }}"><i
                                                                            class="bi bi-indent"></i>View Freelancer</a>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </li>
                                                    @if (in_array('17', $role_section))
                                                        <li><a href="{{ route('sub_admin.service.view') }}"><i
                                                                    class="bi bi-indent"></i>Leads Status</a></li>
                                                    @endif
                                                    <li><a href="{{ route('admin.service.view') }}"><i
                                                                class="bi bi-indent"></i>Service</a></li>
                                                    <li><a href="{{ route('admin.projects.employee.report') }}"><i
                                                                class="bi bi-indent"></i>Employee Project Report</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="#" role="button">
                                                    <i class="bi bi-stickies-fill"></i> Renewal(s)
                                                </a>
                                                <ul>
                                                    @if (in_array('21', $role_section))
                                                        <li><a href="{{ route('sub_admin.domain.hosting.view') }}"><i
                                                                    class="bi bi-indent"></i>Domain Hosting</a></li>
                                                    @endif
                                                    @if (in_array('29', $role_section))
                                                        <li><a href="{{ route('sub_admin.gsuide.view') }}"><i
                                                                    class="bi bi-indent"></i>Gsuite</a></li>
                                                    @endif
                                                    @if (in_array('37', $role_section))
                                                        <li><a href="{{ route('sub_admin.amc.view') }}"><i
                                                                    class="bi bi-indent"></i>AMC</a></li>
                                                    @endif
                                                    @if (in_array('26', $role_section))
                                                        <li><a
                                                                href="{{ route('sub_admin.domain.hosting.view.interest') }}"><i
                                                                    class="bi bi-indent"></i>Not Interested DH</a></li>
                                                    @endif
                                                    @if (in_array('42', $role_section))
                                                        <li><a href="{{ route('sub_admin.amc.view.interest') }}"><i
                                                                    class="bi bi-indent"></i>Not Interested AMC</a>
                                                        </li>
                                                    @endif
                                                    @if (in_array('34', $role_section))
                                                        <li><a href="{{ route('sub_admin.gsuide.view.interest') }}"><i
                                                                    class="bi bi-indent"></i>Not Interested Gsuite</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </li>
                                            @if (in_array('45', $role_section))
                                                <li>
                                                    <a href="#">
                                                        <i class="bi bi-file-earmark-text-fill"></i>Projects
                                                    </a>
                                                    <ul>
                                                        @if (in_array('46', $role_section))
                                                            <li><a href="{{ route('sub_admin.projects.create') }}"><i
                                                                        class="bi bi-indent"></i>Add Projects</a></li>
                                                        @endif
                                                        @if (in_array('45', $role_section))
                                                            <li><a href="{{ route('sub_admin.projects.view') }}"><i
                                                                        class="bi bi-indent"></i>View Projects</a></li>
                                                        @endif
                                                        @if (in_array('86', $role_section))
                                                            <li><a href="{{ route('sub_admin.follow.up.view') }}"><i
                                                                        class="bi bi-indent"></i>Follow Up</a></li>
                                                        @endif
                                                        @if (in_array('49', $role_section))
                                                            <li><a href="{{ route('sub_admin.projects.going') }}"><i
                                                                        class="bi bi-indent"></i>On Progress
                                                                    Projects</a></li>
                                                        @endif
                                                        @if (in_array('50', $role_section))
                                                            <li><a href="{{ route('sub_admin.projects.pending') }}"><i
                                                                        class="bi bi-indent"></i>Pending Projects</a>
                                                            </li>
                                                        @endif
                                                        @if (in_array('51', $role_section))
                                                            <li><a href="{{ route('sub_admin.projects.hold') }}"><i
                                                                        class="bi bi-indent"></i>On Hold Projects</a>
                                                            </li>
                                                        @endif
                                                        @if (in_array('52', $role_section))
                                                            <li><a href="{{ route('sub_admin.projects.completed') }}"><i
                                                                        class="bi bi-indent"></i>Completed Projects</a>
                                                            </li>
                                                        @endif
                                                        @if (in_array('53', $role_section))
                                                            <li><a href="{{ route('sub_admin.projects.renewal') }}"><i
                                                                        class="bi bi-indent"></i>Renewal Projects</a>
                                                            </li>
                                                        @endif
                                                        @if (in_array('67', $role_section))
                                                            <li><a href="{{ route('sub_admin.leads.from.view') }}"><i
                                                                        class="bi bi-indent"></i>Leads From</a></li>
                                                        @endif
                                                    </ul>
                                                </li>
                                            @endif
                                            <li>
                                                <a href="#">
                                                    <i class="bi bi-ubuntu"></i>Task
                                                </a>
                                                <ul>
                                                    @if (in_array('56', $role_section))
                                                        <li><a href="{{ route('sub.task.create') }}"><i
                                                                    class="bi bi-indent"></i>Add Task</a></li>
                                                    @endif
                                                    @if (in_array('55', $role_section))
                                                        <li><a href="{{ route('sub.task.view') }}"><i
                                                                    class="bi bi-indent"></i>Single Task</a></li>
                                                    @endif
                                                    @if (in_array('62', $role_section))
                                                        <li><a href="{{ route('sub.task.recurring.view') }}"><i
                                                                    class="bi bi-indent"></i>Recurring Task</a></li>
                                                    @endif
                                                    <li><a href="{{ route('sub_admin.your.task.view') }}"><i
                                                                class="bi bi-indent"></i>My Task</a></li>
                                                    @if (in_array('76', $role_section))
                                                        <li><a href="{{ route('sub_admin.follow.up.project') }}"><i
                                                                    class="bi bi-indent"></i>Follow Up Project</a></li>
                                                    @endif

                                                </ul>
                                            </li>
                                            @if (in_array('63', $role_section))
                                                <li>
                                                    <a href="#">
                                                        <i class="bi bi-ubuntu"></i>Proposal
                                                    </a>
                                                    <ul>
                                                        @if (in_array('64', $role_section))
                                                            <li><a href="{{ route('sub_admin.proposal.create') }}"><i
                                                                        class="bi bi-indent"></i>Add Proposal</a></li>
                                                        @endif
                                                        @if (in_array('63', $role_section))
                                                            <li><a href="{{ route('sub_admin.proposal.view') }}"><i
                                                                        class="bi bi-indent"></i>View Proposal</a></li>
                                                        @endif
                                                    </ul>
                                                </li>
                                            @endif
                                            <li>
                                                <a href="#">
                                                    <i class="bi bi-ubuntu"></i>Accounts
                                                </a>
                                                <ul>
                                                    <li><a href="{{ route('sub_admin.imcome.amount.view') }}"><i
                                                                class="bi bi-indent"></i>Income Amount</a></li>
                                                    <li><a href="{{ route('sub_admin.expenses.amount.view') }}"><i
                                                                class="bi bi-indent"></i>Expenses Amount</a></li>
                                                </ul>
                                            </li>
                                            @if (in_array('78', $role_section))
                                                <li>
                                                    <a href="{{ route('sub_admin.credentials.view') }}"><i
                                                            class="bi bi-key"></i>Credentials</a>
                                                </li>
                                            @endif
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
                                    <li><a class="dropdown-item"
                                            href="{{ route('sub_admin.notification.view') }}">View all</a></li>
                                </ul>
                            </div>
                            <div class="dropdown">
                                <a class="btn btn-dropdown dropdown-toggle" href="#" role="button"
                                    data-bs-offset="10,20" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('sub.admin.profile') }}">Profile</a>
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
                        </form>
                    </div>


                </div>
            </div>
        </div> --}}


        <header class="main-header web-view">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('sub.admin.index') }}"><img
                            src="<?php echo url(''); ?>/public/admin_assets/images/logo.png" alt=""></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{ route('sub.admin.index') }}"><i
                                        class="bi bi-grid-fill"></i>Dashboard</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-collection-fill"></i>Master
                                </a>
                                <ul class="dropdown-menu">
                                    @if (in_array('1', $role_section))
                                        <li><a class="dropdown-item" href="{{ route('sub_admin.customers.view') }}"><i
                                                    class="bi bi-indent"></i>Customer List</a></li>
                                    @endif
                                    @if (in_array('5', $role_section))
                                        <li><a class="dropdown-item" href="{{ route('sub_admin.branches.view') }}"><i
                                                    class="bi bi-indent"></i>Branches</a></li>
                                    @endif
                                    @if (in_array('9', $role_section))
                                        <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"
                                                href="#"><i class="bi bi-indent"></i>Staff</a>
                                            <ul class="dropdown-menu">
                                                <ul class="treeview-menu" style="display: block;">
                                                    @if (in_array('10', $role_section))
                                                        <li><a href="{{ route('staff.create') }}"
                                                                class="dropdown-item"><i class="bi bi-indent"></i>Add
                                                                Staff</a></li>
                                                    @endif
                                                    <li><a href="{{ route('staff.view') }}" class="dropdown-item"><i
                                                                class="bi bi-indent"></i>View
                                                            Staff</a></li>
                                                    <li><a href="{{ route('sub.task.group') }}" class="dropdown-item"><i
                                                                class="bi bi-indent"></i>Group
                                                            Staff</a></li>
                                                </ul>
                                            </ul>
                                        </li>
                                    @endif
                                    <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle"
                                            href="#"><i class="bi bi-indent"></i>Freelancer</a>
                                        <ul class="dropdown-menu">
                                            <ul class="treeview-menu" style="display: block;">
                                                <li><a href="{{ route('admin.freelancer.create') }}"
                                                        class="dropdown-item"><i class="bi bi-indent"></i>Add
                                                        Freelancer</a></li>
                                                <li><a href="{{ route('admin.freelancer.view') }}"
                                                        class="dropdown-item"><i class="bi bi-indent"></i>View
                                                        Freelancer</a></li>
                                            </ul>
                                        </ul>
                                    </li>
                                    @if (in_array('17', $role_section))
                                        <li><a class="dropdown-item" href="{{ route('sub_admin.service.view') }}"><i
                                                    class="bi bi-indent"></i>Service</a></li>
                                    @endif
                                    @if (in_array('67', $role_section))
                                        <li><a href="{{ route('sub_admin.leads.from.view') }}"
                                                class="dropdown-item"><i class="bi bi-indent"></i>Leads From</a></li>
                                    @endif
                                    @if (in_array('95', $role_section))
                                        <li><a href="{{ route('sub_admin.campaign.details.view') }}"
                                                class="dropdown-item"><i class="bi bi-indent"></i>Campaign</a></li>
                                    @endif
                                     @if (in_array('905', $role_section))
                                        <li><a href="{{ route('sub_admin.hosting_server.view') }}"
                                                class="dropdown-item"><i class="bi bi-indent"></i>Hosting Server</a></li>
                                    @endif
                                     @if (in_array('909', $role_section))
                                        <li><a href="{{ route('sub_admin.domain_server.view') }}"
                                                class="dropdown-item"><i class="bi bi-indent"></i>Domain_ Server</a></li>
                                    @endif
                                </ul>
                            </li>
                            <?php
                            $date = \Carbon\Carbon::now()->addDays(30);
                            $to_date = $date->format('Y-m-d');
                            
                            $domainhosting = \App\Models\DomainHosting::where('fld_status', 1)->where('fld_domain_end_date', '<', $to_date)->get();
                            
                            $domainhosting1 = \App\Models\GSuite::where('fld_status', 1)->where('fld_gsuite_end_date', '<', $to_date)->get();
                            $domainhosting2 = \App\Models\AdminAmc::where('fld_status', 1)->where('fld_amc_end_date', '<', $to_date)->get();
                            ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-ubuntu"></i>Renewal(s)
                                </a>
                                <ul class="dropdown-menu">
                                    @if (in_array('21', $role_section))
                                        <li><a href="{{ route('sub_admin.domain.hosting.view') }}"
                                                class="dropdown-item"><i class="bi bi-indent"></i>Domain Hosting <span
                                                    style="float: right;background: #00afef;width: 22px;height: 22px;padding: 2px 5px;border-radius: 50%;color: #fff;font-size: 11px;">{{ count($domainhosting) }}</span></a>
                                        </li>
                                    @endif
                                    @if (in_array('29', $role_section))
                                        <li><a href="{{ route('sub_admin.gsuide.view') }}" class="dropdown-item"><i
                                                    class="bi bi-indent"></i>Gsuite <span
                                                    style="float: right;background: #00afef;width: 22px;height: 22px;padding: 2px 8px;border-radius: 50%;color: #fff;font-size: 11px;">{{ count($domainhosting1) }}</span></a>
                                        </li>
                                    @endif
                                    @if (in_array('37', $role_section))
                                        <li><a href="{{ route('sub_admin.amc.view') }}" class="dropdown-item"><i
                                                    class="bi bi-indent"></i>AMC <span
                                                    style="float: right;background: #00afef;width: 22px;height: 22px;padding: 2px 8px;border-radius: 50%;color: #fff;font-size: 11px;">{{ count($domainhosting2) }}</span></a>
                                        </li>
                                    @endif
                                    @if (in_array('26', $role_section))
                                        <li><a href="{{ route('sub_admin.domain.hosting.view.interest') }}"
                                                class="dropdown-item"><i class="bi bi-indent"></i>Not Interested
                                                DH</a></li>
                                    @endif
                                    @if (in_array('42', $role_section))
                                        <li><a href="{{ route('sub_admin.amc.view.interest') }}"
                                                class="dropdown-item"><i class="bi bi-indent"></i>Not Interested
                                                AMC</a></li>
                                    @endif
                                    @if (in_array('34', $role_section))
                                        <li><a href="{{ route('sub_admin.gsuide.view.interest') }}"
                                                class="dropdown-item"><i class="bi bi-indent"></i>Not Interested
                                                Gsuite</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                            @if (in_array('45', $role_section))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-stickies-fill"></i> Projects
                                    </a>
                                    <ul class="dropdown-menu">
                                        @if (in_array('46', $role_section))
                                            <li><a href="{{ route('sub_admin.projects.create') }}"
                                                    class="dropdown-item"><i class="bi bi-indent"></i>Add Projects</a>
                                            </li>
                                        @endif
                                        @if (in_array('45', $role_section))
                                            <li><a href="{{ route('sub_admin.projects.view') }}"
                                                    class="dropdown-item"><i class="bi bi-indent"></i>View
                                                    Projects</a></li>
                                        @endif
                                        @if (in_array('86', $role_section))
                                            <li><a href="{{ route('sub_admin.follow.up.view') }}"
                                                    class="dropdown-item"><i class="bi bi-indent"></i>Follow Up</a>
                                            </li>
                                        @endif
                                        @if (in_array('49', $role_section))
                                            <li><a href="{{ route('sub_admin.projects.going') }}"
                                                    class="dropdown-item"><i class="bi bi-indent"></i>On Progress
                                                    Projects</a></li>
                                        @endif
                                        @if (in_array('50', $role_section))
                                            <li><a href="{{ route('sub_admin.projects.pending') }}"
                                                    class="dropdown-item"><i class="bi bi-indent"></i>Pending
                                                    Projects</a></li>
                                        @endif
                                        @if (in_array('51', $role_section))
                                            <li><a href="{{ route('sub_admin.projects.hold') }}"
                                                    class="dropdown-item"><i class="bi bi-indent"></i>On Hold
                                                    Projects</a></li>
                                        @endif
                                        @if (in_array('52', $role_section))
                                            <li><a href="{{ route('sub_admin.projects.completed') }}"
                                                    class="dropdown-item"><i class="bi bi-indent"></i>Completed
                                                    Projects</a></li>
                                        @endif
                                        @if (in_array('53', $role_section))
                                            <li><a href="{{ route('sub_admin.projects.renewal') }}"
                                                    class="dropdown-item"><i class="bi bi-indent"></i>Renewal
                                                    Projects</a></li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-file-earmark-text-fill"></i>Task
                                </a>
                                <ul class="dropdown-menu">
                                    @if (in_array('56', $role_section))
                                        <li><a class="dropdown-item" href="{{ route('sub.task.create') }}"><i
                                                    class="bi bi-indent"></i>Add Task</a></li>
                                    @endif
                                    @if (in_array('55', $role_section))
                                        <li><a class="dropdown-item" href="{{ route('sub.task.view') }}"><i
                                                    class="bi bi-indent"></i>Single Task</a></li>
                                    @endif

                                    @if (in_array('62', $role_section))
                                        <li><a class="dropdown-item" href="{{ route('sub.task.recurring.view') }}"><i
                                                    class="bi bi-indent"></i>Recurring Task</a></li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('sub_admin.your.task.view') }}"><i
                                                class="bi bi-indent"></i>My Task</a></li>
                                    @if (in_array('551', $role_section))
                                        <li><a class="dropdown-item"
                                                href="{{ route('sub_admin.simpletask.view') }}"><i
                                                    class="bi bi-indent"></i>Simple Task</a></li>
                                    @endif
                                    @if (in_array('76', $role_section))
                                        <li><a class="dropdown-item"
                                                href="{{ route('sub_admin.follow.up.project') }}"><i
                                                    class="bi bi-indent"></i>Follow Up's</a></li>
                                    @endif

                                </ul>
                            </li>
                            @if (in_array('63', $role_section))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-ubuntu"></i>Proposal
                                    </a>
                                    <ul class="dropdown-menu">
                                        @if (in_array('64', $role_section))
                                            <li><a class="dropdown-item"
                                                    href="{{ route('sub_admin.proposal.create') }}"><i
                                                        class="bi bi-indent"></i>Add Proposal</a></li>
                                        @endif
                                        @if (in_array('63', $role_section))
                                            <li><a class="dropdown-item"
                                                    href="{{ route('sub_admin.proposal.view') }}"><i
                                                        class="bi bi-indent"></i>View Proposal</a></li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if (in_array('102', $role_section) || in_array('106', $role_section) || in_array('98', $role_section))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-currency-rupee"></i>Accounts
                                    </a>
                                    <ul class="dropdown-menu">
                                        @if (in_array('102', $role_section))
                                            <li><a class="dropdown-item"
                                                    href="{{ route('sub_admin.imcome.amount.view') }}"><i
                                                        class="bi bi-indent"></i>Income Amount</a></li>
                                        @endif
                                        @if (in_array('106', $role_section))
                                            <li><a class="dropdown-item"
                                                    href="{{ route('sub_admin.expenses.amount.view') }}"><i
                                                        class="bi bi-indent"></i>Expenses Amount</a></li>
                                        @endif
                                        @if (in_array('98', $role_section))
                                            <li><a class="dropdown-item"
                                                    href="{{ route('sub_admin.my.bills.view') }}"><i
                                                        class="bi bi-indent"></i>My Bills</a></li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if (in_array('78', $role_section))
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page"
                                        href="{{ route('sub_admin.credentials.view') }}"><i
                                            class="bi bi-key"></i>Credentials</a>
                                </li>
                            @endif

                            @if (in_array('901', $role_section))
                                <li class="nav-item"><a class="nav-link" aria-current="page"
                                        href="{{ route('sub_admin.leads.whatsapp.view') }}"><i
                                            class="bi bi-whatsapp"></i>Leads Whatsapp</a></li>
                            @endif
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
                                    <li><a class="dropdown-item"
                                            href="{{ route('sub_admin.notification.view') }}">View all</a></li>
                                </ul>
                            </div>
                            <div class="dropdown">
                                <a class="btn btn-dropdown dropdown-toggle" href="#" role="button"
                                    data-bs-offset="10,20" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('sub.admin.profile') }}">Profile</a>
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

    <script src="<?php echo url(''); ?>/public/admin_assets/js/vendors.min.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/js/pages/chat-popup.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/icons/feather-icons/feather.min.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/jquery-knob/js/jquery.knob.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/raphael/raphael.min.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/datatable/datatables.min.js"></script>

    <!-- Etikto Admin App -->
    <script src="<?php echo url(''); ?>/public/admin_assets/js/template.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/js/pages/dashboard.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/icons/feather-icons/feather.min.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/js/pages/data-table.js"></script>
    <script src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_components/ckeditor/ckeditor.js"></script>
    <!-- <script src="//cdn.ckeditor.com/4.5.6/standard/ckeditor.js"></script> -->
    <!-- <script
        src="<?php echo url(''); ?>/public/admin_assets/assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js">
    </script> -->
    <script src="<?php echo url(''); ?>/public/admin_assets/js/pages/editor.js"></script>


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

    <!-- <script type="text/javascript" src="<?php echo url(''); ?>/public/admin_assets/js/bootstrap.bundle.min.js"></script> -->
    <script type="text/javascript" src="<?php echo url(''); ?>/public/admin_assets/js/jquery.meanmenu.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        // ckeditor.replace('editor1');
    </script>
    <script>
        function message_hide(id) {
            // alert(id);
            if (id) {
                $.ajax({
                    url: "{{ route('sub_admin.notification.update') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // console.log(response);
                        window.location.href = response;
                    }
                });
            }
        }

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
</body>

</html>
