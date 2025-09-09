<?php

namespace App\Traits;

use App\Models\ActivityLog;
use App\Services\ActivityLogger;

trait Loggable
{
    /**
     * Boot the trait
     */
    protected static function bootLoggable(): void
    {
        // Log when model is created
        static::created(function ($model) {
            if (method_exists($model, 'getLogOnCreate') && $model->getLogOnCreate()) {
                $model->logActivity('create', $model->getCreateDescription());
            }
        });

        // Log when model is updated
        static::updated(function ($model) {
            if (method_exists($model, 'getLogOnUpdate') && $model->getLogOnUpdate()) {
                $oldValues = $model->getOriginal();
                $newValues = $model->getAttributes();
                
                $model->logActivity('update', $model->getUpdateDescription(), [
                    'old_values' => $oldValues,
                    'new_values' => $newValues,
                    'changes' => $model->getChanges(),
                ]);
            }
        });

        // Log when model is deleted
        static::deleted(function ($model) {
            if (method_exists($model, 'getLogOnDelete') && $model->getLogOnDelete()) {
                $model->logActivity('delete', $model->getDeleteDescription());
            }
        });
    }

    /**
     * Log custom activity for this model
     */
    public function logActivity(string $action, string $description, ?array $properties = null): ActivityLog
    {
        $activityLogger = new ActivityLogger();
        
        return $activityLogger->log(
            $this->getActivityType(),
            $action,
            $description,
            $this,
            $properties
        );
    }

    /**
     * Get activity type for this model
     */
    protected function getActivityType(): string
    {
        return strtolower(class_basename($this));
    }

    /**
     * Get activities for this model
     */
    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    // Default implementations - can be overridden in models

    public function getLogOnCreate(): bool
    {
        return true;
    }

    public function getLogOnUpdate(): bool
    {
        return true;
    }

    public function getLogOnDelete(): bool
    {
        return true;
    }

    public function getCreateDescription(): string
    {
        return "membuat " . strtolower(class_basename($this));
    }

    public function getUpdateDescription(): string
    {
        return "memperbarui " . strtolower(class_basename($this));
    }

    public function getDeleteDescription(): string
    {
        return "menghapus " . strtolower(class_basename($this));
    }
}