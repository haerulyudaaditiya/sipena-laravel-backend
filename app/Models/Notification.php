<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory, LogsActivity;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'employee_id',
        'title',
        'message',
        'status',
        'related_model',
        'related_id',
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
        // Ambil nama karyawan dari relasi.
        $employeeName = $this->employee->name ?? 'karyawan tidak dikenal';

        // Ambil judul notifikasi.
        $notificationTitle = $this->title;

        return sprintf(
            'notifikasi "%s" untuk karyawan "%s"',
            $notificationTitle,
            $employeeName
        );
    }
}
