<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    protected $table = 'car_types';
    protected $fillable = [
        'type_name',
        'type_capacity',
        'description',
        'type_image',
    ];
}
