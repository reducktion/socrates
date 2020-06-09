<?php

namespace Reducktion\Socrates\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
* @method static \Reducktion\Socrates\Models\Citizen getCitizenDataFromId(string $id, string $countryCode)
* @method static bool validateId(string $id, string $countryCode)
*
* @see \Reducktion\Socrates\Socrates
*/
class Socrates extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Reducktion\Socrates\Socrates::class;
    }
}
