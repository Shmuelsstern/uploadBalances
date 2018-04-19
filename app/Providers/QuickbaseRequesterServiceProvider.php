<?php

namespace App\Providers;

use App\src\Services\QuickbaseRequester;
use Illuminate\Support\ServiceProvider;

class QuickbaseRequesterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\src\Services\QuickbaseRequester', function($app) {
            return new QuickbaseRequester(env('QB_TEST_APPTOKEN'), env('QB_USERTOKEN'),env('QB_APP_URL'));
        });
    }
}
