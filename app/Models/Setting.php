<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'office_latitude',
        'office_longitude',
        'presence_radius',
        'check_in_time',
        'check_out_time',
        'check_in_start_margin',
        'annual_leave_quota',
    ];

    protected $casts = [
        'presence_radius' => 'integer',
        'check_in_start_margin' => 'integer',
        'annual_leave_quota' => 'integer',
    ];
}
