<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Project extends Model
{
    use HasFactory;

    protected $table    = 'project_list';
    protected $fillable = ['name','description','status','total_days','is_renewal','start_date','end_date','bid_amount','manager_id','sales_user_id','sales_user_date','date_created', 'customer_id', 'services_id', 'added_by', 'payment_status', 'added_year', 'renew_start'];


    public function sales_user_details()
    {
        return $this->hasOne(SalesPerson::class,'id','sales_user_id');
    }

    public function added_user_details()
    {
        return $this->hasOne(User::class,'id','added_by');
    }

    public function service_details()
    {
        return $this->hasOne(Service::class,'id','services_id');
    }

    public function task_details()
    {
        return $this->hasMany(TaskStaff::class,'id','project_id');
    }

    public function project_task()
    {
        return $this->hasMany(TaskStaff::class,'project_id','id');
    }

    public function bit_amounts()
    {
        return $this->hasMany(ProjectBitAmount::class,'fld_project_id','id');
    }

    // public function totalValue()
    // {
    //     return $this->bit_amounts()->sum(DB::raw('fld_project_amount'));
    // }


}
