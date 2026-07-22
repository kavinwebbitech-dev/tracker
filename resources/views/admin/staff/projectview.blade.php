@extends('layouts.dashboard')

@section('content')

    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h3 class="page-title">Staff Overview</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i
                                                class="mdi mdi-home-outline"></i></a></li>
                                    <li class="breadcrumb-item" aria-current="page">Staff</li>
                                    <li class="breadcrumb-item active" aria-current="page">Overview</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="team-filter-wrapper">
                        <select class="form-select premium-team-filter" id="team_filter" onchange="filterTeams()">
                            <option value="all">All Teams</option>
                            <option value="design">UI/UX Design</option>
                            <option value="frontend">Frontend Developers</option>
                            <option value="backend">Backend Architects</option>
                            <option value="testing">Testing</option>
                            <option value="sales">Sales & Marketing</option>
                        </select>
                    </div>
                </div>
            </div>

            <style>
                /* =========================================================
                                               PREMIUM STAFF OVERVIEW CARDS
                                            ========================================================= */

                /* The Card Container */
                .staff-overview-card {
                    background: #ffffff;
                    border-radius: 16px;
                    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
                    border: 1px solid #e7e7e7;
                    overflow: hidden;
                    position: relative;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                }

                .staff-overview-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 15px 35px rgba(0, 175, 239, 0.08);
                    border-color: #e0f2fe;
                }

                /* Glowing Top Accent Line */
                .staff-overview-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 5px;
                    background: linear-gradient(90deg, #00afef, #0284c7);
                }

                .staff-overview-card.theme-orange::before {
                    background: linear-gradient(90deg, #ff6b00, #ea580c);
                }

                .staff-overview-card.theme-purple::before {
                    background: linear-gradient(90deg, #a855f7, #7e22ce);
                }

                /* Card Header (Avatar + Info) */
                .staff-overview-header {
                    padding: 24px 20px;
                    display: flex;
                    align-items: center;
                    gap: 16px;
                }

                .staff-overview-avatar {
                    width: 55px;
                    height: 55px;
                    border-radius: 14px;
                    /* Squircle */
                    object-fit: cover;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
                    border: 2px solid #fff;
                    flex-shrink: 0;
                }

                .staff-overview-info {
                    flex-grow: 1;
                }

                .staff-overview-info h4 {
                    font-weight: 600;
                    color: #1e293b;
                    margin: 0 0 4px 0;
                    letter-spacing: 0.3px;
                }

                .staff-overview-info p {
                    font-weight: 600;
                    color: #64748b;
                    margin: 0;
                    display: flex;
                    align-items: center;
                    gap: 5px;
                }

                /* Controls Row (Count + Filter) */
                .staff-overview-controls {
                    background: #f8fafc;
                    padding: 12px 20px;
                    border-top: 1px solid #f1f5f9;
                    border-bottom: 1px dashed #e2e8f0;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .staff-overview-count {
                    font-weight: 700;
                    color: #475569;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                .staff-count-badge {
                    background: rgba(0, 175, 239, 0.1);
                    color: #00afef;
                    padding: 4px 10px;
                    border-radius: 8px;
                    font-weight: 700;
                }

                .theme-orange .staff-count-badge {
                    background: rgba(255, 107, 0, 0.1);
                    color: #ff6b00;
                }

                .theme-purple .staff-count-badge {
                    background: rgba(168, 85, 247, 0.1);
                    color: #a855f7;
                }

                /* Filter Select */
                .staff-filter-select {
                    background-color: #ffffff;
                    border: 1px solid #cbd5e1;
                    color: #334155;
                    font-weight: 600;
                    padding: 4px 24px 4px 10px;
                    border-radius: 6px;
                    outline: none;
                    cursor: pointer;
                    transition: all 0.2s;
                }

                .staff-filter-select:focus {
                    border-color: #00afef;
                    box-shadow: 0 0 0 3px rgba(0, 175, 239, 0.1);
                }

                /* Project List Body */
                .staff-project-list {
                    padding: 20px;
                    display: flex;
                    flex-direction: column;
                    gap: 16px;
                    flex-grow: 1;
                    max-height: 360px;
                    overflow-y: auto;
                }

                .staff-project-list::-webkit-scrollbar {
                    width: 5px;
                }

                .staff-project-list::-webkit-scrollbar-track {
                    background: #f8fafc;
                    border-radius: 10px;
                }

                .staff-project-list::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 10px;
                }

                .staff-proj-item {
                    width: 100%;
                }

                .staff-proj-head {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-end;
                    margin-bottom: 6px;
                }

                .staff-proj-title {
                    font-weight: 600;
                    color: #334155;
                    margin: 0;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    max-width: 80%;
                }

                .staff-proj-pct {
                    font-size: 0.8rem;
                    font-weight: 800;
                    color: #1e293b;
                }

                /* Custom Progress Bars */
                .staff-prog-track {
                    width: 100%;
                    height: 6px;
                    background: #e2e8f0;
                    border-radius: 10px;
                    overflow: hidden;
                }

                .staff-prog-fill {
                    height: 100%;
                    border-radius: 10px;
                    transition: width 0.8s ease-in-out;
                }

                /* Progress Colors */
                .prog-blue {
                    background: linear-gradient(90deg, #00afef, #0284c7);
                }

                .prog-green {
                    background: linear-gradient(90deg, #10b981, #059669);
                }

                .prog-orange {
                    background: linear-gradient(90deg, #ff6b00, #ea580c);
                }

                .prog-purple {
                    background: linear-gradient(90deg, #a855f7, #7e22ce);
                }

                /* Footer Action */
                .staff-overview-footer {
                    padding: 15px 20px;
                    background: #f8fafc;
                    border-top: 1px solid #f1f5f9;
                    text-align: center;
                }

                .staff-btn-view {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    color: #00afef;
                    font-size: 0.85rem;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    text-decoration: none;
                    transition: all 0.3s;
                }

                .theme-orange .staff-btn-view {
                    color: #ff6b00;
                }

                .theme-purple .staff-btn-view {
                    color: #a855f7;
                }

                .staff-btn-view:hover {
                    color: #0f172a;
                }

                .staff-btn-view i {
                    transition: transform 0.3s;
                }

                .staff-btn-view:hover i {
                    transform: translateX(4px);
                }

                /* Premium Team Filter Dropdown */
                .team-filter-wrapper {
                    position: relative;
                    display: inline-block;
                }

                /* Position the funnel icon inside the select box */

                .premium-team-filter {
                    background-color: #ffffff;
                    border: 1px solid #e2e8f0;
                    color: #334155;
                    font-weight: 600;
                    padding: 10px 35px 10px 12px;
                    border-radius: 10px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    cursor: pointer;
                }

                .premium-team-filter:hover {
                    border-color: #cbd5e1;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                }

                /* Brand Blue Focus State */
                .premium-team-filter:focus {
                    border-color: #00afef;
                    box-shadow: 0 0 0 4px rgba(0, 175, 239, 0.15);
                    outline: none;
                }

                /* Make the icon glow blue when the dropdown is clicked */
                .team-filter-wrapper:focus-within .team-filter-icon {
                    color: #00afef;
                }
            </style>

            <!-- Main content -->
            <section class="content">
                <div class="row g-4 mb-5">

                    @forelse($staff_list as $index => $member)
                        <?php
                        $themes = ['', 'theme-orange', 'theme-purple'];
                        $progClasses = ['prog-blue', 'prog-orange', 'prog-purple'];
                        $theme = $themes[$index % 3];
                        $progClass = $progClasses[$index % 3];
                        
                        // ✅ FIXED: moved teamMap INSIDE the loop
                        $teamMap = [
                            'PHP Developer' => 'backend',
                            'Senior PHP Developer' => 'backend',
                            'React Native Developer' => 'backend',
                            'Frontend Developer' => 'frontend',
                            'Front End Developer' => 'frontend',
                            'UI/UX Designer' => 'design',
                            'Creative Head' => 'design',
                            'Graphic Designer' => 'design',
                            'SEO Developer' => 'sales',
                            'Digital Marketer' => 'sales',
                            'Off Page Seo' => 'sales',
                            'Digital Marketer/Software Testing' => 'testing',
                        ];
                        $member_team = $teamMap[trim($member->role ?? '')] ?? 'other';
                        ?>

                        <div class="col-xl-4 col-lg-4 col-md-6 staff-card-col" data-team="{{ $member_team }}">
                            <div class="staff-overview-card {{ $theme }}">

                                <div class="staff-overview-header">
                                    @if ($member->profile_picture)
                                        <img src="{{ url('') }}/public/profile/{{ $member->profile_picture }}"
                                            alt="{{ $member->name }}" class="staff-overview-avatar">
                                    @elseif($member->gender == 'female')
                                        <img src="{{ url('') }}/public/admin_assets/images/avatar04.png"
                                            alt="{{ $member->name }}" class="staff-overview-avatar">
                                    @else
                                        <img src="{{ url('') }}/public/admin_assets/images/avatar2.png"
                                            alt="{{ $member->name }}" class="staff-overview-avatar">
                                    @endif
                                    <div class="staff-overview-info">
                                        <h4>{{ $member->name }}</h4>
                                        <p><i class="bi bi-person-badge"></i> {{ $member->role ?? 'Staff' }}</p>
                                    </div>
                                </div>

                                <div class="staff-overview-controls">
                                    <div class="staff-overview-count">
                                        <span class="staff-count-badge">
                                            {{ str_pad($member->task_staff->count(), 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        Projects
                                    </div>
                                    <div>
                                        <select class="form-select staff-filter-select" onchange="filterStaffStatus(this)">
                                            <option value="inprogress" selected>In Progress</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="staff-project-list">
                                    @forelse($member->task_staff as $assignment)
                                        <?php
                                        $pct = $assignment->assignment_progress_percent;
                                        $barClass = $pct >= 100 ? 'prog-green' : $progClass;
                                        $pctColorClass = $pct >= 100 ? 'text-success' : '';
                                        
                                        $status = str_replace(' ', '', strtolower(trim($assignment->status)));
                                        ?>

                                        <div class="staff-proj-item" data-status="{{ $status }}">
                                            <div class="staff-proj-head">
                                                <p class="staff-proj-title">
                                                    {{ $assignment->project_details->name ?? 'Unnamed Project' }}
                                                </p>
                                                <span
                                                    class="staff-proj-pct {{ $pctColorClass }}">{{ $pct }}%</span>
                                            </div>
                                            <div class="staff-prog-track">
                                                <div class="staff-prog-fill {{ $barClass }}"
                                                    style="width: {{ $pct }}%;"></div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted mb-0" style="font-size:0.85rem;">No projects assigned yet.</p>
                                    @endforelse
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted text-center py-5">No staff members found.</p>
                        </div>
                    @endforelse

                </div>
                <!-- /.row -->
            </section>
            <!-- /.content -->

        </div>
    </div>
    <!-- /.content-wrapper -->
    <script>
        function filterTeams() {
            var val = document.getElementById('team_filter').value;
            document.querySelectorAll('.staff-card-col').forEach(function(col) {
                var team = col.getAttribute('data-team');
                col.style.display = (val === 'all' || team === val) ? '' : 'none';
            });
        }

        function filterStaffStatus(select) {
            var status = select.value;
            var card = select.closest('.staff-overview-card');

            card.querySelectorAll('.staff-proj-item').forEach(function(item) {
                item.style.display = (item.getAttribute('data-status') === status) ? '' : 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.staff-filter-select').forEach(function(select) {
                filterStaffStatus(select); // enforce default: In Progress only, on first load
            });
        });
    </script>
@endsection
