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
        'late_tolerance',
        'annual_leave_quota',
    ];

    protected $casts = [
        'presence_radius' => 'integer',
        'late_tolerance' => 'integer',
        'annual_leave_quota' => 'integer',
    ];
}
