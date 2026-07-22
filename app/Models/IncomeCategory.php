<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeCategory extends Model
{
    protected $fillable = ['name', 'status'];
    
    public function category()
    {
        return $this->belongsTo(IncomeCategory::class, 'category_id');
    }
}
