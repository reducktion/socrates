<?php

namespace App\Socrates\Core;

use App\Socrates\Contracts\IdValidator;
use App\Socrates\Exceptions\Denmark\InvalidCprLengthException;

class DenmarkIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $cpr = $this->sanitize($id);

        $cprArray = array_map(
            static function ($digit) {
                return (int) $digit;
            },
            str_split($cpr)
        );

        $multipliers = [4, 3, 2, 7, 6, 5, 4, 3, 2, 1];

        $sum = 0;
        foreach ($cprArray as $i => $digit) {
            $sum += ($digit * $multipliers[$i]);
        }

        return $sum % 11 === 0;
    }

    private function sanitize(string $id): string
    {
        $cpr = str_replace('-', '', $id);

        $cprLength = strlen($cpr);

        if ($cprLength !== 10) {
            throw new InvalidCprLengthException("Danish CPR must have 10 digits, got $cprLength");
        }

        return $cpr;
    }
}
