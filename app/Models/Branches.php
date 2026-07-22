<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{
    use HasFactory;

    protected $table    = 'tbl_branch_list';
    protected $fillable = ['fld_branch_name','fld_status'];

}
