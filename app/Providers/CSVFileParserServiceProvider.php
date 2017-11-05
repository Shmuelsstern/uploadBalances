<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CSVFileParserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }


    public function register()
    {
        $this->app->bind('CSVFileParser', function ($app) {
            return new CSVFileParser();
            //return new CSVFileParser($app->make('Illuminate\Support\Facades\Storage'));
        });
    }
}
