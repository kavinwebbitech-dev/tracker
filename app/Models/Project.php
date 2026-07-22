<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Service;
use DB;

class Project extends Model
{
    use HasFactory;

    protected $table    = 'project_list';
    protected $fillable = [
        'name',
        'description',
        'status',
        'total_days',
        'is_renewal',
        'start_date',
        'end_date',
        'bid_amount',
        'manager_id',
        'sales_user_id',
        'sales_user_date',
        'date_created',
        'customer_id',
        'services_id',
        'added_by',
        'payment_status',
        'added_year',
        'renew_start',
        'frontend_hours',
        'backend_hours',
        'seo_hours',
        'testing_hours',
        'designer_hours',
        'assigned_staff'
    ];
    // add colunm all hours
    protected $casts = [
        'assigned_staff' => 'array'
    ];

    public function sales_user_details()
    {
        return $this->hasOne(SalesPerson::class, 'id', 'sales_user_id');
    }

    public function added_user_details()
    {
        return $this->hasOne(User::class, 'id', 'added_by');
    }

    public function service_details()
    {
        return $this->hasOne(Service::class, 'id', 'services_id');
    }

    public function task_details()
    {
        return $this->hasMany(TaskStaff::class, 'id', 'project_id');
    }

    public function bit_amounts()
    {
        return $this->hasMany(ProjectBitAmount::class, 'fld_project_id', 'id');
    }

    protected static $hourColumns = [
        'frontend_hours',
        'backend_hours',
        'seo_hours',
        'testing_hours',
        'designer_hours',
    ];


    public function tasks()
    {
        return $this->hasMany(\App\Models\Task::class, 'project_id');
    }

    public function task_staff()
    {
        return $this->hasMany(\App\Models\TaskStaff::class, 'project_id');
    }

    protected static $validHourColumns = null;

    public function getTotalAllocatedHoursAttribute()
    {
        if (static::$validHourColumns === null) {
            static::$validHourColumns = array_filter(static::$hourColumns, function ($col) {
                return \Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), $col);
            });
        }

        $total = 0;
        foreach (static::$validHourColumns as $col) {
            $total += (float) ($this->{$col} ?? 0);
        }
        return $total;
    }

    public function getTotalLoggedHoursAttribute()
    {
        return (float) $this->task_staff->sum(function ($ts) {
            return $ts->logged_total_hours;
        });
    }

    public function getIsOverBudgetAttribute()
    {
        return $this->total_allocated_hours > 0
            && $this->total_logged_hours > $this->total_allocated_hours;
    }

    public function getOverBudgetHoursAttribute()
    {
        return max(0, round($this->total_logged_hours - $this->total_allocated_hours, 2));
    }

    public function getAllTasksCompletedAttribute()
    {
        return $this->tasks->count() > 0
            && $this->tasks->where('status', '!=', 'completed')->count() == 0;
    }

    public function getProgressPercentAttribute()
    {
        if ($this->all_tasks_completed) {
            return 100;
        }

        $allocated = $this->total_allocated_hours;
        if ($allocated <= 0) {
            return 0;
        }

        $raw = ($this->total_logged_hours / $allocated) * 100;
        return min((int) round($raw), 98);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'services_id', 'id');
    }
}