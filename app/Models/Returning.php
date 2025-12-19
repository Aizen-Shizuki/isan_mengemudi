<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Returning extends Model
{
    protected $table = 'returnings';
    protected $fillable = [
        'loaning_id',
        'return_date',
        'return_time',
        'proof_of_return',
        'car_condition',
    ];
}
