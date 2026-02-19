<?php

use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Model;

/**
 * Log an activity.
 */
function activity()
{
    return app(ActivityLogService::class);
}

/**
 * Log model creation.
 */
function logCreate(Model $model, $description = null)
{
    return activity()->logCreate($model, $description);
}

/**
 * Log model update.
 */
function logUpdate(Model $model, array $changes = [], $description = null)
{
    return activity()->logUpdate($model, $changes, $description);
}

/**
 * Log model deletion.
 */
function logDelete(Model $model, $description = null)
{
    return activity()->logDelete($model, $description);
}

/**
 * Log a custom action.
 */
function logAction($action, Model $model = null, $description = null)
{
    return activity()->logAction($action, $model, $description);
}
