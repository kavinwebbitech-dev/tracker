<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function project_details()
    {
        return $this->hasMany(Project::class,'services_id','id')->where('status', 1);
    }

}
