<?php

namespace App\Services;

use App\Models\Resource;
use App\Models\ResourceCategory;

class ResourceService
{
    /**
     * Create a new resource.
     */
    public function createResource(array $data)
    {
        // Verify category exists
        ResourceCategory::findOrFail($data['resource_category_id']);

        return Resource::create($data);
    }

    /**
     * Update a resource.
     */
    public function updateResource(Resource $resource, array $data)
    {
        $resource->update($data);
        return $resource;
    }

    /**
     * Set resource to maintenance.
     */
    public function setMaintenance(Resource $resource)
    {
        if ($resource->reservations()->whereIn('status', ['approved', 'active'])->exists()) {
            throw new \Exception('Impossible de mettre en maintenance une ressource avec des réservations actives.');
        }

        $resource->update(['status' => Resource::STATUS_MAINTENANCE]);
        return $resource;
    }

    /**
     * Get resource statistics.
     */
    public function getResourceStats(Resource $resource)
    {
        return [
            'total_reservations' => $resource->reservations()->count(),
            'active_reservations' => $resource->reservations()->where('status', 'active')->count(),
            'utilization_rate' => $resource->getUtilizationRate(),
            'revenue' => $resource->reservations()
                ->where('status', 'completed')
                ->where('payment', 'paid')
                ->sum('total_price'),
        ];
    }

    /**
     * Get all resources with availability status.
     */
    public function getResourcesWithAvailability($categoryId = null, $startTime = null, $endTime = null)
    {
        $query = Resource::active();

        if ($categoryId) {
            $query->where('resource_category_id', $categoryId);
        }

        $resources = $query->get();

        // Add availability data
        return $resources->map(function ($resource) use ($startTime, $endTime) {
            $resource->is_available_for_period = !$resource->hasConflict($startTime, $endTime);
            return $resource;
        });
    }

    /**
     * Get resources by status.
     */
    public function getResourcesByStatus($status)
    {
        return Resource::where('status', $status)->get();
    }

    /**
     * Get low-stock or critical resources.
     */
    public function getCriticalResources()
    {
        return Resource::where('status', Resource::STATUS_MAINTENANCE)
            ->orWhere('status', Resource::STATUS_INACTIVE)
            ->get();
    }

    /**
     * Calculate category statistics.
     */
    public function getCategoryStats(ResourceCategory $category)
    {
        $resources = $category->resources;

        return [
            'total_resources' => $resources->count(),
            'available' => $resources->where('status', Resource::STATUS_AVAILABLE)->count(),
            'in_use' => $resources->where('status', Resource::STATUS_IN_USE)->count(),
            'maintenance' => $resources->where('status', Resource::STATUS_MAINTENANCE)->count(),
            'total_reservations' => $resources->sum(function ($r) {
                return $r->reservations()->count();
            }),
        ];
    }
}
