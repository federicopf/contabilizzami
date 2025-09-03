<?php

namespace App\Http\Controllers;

use App\Contracts\Services\DashboardServiceInterface;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $dashboardData = $this->dashboardService->getDashboardData(Auth::id() ?? 0);

        return view('home', $dashboardData);
    }
}
