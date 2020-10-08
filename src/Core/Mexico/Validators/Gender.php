<?php

namespace Reducktion\Socrates\Core\Mexico\Validators;

use Reducktion\Socrates\Contracts\IdValidator;

class Gender implements IdValidator
{
    private $genders = ['H', 'M'];

    public function validate(string $gender): bool
    {
        if (
            !ctype_alpha($gender) ||
            !in_array($gender, $this->genders)
        ) {
            return false;
        }

        return true;
    }
}
