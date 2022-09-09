<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\RadiusService;

class RadiusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //  $this->app->bind(RadiusService::class,function($app){
        //      return new RadiusService();
        //  });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('Radius',RadiusService::class);
    }
}
