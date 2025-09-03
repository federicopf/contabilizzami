<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Services\TransactionService;
use App\Services\AccountService;
use App\Services\UserService;
use App\Services\StatsService;
use App\Services\ProfileService;
use App\Services\DashboardService;
use App\Services\AuthService;
use App\Contracts\Services\TransactionServiceInterface;
use App\Contracts\Services\AccountServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Contracts\Services\StatsServiceInterface;
use App\Contracts\Services\ProfileServiceInterface;
use App\Contracts\Services\DashboardServiceInterface;
use App\Contracts\Services\AuthServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registra i servizi nel container DI
        $this->app->bind(TransactionServiceInterface::class, TransactionService::class);
        $this->app->bind(AccountServiceInterface::class, AccountService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(StatsServiceInterface::class, StatsService::class);
        $this->app->bind(ProfileServiceInterface::class, ProfileService::class);
        $this->app->bind(DashboardServiceInterface::class, DashboardService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
        	URL::forceScheme('https');
	}
    }
}
