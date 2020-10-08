<?php

namespace Reducktion\Socrates\Core\Mexico\Validators;

use Reducktion\Socrates\Contracts\IdValidator;

class State implements IdValidator
{
    private $states = ['AS', 'BS', 'CL', 'CS', 'DF', 'GT', 'HG', 'MC', 'MS', 'NL', 'PL', 'QR', 'SL', 'TC', 'TL', 'YN', 'NE', 'BC', 'CC', 'CM', 'CH', 'DG', 'GR', 'JC', 'MN', 'NT', 'OC', 'QT', 'SP', 'SR', 'TS', 'VZ', 'ZS'];

    public function validate(string $state): bool
    {
        if (
            !ctype_alpha($state) ||
            !in_array($state, $this->states)
        ) {
            return false;
        }

        return true;
    }
}
