<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskFollowComment extends Model
{
    use HasFactory;

    public function follower_details()
    {
        return $this->hasOne(User::class,'id','follower_id');
    }

    public function staff_details()
    {
        return $this->hasOne(User::class,'id','staff_id');
    }

    public function project_details()
    {
        return $this->hasOne(Project::class,'id','project_id');
    }

    public function task_details()
    {
        return $this->hasOne(Task::class,'id','task_id');
    }

}
