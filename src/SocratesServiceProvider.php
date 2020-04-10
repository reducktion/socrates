<?php

namespace Reducktion\Socrates;

use Illuminate\Support\Facades\Validator;
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

    public function boot()
    {
        Validator::extend('national_id', function ($attribute, $value, $parameters, $validator) {
            $countryCode = strtoupper($parameters[0]);

            if (! in_array($countryCode, config('socrates.all'), true)){
                return false;
            }

            try {
                return app('socrates')->validateId($value, $countryCode);
            } catch (\Exception $e) {
                return false;
            }
        }, 'National ID number is invalid.');
    }
}