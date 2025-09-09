<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity_type',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
        'url',
        'method',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject model (Document, ScanBatch, etc.)
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope untuk filter berdasarkan tipe aktivitas
     */
    public function scopeActivityType($query, string $type)
    {
        return $query->where('activity_type', $type);
    }

    /**
     * Scope untuk filter berdasarkan action
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk aktivitas terbaru
     */
    public function scopeRecent($query, int $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    /**
     * Get formatted activity description with user name
     */
    public function getFormattedDescriptionAttribute(): string
    {
        $userName = $this->user ? $this->user->name : 'System';
        return "{$userName} {$this->description}";
    }

    /**
     * Get activity icon based on activity type and action
     */
    public function getIconAttribute(): string
    {
        return match($this->activity_type) {
            'document' => match($this->action) {
                'create' => '📄',
                'read' => '👁️',
                'update' => '✏️',
                'delete' => '🗑️',
                'download' => '⬇️',
                default => '📄'
            },
            'scan' => match($this->action) {
                'start' => '🔍',
                'upload' => '📤',
                'save' => '💾',
                'cancel' => '❌',
                default => '🔍'
            },
            'auth' => match($this->action) {
                'login' => '🔐',
                'logout' => '🚪',
                'register' => '👤',
                default => '🔐'
            },
            default => '📝'
        };
    }

    /**
     * Get activity color class for UI
     */
    public function getColorClassAttribute(): string
    {
        return match($this->activity_type) {
            'document' => match($this->action) {
                'create' => 'text-green-600 bg-green-100',
                'read' => 'text-blue-600 bg-blue-100',
                'update' => 'text-yellow-600 bg-yellow-100',
                'delete' => 'text-red-600 bg-red-100',
                'download' => 'text-indigo-600 bg-indigo-100',
                default => 'text-gray-600 bg-gray-100'
            },
            'scan' => 'text-purple-600 bg-purple-100',
            'auth' => 'text-emerald-600 bg-emerald-100',
            default => 'text-gray-600 bg-gray-100'
        };
    }
}