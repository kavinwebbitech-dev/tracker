<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAmc extends Model
{
    use HasFactory;

    protected $table    = 'tbl_amc_renewals';
    protected $fillable = ['fld_branch_id','fld_cust_id','fld_amc_dh_details','fld_amc_reg_date','fld_amc_dh_tenure','fld_amc_end_date','fld_amc_tax_rate','fld_amc_amount','fld_amc_total_amount','fld_amc_description','fld_createdon','fld_updatedon','fld_status'];

    public function payment_list()
    {
        return $this->hasMany(PaymentList::class,'fld_amc_id','id');
    }

    public function customers_list()
    {
        return $this->hasOne(Customers::class,'id','fld_cust_id');
    }

}
