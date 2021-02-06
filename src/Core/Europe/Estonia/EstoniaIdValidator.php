<?php

namespace Reducktion\Socrates\Core\Europe\Estonia;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class EstoniaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $idLength = strlen($id);

        if ($idLength !== 11) {
            throw new InvalidLengthException('Estonian IK', '11', $idLength);
        }

        $checksum = (int) substr($id, -1);
        $id = substr($id, 0, -1);

        $picArray = array_map(
            static function ($digit) {
                return (int) $digit;
            },
            str_split($id)
        );

        $multipliers1 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 1];
        $multipliers2 = [3, 4, 5, 6, 7, 8, 9, 1, 2, 3];

        $result = $this->calculateChecksum($picArray, $multipliers1);

        if ($result === 10) {
            $result = $this->calculateChecksum($picArray, $multipliers2) === 10
                ? 0
                : $this->calculateChecksum($picArray, $multipliers2);
        }

        return $checksum === $result;
    }

    private function calculateChecksum(array $picArray, array $multipliers): int
    {
        return array_sum(
            array_map(
                static function ($digit, $weight) {
                    return $digit * $weight;
                },
                $picArray,
                $multipliers
            )
        ) % 11;
    }
}
