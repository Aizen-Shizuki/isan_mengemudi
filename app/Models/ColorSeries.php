<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorSeries extends Model
{
    protected $table = 'color_series';
    protected $fillable = [
        'color_id',
        'series_id',
        'stock',
    ];
}
