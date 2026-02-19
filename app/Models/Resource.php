<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Resource extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'resources';

    protected $fillable = [
        'name',
        'resource_category_id',
        'serial_number',
        'cpu_cores',
        'ram_gb',
        'storage_gb',
        'purchase_price',
        'status',
        'price_per_hour',
        'description',
        'location',
        'bandwidth_gbps',
        'storage_type',
    ];

    protected $casts = [
        'price_per_hour' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'cpu_cores' => 'integer',
        'ram_gb' => 'integer',
        'storage_gb' => 'integer',
        'bandwidth_gbps' => 'decimal:2',
    ];

    const STATUS_AVAILABLE = 'available';
    const STATUS_IN_USE = 'in_use';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_INACTIVE = 'inactive';

    /* -----------------------------------------------------------------
     * RELATIONS
     * ----------------------------------------------------------------- */

    /**
     * Une Ressource appartient à une Catégorie.
     */
    public function category()
    {
        return $this->belongsTo(ResourceCategory::class, 'resource_category_id');
    }

    /**
     * Une Ressource a plusieurs réservations.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Une Ressource peut avoir plusieurs incidents.
     */
    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    /* -----------------------------------------------------------------
     * SCOPES
     * ----------------------------------------------------------------- */

    /**
     * Récupère uniquement les ressources disponibles.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Récupère les ressources actives (disponibles ou en usage).
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_AVAILABLE, self::STATUS_IN_USE]);
    }

    /**
     * Filtre par catégorie.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('resource_category_id', $categoryId);
    }

    /**
     * Filtre les ressources en maintenance.
     */
    public function scopeInMaintenance($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }

    /* -----------------------------------------------------------------
     * METHODS
     * ----------------------------------------------------------------- */

    /**
     * Vérifie si la ressource est disponible.
     */
    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Vérifie si la ressource est en maintenance.
     */
    public function isInMaintenance()
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    /**
     * Vérifie les conflits de réservation pour une période donnée.
     */
    public function hasConflict(Carbon $startTime, Carbon $endTime, $excludeReservationId = null)
    {
        $query = $this->reservations()
            ->whereIn('status', ['approved', 'active'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(function ($query) use ($startTime, $endTime) {
                      $query->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                  });
            });

        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return $query->exists();
    }

    /**
     * Récupère les réservations actuelles/actives.
     */
    public function getActiveReservations()
    {
        return $this->reservations()
            ->where('status', 'active')
            ->where('end_time', '>=', Carbon::now())
            ->get();
    }

    /**
     * Récupère le taux d'utilisation (en %).
     */
    public function getUtilizationRate()
    {
        $total = 24 * 30; // 30 days * 24 hours
        $used = $this->reservations()
            ->where('status', 'approved')
            ->whereBetween('start_time', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->get()
            ->sum(function ($reservation) {
                return $reservation->getDurationInHoursAttribute();
            });

        return round(($used / $total) * 100, 2);
    }
}