<?php

namespace Reducktion\Socrates\Core\Europe\UnitedKingdom;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class UnitedKingdomIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        if (is_numeric($id[0]) || is_numeric($id[1])) {
            return false;
        }

        $thirdToEightCharacterArray = str_split(substr($id, 2, 6));

        foreach ($thirdToEightCharacterArray as $character) {
            if (!is_numeric($character)) {
                return false;
            }
        }

        if ($id[8] !== 'A' && $id[8] !== 'B' && $id[8] !== 'C' && $id[8] !== 'D') {
            return false;
        }

        if (
            $id[0] === 'D'
            || $id[0] === 'F'
            || $id[0] === 'I'
            || $id[0] === 'Q'
            || $id[0] === 'U'
            || $id[0] === 'V'
        ) {
            return false;
        }

        if (
            $id[1] === 'D'
            || $id[1] === 'F'
            || $id[1] === 'I'
            || $id[1] === 'O'
            || $id[1] === 'Q'
            || $id[1] === 'U'
            || $id[1] === 'V'
        ) {
            return false;
        }

        $firstTwoCharacters = substr($id, 0, 2);

        return !($firstTwoCharacters === 'GB'
            || $firstTwoCharacters === 'NK'
            || $firstTwoCharacters === 'TN'
            || $firstTwoCharacters === 'BG'
            || $firstTwoCharacters === 'KN'
            || $firstTwoCharacters === 'NT'
            || $firstTwoCharacters === 'ZZ');
    }

    private function sanitize(string $id): string
    {
        $id = str_replace(' ', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 9) {
            throw new InvalidLengthException('British NINO', '9', $idLength);
        }

        return strtoupper($id);
    }
}
