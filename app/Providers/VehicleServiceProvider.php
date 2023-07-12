<?php

namespace App\Providers;

use App\Models\Vehicle;
use App\Services\VehicleService;
use Illuminate\Support\Facades\Route;
use App\Repositories\VehicleRepository;
use Illuminate\Support\ServiceProvider;

class VehicleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerRepositories();
        $this->registerServices();

        $this->registerVehicleResolutionLogic();
    }

    /**
     * Register vehicle resource repositories.
     *
     * @return void
     */
    protected function registerRepositories(): void
    {
        $this->app->bind(VehicleRepository::class, function ($app) {
            return new VehicleRepository($app->make(Vehicle::class));
        });
    }

    /**
     * Register vehicle resource services.
     *
     * @return void
     */
    protected function registerServices(): void
    {
        $this->app->bind(VehicleService::class, function ($app) {
            return new VehicleService($app->make(VehicleRepository::class));
        });
    }

    /**
     * Register vehicle resource resolution logic.
     *
     * @return void
     */
    protected function registerVehicleResolutionLogic(): void
    {
        Route::bind('vehicle', function (string $value) {
            return $this->app->make(VehicleService::class)->find($value);
        });
    }
}
