<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait.
     */
    protected static function bootLogsActivity()
    {
        // Event untuk data BARU DIBUAT
        static::created(function (Model $model) {
            self::logActivity($model, 'dibuat');
        });

        // Event untuk data DIHAPUS
        static::deleted(function (Model $model) {
            self::logActivity($model, 'dihapus');
        });

        // DIUBAH: Event 'updated' kini memiliki logika yang lebih canggih
        static::updated(function (Model $model) {
            $changes = [];
            
            // Loop melalui setiap atribut yang berubah
            foreach ($model->getDirty() as $attribute => $newValue) {
                // Jangan catat perubahan pada kolom 'updated_at'
                if ($attribute === 'updated_at') {
                    continue;
                }

                $originalValue = $model->getOriginal($attribute);
                // Tambahkan detail perubahan ke dalam array
                $changes[] = "kolom '{$attribute}' dari '{$originalValue}' menjadi '{$newValue}'";
            }

            // Hanya buat log jika ada perubahan yang signifikan
            if (count($changes) > 0) {
                $description = "memperbarui " . implode(', ', $changes);
                self::logActivity($model, $description);
            }
        });
    }

    /**
     * Fungsi untuk mencatat log ke database.
     *
     * @param Model $model Model yang sedang diproses
     * @param string $action Deskripsi aksi yang dilakukan
     */
    protected static function logActivity(Model $model, string $action)
    {
        if (!Auth::check()) {
            return;
        }

        ActivityLog::create([
            'user_id'       => Auth::id(),
            'activity'      => self::getActivityDescription($model, $action),
            'activity_date' => now(),
        ]);
    }

    /**
     * Membuat deskripsi log yang lengkap dan informatif.
     *
     * @param Model $model
     * @param string $action
     * @return string
     */
    protected static function getActivityDescription(Model $model, string $action): string
    {
        $modelName = strtolower(class_basename($model));
        $userName = Auth::user()->name ?? 'Sistem';
        
        $identifiableName = $model->name ?? $model->title ?? $model->id;

        // Contoh: "Admin telah memperbarui data karyawan: John Doe. Mengubah kolom 'status' dari 'active' ke 'inactive'"
        return "{$userName} telah {$action} pada data {$modelName} '{$identifiableName}'.";
    }
}
