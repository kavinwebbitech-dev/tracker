<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $table    = 'tbl_customers';
    protected $fillable = ['fld_name','fld_email','fld_phone','fld_address','fld_company_name','fld_customer_gstno','fld_status'];

}
