<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequest extends Model
{
    use HasFactory, LogsActivity;

    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'employee_id',
        'type',
        'start_date',
        'end_date',
        'reason',
        'status',
        'contact',
        'rejection_reason',
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

        static::deleting(function ($leaveRequest) {
            // Hapus semua notifikasi yang terkait dengan pengajuan cuti ini.
            // Ia akan mencari di tabel notifications di mana 'related_model' dan 'related_id' cocok.
            Notification::where('related_model', self::class)
                        ->where('related_id', $leaveRequest->id)
                        ->delete();
        });
    }
}
