<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Committee extends Model
{
    use HasApiTokens;
    
    protected $fillable = [
        'owner_id',
        'committee_name',
        'committee_code',
        'committee_type',
        'start_date',
        'end_date',
        'no_of_members',
        'draw_frequency',
        'payment_amount',
        'payment_method',
    ];

    public function members(){
        return $this->belongsToMany(User::class, 'committee_members');
    }
}
