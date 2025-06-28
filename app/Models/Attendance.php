<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory, LogsActivity;

    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'employee_id',
        'check_in',
        'check_out',
        'check_in_location',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_photo_url',
        'check_out_location',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_photo_url',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}
