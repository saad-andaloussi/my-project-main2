<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label', 'description'];

    const GUEST = 'guest';
    const USER = 'user';
    const MANAGER = 'manager';
    const ADMIN = 'admin';

    /* -----------------------------------------------------------------
     * RELATIONS
     * ----------------------------------------------------------------- */

    /**
     * Un rôle est assigné à plusieurs utilisateurs.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /* -----------------------------------------------------------------
     * SCOPES
     * ----------------------------------------------------------------- */

    /**
     * Récupère un rôle par son nom.
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }
}
