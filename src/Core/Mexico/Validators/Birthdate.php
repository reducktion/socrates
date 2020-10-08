<?php

namespace Reducktion\Socrates\Core\Mexico\Validators;

use Reducktion\Socrates\Contracts\IdValidator;

class Birthdate implements IdValidator
{
    public function validate(string $birthdate): bool
    {
        for ($i = 0; $i < strlen($birthdate); $i++) {
            if (!is_numeric($birthdate[$i])) {
                return false;
            }
        }

        //validate month
        if (!in_array(intval(substr($birthdate, 2, 2)), range(1, 12))) {
            return false;
        }

        //validate day
        if (!in_array(intval(substr($birthdate, 4, 2)), range(1, 31))) {
            return false;
        }

        return true;
    }
}
