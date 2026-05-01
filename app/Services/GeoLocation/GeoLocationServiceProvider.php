<?php

namespace App\Services\GeoLocation;

use Illuminate\Support\ServiceProvider;
use App\Services\GeoLocation\Contracts\GeoIpProvider;
use App\Services\GeoLocation\Providers\IpApiProvider;
use App\Services\GeoLocation\Services\GeoLocationService;

class GeoLocationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GeoIpProvider::class, IpApiProvider::class);

        $this->app->singleton('geo-location', function ($app) {
            return new GeoLocationService($app->make(GeoIpProvider::class));
        });
    }
}
