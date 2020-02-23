<?php

namespace Reducktion\Socrates;

use Illuminate\Support\ServiceProvider;

class SocratesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('socrates', function($app) {
            return new Socrates();
        });

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'socrates');
    }

    public function boot()
    {
//        $this->publishes([
//          __DIR__.'/../config/config.php' =>   config_path('socrates.php'),
//        ], 'config');
    }
}