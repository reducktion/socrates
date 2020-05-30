<?php

namespace Reducktion\Socrates\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Socrates extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Reducktion\Socrates\Socrates::class;
    }
}
