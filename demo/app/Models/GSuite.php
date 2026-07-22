<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GSuite extends Model
{
    use HasFactory;

    protected $table    = 'tbl_guite_renewals';
    protected $fillable = ['fld_branch_id','fld_cust_id','fld_domain_name','fld_gsuite_start_date','fld_gsuite_tenure','fld_gsuite_end_date','fld_tax_percentage','fld_amount','fld_email_count','fld_total_amount','fld_description','fld_createdon', 'fld_updatedon', 'fld_status'];

    public function email_list()
    {
        return $this->hasMany(EmailList::class,'fld_gsuite_id','id');
    }

    public function customers_list()
    {
        return $this->hasOne(Customers::class,'id','fld_cust_id');
    }

}
