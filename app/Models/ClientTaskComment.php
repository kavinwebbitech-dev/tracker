<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientTaskComment extends Model
{
    use HasFactory;

    public function user_details()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
