<?php

namespace Reducktion\Socrates\Core\Mexico\Validators;

use Reducktion\Socrates\Contracts\IdValidator;
use Facades\Reducktion\Socrates\Core\Mexico\Constants\Vowels;

class Consoants implements IdValidator
{
    public function validate(string $names): bool
    {
        if (!ctype_alpha($names)) {
            return false;
        }

        for ($i = 0; $i < strlen($names); $i++) {
            if (in_array($names[$i], Vowels::get())) {
                return false;
            }
        }

        return true;
    }
}
