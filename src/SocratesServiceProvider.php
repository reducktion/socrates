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
    }

    public function boot()
    {
        //
    }
}