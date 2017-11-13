<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SourceFileServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }


    public function register()
    {
        $this->app->bind('File', function ($app) {
            return new File();
        });
    }
}