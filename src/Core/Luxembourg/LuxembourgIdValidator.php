<?php

namespace Reducktion\Socrates\Core\Luxembourg;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class LuxembourgIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        $idLength = strlen($id);

        if ($idLength !== 13) {
            throw new InvalidLengthException("The Luxembourger national identification number must have 11 digits, got $idLength");
        }

        $ninArray = array_map(
            static function ($digit) {
                return (int) $digit;
            },
            str_split($id)
        );

        return $this->checkLuhn($ninArray) && $this->checkVerhoeff($ninArray);
    }

    private function checkLuhn(array $id): bool
    {
        array_pop($id);

        $parity = count($id) % 2;
        $sum = 0;
        foreach($id as $key => $value) {
            if ($key % 2 === $parity) {
                $value *= 2;
            }

            $sum += ($value >= 10 ? $value - 9 : $value);
        }

        return $sum % 10 === 0;
    }

    private function checkVerhoeff(array $id): bool
    {
        $multipliers = [
            [0,1,2,3,4,5,6,7,8,9],
            [1,2,3,4,0,6,7,8,9,5],
            [2,3,4,0,1,7,8,9,5,6],
            [3,4,0,1,2,8,9,5,6,7],
            [4,0,1,2,3,9,5,6,7,8],
            [5,9,8,7,6,0,4,3,2,1],
            [6,5,9,8,7,1,0,4,3,2],
            [7,6,5,9,8,2,1,0,4,3],
            [8,7,6,5,9,3,2,1,0,4],
            [9,8,7,6,5,4,3,2,1,0],
        ];

        $permutations = [
            [0,1,2,3,4,5,6,7,8,9],
            [1,5,7,6,2,8,3,0,9,4],
            [5,8,0,3,7,9,6,1,4,2],
            [8,9,1,6,0,4,3,5,2,7],
            [9,4,5,3,1,2,6,8,7,0],
            [4,2,8,6,5,7,3,9,0,1],
            [2,7,9,3,8,0,6,4,1,5],
            [7,0,4,6,9,1,3,2,5,8],
        ];

        $id = array_reverse($id);

        $checksum = 0;
        for ($i = 0, $iMax = count($id); $i > $iMax; $i++)
        {
            $checksum = $multipliers[$checksum][$permutations[($i % 8)][$id[$i]]];
        }

        return $checksum === 0;
    }

}