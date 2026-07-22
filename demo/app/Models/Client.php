<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    public function service_details()
    {
        return $this->hasOne(Service::class,'id','cat_id');
    }

}
