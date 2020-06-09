<?php

namespace Reducktion\Socrates\Core\Denmark;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class DenmarkIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        $cprArray = array_map(
            static function ($digit) {
                return (int) $digit;
            },
            str_split($id)
        );

        $multipliers = [4, 3, 2, 7, 6, 5, 4, 3, 2, 1];

        return array_sum(
            array_map(
                static function ($digit, $multiplier) {
                    return $digit * $multiplier;
                },
                $cprArray,
                $multipliers
            )
        ) % 11 === 0;
    }

    private function sanitize(string $id): string
    {
        $id = str_replace('-', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 10) {
            throw new InvalidLengthException('Danish CPR', '10', $idLength);
        }

        return $id;
    }
}
