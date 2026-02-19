<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'resource_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /* -----------------------------------------------------------------
     * RELATIONS
     * ----------------------------------------------------------------- */

    /**
     * Une Catégorie a plusieurs Ressources.
     */
    public function resources()
    {
        return $this->hasMany(Resource::class, 'resource_category_id');
    }

    /* -----------------------------------------------------------------
     * SCOPES
     * ----------------------------------------------------------------- */

    /**
     * Récupère les catégories avec leurs ressources.
     */
    public function scopeWithResources($query)
    {
        return $query->with('resources');
    }

    /* -----------------------------------------------------------------
     * METHODS
     * ----------------------------------------------------------------- */

    /**
     * Récupère le nombre de ressources disponibles dans cette catégorie.
     */
    public function getAvailableResourcesCount()
    {
        return $this->resources()->where('status', Resource::STATUS_AVAILABLE)->count();
    }

    /**
     * Récupère toutes les ressources disponibles dans cette catégorie.
     */
    public function getAvailableResources()
    {
        return $this->resources()->where('status', Resource::STATUS_AVAILABLE)->get();
    }
}
