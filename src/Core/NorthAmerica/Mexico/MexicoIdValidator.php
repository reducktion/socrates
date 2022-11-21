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
    private const VOWELS = ['A', 'E', 'I', 'O', 'U'];
    private const GENDERS = ['H', 'M'];
    private const BLACKLISTED_NAMES = [
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
        return preg_match('/^[A-Z]{4}\d{6}[A-Z]{6}[0-9A-Z]\d$/', $id);
    }

    private function validateNames(string $names): bool
    {
        $length = strlen($names);
        for ($i = 0; $i < $length; $i++) {
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
        $length = strlen($birthdate);
        for ($i = 0; $i < $length; $i++) {
            if (!is_numeric($birthdate[$i])) {
                return false;
            }
        }

        if (!in_array((int)substr($birthdate, 2, 2), range(1, 12), true)) {
            return false;
        }

        if (!in_array((int)substr($birthdate, 4, 2), range(1, 31), true)) {
            return false;
        }

        return true;
    }

    private function validateGender(string $gender): bool
    {
        return !(!ctype_alpha($gender) || !in_array($gender, self::GENDERS));
    }

    private function validateState(string $state): bool
    {
        return !(!array_key_exists($state, MexicoStatesList::$states) || !ctype_alpha($state));
    }

    private function validateConsonants(string $names): bool
    {
        if (!ctype_alpha($names)) {
            return false;
        }

        $length = strlen($names);
        for ($i = 0; $i < $length; $i++) {
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

        $length = strlen($code);
        for ($i = 0; $i < $length; $i++) {
            $check += array_search($code[$i], str_split($alphabet), true) * (18 - $i);
        }
        return (10 - $check % 10) % 10 === (int) $id[17];
    }
}
