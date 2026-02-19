<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reservations';

    protected $fillable = [
        'user_id',
        'resource_id',
        'reason',
        'quantity',
        'start_time',
        'end_time',
        'total_price',
        'payment',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DECLINED = 'declined';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';

    const PAYMENT_PAID = 'paid';
    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PROCESSING = 'processing';

    /* -----------------------------------------------------------------
     * RELATIONS
     * ----------------------------------------------------------------- */

    /**
     * La réservation appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * La réservation concerne une ressource spécifique.
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    /* -----------------------------------------------------------------
     * SCOPES
     * ----------------------------------------------------------------- */

    /**
     * Récupère les réservations en attente.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Récupère les réservations approuvées.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Récupère les réservations actives.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Récupère les réservations pour un utilisateur spécifique.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Récupère les réservations pour une ressource spécifique.
     */
    public function scopeByResource($query, $resourceId)
    {
        return $query->where('resource_id', $resourceId);
    }

    /**
     * Récupère les réservations dans une période donnée.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_time', [$startDate, $endDate])
                     ->orWhereBetween('end_time', [$startDate, $endDate]);
    }

    /* -----------------------------------------------------------------
     * ACCESSORS & METHODS
     * ----------------------------------------------------------------- */

    /**
     * Calcule la durée en heures.
     */
    public function getDurationInHoursAttribute()
    {
        return $this->start_time->diffInHours($this->end_time);
    }

    /**
     * Récupère le prix par heure de la ressource.
     */
    public function getPricePerHourAttribute()
    {
        return $this->resource ? $this->resource->price_per_hour : 0;
    }

    /**
     * Vérifie si la réservation est dans le passé.
     */
    public function isPast()
    {
        return $this->end_time < Carbon::now();
    }

    /**
     * Vérifie si la réservation est actuellement active.
     */
    public function isCurrentlyActive()
    {
        $now = Carbon::now();
        return $this->start_time <= $now && $this->end_time >= $now && $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Approuve la réservation.
     */
    public function approve()
    {
        $this->status = self::STATUS_APPROVED;
        $this->save();
        return $this;
    }

    /**
     * Refuse la réservation.
     */
    public function decline()
    {
        $this->status = self::STATUS_DECLINED;
        $this->save();
        return $this;
    }

    /**
     * Annule la réservation.
     */
    public function cancel()
    {
        $this->status = self::STATUS_CANCELLED;
        $this->save();
        return $this;
    }

    /**
     * Active la réservation (démarrage de l'utilisation).
     */
    public function activate()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save();
        return $this;
    }

    /**
     * Marque comme complétée.
     */
    public function complete()
    {
        $this->status = self::STATUS_COMPLETED;
        $this->save();
        return $this;
    }

    /* -----------------------------------------------------------------
     * BOOT & AUTO-CALCULATION
     * ----------------------------------------------------------------- */

    /**
     * Recalcule automatiquement le prix total avant de sauvegarder.
     */
    protected static function booted()
    {
        static::saving(function ($reservation) {
            if ($reservation->resource && $reservation->start_time && $reservation->end_time) {
                $hours = $reservation->start_time->diffInHours($reservation->end_time);
                $hours = max($hours, 1); // Minimum 1 hour
                $reservation->total_price = $reservation->quantity * $hours * $reservation->resource->price_per_hour;
            }
        });
    }
}