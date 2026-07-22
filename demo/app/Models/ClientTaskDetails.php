<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientTaskDetails extends Model
{
    use HasFactory;

    public function client_comment_details()
    {
        return $this->hasMany(ClientTaskComment::class,'client_task_details_id','id')->latest();
    }

    public function client_task_details()
    {
        return $this->hasOne(ClientTask::class,'id','client_task_id');
    }

}
