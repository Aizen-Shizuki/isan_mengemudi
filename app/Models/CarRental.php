<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarRental extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'car_rentals';
    protected $fillable = [
        'user_id',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'car_rental_name',
        'description',
        'full_address',
        'image',
        'phone_number',
        'npwp',
        'npwp_image',
        'latitude',
        'longitude',
        'open_days',
        'open_time',
        'average_rating'
    ];
}
