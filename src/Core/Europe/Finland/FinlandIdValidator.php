<?php

namespace Reducktion\Socrates\Core\Europe\Finland;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class FinlandIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $idLength = strlen($id);
        if ($idLength !== 11) {
            throw new InvalidLengthException('Finnish HETU', '11', $idLength);
        }

        $control = substr($id, -1);
        $id = substr($id, 0, -1);
        $id = substr_replace($id, '', 6, 1);

        $result = ((int) $id % 31);

        $controlCharacters = '0123456789ABCDEFHJKLMNPRSTUVWXY';

        return $controlCharacters[$result] === $control;
    }
}
