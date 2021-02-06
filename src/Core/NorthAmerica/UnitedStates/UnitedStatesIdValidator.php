<?php

namespace Reducktion\Socrates\Core\NorthAmerica\UnitedStates;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

/**
 * Class UnitedStatesIdValidator
 *
 * Algorithm adapted from: https://en.wikipedia.org/wiki/Social_Security_number
 *
 * @package Reducktion\Socrates\Core\NorthAmerica\UnitedStates
 */
class UnitedStatesIdValidator implements IdValidator
{
    private $blacklist = [
        '078051120',
        '219099999',
        '457555462'
    ];

    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        if (in_array($id, $this->blacklist, true)) {
            return false;
        }

        $areaNumber = (int) substr($id, 0, 3);
        if ($areaNumber === 0 || $areaNumber === 666 || $areaNumber > 899) {
            return false;
        }

        $groupNumber = (int) substr($id, 3, 2);
        if ($groupNumber === 0) {
            return false;
        }

        $serialNumber = (int) substr($id, 5, 4);

        return !($serialNumber === 0);
    }

    private function sanitize(string $id): string
    {
        $id = str_replace('-', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 9) {
            throw new InvalidLengthException('American SSN', '9', $idLength);
        }

        return $id;
    }
}
