<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ActivityLogController extends Controller
{
    use AuthorizesRequests;
    
    protected $activityService;

    public function __construct(ActivityLogService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * Display activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->has('model_type') && $request->model_type) {
            $query->where('model_type', $request->model_type);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from)->startOfDay());
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }

        $logs = $query->latest()->paginate(50);
        $users = \App\Models\User::all();

        return view('admin.activity-logs.index', compact('logs', 'users'));
    }

    /**
     * Display activity details.
     */
    public function show(ActivityLog $log)
    {
        return view('admin.activity-logs.show', compact('log'));
    }

    /**
     * Display user activity.
     */
    public function userActivity($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $logs = $this->activityService->getUserLogs($userId, 100);

        return view('admin.activity-logs.user', compact('user', 'logs'));
    }

    /**
     * Export activity logs.
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to));
        }

        $logs = $query->latest()->get();

        // CSV export
        $csvData = [];
        $csvData[] = ['ID', 'Utilisateur', 'Action', 'Modèle', 'ID Modèle', 'Description', 'Date', 'IP'];

        foreach ($logs as $log) {
            $csvData[] = [
                $log->id,
                $log->user?->name ?? 'System',
                $log->getActionLabel(),
                $log->getModelLabel(),
                $log->model_id,
                $log->description,
                $log->created_at->format('Y-m-d H:i:s'),
                $log->ip_address,
            ];
        }

        $csv = fopen('php://output', 'w');
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=activity_logs_' . now()->format('Y-m-d') . '.csv');

        foreach ($csvData as $row) {
            fputcsv($csv, $row);
        }
        fclose($csv);
        exit;
    }

    /**
     * Clear old activity logs.
     */
    public function clearOldLogs(Request $request)
    {
        $days = $request->input('days', 90);
        $deleted = $this->activityService->clearOldLogs($days);

        return back()->with('success', "$deleted anciens logs d'activité supprimés.");
    }
}
