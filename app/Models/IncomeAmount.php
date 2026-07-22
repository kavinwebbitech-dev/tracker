<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeAmount extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(IncomeCategory::class, 'category_id');
    }

    // public function service_details()
    // {
    //     return $this->hasOne(Service::class,'id','services_id');
    // }

}
