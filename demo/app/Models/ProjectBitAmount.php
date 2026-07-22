<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBitAmount extends Model
{
    use HasFactory;

    protected $table    = 'project_bit_amount';
    protected $fillable = ['fld_project_id','fld_project_amount','fld_payment_date','fld_status'];

}
