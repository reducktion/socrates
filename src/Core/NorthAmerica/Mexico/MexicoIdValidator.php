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
    public const VOWELS = ['A', 'E', 'I', 'O', 'U'];
    public const GENDERS = ['H', 'M'];
    public const BLACKLISTED_NAMES = [
        'BACA', 'BAKA', 'BUEI', 'BUEY', 'CACA', 'CACO', 'CAGA', 'CAGO', 'CAKA', 'CAKO', 'COGE', 'COGI', 'COJA', 'COJE',
        'COJI', 'COJO', 'COLA', 'CULO', 'FALO', 'FETO', 'GETA', 'GUEI', 'GUEY', 'JETA', 'JOTO', 'KACA', 'KACO', 'KAGA',
        'KAGO', 'KAKA', 'KAKO', 'KOGE', 'KOGI', 'KOJA', 'KOJE', 'KOJI', 'KOJO', 'KOLA', 'KULO', 'LILO', 'LOCA', 'LOCO',
        'LOKA', 'LOKO', 'MAME', 'MAMO', 'MEAR', 'MEAS', 'MEON', 'MIAR', 'MION', 'MOCO', 'MOKO', 'MULA', 'MULO', 'NACA',
        'NACO', 'PEDA', 'PEDO', 'PENE', 'PIPI', 'PITO', 'POPO', 'PUTA', 'PUTO', 'QULO', 'RATA', 'ROBA', 'ROBE', 'ROBO',
        'RUIN', 'SENO', 'TETA', 'VACA', 'VAGA', 'VAGO', 'VAKA', 'VUEI', 'VUEY', 'WUEI', 'WUEY',
    ];

    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        if (!$this->validateRegex($id)) {
            return false;
        }

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

        if (!$this->validateConsonants(substr($id, 13, 3))) {
            return false;
        }

        return $this->validateChecksum($id);
    }

    private function sanitize(string $id): string
    {
        $idLength = strlen($id);

        if ($idLength !== 18) {
            throw new InvalidLengthException('Mexico CURP', 18, $idLength);
        }

        return strtoupper($id);
    }

    private function validateRegex(string $id): bool
    {
        return preg_match('/^[A-Z]{4}[0-9]{6}[A-Z]{6}[0-9A-Z][0-9]$/', $id, $matches);
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

        if (in_array($names, self::BLACKLISTED_NAMES)) {
            return false;
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

    private function validateConsonants(string $names): bool
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
