<?php

namespace App\Services;

use App\Models\Incident;
use App\Models\Resource;

class IncidentService
{
    /**
     * Create a new incident.
     */
    public function createIncident(array $data)
    {
        // Verify resource exists
        Resource::findOrFail($data['resource_id']);

        $data['status'] = Incident::STATUS_OPEN;
        return Incident::create($data);
    }

    /**
     * Resolve an incident.
     */
    public function resolveIncident(Incident $incident)
    {
        if (!in_array($incident->status, [Incident::STATUS_OPEN, Incident::STATUS_IN_PROGRESS])) {
            throw new \Exception('Cet incident ne peut pas être résolu.');
        }

        return $incident->resolve();
    }

    /**
     * Close an incident.
     */
    public function closeIncident(Incident $incident)
    {
        return $incident->close();
    }

    /**
     * Get open incidents for a resource.
     */
    public function getOpenIncidentsForResource(Resource $resource)
    {
        return $resource->incidents()->open()->get();
    }

    /**
     * Get critical incidents.
     */
    public function getCriticalIncidents()
    {
        return Incident::critical()->open()->get();
    }

    /**
     * Get incident statistics.
     */
    public function getIncidentStats()
    {
        return [
            'total_incidents' => Incident::count(),
            'open' => Incident::open()->count(),
            'critical' => Incident::critical()->count(),
            'resolved' => Incident::where('status', Incident::STATUS_RESOLVED)->count(),
            'closed' => Incident::where('status', Incident::STATUS_CLOSED)->count(),
        ];
    }

    /**
     * Get incidents by severity level.
     */
    public function getIncidentsBySeverity()
    {
        return [
            'low' => Incident::where('severity', Incident::SEVERITY_LOW)->count(),
            'medium' => Incident::where('severity', Incident::SEVERITY_MEDIUM)->count(),
            'high' => Incident::where('severity', Incident::SEVERITY_HIGH)->count(),
            'critical' => Incident::where('severity', Incident::SEVERITY_CRITICAL)->count(),
        ];
    }

    /**
     * Get incidents for a resource.
     */
    public function getResourceIncidents(Resource $resource)
    {
        return $resource->incidents()->latest()->get();
    }

    /**
     * Mark incident as in progress.
     */
    public function setInProgress(Incident $incident)
    {
        if ($incident->status !== Incident::STATUS_OPEN) {
            throw new \Exception('Seuls les incidents ouverts peuvent être marqués comme en cours.');
        }

        $incident->status = Incident::STATUS_IN_PROGRESS;
        $incident->save();

        return $incident;
    }
}
