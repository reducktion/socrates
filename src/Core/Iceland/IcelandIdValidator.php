<?php

namespace Reducktion\Socrates\Core\Iceland;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class IcelandIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        $ktArray = array_map(
            static function ($digit) {
                return (int) $digit;
            },
            str_split($id)
        );

        $multipliers = [3, 2, 7, 6, 5, 4, 3, 2, 0, 0];

        $sum = 0;
        foreach ($ktArray as $key => $digit) {
            $sum += $digit * $multipliers[$key];
        }

        $remainder = 11 - ($sum % 11);
        $secretNumber = intval(substr($id, 8, 1), 0);

        return ($remainder === 11 && $secretNumber === 0) || $remainder === $secretNumber;
    }

    private function sanitize(string $id): string
    {
        $id = str_replace('-', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 10) {
            throw new InvalidLengthException("Icelandic KR must have 10 digits, got $idLength");
        }

        return $id;
    }
}
