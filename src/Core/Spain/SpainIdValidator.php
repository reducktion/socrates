<?php

namespace Reducktion\Socrates\Core\Spain;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class SpainIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        $position = strpos('XYZ', $id[0]);

        if (is_numeric($position)) {
            $id = substr_replace($id, $position, 0, 1);
        }

        $control = substr($id, -1);

        $result = ((int) $id) % 23;

        $controlCharacters = 'TRWAGMYFPDXBNJZSQVHLCKET';

        return $controlCharacters[$result] === $control;
    }

    private function sanitize(string $id): string
    {
        $id = strtoupper($id);

        $id = str_replace('-', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 9) {
            throw new InvalidLengthException('Spanish DNI', '9', $idLength);
        }

        return $id;
    }
}
