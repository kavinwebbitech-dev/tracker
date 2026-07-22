@php
    use App\Models\TaskStaff;
    use App\Models\Project;

    $toasts = [];

    if (Auth::user()->user_type === 'super_admin' || Auth::user()->user_type === 'sub_admin') {
        // ── Toast type 1: staff whose logged hours exceed assigned hours ──
        // $overStaff = TaskStaff::with(['staff_details', 'project_details'])
        //     ->get()
        //     ->filter(fn($ts) => $ts->is_over_budget);
        $overStaff = TaskStaff::with(['staff_details', 'project_details'])
            ->whereNotIn('status', ['completed', 'rejected'])
            ->get()
            ->filter(fn($ts) => $ts->is_over_budget);

        foreach ($overStaff as $ts) {
            $toasts[] = [
                'type' => 'danger',
                'title' => 'Staff hours exceeded',
                'msg' =>
                    ($ts->staff_details->name ?? 'Staff') .
                    ' logged ' .
                    $ts->logged_total_hours .
                    ' hrs — ' .
                    $ts->over_budget_hours .
                    ' hrs over the ' .
                    $ts->assigned_total_hours .
                    ' hr budget.',
                'badge' => $ts->over_budget_hours . ' hrs over',
                'project' => $ts->project_details->name ?? 'Unknown Project',
            ];
        }

        // ── Toast type 2: projects where total logged > total allocated ──
        // $overProjects = Project::with('task_staff')
        //     ->where('is_renewal', 0)
        //     ->get()
        //     ->filter(fn($p) => $p->is_over_budget);

        $overProjects = Project::with([
            'task_staff' => function ($q) {
                $q->whereNotIn('status', ['completed', 'rejected']);
            },
        ])
            ->where('is_renewal', 0)
            ->get()
            ->filter(fn($p) => $p->task_staff->isNotEmpty() && $p->is_over_budget);

        foreach ($overProjects as $project) {
            $toasts[] = [
                'type' => 'warning',
                'title' => 'Project budget exceeded',
                'msg' =>
                    '<b>' .
                    e($project->name) .
                    '</b> logged ' .
                    $project->total_logged_hours .
                    '/' .
                    $project->total_allocated_hours .
                    ' hrs. Excess: ' .
                    $project->over_budget_hours .
                    ' hrs.',
                'badge' => $project->total_logged_hours . '/' . $project->total_allocated_hours . ' hrs',
                'project' => $project->name,
            ];
        }
    } else {
        // ── Staff dashboard: only show their own over-budget tasks ──
        // $myOverStaff = TaskStaff::with('project_details')
        //     ->where('staff_id', Auth::id())
        //     ->get()
        //     ->filter(fn($ts) => $ts->is_over_budget);

        $myOverStaff = TaskStaff::with('project_details')
            ->where('staff_id', Auth::id())
            ->whereNotIn('status', ['completed', 'rejected'])
            ->get()
            ->filter(fn($ts) => $ts->is_over_budget);

        foreach ($myOverStaff as $ts) {
            $toasts[] = [
                'type' => 'danger',
                'title' => 'You exceeded your hours',
                'msg' =>
                    'You logged ' .
                    $ts->logged_total_hours .
                    ' hrs — ' .
                    $ts->over_budget_hours .
                    ' hrs over your ' .
                    $ts->assigned_total_hours .
                    ' hr budget.',
                'badge' => $ts->over_budget_hours . ' hrs over',
                'project' => $ts->project_details->name ?? 'Unknown Project',
            ];
        }
    }
@endphp

@if (count($toasts))
    <style>
        .ob-toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 360px;
            max-width: calc(100vw - 40px);
        }

        .ob-toast {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            border-left: 4px solid;
            padding: 14px 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.10);
            display: flex;
            flex-direction: column;
            gap: 6px;
            animation: obSlideIn 0.35s ease;
            transition: opacity 0.25s, transform 0.25s;
        }

        .ob-toast.danger {
            border-left-color: #E24B4A;
        }

        .ob-toast.warning {
            border-left-color: #EF9F27;
        }

        .ob-toast-top {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ob-toast-icon {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .ob-toast.danger .ob-toast-icon {
            background: #FCEBEB;
            color: #E24B4A;
        }

        .ob-toast.warning .ob-toast-icon {
            background: #FAEEDA;
            color: #EF9F27;
        }

        .ob-toast-title {
            font-weight: 600;
            font-size: 13px;
            color: #1e293b;
            flex: 1;
        }

        .ob-toast-close {
            background: none;
            border: none;
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
            color: #94a3b8;
            padding: 0;
        }

        .ob-toast-close:hover {
            color: #334155;
        }

        .ob-toast-msg {
            font-size: 12px;
            color: #475569;
            line-height: 1.5;
        }

        .ob-toast-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .ob-toast-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .ob-toast.danger .ob-toast-badge {
            background: #FCEBEB;
            color: #A32D2D;
        }

        .ob-toast.warning .ob-toast-badge {
            background: #FAEEDA;
            color: #854F0B;
        }

        .ob-toast-project {
            font-size: 11px;
            color: #64748b;
        }

        .ob-toast-timer {
            height: 3px;
            background: #f1f5f9;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 2px;
        }

        .ob-toast-bar {
            height: 100%;
            border-radius: 2px;
            width: 100%;
            transition: width linear;
        }

        .ob-toast.danger .ob-toast-bar {
            background: #E24B4A;
        }

        .ob-toast.warning .ob-toast-bar {
            background: #EF9F27;
        }

        @keyframes obSlideIn {
            from {
                opacity: 0;
                transform: translateX(24px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>

    <div class="ob-toast-container" id="obToastContainer"></div>

    <script>
        (function() {
            var DURATION = 120000; // 2 minutes in ms
            var toasts = @json($toasts);

            function removeToast(el) {
                el.style.opacity = '0';
                el.style.transform = 'translateX(24px)';
                setTimeout(function() {
                    el.remove();
                }, 260);
            }

            function createToast(data, index) {
                setTimeout(function() {
                    var box = document.createElement('div');
                    box.className = 'ob-toast ' + data.type;

                    var iconClass = data.type === 'danger' ?
                        'fa fa-clock' :
                        'fa fa-chart-bar';

                    box.innerHTML =
                        '<div class="ob-toast-top">' +
                        '<div class="ob-toast-icon"><i class="' + iconClass + '"></i></div>' +
                        '<span class="ob-toast-title">' + data.title + '</span>' +
                        '<button class="ob-toast-close" aria-label="Close">&times;</button>' +
                        '</div>' +
                        '<div class="ob-toast-msg">' + data.msg + '</div>' +
                        '<div class="ob-toast-meta">' +
                        '<span class="ob-toast-badge">' + data.badge + '</span>' +
                        '<span class="ob-toast-project"><i class="fa fa-folder" style="margin-right:4px;"></i>' +
                        data.project + '</span>' +
                        '</div>' +
                        '<div class="ob-toast-timer"><div class="ob-toast-bar" id="obBar_' + index +
                        '"></div></div>';

                    document.getElementById('obToastContainer').appendChild(box);

                    // close button
                    box.querySelector('.ob-toast-close').addEventListener('click', function() {
                        removeToast(box);
                    });

                    // start timer bar drain
                    var bar = box.querySelector('.ob-toast-bar');
                    requestAnimationFrame(function() {
                        bar.style.transition = 'width ' + (DURATION / 1000) + 's linear';
                        bar.style.width = '0%';
                    });

                    // auto remove after 2 min
                    setTimeout(function() {
                        removeToast(box);
                    }, DURATION);

                }, index * 400); // stagger each toast by 400ms
            }

            toasts.forEach(function(t, i) {
                createToast(t, i);
            });
        })();
    </script>
@endif
