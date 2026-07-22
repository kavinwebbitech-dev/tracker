<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public function receiver_details()
    {
        return $this->hasOne(User::class,'id','receiver_id');
    }

    public function task_details()
    {
        return $this->hasOne(Task::class,'id','task_id');
    }

}
