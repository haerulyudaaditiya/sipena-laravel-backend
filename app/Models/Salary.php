<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory, LogsActivity;

    public $incrementing = false;
    protected $keyType = 'uuid';
    protected $fillable = [
        'employee_id',
        'basic_salary',
        'bonus',
        'deductions',
        'allowances',
        'salary_date',
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
