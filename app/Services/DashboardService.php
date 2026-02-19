<?php

namespace App\Services;

use App\Models\User;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Incident;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Get user dashboard data.
     */
    public function getUserDashboard(User $user)
    {
        return [
            'pending_reservations' => $user->reservations()->pending()->count(),
            'approved_reservations' => $user->reservations()->approved()->count(),
            'active_reservations' => $user->reservations()->active()->count(),
            'recent_reservations' => $user->reservations()->latest()->limit(5)->get(),
            'incidents_reported' => $user->incidents()->count(),
            'total_spent' => $user->reservations()
                ->where('payment', 'paid')
                ->sum('total_price'),
        ];
    }

    /**
     * Get admin dashboard data.
     */
    public function getAdminDashboard()
    {
        $now = Carbon::now();

        return [
            // User statistics
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'new_users_this_month' => User::whereBetween('created_at', [$now->startOfMonth(), $now->endOfMonth()])->count(),

            // Resource statistics
            'total_resources' => Resource::count(),
            'available_resources' => Resource::available()->count(),
            'in_use_resources' => Resource::where('status', Resource::STATUS_IN_USE)->count(),
            'maintenance_resources' => Resource::inMaintenance()->count(),

            // Reservation statistics
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::pending()->count(),
            'active_reservations' => Reservation::active()->count(),
            'completed_reservations' => Reservation::where('status', Reservation::STATUS_COMPLETED)->count(),

            // Financial data
            'total_revenue' => Reservation::where('payment', 'paid')->sum('total_price'),
            'pending_payments' => Reservation::where('payment', 'unpaid')->sum('total_price'),
            'revenue_this_month' => Reservation::where('payment', 'paid')
                ->whereBetween('created_at', [$now->startOfMonth(), $now->endOfMonth()])
                ->sum('total_price'),

            // Incidents
            'total_incidents' => Incident::count(),
            'open_incidents' => Incident::open()->count(),
            'critical_incidents' => Incident::critical()->count(),

            // Charts data
            'reservation_trend' => $this->getReservationTrend(),
            'resource_utilization' => $this->getResourceUtilization(),
            'revenue_trend' => $this->getRevenueTrend(),
        ];
    }

    /**
     * Get reservation trend for the last 7 days.
     */
    private function getReservationTrend()
    {
        $days = [];
        $counts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('d/m');
            $counts[] = Reservation::whereDate('created_at', $date)->count();
        }

        return [
            'labels' => $days,
            'data' => $counts,
        ];
    }

    /**
     * Get resource utilization by category.
     */
    private function getResourceUtilization()
    {
        $categories = \App\Models\ResourceCategory::with('resources')->get();

        $labels = [];
        $available = [];
        $in_use = [];
        $maintenance = [];

        foreach ($categories as $category) {
            $labels[] = $category->name;
            $available[] = $category->resources()->where('status', Resource::STATUS_AVAILABLE)->count();
            $in_use[] = $category->resources()->where('status', Resource::STATUS_IN_USE)->count();
            $maintenance[] = $category->resources()->where('status', Resource::STATUS_MAINTENANCE)->count();
        }

        return [
            'labels' => $labels,
            'available' => $available,
            'in_use' => $in_use,
            'maintenance' => $maintenance,
        ];
    }

    /**
     * Get revenue trend for the last 12 months.
     */
    private function getRevenueTrend()
    {
        $months = [];
        $revenue = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            $revenue[] = Reservation::where('payment', 'paid')
                ->whereBetween('created_at', [$date->startOfMonth(), $date->endOfMonth()])
                ->sum('total_price');
        }

        return [
            'labels' => $months,
            'data' => $revenue,
        ];
    }

    /**
     * Get upcoming maintenance.
     */
    public function getUpcomingMaintenance()
    {
        return Resource::inMaintenance()
            ->with('incidents')
            ->latest()
            ->limit(10)
            ->get();
    }

    /**
     * Get resource performance metrics.
     */
    public function getResourcePerformance()
    {
        $resources = Resource::active()
            ->withCount('reservations')
            ->get()
            ->map(function ($resource) {
                $resource->utilization_rate = $resource->getUtilizationRate();
                return $resource;
            })
            ->sortByDesc('utilization_rate')
            ->take(10);

        return $resources;
    }
}
