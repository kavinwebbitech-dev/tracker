<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientTask extends Model
{
    use HasFactory;

    public function client_task_details()
    {
        return $this->hasMany(ClientTaskDetails::class,'client_task_id','id');
    }

    public function client_task_approv()
    {
        return $this->hasMany(ClientTaskDetails::class,'client_task_id','id')->where('status', 'Approved');
    }

    public function user_details()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

}
