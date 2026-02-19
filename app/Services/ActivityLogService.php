<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogService
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * Log an activity.
     */
    public function log(
        string $action,
        Model $model = null,
        array $changes = null,
        string $description = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => auth('web')->id(),
            'action' => $action,
            'model_type' => $model ? class_basename($model) : null,
            'model_id' => $model ? $model->id : null,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => $this->request ? $this->request->ip() : null,
            'user_agent' => $this->request ? $this->request->userAgent() : null,
        ]);
    }

    /**
     * Log model creation.
     */
    public function logCreate(Model $model, string $description = null)
    {
        return $this->log('created', $model, null, $description ?? 'Objet créé');
    }

    /**
     * Log model update.
     */
    public function logUpdate(Model $model, array $changes = [], string $description = null)
    {
        return $this->log('updated', $model, $changes, $description ?? 'Objet mis à jour');
    }

    /**
     * Log model deletion.
     */
    public function logDelete(Model $model, string $description = null)
    {
        return $this->log('deleted', $model, null, $description ?? 'Objet supprimé');
    }

    /**
     * Log a custom action.
     */
    public function logAction(string $action, Model $model = null, string $description = null)
    {
        return $this->log($action, $model, null, $description);
    }

    /**
     * Get activity logs for a specific model.
     */
    public function getModelLogs(Model $model, $limit = 50)
    {
        return ActivityLog::where('model_type', class_basename($model))
            ->where('model_id', $model->id)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get activity logs for a specific user.
     */
    public function getUserLogs($userId, $limit = 100)
    {
        return ActivityLog::where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get all activity logs (paginated).
     */
    public function getAllLogs($perPage = 50)
    {
        return ActivityLog::with('user')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get statistics for a date range.
     */
    public function getStats(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate)
    {
        $logs = ActivityLog::whereBetween('created_at', [$startDate, $endDate]);

        return [
            'total_activities' => $logs->count(),
            'by_action' => $logs->clone()->groupBy('action')->selectRaw('action, count(*) as count')->get(),
            'by_model' => $logs->clone()->groupBy('model_type')->selectRaw('model_type, count(*) as count')->get(),
            'by_user' => $logs->clone()->with('user')->groupBy('user_id')->selectRaw('user_id, count(*) as count')->get(),
        ];
    }

    /**
     * Clear old activity logs.
     */
    public function clearOldLogs($days = 90)
    {
        return ActivityLog::where('created_at', '<', \Carbon\Carbon::now()->subDays($days))->delete();
    }
}
