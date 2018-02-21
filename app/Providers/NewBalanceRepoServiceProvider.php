<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Entiites\Repositories\NewBalanceRepo;

class NewBalanceRepoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(NewBalanceRepo::class, function ($app) {
            return new NewBalanceRepo();
        });
    }
}