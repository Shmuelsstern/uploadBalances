<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NewBalanceRepoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('NewBalanceRepo', function ($app) {
            return new NewBalanceRepo();
        });
    }
}