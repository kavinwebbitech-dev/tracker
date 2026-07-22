<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPerson extends Model
{
    use HasFactory;

    protected $table    = 'sales_users';
    protected $fillable = ['firstname','lastname','email','password','phone','type','profile','color_code','date_created'];

}
