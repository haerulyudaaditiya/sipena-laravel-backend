<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Trait untuk secara otomatis mencatat aktivitas (Create, Update, Delete) pada sebuah model.
 * Log yang dihasilkan lebih simpel dan fokus pada tindakan utama.
 * Versi ini lebih fleksibel dan bisa disesuaikan per model.
 */
trait LogsActivity
{
    /**
     * Boot the trait untuk mengaitkan event Eloquent.
     */
    protected static function bootLogsActivity()
    {
        // Event untuk data BARU DIBUAT
        static::created(function (Model $model) {
            static::logActivity($model, 'membuat');
        });

        // Event untuk data DIHAPUS
        static::deleted(function (Model $model) {
            static::logActivity($model, 'menghapus');
        });

        // Event untuk data DIUBAH (disederhanakan)
        static::updated(function (Model $model) {
            static::logActivity($model, 'memperbarui');
        });
    }

    /**
     * Fungsi utama untuk mencatat log ke database.
     *
     * @param Model $model Model yang sedang diproses.
     * @param string $action Deskripsi aksi (cth: 'membuat', 'memperbarui', 'menghapus').
     */
    protected static function logActivity(Model $model, string $action)
    {
        if (!Auth::check()) {
            return;
        }
        
        // Dapatkan deskripsi subjek dari metode custom di model, atau gunakan fallback.
        $subject = static::getLogSubject($model);

        // Contoh: "Admin telah membuat data Gaji bulan Juni untuk Budi Santoso"
        $description = sprintf(
            '%s telah %s %s',
            Auth::user()->name,
            $action,
            $subject
        );

        ActivityLog::create([
            'user_id'       => Auth::id(),
            'activity'      => $description,
            'activity_date' => now(),
        ]);
    }
    
    /**
     * Mendapatkan deskripsi subjek log.
     * Prioritas:
     * 1. Metode getLogSubjectDescription() pada model.
     * 2. Kolom 'name' atau 'title' pada model.
     * 3. Nama model dan ID-nya.
     *
     * @param Model $model
     * @return string
     */
    protected static function getLogSubject(Model $model): string
    {
        // OPSI 1: Jika model memiliki metode khusus untuk deskripsi log
        if (method_exists($model, 'getLogSubjectDescription')) {
            return $model->getLogSubjectDescription();
        }

        $modelName = strtolower(class_basename($model));
        
        // OPSI 2 (Fallback): Gunakan nama/judul jika ada
        $identifiableName = $model->name ?? $model->title ?? null;
        if ($identifiableName) {
            return "data {$modelName} \"{$identifiableName}\"";
        }
        
        // OPSI 3 (Fallback Terakhir): Gunakan ID
        return "data {$modelName} dengan ID {$model->id}";
    }
}
