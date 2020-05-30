<?php

namespace Reducktion\Socrates\Laravel;

use Reducktion\Socrates\Socrates;
use Illuminate\Support\ServiceProvider;
use Reducktion\Socrates\Config\Countries;
use Illuminate\Support\Facades\Validator;

class SocratesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('socrates', static function ($app) {
            return new Socrates();
        });
    }

    public function boot()
    {
        Validator::extend('national_id', function ($attribute, $value, $parameters, $validator) {
            $countryCode = strtoupper($parameters[0]);

            if (! in_array($countryCode, Countries::$all, true)) {
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
