<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class QuickbaseQuerierServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }


    public function register()
    {
        $this->app->bind('QuickbaseQuerier', function ($app) {
            return new QuickbaseQuerier();
        });
    }
}