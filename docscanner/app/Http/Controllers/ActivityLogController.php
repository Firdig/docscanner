<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    protected ActivityLogger $activityLogger;

    public function __construct()
    {
        $this->activityLogger = new ActivityLogger();
    }

    /**
     * Display activity logs with filters
     */
    public function index(Request $request)
    {
        $activityType = $request->input('activity_type');
        $action = $request->input('action');
        $userId = $request->input('user_id');
        $from = $request->input('from');
        $to = $request->input('to');

        $activities = ActivityLog::query()
            ->with(['user', 'subject'])
            ->when($activityType, fn($q) => $q->activityType($activityType))
            ->when($action, fn($q) => $q->action($action))
            ->when($userId, fn($q) => $q->byUser($userId))
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Get filter options
        $activityTypes = ActivityLog::select('activity_type')
            ->distinct()
            ->orderBy('activity_type')
            ->pluck('activity_type');

        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $users = \App\Models\User::select('id', 'name')
            ->whereExists(function ($query) {
                $query->select(\Illuminate\Support\Facades\DB::raw(1))
                      ->from('activity_logs')
                      ->whereColumn('activity_logs.user_id', 'users.id');
            })
            ->orderBy('name')
            ->get();

        return view('activity-logs.index', compact(
            'activities', 
            'activityTypes', 
            'actions', 
            'users',
            'activityType',
            'action', 
            'userId', 
            'from', 
            'to'
        ));
    }

    /**
     * Show detailed activity log
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load(['user', 'subject']);
        
        return view('activity-logs.show', compact('activityLog'));
    }

    /**
     * Get recent activities for dashboard (API)
     */
    public function recent(Request $request)
    {
        $limit = $request->input('limit', 10);
        
        $activities = $this->activityLogger->getRecentActivities($limit);

        return response()->json([
            'activities' => $activities->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'description' => $activity->formatted_description,
                    'icon' => $activity->icon,
                    'color_class' => $activity->color_class,
                    'created_at' => $activity->created_at->format('d/m/Y H:i'),
                    'created_at_human' => $activity->created_at->diffForHumans(),
                ];
            })
        ]);
    }

    /**
     * Get activities by user (API)
     */
    public function userActivities(Request $request, int $userId)
    {
        $limit = $request->input('limit', 50);
        
        $activities = $this->activityLogger->getUserActivities($userId, $limit);

        return response()->json([
            'activities' => $activities
        ]);
    }

    /**
     * Clean old logs (admin only)
     */
    public function cleanOldLogs(Request $request)
    {
        $days = $request->input('days', 90);
        
        $deletedCount = $this->activityLogger->cleanOldLogs($days);

        return redirect()->back()->with('ok', "Berhasil menghapus {$deletedCount} log aktivitas yang lebih dari {$days} hari.");
    }

    /**
     * Export activities to CSV
     */
    public function export(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        
        $activities = ActivityLog::query()
            ->with(['user'])
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->latest()
            ->get();

        $filename = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Tanggal',
                'User',
                'Tipe Aktivitas',
                'Aksi',
                'Deskripsi',
                'IP Address',
                'User Agent'
            ]);

            // CSV Data
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->created_at->format('Y-m-d H:i:s'),
                    $activity->user ? $activity->user->name : 'System',
                    $activity->activity_type,
                    $activity->action,
                    $activity->description,
                    $activity->ip_address,
                    $activity->user_agent
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}