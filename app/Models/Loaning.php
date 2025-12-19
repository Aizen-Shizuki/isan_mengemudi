<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loaning extends Model
{
    protected $table = 'loanings';
    protected $fillable = [
        'car_id',
        'user_id',
        'tenant_ktp',
        'loan_date',
        'loan_time',
        'return_date_plan',
        'return_time_plan',
        'status',
        'car_condition',
    ];
}
