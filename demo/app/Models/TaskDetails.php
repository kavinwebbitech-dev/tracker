<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDetails extends Model
{
    use HasFactory;

    public function task_main()
    {
        return $this->hasOne(Task::class,'id','task_id');
    }
}
