<?php

namespace Reducktion\Socrates;

use Illuminate\Support\ServiceProvider;

class SocratesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('socrates', static function($app) {
            return new Socrates();
        });

        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'socrates');
    }
}