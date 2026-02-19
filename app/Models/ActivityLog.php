<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ActivityLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'changes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
    ];

    /* -----------------------------------------------------------------
     * RELATIONS
     * ----------------------------------------------------------------- */

    /**
     * L'action a été effectuée par un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /* -----------------------------------------------------------------
     * SCOPES
     * ----------------------------------------------------------------- */

    /**
     * Filtre par utilisateur.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Filtre par action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Filtre par type de modèle.
     */
    public function scopeByModel($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Récupère les activités des derniers N jours.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days))->latest();
    }

    /**
     * Récupère les activités de ce mois.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ]);
    }

    /* -----------------------------------------------------------------
     * METHODS
     * ----------------------------------------------------------------- */

    /**
     * Récupère le nom lisible de l'action.
     */
    public function getActionLabel()
    {
        $labels = [
            'created' => 'Créé',
            'updated' => 'Mis à jour',
            'deleted' => 'Supprimé',
            'viewed' => 'Consulté',
            'approved' => 'Approuvé',
            'declined' => 'Refusé',
            'cancelled' => 'Annulé',
            'resolved' => 'Résolu',
        ];

        return $labels[$this->action] ?? $this->action;
    }

    /**
     * Récupère le nom lisible du type de modèle.
     */
    public function getModelLabel()
    {
        $labels = [
            'Reservation' => 'Réservation',
            'Resource' => 'Ressource',
            'Incident' => 'Incident',
            'ResourceCategory' => 'Catégorie',
            'User' => 'Utilisateur',
        ];

        return $labels[$this->model_type] ?? $this->model_type;
    }
}
