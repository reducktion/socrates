<?php

namespace Reducktion\Socrates\Core\Croatia;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaIdValidator;

class CroatiaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $idLength = strlen($id);

        if ($idLength !== 11 && $idLength !== 13) {
            throw new InvalidLengthException("Croatian OIB must have 11 or 13 characters, got $idLength");
        }

        if ($idLength === 11) {
            $checksum = (int) substr($id, -1);
            $test = $this->calculateChecksum($id);
            return $checksum === $test;
        }

        try {
            $result = YugoslaviaIdValidator::validate($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('The Croatian JMBG must have 13 digits, ' . $e->getMessage());
        }

        $regionDigits = (int) substr($id, 7, 2);

        if (($regionDigits < 30 || $regionDigits > 39) && $regionDigits !== 3) {
            return false;
        }

        return $result;
    }

    private function calculateChecksum(string $id): int
    {
        $weight = 10;

        for ($counter = 0; $counter < 10; $counter++) {
            $weight += (int) $id[$counter];
            $weight %= 10;

            if ($weight === 0) {
                $weight = 10;
            }

            $weight *= 2;
            $weight %= 11;
        }

        $checksum = 11 - $weight;

        if ($checksum === 10) {
            $checksum = 0;
        }

        return $checksum;
    }
}
