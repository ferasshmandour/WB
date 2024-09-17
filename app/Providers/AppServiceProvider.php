<?php

namespace App\Providers;

use App\Http\Repositories\DoctorRepository;
use App\Http\Repositories\DoctorRepositoryImpl;
use App\Http\Services\DoctorService;
use App\Http\Services\DoctorServiceImpl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(DoctorRepository::class, DoctorRepositoryImpl::class);

        // Services
        $this->app->bind(DoctorService::class, DoctorServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
