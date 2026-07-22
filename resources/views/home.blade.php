@extends('layouts.dashboard')

@section('content')
    @include('partials.over_budget_toasts')
    <div class="content-wrapper">
        <div class="container-full">

            <style>
                /* =============================================
                                                                               PROJECT COLUMN CARDS
                                                                            ============================================= */
                .project-card {
                    background: #fff;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                    height: 100%;
                    border: 1px solid #e9e9e9;
                }

                .project-header {
                    padding: 18px 10px;
                    color: white;
                    font-weight: 600;
                    font-size: 1rem;
                    letter-spacing: 0.5px;
                    flex-wrap: wrap;
                    gap: 8px;
                }

                .bg-gradient-web {
                    background: linear-gradient(90deg, #1A73E8, #4ECDC4);
                }

                .bg-gradient-logo {
                    background: linear-gradient(90deg, #9C27B0, #FF6B6B, #FFA07A);
                }

                .bg-gradient-app {
                    background: linear-gradient(90deg, #00B4DB, #0083B0, #4ECDC4);
                }

                .bg-gradient-over {
                    background: linear-gradient(90deg, #e24b4a, #ff8c00);
                }

                /* =============================================
                                                                               TABLE
                                                                            ============================================= */
                .table-custom th {
                    font-size: 0.9rem;
                    color: #6c757d;
                    border-bottom: 2px solid #f3f4f6;
                    padding: 11px 16px !important;
                    font-weight: 600;
                }

                .table-custom> :not(:last-child)> :last-child>* {
                    border-bottom: 1px solid #ececec;
                }

                .table-custom td {
                    padding: 10px 16px !important;
                    vertical-align: middle;
                    font-size: 0.9rem;
                    font-weight: 500;
                    border-bottom: 1px solid #f8f9fa;
                }

                .prj-name {
                    max-width: 0;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }

                .task-icon {
                    width: 26px;
                    height: 26px;
                    border-radius: 6px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 8px;
                    color: white;
                    font-size: 0.8rem;
                    flex-shrink: 0;
                }

                /* =============================================
                                                                               PROGRESS BARS
                                                                            ============================================= */
                .progress-wrapper {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                .progress-slim {
                    height: 7px;
                    border-radius: 10px;
                    flex-grow: 1;
                    background-color: #e9ecef;
                    overflow: hidden;
                }

                .progress-text {
                    font-size: 0.85rem;
                    font-weight: 600;
                    width: 38px;
                    text-align: right;
                    flex-shrink: 0;
                }

                .bg-web-progress {
                    background: linear-gradient(90deg, #1A73E8, #4ECDC4);
                }

                .bg-logo-progress {
                    background: linear-gradient(90deg, #9C27B0, #FF6B6B);
                }

                .bg-over-progress {
                    background: linear-gradient(90deg, #e24b4a, #ff8c00);
                }

                /* scrollable table body */
                .project-table-wrap {
                    max-height: 340px;
                    overflow-y: auto;
                }

                .project-table-wrap::-webkit-scrollbar {
                    width: 4px;
                }

                .project-table-wrap::-webkit-scrollbar-track {
                    background: #f8fafc;
                }

                .project-table-wrap::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 4px;
                }

                /* over-budget inline badge */
                .badge-over {
                    display: inline-block;
                    background: #fee2e2;
                    color: #dc2626;
                    font-size: 0.72rem;
                    font-weight: 600;
                    padding: 2px 7px;
                    border-radius: 4px;
                    margin-left: 6px;
                    vertical-align: middle;
                }

                /* =============================================
                                                                               DARK CARD & TOP FILTER (Right Sidebar)
                                                                            ============================================= */
                .dark-card {
                    background: #000;
                    padding: 20px;
                    border-radius: 14px;
                }

                .custom-dark-input {
                    background-color: #2a2d3e;
                    border: 1px solid #363a4f;
                    color: #fff;
                    font-size: 15px;
                    padding: 8px 12px;
                    border-radius: 6px;
                }

                .custom-dark-input:focus {
                    background-color: #2a2d3e;
                    border-color: #4ECDC4;
                    color: #fff;
                    box-shadow: none;
                    outline: none;
                }

                .custom-dark-input::-webkit-calendar-picker-indicator {
                    filter: invert(1);
                    opacity: 0.7;
                    cursor: pointer;
                }

                /* keep option text readable */
                .custom-dark-input option {
                    background: #2a2d3e;
                    color: #fff;
                }

                .search-btn {
                    background: linear-gradient(90deg, #9C27B0, #FF6B6B, #FFA07A);
                    border: none;
                    color: white;
                    font-size: 15px;
                    font-weight: 600;
                    padding: 8px 12px;
                    border-radius: 6px;
                    width: 100%;
                    cursor: pointer;
                    transition: opacity 0.2s;
                }

                .search-btn:hover {
                    opacity: 0.88;
                    color: white;
                }

                /* =============================================
                                                                               STAT CARDS (Right Sidebar)
                                                                            ============================================= */
                .unique-stat-card {
                    border-radius: 16px;
                    padding: 22px;
                    position: relative;
                    overflow: hidden;
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                    color: #fff;
                }

                .unique-stat-card:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
                }

                .unique-stat-card::before {
                    content: '';
                    position: absolute;
                    left: 0;
                    top: 0;
                    height: 100%;
                    width: 5px;
                }

                .card-today {
                    background: rgb(36 36 36);
                    border: 1px solid rgba(255, 255, 255, 0.05);
                }

                .card-today::before {
                    background: linear-gradient(180deg, #FF6B6B, #9C27B0);
                }

                .card-month {
                    background: #004859;
                    border: 1px solid rgba(255, 255, 255, 0.05);
                }

                .card-month::before {
                    background: linear-gradient(180deg, #4ECDC4, #1A73E8);
                }

                .stat-main {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 12px;
                    padding-bottom: 12px;
                    border-bottom: 1px dashed rgba(255, 255, 255, 0.15);
                }

                .stat-main-title {
                    color: #ffffff;
                    font-size: 0.8rem;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    font-weight: 700;
                    margin-bottom: 6px;
                }

                .stat-main-value {
                    font-size: 1.9rem;
                    font-weight: 800;
                    margin: 0;
                    line-height: 1.1;
                }

                .btn-reveal {
                    background: rgba(255, 255, 255, 0.1);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    color: #fff;
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    flex-shrink: 0;
                }

                .btn-reveal:hover {
                    background: #fff;
                    color: #1e2130;
                    box-shadow: 0 0 15px rgba(255, 255, 255, 0.4);
                }

                .stat-sub-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 12px;
                }

                .stat-sub-item {
                    background: rgba(0, 0, 0, 0.25);
                    padding: 12px;
                    border-radius: 10px;
                    border: 1px solid rgba(255, 255, 255, 0.03);
                }

                .stat-sub-title {
                    color: #e4e4e4;
                    font-size: 12px;
                    font-weight: 500;
                    margin-bottom: 4px;
                }

                .stat-sub-value {
                    font-size: 1.1rem;
                    font-weight: 700;
                }

                /* =============================================
                                                                               HEADER FILTER SELECT
                                                                            ============================================= */
                .header-filter-select {
                    background-color: rgba(255, 255, 255, 0.22);
                    color: #ffffff;
                    border: 1px solid rgba(255, 255, 255, 0.4);
                    border-radius: 6px;
                    padding: 5px 4px;
                    font-size: 13px;
                    font-weight: 600;
                    outline: none;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }

                .header-filter-select:hover,
                .header-filter-select:focus {
                    background-color: rgba(255, 255, 255, 0.35);
                    border-color: #ffffff;
                }

                .header-filter-select option {
                    color: #1e2130;
                    background-color: #ffffff;
                    font-weight: 500;
                }

                /* =============================================
                                                                               STAFF ROUTING BANNER
                                                                            ============================================= */
                .staff-routing-banner {
                    background: linear-gradient(145deg, #1e2130 0%, #25283b 100%);
                    border: 1px solid rgba(0, 175, 239, 0.15);
                    border-radius: 16px;
                    padding: 20px 28px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    position: relative;
                    overflow: hidden;
                    gap: 20px;
                    flex-wrap: wrap;
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }

                .staff-routing-banner::before {
                    content: '';
                    position: absolute;
                    top: -50px;
                    left: -50px;
                    width: 200px;
                    height: 200px;
                    background: #00afef;
                    filter: blur(90px);
                    opacity: 0.15;
                    z-index: 0;
                    pointer-events: none;
                }

                .banner-content {
                    display: flex;
                    align-items: center;
                    gap: 20px;
                    z-index: 1;
                }

                .banner-icon {
                    width: 60px;
                    height: 60px;
                    background: rgba(0, 175, 239, 0.1);
                    color: #00afef;
                    border-radius: 14px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.8rem;
                    border: 1px solid rgba(0, 175, 239, 0.25);
                    flex-shrink: 0;
                }

                .banner-text h3 {
                    color: #ffffff;
                    margin: 0 0 5px 0;
                    font-size: 1.4rem;
                    font-weight: 600;
                }

                .banner-text p {
                    color: #a0a6c2;
                    margin: 0;
                    font-size: 0.9rem;
                }

                .btn-routing-cta {
                    z-index: 1;
                    background: linear-gradient(90deg, #00afef, #0284c7);
                    color: #ffffff !important;
                    padding: 14px 28px;
                    border-radius: 12px;
                    font-weight: 600;
                    font-size: 1rem;
                    text-transform: uppercase;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 10px;
                    transition: all 0.3s ease;
                    white-space: nowrap;
                }

                .btn-routing-cta:hover {
                    transform: translateY(-2px);
                    background: linear-gradient(90deg, #0284c7, #00afef);
                }

                .btn-routing-cta i {
                    font-size: 1.2rem;
                    transition: transform 0.3s ease;
                }

                .btn-routing-cta:hover i {
                    transform: translateX(5px);
                }

                @media (max-width: 768px) {
                    .staff-routing-banner {
                        flex-direction: column;
                        align-items: flex-start;
                    }

                    .btn-routing-cta {
                        width: 100%;
                        justify-content: center;
                    }
                }

                /* =============================================
                                                                               ANNIVERSARY WIDGET
                                                                            ============================================= */
                .anni-widget {
                    background: #ffffff;
                    border-radius: 16px;
                    padding: 22px 24px;
                    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
                    border: 1px solid #e2e8f0;
                    display: flex;
                    flex-direction: column;
                }

                .anni-widget-title {
                    font-size: 1.2rem;
                    font-weight: 600;
                    color: #0f172a;
                    margin-bottom: 16px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    border-bottom: 2px solid #f1f5f9;
                    padding-bottom: 12px;
                }

                .anni-list-container {
                    display: flex;
                    gap: 10px;
                    flex-wrap: wrap;
                    max-height: 380px;
                    overflow-y: auto;
                    padding-right: 4px;
                }

                .anni-list-container::-webkit-scrollbar {
                    width: 5px;
                }

                .anni-list-container::-webkit-scrollbar-track {
                    background: #f8fafc;
                    border-radius: 10px;
                }

                .anni-list-container::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 10px;
                }

                .premium-anni-card {
                    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
                    border: 1px solid #e2e8f0;
                    border-left: 5px solid #f59e0b;
                    border-radius: 10px;
                    padding: 12px 16px;
                    display: flex;
                    align-items: center;
                    gap: 14px;
                    transition: all 0.3s ease;
                    width: calc(33.33% - 8px);
                }

                @media (max-width: 992px) {
                    .premium-anni-card {
                        width: calc(50% - 6px);
                    }
                }

                @media (max-width: 576px) {
                    .premium-anni-card {
                        width: 100%;
                    }
                }

                .premium-anni-card:hover {
                    border-left-color: #ea580c;
                    box-shadow: 0 6px 15px rgba(245, 158, 11, 0.15);
                    transform: translateX(3px);
                }

                .premium-anni-avatar {
                    width: 54px;
                    height: 54px;
                    border-radius: 12px;
                    object-fit: cover;
                    border: 2px solid #ffffff;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                    flex-shrink: 0;
                }

                .premium-anni-info {
                    flex-grow: 1;
                }

                .premium-anni-name {
                    font-size: 1rem;
                    font-weight: 600;
                    color: #1e293b;
                    margin: 0 0 3px 0;
                    line-height: 1.2;
                }

                .premium-anni-date {
                    font-size: 0.82rem;
                    color: #475569;
                    font-weight: 600;
                    display: flex;
                    align-items: center;
                    gap: 5px;
                    margin-bottom: 6px;
                }

                .premium-anni-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                    font-size: 0.78rem;
                    font-weight: 600;
                    padding: 4px 10px;
                    border-radius: 5px;
                    width: fit-content;
                }

                .badge-upcoming {
                    background: #fef3c7;
                    color: #d97706;
                    border: 1px solid #fde68a;
                }

                .badge-today {
                    background: linear-gradient(135deg, #f59e0b, #ea580c);
                    color: #ffffff;
                    box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
                    animation: gentlePulse 2s infinite;
                }

                @keyframes gentlePulse {
                    0% {
                        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4);
                    }

                    70% {
                        box-shadow: 0 0 0 7px rgba(245, 158, 11, 0);
                    }

                    100% {
                        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
                    }
                }

                /* =============================================
                                                                               APEXCHARTS
                                                                            ============================================= */
                .apexcharts-tooltip {
                    background: #ffffff !important;
                    border: none !important;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15) !important;
                    border-radius: 6px !important;
                    padding: 4px 10px !important;
                    color: #1e2130 !important;
                    font-weight: 700 !important;
                    font-size: 0.85rem !important;
                }

                .apexcharts-tooltip-title,
                .apexcharts-tooltip-marker {
                    display: none !important;
                }

                .apexcharts-tooltip-text-y-value {
                    margin-left: 0 !important;
                }

                .apexcharts-tooltip-series-group {
                    padding: 0 !important;
                    justify-content: center !important;
                }

                .badge-hours {
                    display: inline-block;
                    background: #f1f5f9;
                    color: #475569;
                    font-size: 0.72rem;
                    font-weight: 600;
                    padding: 2px 7px;
                    border-radius: 4px;
                    margin-left: 6px;
                    vertical-align: middle;
                }

                .badge-completed {
                    display: inline-block;
                    background: #dcfce7;
                    color: #16a34a;
                    font-size: 0.72rem;
                    font-weight: 600;
                    padding: 2px 7px;
                    border-radius: 4px;
                    margin-left: 6px;
                    vertical-align: middle;
                }
            </style>

            <section class="m-3">

                <!-- =============================================
                                                                                 MAIN ROW — PROJECT CARDS (left 9) + SIDEBAR (right 3)
                                                                            ============================================= -->
                <div class="row g-4">

                    <!-- LEFT: Project Hours Budget as styled cards -->
                    <div class="col-lg-9">
                        <div class="row g-4">

                            <?php
                            $all_projects = $projects_progress ?? collect();
                            $all_projects = $all_projects->reject(function ($project) {
                                return ($project->progress_percent ?? 0) >= 100;
                            });
                            $web_projects = $all_projects->whereIn('service_category', ['web', 'digital'])->values();
                            $logo_projects = $all_projects->where('service_category', 'logo')->values();
                            $web_total_count = $web_projects->count();
                            $logo_total_count = $logo_projects->count();
                            ?>
                            <!-- WEB / LEFT PROJECT COLUMN -->
                            <div class="col-md-6">
                                <div class="project-card">
                                    <div
                                        class="project-header bg-gradient-web d-flex justify-content-between align-items-center">
                                        <span>WEB DEVELOPMENT & DIGITAL MARKETING</span>
                                        <div class="d-flex gap-2">
                                            <select class="header-filter-select" id="web_service_filter"
                                                onchange="filterProjects('web')">
                                                <option value="all">Choose Service</option>
                                                <option value="web">Web Development</option>
                                                <option value="digital">Digital Marketing</option>
                                            </select>
                                            <select class="header-filter-select" id="web_time_filter"
                                                onchange="filterProjects('web')">
                                                <option value="recent" selected>Recent Task</option>
                                                <option value="all">All Tasks</option>
                                                <option value="old">Old Task</option>
                                                <option value="month">This Month</option>
                                                <option value="lmonth">Last Month</option>
                                                <option value="alltaskcomplted">Completed Task</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="project-table-wrap">
                                        <table class="table table-borderless table-custom mb-0">
                                            <thead>
                                                <tr>
                                                    <th width="8%">S.No</th>
                                                    <th width="47%">Project Name</th>
                                                    <th width="45%">Progress (%)</th>
                                                </tr>
                                            </thead>
                                            <tbody id="web_tbody">
                                                @forelse($web_projects as $i => $project)
                                                    <?php
                                                    $pct = $project->progress_percent ?? 0;
                                                    $isOver = $project->is_over_budget ?? false;
                                                    // $barClass = $isOver ? 'bg-over-progress' : 'bg-web-progress';
                                                    $isCompleted = $pct >= 100;
                                                    $barClass = $isOver ? 'bg-over-progress' : ($isCompleted ? 'bg-success' : 'bg-web-progress');
                                                    $icons = ['fa-globe', 'fa-code', 'fa-laptop', 'fa-cloud', 'fa-window-maximize', 'fa-sitemap'];
                                                    $colors = ['bg-primary', 'bg-info', 'bg-dark', 'bg-secondary', 'bg-success', 'bg-warning'];
                                                    $ic = $icons[$i % count($icons)];
                                                    $cl = $colors[$i % count($colors)];
                                                    $raw_date = $project->date_created ?? $project->created_at;
                                                    $iso_date = $raw_date ? \Carbon\Carbon::parse($raw_date)->toIso8601String() : '';
                                                    $hideStyle = $i >= 10 ? 'display:none;' : '';
                                                    ?>
                                                    <tr data-date="{{ $iso_date }}"
                                                        data-category="{{ $project->service_category }}"
                                                        data-completed="{{ $isCompleted ? 1 : 0 }}"style="{{ $hideStyle }}">
                                                        <td>{{ $i + 1 }}.</td>
                                                        <td class="prj-name">
                                                            <span class="task-icon {{ $cl }}"><i
                                                                    class="fas {{ $ic }}"></i></span>
                                                            {{ $project->name }}
                                                            <span
                                                                class="badge-hours">{{ $project->total_logged_hours }}/{{ $project->total_allocated_hours }}
                                                                hrs</span>
                                                            @if ($isOver)
                                                                <span class="badge-over">Over</span>
                                                            @elseif ($isCompleted)
                                                                <span class="badge-completed">Completed</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="progress-wrapper">
                                                                <div class="progress progress-slim">
                                                                    <div class="progress-bar {{ $barClass }}"
                                                                        style="width:{{ $pct }}%"></div>
                                                                </div>
                                                                <span class="progress-text">{{ $pct }}%</span>
                                                            </div>
                                                            @if ($isOver)
                                                                <div style="font-size:11px;color:#dc2626;margin-top:3px;">
                                                                    {{ $project->total_logged_hours }}/{{ $project->total_allocated_hours }}
                                                                    hrs
                                                                    ({{ $project->over_budget_hours }} over)
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted py-4">No projects
                                                            found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="text-center py-2 border-top" id="web_view_all_wrap"
                                            style="display:none;">
                                            <a href="#" id="web_view_all_link" target="_blank" rel="noopener"
                                                class="small fw-semibold text-primary text-decoration-none">
                                                View All Projects <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- LOGO / RIGHT PROJECT COLUMN -->
                            <div class="col-md-6">
                                <div class="project-card">
                                    <div
                                        class="project-header bg-gradient-logo d-flex justify-content-between align-items-center">
                                        <span>LOGO DESIGN PROJECTS</span>
                                        <div class="d-flex gap-2">
                                            <select class="header-filter-select" id="logo_service_filter"
                                                onchange="filterProjects('logo')">
                                                <option value="all">Choose Service</option>
                                                <option value="logo">Logo Design</option>
                                            </select>
                                            <select class="header-filter-select" id="logo_time_filter"
                                                onchange="filterProjects('logo')">
                                                <option value="recent" selected>Recent Task</option>
                                                <option value="all">All Tasks</option>
                                                <option value="old">Old Task</option>
                                                <option value="month">This Month</option>
                                                <option value="lmonth">Last Month</option>
                                                <option value="alltaskcomplted">Completed Task</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="project-table-wrap">
                                        <table class="table table-borderless table-custom mb-0">
                                            <thead>
                                                <tr>
                                                    <th width="8%">S.No</th>
                                                    <th width="47%">Project Name</th>
                                                    <th width="45%">Progress (%)</th>
                                                </tr>
                                            </thead>
                                            <tbody id="logo_tbody">
                                                @forelse($logo_projects as $j => $project)
                                                    <?php
                                                    $pct = $project->progress_percent ?? 0;
                                                    $isOver = $project->is_over_budget ?? false;
                                                    // $barClass = $isOver ? 'bg-over-progress' : 'bg-logo-progress';
                                                    $isCompleted = $pct >= 100;
                                                    $barClass = $isOver ? 'bg-over-progress' : ($isCompleted ? 'bg-success' : 'bg-logo-progress');
                                                    $icons2 = ['fa-paint-brush', 'fa-star', 'fa-bolt', 'fa-leaf', 'fa-gem', 'fa-mountain'];
                                                    $colors2 = ['bg-dark', 'bg-warning', 'bg-danger', 'bg-success', 'bg-info', 'bg-secondary'];
                                                    $ic2 = $icons2[$j % count($icons2)];
                                                    $cl2 = $colors2[$j % count($colors2)];
                                                    $raw_date = $project->date_created ?? $project->created_at;
                                                    $iso_date = $raw_date ? \Carbon\Carbon::parse($raw_date)->toIso8601String() : '';
                                                    $hideStyle = $j >= 10 ? 'display:none;' : '';
                                                    ?>
                                                    <tr data-date="{{ $iso_date }}"
                                                        data-category="{{ $project->service_category }}"
                                                        data-completed="{{ $isCompleted ? 1 : 0 }}"
                                                        style="{{ $hideStyle }}">
                                                        <td>{{ $j + 1 }}.</td>
                                                        <td class="prj-name">
                                                            <span class="task-icon {{ $cl2 }}"><i
                                                                    class="fas {{ $ic2 }}"></i></span>
                                                            {{ $project->name }}
                                                            @if ($isOver)
                                                                <span class="badge-over">Over</span>
                                                            @elseif ($isCompleted)
                                                                <span class="badge-completed">Completed</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="progress-wrapper">
                                                                <div class="progress progress-slim">
                                                                    <div class="progress-bar {{ $barClass }}"
                                                                        style="width:{{ $pct }}%"></div>
                                                                </div>
                                                                <span class="progress-text">{{ $pct }}%</span>
                                                            </div>
                                                            @if ($isOver)
                                                                <div style="font-size:11px;color:#dc2626;margin-top:3px;">
                                                                    {{ $project->total_logged_hours }}/{{ $project->total_allocated_hours }}
                                                                    hrs
                                                                    ({{ $project->over_budget_hours }} over)
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted py-4">No projects
                                                            found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="text-center py-2 border-top" id="logo_view_all_wrap"
                                            style="display:none;">
                                            <a href="#" id="logo_view_all_link" target="_blank" rel="noopener"
                                                class="small fw-semibold text-primary text-decoration-none">
                                                View All Projects <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- STAFF BANNER -->
                            <div class="col-md-12">
                                <div class="staff-routing-banner">
                                    <div class="banner-content">
                                        <div class="banner-icon">
                                            <i class="bi bi-people-fill"></i>
                                        </div>
                                        <div class="banner-text">
                                            <h3>Staff Task Overview</h3>
                                            <p>Manage, track, and review all active project assignments and deadlines across
                                                your entire team.</p>
                                        </div>
                                    </div>
                                    <div class="banner-action">
                                        <a href="{{ route('sub.staff.project.view') }}" class="btn-routing-cta">
                                            View All Assignments <i class="bi bi-arrow-right-circle-fill"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- ANNIVERSARIES -->
                            <div class="col-md-12">
                                <div class="anni-widget">
                                    <h4 class="anni-widget-title">
                                        <i class="bi bi-award-fill text-warning fs-4"></i> Upcoming Staff Anniversaries
                                    </h4>
                                    <div class="anni-list-container">
                                        @foreach ($year_calculate as $key => $value)
                                            <?php
                                            $user_Details = \App\Models\User::where('id', $key)->first();
                                            $check_date = $value['next_anniversary'];
                                            
                                            if ($check_date) {
                                                $toDate = \Carbon\Carbon::parse($check_date);
                                                $add_days = (clone $toDate)->addYear();
                                                $today_date_c = \Carbon\Carbon::now();
                                            
                                                $different_date = $add_days > $today_date_c ? $add_days->diffInDays($today_date_c) : '';
                                            
                                                $day1 = \Carbon\Carbon::parse($value['next_anniversary']);
                                                $daysDifference = $day1->diffInDays($today_date_c);
                                                $hireDate = \Carbon\Carbon::parse($user_Details->join_date);
                                                $yearsDifference = $hireDate->diffInYears($today_date_c);
                                            }
                                            ?>
                                            @if (($daysDifference && $daysDifference <= 30) || $daysDifference == '364')
                                                <div class="premium-anni-card">
                                                    @if ($user_Details->profile_picture)
                                                        <img class="premium-anni-avatar"
                                                            src="{{ url('') }}/public/profile/{{ $user_Details->profile_picture }}"
                                                            alt="User">
                                                    @elseif($user_Details->gender == 'female')
                                                        <img class="premium-anni-avatar"
                                                            src="{{ url('') }}/public/admin_assets/images/avatar04.png"
                                                            alt="User">
                                                    @else
                                                        <img class="premium-anni-avatar"
                                                            src="{{ url('') }}/public/admin_assets/images/avatar2.png"
                                                            alt="User">
                                                    @endif

                                                    <div class="premium-anni-info">
                                                        <h5 class="premium-anni-name">{{ $value['name'] }}</h5>
                                                        @if ($daysDifference == '364')
                                                            <div class="premium-anni-date">
                                                                <i class="bi bi-calendar-check text-success"></i>
                                                                {{ date('d-m-Y') }}
                                                            </div>
                                                            <span class="premium-anni-badge badge-today">
                                                                <i class="bi bi-stars"></i> Happy {{ $yearsDifference }}
                                                                Year!
                                                            </span>
                                                        @else
                                                            <div class="premium-anni-date">
                                                                <i class="bi bi-calendar-event"></i>
                                                                {{ date('d-m-Y', strtotime($value['next_anniversary'])) }}
                                                            </div>
                                                            <span class="premium-anni-badge badge-upcoming">
                                                                <i class="bi bi-hourglass-split"></i>
                                                                {{ $daysDifference }} days to go &bull;
                                                                {{ $yearsDifference + 1 }} Year Anniversary
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>{{-- /inner row --}}
                    </div>{{-- /col-lg-9 --}}

                    <!-- RIGHT SIDEBAR -->
                    <div class="col-lg-3">
                        <div class="row g-4">

                            <!-- TOP FILTER -->
                            <div class="col-12">
                                <div class="dark-card">
                                    <h6 class="text-white mb-3">
                                        TOP FILTER : <span class="text-capitalize" id="filter_label">
                                            @if ($start_date && $end_date)
                                                {{ date('d-m-Y', strtotime($start_date)) }} –
                                                {{ date('d-m-Y', strtotime($end_date)) }}
                                            @elseif($start_date)
                                                {{ date('d-m-Y', strtotime($start_date)) }}
                                            @else
                                                This Month
                                            @endif
                                        </span>
                                    </h6>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input style="background: #19222b !important; color: #fff !important;"
                                                type="date" class="form-control custom-dark-input" id="start_date"
                                                name="start_date" value="{{ $start_date ?? '' }}"
                                                onfocus="'showPicker' in this && this.showPicker()">
                                        </div>
                                        <div class="col-6">
                                            <input style="background: #19222b !important; color: #fff !important;"
                                                type="date" class="form-control custom-dark-input" id="end_date"
                                                name="end_date" value="{{ $end_date ?? '' }}"
                                                onfocus="'showPicker' in this && this.showPicker()">
                                        </div>
                                        {{-- <div class="col-8">
                                            <select class="form-select custom-dark-input" id="salesperson"
                                                name="salesperson">
                                                <option value="">Select Sales Person</option>
                                                @if (!empty($salesperson))
                                                    @foreach ($salesperson as $person)
                                                        @if ($person->added_by)
                                                            <?php $sp_user = \App\Models\User::find($person->added_by); ?>
                                                            @if ($sp_user)
                                                                <option value="{{ $sp_user->id }}"
                                                                    {{ $salesperson1 == $sp_user->id ? 'selected' : '' }}>
                                                                    {{ $sp_user->name }}
                                                                </option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div> --}}
                                        <div class="col-8">
                                            <select class="form-select custom-dark-input" id="salesperson"
                                                name="salesperson">
                                                <option value="">Select Sales Person</option>

                                                @foreach ($salesperson as $person)
                                                    <option value="{{ $person->id }}"
                                                        {{ $salesperson1 == $person->id ? 'selected' : '' }}>
                                                        {{ $person->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <button type="button" class="search-btn"
                                                onclick="sort_book()">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TODAY ORDER VALUE -->
                            <?php
                            $today_order_amount = 0;
                            $today_receive_amount = 0;
                            $today_cancel_amount = 0;
                            $month_order_amount = 0;
                            $month_receive_amount = 0;
                            $month_cancel_amount = 0;
                            
                            if (!empty($today_project)) {
                                foreach ($today_project as $v) {
                                    $today_order_amount += (int) ($v->bid_amount ?? 0);
                                    $tb = \App\Models\ProjectBitAmount::where('fld_project_id', $v->id)->get();
                                    foreach ($tb as $b) {
                                        $today_receive_amount += $b->fld_project_amount;
                                    }
                                }
                            }
                            if (!empty($today_cancel_project)) {
                                foreach ($today_cancel_project as $v) {
                                    $tb = \App\Models\ProjectBitAmount::where('fld_project_id', $v->id)->get();
                                    foreach ($tb as $b) {
                                        $today_cancel_amount += $b->fld_project_amount;
                                    }
                                }
                            }
                            if (!empty($month_project)) {
                                foreach ($month_project as $v) {
                                    $month_order_amount += (int) ($v->bid_amount ?? 0);
                                    $mb = \App\Models\ProjectBitAmount::where('fld_project_id', $v->id)->get();
                                    foreach ($mb as $b) {
                                        $month_receive_amount += $b->fld_project_amount;
                                    }
                                }
                            }
                            if (!empty($month_cancel_project)) {
                                foreach ($month_cancel_project as $v) {
                                    $mb = \App\Models\ProjectBitAmount::where('fld_project_id', $v->id)->get();
                                    foreach ($mb as $b) {
                                        $month_cancel_amount += $b->fld_project_amount;
                                    }
                                }
                            }
                            ?>

                            <div class="col-12">
                                <div class="unique-stat-card card-today">
                                    <div class="stat-main">
                                        <div>
                                            <div class="stat-main-title">
                                                Today Order Value
                                                <span style="font-size:0.72rem;opacity:0.8;">— {{ date('d-m-Y') }}</span>
                                            </div>
                                            <div class="stat-main-value">₹{{ number_format($today_order_amount, 2) }}
                                            </div>
                                        </div>
                                        <div class="btn-reveal" onclick="OrderAmount('{{ $today_date }}')">
                                            <i class="far fa-eye"></i>
                                        </div>
                                    </div>
                                    <div class="stat-sub-grid">
                                        <div class="stat-sub-item">
                                            <div class="stat-sub-title">Received Amount</div>
                                            <div class="stat-sub-value text-success">
                                                ₹{{ number_format($today_receive_amount, 2) }}</div>
                                        </div>
                                        <div class="stat-sub-item">
                                            <div class="stat-sub-title">Cancel Amount</div>
                                            <div class="stat-sub-value text-danger">
                                                ₹{{ number_format($today_cancel_amount, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- THIS MONTH / CUSTOM RANGE ORDER VALUE -->
                            <div class="col-12">
                                <div class="unique-stat-card card-month">
                                    <div class="stat-main">
                                        <div>
                                            @if ($start_date && $end_date)
                                                <div class="stat-main-title">{{ date('d-m-Y', strtotime($start_date)) }} —
                                                    {{ date('d-m-Y', strtotime($end_date)) }}</div>
                                            @elseif($start_date)
                                                <div class="stat-main-title">{{ date('d-m-Y', strtotime($start_date)) }}
                                                    Order Value</div>
                                            @else
                                                <div class="stat-main-title">This Month Order Value</div>
                                            @endif
                                            <div class="stat-main-value">₹{{ number_format($month_order_amount, 2) }}
                                            </div>
                                        </div>
                                        <div class="btn-reveal"
                                            onclick="TotalOrderAmount('{{ $this_month }}', '{{ $this_year }}')">
                                            <i class="far fa-eye"></i>
                                        </div>
                                    </div>
                                    <div class="stat-sub-grid">
                                        <div class="stat-sub-item">
                                            <div class="stat-sub-title">Received Amount</div>
                                            <div class="stat-sub-value text-success">
                                                ₹{{ number_format($month_receive_amount, 2) }}</div>
                                        </div>
                                        <div class="stat-sub-item">
                                            <div class="stat-sub-title">Cancel Amount</div>
                                            <div class="stat-sub-value text-danger">
                                                ₹{{ number_format($month_cancel_amount, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- /sidebar row --}}
                    </div>{{-- /col-lg-3 --}}

                </div>{{-- /main row --}}

            </section>
        </div>
    </div>

    <!-- Today Order Details Modal -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Today Order Details</h4>
                    <span class="btn-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body" id="load_html"></div>
            </div>
        </div>
    </div>

    <?php $current_route = Route::currentRouteName(); ?>

    <script>
        /* ---- Sort / Filter sidebar ---- */
        function sort_book() {
            var url = "{{ route($current_route) }}";
            var start_date = $("#start_date").val() || "";
            var end_date = $("#end_date").val() || "";
            var salesperson = $("#salesperson").val() || "";
            window.location.href = url + '?start_date=' + start_date +
                '&end_date=' + end_date +
                '&salesperson=' + salesperson;
        }

        /* ---- Today order detail modal ---- */
        function OrderAmount(ref) {
            $.ajax({
                url: '{{ route('super.admin.project.details') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: ref
                },
                success: function(response) {
                    $("#load_html").html(response.html);
                    $('#modal-default').modal('show');
                }
            });
        }

        /* ---- Month total redirect ---- */
        function TotalOrderAmount(month, year) {
            window.location.href = "{{ url('/') }}/super-admin/this-month-projects/" + month + '/' + year;
        }

        /* ---- Project card column filter (client-side row hide) ---- */
        // function filterProjects(col) {
        //     var timeVal = $("#" + col + "_time_filter").val();
        //     var now = new Date();
        //     var startOfThisMonth = new Date(now.getFullYear(), now.getMonth(), 1);
        //     var startOfLastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        //     var endOfLastMonth = new Date(now.getFullYear(), now.getMonth(), 0);
        //     var thirtyDaysAgo = new Date(now);
        //     thirtyDaysAgo.setDate(now.getDate() - 30);

        //     $("#" + col + "_tbody tr").each(function() {
        //         var dateStr = $(this).attr('data-date');
        //         if (!dateStr) {
        //             $(this).toggle(timeVal === 'all');
        //             return;
        //         }

        //         var rowDate = new Date(dateStr);
        //         var show = true;

        //         if (timeVal === 'recent') {
        //             show = rowDate >= thirtyDaysAgo;
        //         } else if (timeVal === 'old') {
        //             show = rowDate < thirtyDaysAgo;
        //         } else if (timeVal === 'month') {
        //             show = rowDate >= startOfThisMonth;
        //         } else if (timeVal === 'lmonth') {
        //             show = rowDate >= startOfLastMonth && rowDate <= endOfLastMonth;
        //         }

        //         $(this).toggle(show);
        //     });
        // }
        // function filterProjects(col) {
        //     var categoryVal = $("#" + col + "_service_filter").val();
        //     var timeVal = $("#" + col + "_time_filter").val();

        //     var now = new Date();
        //     var startOfThisMonth = new Date(now.getFullYear(), now.getMonth(), 1);
        //     var startOfLastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        //     var endOfLastMonth = new Date(now.getFullYear(), now.getMonth(), 0);
        //     var thirtyDaysAgo = new Date(now);
        //     thirtyDaysAgo.setDate(now.getDate() - 30);

        //     $("#" + col + "_tbody tr").each(function() {
        //         var dateStr = $(this).attr('data-date');
        //         var rowCategory = $(this).attr('data-category');

        //         var categoryMatch = (categoryVal === 'all' || rowCategory === categoryVal);

        //         var timeMatch = true;
        //         if (dateStr) {
        //             var rowDate = new Date(dateStr);
        //             if (timeVal === 'recent') {
        //                 timeMatch = rowDate >= thirtyDaysAgo;
        //             } else if (timeVal === 'old') {
        //                 timeMatch = rowDate < thirtyDaysAgo;
        //             } else if (timeVal === 'month') {
        //                 timeMatch = rowDate >= startOfThisMonth;
        //             } else if (timeVal === 'lmonth') {
        //                 timeMatch = rowDate >= startOfLastMonth && rowDate <= endOfLastMonth;
        //             }
        //         } else if (timeVal !== 'all') {
        //             timeMatch = false;
        //         }

        //         $(this).toggle(categoryMatch && timeMatch);
        //     });
        // }

        // // Apply default "Recent Task" filter on load for both columns
        // $(document).ready(function() {
        //     filterProjects('web');
        //     filterProjects('logo');
        // });

        function filterProjects(col) {
            var categoryVal = $("#" + col + "_service_filter").val();
            var timeVal = $("#" + col + "_time_filter").val();
            var maxShow = 10;

            var now = new Date();
            var startOfThisMonth = new Date(now.getFullYear(), now.getMonth(), 1);
            var startOfLastMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            var endOfLastMonth = new Date(now.getFullYear(), now.getMonth(), 0);
            var thirtyDaysAgo = new Date(now);
            thirtyDaysAgo.setDate(now.getDate() - 30);

            var visibleCount = 0;
            var totalMatched = 0;

            $("#" + col + "_tbody tr").each(function() {
                var dateStr = $(this).attr('data-date');
                var rowCategory = $(this).attr('data-category');
                var isCompleted = $(this).attr('data-completed') === '1';

                var categoryMatch = (categoryVal === 'all' || rowCategory === categoryVal);
                var timeMatch = true;

                if (timeVal === 'alltaskcomplted') {
                    timeMatch = isCompleted;
                } else {
                    if (isCompleted) {
                        timeMatch = false;
                    } else if (dateStr) {
                        var rowDate = new Date(dateStr);
                        if (timeVal === 'recent') {
                            timeMatch = rowDate >= thirtyDaysAgo;
                        } else if (timeVal === 'old') {
                            timeMatch = rowDate < thirtyDaysAgo;
                        } else if (timeVal === 'month') {
                            timeMatch = rowDate >= startOfThisMonth;
                        } else if (timeVal === 'lmonth') {
                            timeMatch = rowDate >= startOfLastMonth && rowDate <= endOfLastMonth;
                        }
                    } else if (timeVal !== 'all') {
                        timeMatch = false;
                    }
                }

                var isMatch = categoryMatch && timeMatch;

                if (isMatch) {
                    totalMatched++;
                    if (visibleCount < maxShow) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                } else {
                    $(this).hide();
                }
            });

            if (totalMatched > maxShow) {
                $("#" + col + "_view_all_wrap").show();
                $("#" + col + "_view_all_link").attr(
                    'href',
                    "{{ url('/super-admin/all-projects') }}/" + col + "?category=" + categoryVal + "&time=" + timeVal
                );
            } else {
                $("#" + col + "_view_all_wrap").hide();
            }
        }

        function initDefaultFilters() {
            $("#web_time_filter").val('recent');
            $("#web_service_filter").val('all');
            $("#logo_time_filter").val('recent');
            $("#logo_service_filter").val('all');

            filterProjects('web');
            filterProjects('logo');
        }

        $(document).ready(function() {
            initDefaultFilters();
        });

        $(window).on('load', function() {
            initDefaultFilters();
        });

        setTimeout(function() {
            initDefaultFilters();
        }, 300);
    </script>
@endsection
