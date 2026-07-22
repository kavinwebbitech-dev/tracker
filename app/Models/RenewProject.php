<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewProject extends Model
{
    use HasFactory;

    protected $table    = 'renewal_details';
    protected $fillable = ['project_id','start_date','end_date','alert_day','renewal_days','amount'];

}
