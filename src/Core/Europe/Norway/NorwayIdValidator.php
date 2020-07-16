<?php

namespace Reducktion\Socrates\Core\Europe\Norway;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class NorwayIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $idLength = strlen($id);

        if ($idLength !== 11) {
            throw new InvalidLengthException('Norwegian fødselsnummer', '11', $idLength);
        }

        $firstControlDigit = (int) $id[9];
        $secondControlDigit = (int) $id[10];

        $firstDayDigit = (int) $id[0];
        $secondDayDigit = (int) $id[1];
        $firstMonthDigit = (int) $id[2];
        $secondMonthDigit = (int) $id[3];
        $firstYearDigit = (int) $id[4];
        $secondYearDigit = (int) $id[5];
        $firstPersonalDigit = (int) $id[6];
        $secondPersonalDigit = (int) $id[7];
        $thirdPersonalDigit = (int) $id[8];

        $firstTest = 11 - (((3 * $firstDayDigit) + (7 * $secondDayDigit)
                    + (6 * $firstMonthDigit) + $secondMonthDigit
                    + (8 * $firstYearDigit) + (9 * $secondYearDigit)
                    + (4 * $firstPersonalDigit) + (5 * $secondPersonalDigit) + (2 * $thirdPersonalDigit)) % 11);

        $firstControlResult = $firstTest === 11 ? 0 : $firstTest;

        $secondTest = 11 - (((5 * $firstDayDigit) + (4 * $secondDayDigit)
                    + (3 * $firstMonthDigit) + (2 * $secondMonthDigit)
                    + (7 * $firstYearDigit) + (6 * $secondYearDigit)
                    + (5 * $firstPersonalDigit) + (4 * $secondPersonalDigit)
                    + (3 * $thirdPersonalDigit) + (2 * $firstControlResult)) % 11);

        $secondControlResult = $secondTest === 11 ? 0 : $secondTest;

        return $firstControlDigit === $firstControlResult
            && $secondControlDigit === $secondControlResult;
    }
}
