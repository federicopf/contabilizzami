<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Contracts\Services\StatsServiceInterface;
use Illuminate\Support\Facades\Auth;

class ApiStatsController extends Controller
{
    protected $statsService;

    public function __construct(StatsServiceInterface $statsService)
    {
        $this->statsService = $statsService;
    }

    public function getStatsMonthlyInOut($year)
    {
        $stats = $this->statsService->getMonthlyInOutStats($year, Auth::id() ?? 0);
        return response()->json($stats);
    }

    public function getStatsYearlyInOut()
    {
        $stats = $this->statsService->getYearlyInOutStats(Auth::id() ?? 0);
        return response()->json($stats);
    }

    public function getStatsMonthlyTotal($year)
    {
        $stats = $this->statsService->getMonthlyTotalStats($year, Auth::id() ?? 0);
        return response()->json($stats);
    }

    public function getStatsYearlyTotal()
    {
        $stats = $this->statsService->getYearlyTotalStats(Auth::id() ?? 0);
        return response()->json($stats);
    }
}
