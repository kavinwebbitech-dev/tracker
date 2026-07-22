<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    public function user_details()
    {
        return $this->hasOne(User::class,'id','uploaded_by');
    }

    public function proposal_details()
    {
        return $this->hasMany(ProposalDetails::class,'proposal_id','id');
    }
}
