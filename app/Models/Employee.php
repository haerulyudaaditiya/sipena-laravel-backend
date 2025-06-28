<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, LogsActivity;

    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'npwp',
        'position',
        'status',
        'hire_date',
        'phone',
        'address',
        'department',
        'photo',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }

   public function getUsedAndPendingLeaveDays(): int
    {
        return $this->leaveRequests()
            // Perubahan utama ada di sini:
            ->whereIn('status', ['approved', 'pending']) 
            ->where('type', 'annual')
            ->whereYear('start_date', now()->year)
            ->get()
            ->sum(function ($leave) {
                // Hitung durasi dalam hari untuk setiap pengajuan
                return \Carbon\Carbon::parse($leave->start_date)
                    ->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
            });
    }

    /**
     * Pastikan Anda memiliki relasi ini ke LeaveRequest
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
