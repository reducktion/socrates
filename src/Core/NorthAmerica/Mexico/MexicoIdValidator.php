<?php

namespace Reducktion\Socrates\Core\NorthAmerica\Mexico;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

/**
 * Class MexicoIdValidator
 *
 * Algorithm adapted from: https://sayari.com/blog/breaking-down-mexican-national-id/.
 *
 * @package Reducktion\Socrates\Core\Mexico
 */
class MexicoIdValidator implements IdValidator
{
    // Mexican id number size
    public const CURP_SIZE = 18;
    public const VOWELS = ['A', 'E', 'I', 'O', 'U'];
    public const GENDERS = ['H', 'M'];

    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        if (!$this->validateNames(substr($id, 0, 3))) {
            return false;
        }

        if (!$this->validateBirthdate(substr($id, 4, 6))) {
            return false;
        }

        if (!$this->validateGender($id[10])) {
            return false;
        }

        if (!$this->validateState(substr($id, 11, 2))) {
            return false;
        }

        if (!$this->validateConsoants(substr($id, 13, 3))) {
            return false;
        }

        return $this->validateChecksum($id);
    }

    private function sanitize(string $id): string
    {
        $idLength = strlen($id);

        if ($idLength !== self::CURP_SIZE) {
            throw new InvalidLengthException('Mexico CURP', self::CURP_SIZE, $idLength);
        }

        return strtoupper($id);
    }

    private function validateNames(string $names): bool
    {
        for ($i = 0; $i < strlen($names); $i++) {
            if (!ctype_alpha($names[$i])) {
                return false;
            }

            if (!in_array($names[1], self::VOWELS)) {
                return false;
            }
        }

        return true;
    }

    private function validateBirthdate(string $birthdate): bool
    {
        for ($i = 0; $i < strlen($birthdate); $i++) {
            if (!is_numeric($birthdate[$i])) {
                return false;
            }
        }

        //validate month
        if (!in_array(intval(substr($birthdate, 2, 2)), range(1, 12))) {
            return false;
        }

        //validate day
        if (!in_array(intval(substr($birthdate, 4, 2)), range(1, 31))) {
            return false;
        }

        return true;
    }

    private function validateGender(string $gender): bool
    {
        if (
            !ctype_alpha($gender) ||
            !in_array($gender, self::GENDERS)
        ) {
            return false;
        }

        return true;
    }

    private function validateState(string $state): bool
    {
        if (
            !ctype_alpha($state) ||
            !in_array($state, array_keys(MexicoStatesList::$states))
        ) {
            return false;
        }

        return true;
    }

    private function validateConsoants(string $names): bool
    {
        if (!ctype_alpha($names)) {
            return false;
        }

        for ($i = 0; $i < strlen($names); $i++) {
            if (in_array($names[$i], self::VOWELS)) {
                return false;
            }
        }

        return true;
    }

    private function validateChecksum(string $id): bool
    {
        $alphabet = "0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ";

        if (!is_numeric($id[17])) {
            return false;
        }

        $code = substr($id, 0, 17);
        $check = 0;

        for ($i = 0; $i < strlen($code); $i++) {
            $check += array_search($code[$i], str_split($alphabet)) * (18 - $i);
        }
        return (10 - $check % 10) % 10 === intval($id[17]);
    }
}
