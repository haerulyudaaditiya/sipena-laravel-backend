<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\LogsActivity;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, LogsActivity;

    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
        'fcm_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function tokens()
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }
}
