<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $data = $this->dashboardService->getUserDashboard(auth('web')->user());
        return view('dashboard', $data);
    }

    /**
     * Display the admin dashboard.
     */
    public function admin()
    {
        $data = $this->dashboardService->getAdminDashboard();
        return view('admin.dashboard', $data);
    }
}
