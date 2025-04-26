<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class CommitteeMember extends Model
{
    use HasApiTokens;

    protected $fillable = [
        'user_id',
        'committee_id',
        'payment_method'
    ];
}
