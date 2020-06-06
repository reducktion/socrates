<?php

namespace Reducktion\Socrates\Core\Hungary;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class HungaryIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        $checksum = (int) substr($id, -1);
        $id = substr($id, 0, -1);

        $yearDigits = (int) substr($id, 1, 2);

        $pinArray = array_map(
            static function ($digit) {
                return (int) $digit;
            },
            str_split($id)
        );

        if ($yearDigits < 96) {
            $sum = $this->oldChecksum($pinArray);
        } else {
            $sum = $this->newChecksum($pinArray);
        }

        return $checksum === $sum % 11;
    }

    private function sanitize(string $id): string
    {
        $id = str_replace('-', '', $id);
        $idLength = strlen($id);

        if ($idLength !== 11) {
            throw new InvalidLengthException(
                'Hungarian personal identification number', '11', $idLength
            );
        }

        return $id;
    }

    private function oldChecksum(array $pinArray): int
    {
        $multipliers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        return array_sum(
            array_map(
                static function ($digit, $weight) {
                    return $digit * $weight;
                },
                $pinArray,
                $multipliers
            )
        );
    }

    private function newChecksum(array $pinArray): int
    {
        $multipliers = [10, 9, 8, 7, 6, 5, 4, 3, 2, 1];

        return array_sum(
            array_map(
                static function ($digit, $weight) {
                    return $digit * $weight;
                },
                $pinArray,
                $multipliers
            )
        );
    }
}
