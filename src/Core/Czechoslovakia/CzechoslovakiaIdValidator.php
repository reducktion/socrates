<?php

namespace Reducktion\Socrates\Core\Czechoslovakia;

use Reducktion\Socrates\Exceptions\InvalidLengthException;

class CzechoslovakiaIdValidator
{
    public static function validate(string $id): bool
    {
        $id = str_replace('/', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 10) {
            throw new InvalidLengthException("got $idLength");
        }

        $checksum = (int) substr($id, -1);
        $id = substr($id, 0, -1);

        $result = ($id % 11) === 10 ? 0 : ($id % 11);

        return $checksum === $result;
    }
}
