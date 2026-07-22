<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostingServer extends Model
{
    protected $table = 'hosting_server';

    protected $fillable = [
        'fld_hosting_name',
        'fld_status',
    ];
}