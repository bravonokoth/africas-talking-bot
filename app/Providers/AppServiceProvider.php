<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AfricasTalkingService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AfricasTalkingService::class, function ($app) {
            return new AfricasTalkingService();
        });
    }

    public function boot()
    {
        //
    }
}