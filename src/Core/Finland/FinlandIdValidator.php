<?php

namespace Reducktion\Socrates\Core\Finland;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class FinlandIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $idLength = strlen($id);
        if ($idLength !== 11) {
            throw new InvalidLengthException("Finnish PIC must have 11 digits, got $idLength");
        }

        $control = substr($id, -1);
        $id = substr($id, 0, -1);
        $id = substr_replace($id, '', 6, 1);

        $result = ((int) $id % 31);

        $controlCharacters = '0123456789ABCDEFHJKLMNPRSTUVWXY';

        return $controlCharacters[$result] === $control;
    }
}
