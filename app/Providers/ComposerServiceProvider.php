<?php
/**
 * Created by PhpStorm.
 * User: Shmuel
 * Date: 4/11/2018
 * Time: 10:49 PM
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer(
            '*', 'App\Http\ViewComposers\NavbarComposer'
        );
    }
}