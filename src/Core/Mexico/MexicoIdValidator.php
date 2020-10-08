<?php

namespace Reducktion\Socrates\Core\Mexico;

use Reducktion\Socrates\Contracts\IdValidator;
use Facades\Reducktion\Socrates\Core\Mexico\Validators\Gender as GenderValidator;
use Facades\Reducktion\Socrates\Core\Mexico\Validators\Birthdate as BirthdateValidator;
use Facades\Reducktion\Socrates\Core\Mexico\Validators\State as StateValidator;
use Facades\Reducktion\Socrates\Core\Mexico\Validators\Consoants as ConsoantsValidator;
use Facades\Reducktion\Socrates\Core\Mexico\Validators\Names as NamesValidator;
use Facades\Reducktion\Socrates\Core\Mexico\Validators\Checksum as ChecksumValidator;
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

    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        if (!NamesValidator::validate(substr($id, 0, 3))) {
            return false;
        }

        if (!BirthdateValidator::validate(substr($id, 4, 5))) {
            return false;
        }

        if (!GenderValidator::validate($id[10])) {
            return false;
        }

        if (!StateValidator::validate(substr($id, 11, 2))) {
            return false;
        }

        if (!ConsoantsValidator::validate(substr($id, 13, 3))) {
            return false;
        }

        return ChecksumValidator::validate($id);
    }

    private function sanitize(string $id): string
    {
        $idLength = strlen($id);

        if ($idLength !== SELF::CURP_SIZE) {
            throw new InvalidLengthException('Mexico CURP', self::CURP_SIZE, $idLength);
        }

        return strtoupper($id);
    }
}
