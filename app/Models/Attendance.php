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

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
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

    public function getLogSubjectDescription(): string
    {
        // Pastikan relasi employee sudah di-load untuk menghindari query tambahan jika memungkinkan.
        $employeeName = $this->employee->name ?? 'karyawan tidak dikenal';

        // Format tanggal absensi agar mudah dibaca.
        // Asumsi 'check_in' selalu ada saat record dibuat.
        $date = $this->check_in->format('d F Y');

        return sprintf(
            'data absensi untuk karyawan "%s" pada tanggal %s',
            $employeeName,
            $date
        );
    }
}
