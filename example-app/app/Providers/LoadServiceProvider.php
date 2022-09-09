<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\loadAndMoveService;

class LoadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(loadAndMoveService::class,function($app){
        //     return new loadAndMoveService();
        // });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('Load',loadAndMoveService::class);
    }
}
