<?php

namespace Reducktion\Socrates\Core\Mexico\Validators;

use Reducktion\Socrates\Contracts\IdValidator;

class Checksum implements IdValidator
{
    private $alphabet = "0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ";

    public function validate(string $id): bool
    {
        if (!is_numeric($id[17])) {
            return false;
        }

        $code = substr($id, 0, 17);
        $check = 0;

        for ($i = 0; $i < strlen($code); $i++) {
            $check += array_search($code[$i], str_split($this->alphabet)) * (18 - $i);
        }
        return (10 - $check % 10) % 10 === intval($id[17]);
    }
}
