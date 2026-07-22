@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="m-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">{{ $columnTitle }} — All Projects</h4>
                <span class="text-muted small">
                    Filter: {{ ucfirst($category) }} &bull; {{ ucfirst($time) }} &bull; {{ $allProjects->count() }} results
                </span>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">S.No</th>
                                <th width="35%">Project Name</th>
                                <th width="15%">Category</th>
                                <th width="15%">Hours (Logged/Allocated)</th>
                                <th width="20%">Progress</th>
                                <th width="10%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allProjects as $i => $project)
                                <?php
                                $pct = $project->progress_percent ?? 0;
                                $isOver = $project->is_over_budget ?? false;
                                $isCompleted = $pct >= 100;
                                ?>
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $project->name }}</td>
                                    <td class="text-capitalize">{{ $project->service_category }}</td>
                                    <td>{{ $project->total_logged_hours }}/{{ $project->total_allocated_hours }} hrs</td>
                                    <td>
                                        <div class="progress" style="height:8px;">
                                            <div class="progress-bar {{ $isOver ? 'bg-danger' : ($isCompleted ? 'bg-success' : 'bg-info') }}"
                                                 style="width:{{ $pct }}%"></div>
                                        </div>
                                        <span class="small">{{ $pct }}%</span>
                                    </td>
                                    <td>
                                        @if ($isCompleted)
                                            <span class="badge bg-success">Completed</span>
                                        @elseif ($isOver)
                                            <span class="badge bg-danger">Over Budget</span>
                                        @else
                                            <span class="badge bg-secondary">In Progress</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No projects found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection