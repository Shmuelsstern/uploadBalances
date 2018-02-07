<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Entities\Repositories\ResidentRepo;

class ResidentRepoServiceProvider extends ServiceProvider
{
    
    public function register()
    {
        $this->app->bind('ResidentRepo', function ($app) {
            return new ResidentRepo();
        });
    }
}