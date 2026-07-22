<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use HasFactory;

    public function task_details()
    {
        return $this->hasOne(Task::class,'id','task_id');
    }

    public function user_details()
    {
        return $this->hasOne(User::class,'id','staff_id');
    }
    
}
