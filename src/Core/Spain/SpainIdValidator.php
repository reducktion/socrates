<?php

namespace Reducktion\Socrates\Core\Spain;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class SpainIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        if (!$this->isNIF($id) && !$this->isNIE($id)) {
            return false;
        }

        $f = strpos('XYZ', $id[0]) === false
            ? -1
            : strpos('XYZ', $id[0]) % 3;

        $s = $f;

        if ($f === -1) {
            $s = $id[0];
        }

        $i = ($s + substr($id, 1, 7)) % 23;

        return strpos('TRWAGMYFPDXBNJZSQVHLCKET', $id[8]) === $i;
    }

    private function sanitize(string $id): string
    {
        $id = strtoupper($id);

        $id = str_replace('-', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 9) {
            throw new InvalidLengthException("Spanish DNI must have 9 characters, got $idLength");
        }

        return $id;
    }

    private function isNIF(string $id): bool
    {
        return preg_match('/^\d{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/', $id, $matches);
    }

    private function isNIE(string $id): bool
    {
        return preg_match('/^[XYZ]\d{7}[TRWAGMYFPDXBNJZSQVHLCKE]$/', $id, $matches);
    }
}
