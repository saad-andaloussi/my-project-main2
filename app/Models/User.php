<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'department',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /* -----------------------------------------------------------------
     * RELATIONS
     * ----------------------------------------------------------------- */

    /**
     * Un utilisateur appartient à un rôle.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Un utilisateur a plusieurs réservations.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Un utilisateur peut avoir plusieurs incidents signalés.
     */
    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    /* -----------------------------------------------------------------
     * SCOPES
     * ----------------------------------------------------------------- */

    /**
     * Scope pour récupérer les utilisateurs actifs.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour filtrer par rôle.
     */
    public function scopeByRole($query, $roleName)
    {
        return $query->whereHas('role', function ($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    /* -----------------------------------------------------------------
     * ACCESSORS & METHODS
     * ----------------------------------------------------------------- */

    /**
     * Vérifie si l'utilisateur a un rôle spécifique.
     */
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Récupère le nombre total de réservations confirmées.
     */
    public function getApprovedReservationsCount()
    {
        return $this->reservations()->where('status', 'approved')->count();
    }

    /**
     * Récupère le nombre de réservations en attente.
     */
    public function getPendingReservationsCount()
    {
        return $this->reservations()->where('status', 'pending')->count();
    }
}
