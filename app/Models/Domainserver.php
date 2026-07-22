<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainServer extends Model
{
    protected $table = 'domain_server';

    protected $fillable = [
        'fld_domain_server_name',
        'fld_status',
    ];
}