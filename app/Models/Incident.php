<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incident extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'resource_id',
        'title',
        'description',
        'severity',
        'status',
    ];

    const SEVERITY_LOW = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH = 'high';
    const SEVERITY_CRITICAL = 'critical';

    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';

    /* -----------------------------------------------------------------
     * RELATIONS
     * ----------------------------------------------------------------- */

    /**
     * Un incident est signalé par un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un incident concerne une ressource.
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    /* -----------------------------------------------------------------
     * SCOPES
     * ----------------------------------------------------------------- */

    /**
     * Récupère les incidents ouverts.
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', [self::STATUS_OPEN, self::STATUS_IN_PROGRESS]);
    }

    /**
     * Récupère les incidents critiques.
     */
    public function scopeCritical($query)
    {
        return $query->where('severity', self::SEVERITY_CRITICAL);
    }

    /**
     * Filtre par ressource.
     */
    public function scopeByResource($query, $resourceId)
    {
        return $query->where('resource_id', $resourceId);
    }

    /* -----------------------------------------------------------------
     * METHODS
     * ----------------------------------------------------------------- */

    /**
     * Marque comme résolu.
     */
    public function resolve()
    {
        $this->status = self::STATUS_RESOLVED;
        $this->save();
        return $this;
    }

    /**
     * Marque comme fermé.
     */
    public function close()
    {
        $this->status = self::STATUS_CLOSED;
        $this->save();
        return $this;
    }
}
