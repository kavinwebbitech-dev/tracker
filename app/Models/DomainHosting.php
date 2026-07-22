<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainHosting extends Model
{
    use HasFactory;

    protected $table    = 'tbl_domain_hosting_renewals';
    protected $fillable = ['fld_branch_id', 'fld_cust_id','ccname','cemail','cname', 'fld_domain_name', 'fld_domain_start_date', 'fld_domain_tenure', 'fld_expiry_domain_tenure', 'fld_domain_end_date', 'fld_hosting_name', 'fld_hosting_start_date', 'fld_hosting_tenure', 'fld_expiry_hosting_tenure','fld_hosting_end_date', 'fld_tax_percentage', 'fld_amount', 'fld_total_amount', 'fld_description', 'fld_createdon', 'fld_updatedon', 'fld_status'];


    public function customers_list()
    {
        return $this->hasOne(Customers::class, 'id', 'fld_cust_id');
    }
}
