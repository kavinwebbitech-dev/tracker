<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    public function admin_details()
    {
        return $this->hasOne(User::class,'id','admin_id');
    }

    public function staff_details()
    {
        return $this->hasOne(User::class,'id','staff_id');
    }

    public function assign_details()
    {
        return $this->hasOne(User::class,'id','assign_by');
    }

    public function project_details()
    {
        return $this->hasOne(Project::class,'id','project_id');
    }

    public function task_details()
    {
        return $this->hasMany(TaskDetails::class,'task_id','id');
    }

    public function task_staff()
    {
        return $this->hasMany(TaskStaff::class,'task_id','id');
    }

    public function project_follow_up_details()
    {
        return $this->hasOne(User::class,'id','project_follow_up');
    }

    public function payment_follow_up_details()
    {
        return $this->hasOne(User::class,'id','payment_follow_up');
    }

}
