<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use SoftDeletes;

    protected $table = 'cars';
    protected $fillable = [
        'car_rental_id',
        'car_type_id',
        'series_year_id',
        'car_name',
        'car_capacity',
        'stock',
        'car_image',
        'car_condition',
        'price_per_days',
        'latitude',
        'longitude',
        'is_active',
    ];
}
