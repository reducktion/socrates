<?php

namespace Reducktion\Socrates\Core\NorthAmerica\Canada;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

/**
 * Class CanadaIdValidator
 *
 * Algorithm adapted from: https://en.wikipedia.org/wiki/Social_Insurance_Number
 *
 * @package Reducktion\Socrates\Core\NorthAmerica\Canada
 */
class CanadaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        $id = str_split($id);
        $parity = count($id) % 2;
        $sum = 0;
        foreach ($id as $key => $value) {
            if ($key % 2 === $parity) {
                $value *= 2;
            }

            $sum += ($value >= 10 ? $value - 9 : $value);
        }

        return $sum % 10 === 0;
    }

    public function sanitize(string $id): string
    {
        $id = str_replace(' ', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 9) {
            throw new InvalidLengthException('Canadian SIN', '9', $idLength);
        }

        return $id;
    }
}