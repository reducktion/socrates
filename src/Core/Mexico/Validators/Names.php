<?php

namespace Reducktion\Socrates\Core\Mexico\Validators;

use Reducktion\Socrates\Contracts\IdValidator;
use Facades\Reducktion\Socrates\Core\Mexico\Constants\Vowels;

class Names implements IdValidator
{
    public function validate(string $names): bool
    {
        for ($i = 0; $i < strlen($names); $i++) {
            if (!ctype_alpha($names[$i])) {
                return false;
            }

            if (!in_array($names[1], Vowels::get())) {
                return false;
            }
        }

        return true;
    }
}
