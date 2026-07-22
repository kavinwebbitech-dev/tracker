<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentList extends Model
{
    use HasFactory;

    protected $table    = 'tbl_amc_payments';
    protected $fillable = ['fld_amc_id','fld_amc_payment_input','fld_amc_payment_amount','fld_amc_payment_date','fld_amc_payment_desc','fld_status'];
}
