<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $table = 'charges';
    protected $fillable = [
        'returning_id',
        'charge_name',
        'description',
        'image',
        'additional_price',
    ];
}
