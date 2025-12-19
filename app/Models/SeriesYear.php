<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeriesYear extends Model
{
    protected $table = 'series_years';
    protected $fillable = [
        'color_series_id',
        'year_id',
        'stock',
    ];
}
