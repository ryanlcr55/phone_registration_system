<?php

namespace App\Providers;

use App\Contracts\LocationContract;
use App\Exceptions\CustomException;
use App\Services\LocationServices\Gecoding;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LocationContract::class, function ($app) {
            switch (config('services.location.service')) {
                case 'geocoding':
                    return new Gecoding();
                default:
                    throw new CustomException('get location service failed', CustomException::ERROR_CODE_LOCATION_SERVICE_GET_SERVICE_FAILED);
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
