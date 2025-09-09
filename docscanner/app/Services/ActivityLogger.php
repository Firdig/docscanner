<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    protected ?Request $request;

    public function __construct(?Request $request = null)
    {
        $this->request = $request ?: request();
    }

    /**
     * Log aktivitas user
     */
    public function log(
        string $activityType,
        string $action,
        string $description,
        ?Model $subject = null,
        ?array $properties = null,
        ?int $userId = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => $userId ?: Auth::id(),
            'activity_type' => $activityType,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'properties' => $properties,
            'ip_address' => $this->request?->ip(),
            'user_agent' => $this->request?->userAgent(),
            'url' => $this->request?->fullUrl(),
            'method' => $this->request?->method(),
        ]);
    }

    /**
     * Log aktivitas dokumen
     */
    public function logDocument(string $action, Model $document, ?array $properties = null): ActivityLog
    {
        $descriptions = [
            'create' => "mengunggah dokumen '{$document->title}'",
            'read' => "melihat dokumen '{$document->title}'",
            'update' => "memperbarui dokumen '{$document->title}'",
            'delete' => "menghapus dokumen '{$document->title}'",
            'download' => "mengunduh dokumen '{$document->title}'",
            'stream' => "membuka preview dokumen '{$document->title}'",
        ];

        return $this->log(
            'document',
            $action,
            $descriptions[$action] ?? "melakukan aksi '{$action}' pada dokumen '{$document->title}'",
            $document,
            $properties
        );
    }

    /**
     * Log aktivitas scan
     */
    public function logScan(string $action, ?Model $scanBatch = null, ?array $properties = null): ActivityLog
    {
        $descriptions = [
            'start' => 'memulai sesi scanning dokumen',
            'upload' => 'mengunggah hasil scan dokumen',
            'save' => 'menyimpan batch scan sebagai dokumen',
            'cancel' => 'membatalkan sesi scanning',
            'page_add' => 'menambah halaman scan',
            'page_delete' => 'menghapus halaman scan',
            'page_reorder' => 'mengatur ulang urutan halaman',
        ];

        return $this->log(
            'scan',
            $action,
            $descriptions[$action] ?? "melakukan aksi '{$action}' pada proses scan",
            $scanBatch,
            $properties
        );
    }

    /**
     * Log aktivitas autentikasi
     */
    public function logAuth(string $action, ?int $userId = null, ?array $properties = null): ActivityLog
    {
        $descriptions = [
            'login' => 'login ke sistem',
            'logout' => 'logout dari sistem',
            'register' => 'mendaftar akun baru',
            'failed_login' => 'gagal login ke sistem',
        ];

        return $this->log(
            'auth',
            $action,
            $descriptions[$action] ?? "melakukan aksi '{$action}'",
            null,
            $properties,
            $userId
        );
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::with('user')
            ->recent($limit)
            ->get();
    }

    /**
     * Get activities by user
     */
    public function getUserActivities(int $userId, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::byUser($userId)
            ->with('user')
            ->recent($limit)
            ->get();
    }

    /**
     * Get activities by type
     */
    public function getActivitiesByType(string $type, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return ActivityLog::activityType($type)
            ->with('user')
            ->recent($limit)
            ->get();
    }

    /**
     * Clean old logs (older than specified days)
     */
    public function cleanOldLogs(int $days = 90): int
    {
        return ActivityLog::where('created_at', '<', now()->subDays($days))->delete();
    }
}