<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStaff extends Model
{
    use HasFactory;

    public function staff_details()
    {
        return $this->hasOne(User::class, 'id', 'staff_id');
    }

    public function task_details()
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }

    public function project_details()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function task_comments()
    {
        return $this->hasMany(TaskComment::class, 'task_id', 'task_id')->where('is_delete', 1);
    }
    protected static $loggedHoursMap = null;

    public static function loadLoggedHoursMap()
    {
        if (static::$loggedHoursMap === null) {
            static::$loggedHoursMap = \App\Models\TaskComment::selectRaw('task_id, staff_id, SUM(working_hours) as total_hours')
                ->groupBy('task_id', 'staff_id')
                ->get()
                ->keyBy(function ($row) {
                    return $row->task_id . '-' . $row->staff_id;
                });
        }
        return static::$loggedHoursMap;
    }

    public function getAssignedTotalHoursAttribute()
    {
        return ($this->assigned_frontend_hours ?? 0)
            + ($this->assigned_backend_hours ?? 0)
            + ($this->assigned_seo_hours ?? 0)
            + ($this->assigned_testing_hours ?? 0)
            + ($this->assigned_designer_hours ?? 0);
    }

    public function getLoggedTotalHoursAttribute()
    {
        $map = static::loadLoggedHoursMap();
        $key = $this->task_id . '-' . $this->staff_id;
        return isset($map[$key]) ? (float) $map[$key]->total_hours : 0;
    }

    public function getIsOverBudgetAttribute()
    {
        return $this->assigned_total_hours > 0
            && $this->logged_total_hours > $this->assigned_total_hours;
    }

    public function getOverBudgetHoursAttribute()
    {
        return max(0, round($this->logged_total_hours - $this->assigned_total_hours, 2));
    }
    public function getAssignmentProgressPercentAttribute()
    {
        if (($this->status ?? '') === 'completed') {
            return 100;
        }

        $allocated = $this->assigned_total_hours;
        if ($allocated <= 0) {
            return 0;
        }

        $raw = ($this->logged_total_hours / $allocated) * 100;
        return min((int) round($raw), 98);
    }
}