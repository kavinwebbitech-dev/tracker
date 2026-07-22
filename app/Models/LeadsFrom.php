<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadsFrom extends Model
{
    use HasFactory;

    public function service_get()
    {
        return $this->hasOne(Service::class, 'id', 'service');
    }

    public function user_details()
{
    return $this->belongsTo(\App\Models\User::class, 'added_by');
}

    public function lead_status()
    {
        return $this->hasOne(LeadStatus::class, 'id', 'status');
    }
}
