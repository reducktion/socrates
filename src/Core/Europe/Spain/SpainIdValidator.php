<?php

namespace Reducktion\Socrates\Core\Europe\Spain;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

/**
 * Class SpainIdValidator
 *
 * Algorithm adapted from: http://www.interior.gob.es/web/servicios-al-ciudadano/dni/calculo-del-digito-de-control-del-nif-nie.
 *
 * @package Reducktion\Socrates\Core\Europe\Spain
 */
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

        $idArray = str_replace('-', '', $id);

        $idLength = strlen($idArray);

        if ($idLength !== 9) {
            throw new InvalidLengthException('Spanish DNI', '9', $idLength);
        }

        return $idArray;
    }
}
