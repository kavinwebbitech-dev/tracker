<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailList extends Model
{
    use HasFactory;

    protected $table    = 'tbl_gsuite_email_list';
    protected $fillable = ['fld_email_input','fld_email','fld_email_date','fld_email_desc','fld_gsuite_id','fld_status'];

}
