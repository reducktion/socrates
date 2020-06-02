<?php

namespace Reducktion\Socrates\Core\Lithuania;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class LithuaniaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $idLength = strlen($id);
        if ($idLength !== 11) {
            throw new InvalidLengthException("Lithuanian AK must have 11 digits, got $idLength");
        }

        $control = substr($id, -1);
        $id = substr($id, 0, -1);

        return (int) $control === $this->calculateChecksum($id);
    }

    private function calculateChecksum(string $id): int
    {
        $b = 1;
        $c = 3;
        $d = 0;
        $e = 0;

        for ($i = 0; $i < 10; $i++) {
            $digit = (int) $id[$i];
            $d += $digit * $b;
            $e += $digit * $c;
            $b++;
            if ($b === 10) {
                $b = 1;
            }
            $c++;
            if ($c === 10) {
                $c = 1;
            }
        }
        $d %= 11;
        $e %= 11;
        if ($d < 10) {
            return $d;
        }

        if ($e < 10) {
            return $e;
        }

        return 0;
    }
}
