<?php

namespace Reducktion\Socrates\Core\Europe\Belgium;

use DateTime;
use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

/**
 * Class BelgiumIdValidator
 *
 * Algorithm adapted from: http://www.ibz.rrn.fgov.be/fileadmin/user_upload/nl/rr/instructies/IT-lijst/IT000_Rijksregisternummer.pdf.
 *
 * @package Reducktion\Socrates\Core\Belgium
 */
class BelgiumIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        if (! $this->validateSequenceNumber($id)) {
            return false;
        }

        $checksumFromId = (int) substr($id, -2);
        $after2000 = false;

        $checksum = $this->calculateChecksum($id, after2000: false);

        if ($checksum !== $checksumFromId) {
            $after2000 = true;
        }

        $checksum = $this->calculateChecksum($id, $after2000);
        if ($checksum !== $checksumFromId) {
            return false;
        }

        if (! $this->validDateOfBirth($id, $after2000)) {
            return false;
        }

        return true;
    }

    private function sanitize(string $id): string
    {
        $id = str_replace(['-', ' ', '.'], '', $id);

        $idLength = strlen($id);

        if ($idLength !== 11) {
            throw new InvalidLengthException('Belgian NRN', '11', $idLength);
        }

        return $id;
    }

    private function validateSequenceNumber(string $id): bool
    {
        $sequenceNumber = (int) substr($id, 6, 3);

        return $sequenceNumber !== 0 && $sequenceNumber !== 999;
    }

    private function calculateChecksum(string $id, bool $after2000): int
    {
        if ($after2000) {
            $id = '2' . $id;
        }

        $number = (int) substr($id, 0, -2);

        return 97 - ($number % 97);
    }

    private function validDateOfBirth(string $id, bool $after2000): bool
    {
        $dateDigits = substr($id, 0, 6);
        [$year, $month, $day] = str_split($dateDigits, 2);

        $year = (int) $year;
        $month = (int) $month;
        $day = (int) $day;

        $month = $month === 0 ? 1 : $month;
        $day = $day === 0 ? 1 : $day;

        if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
            return false;
        }

        $year = $after2000 ? $year + 2000 : $year + 1900;

        $dateOfBirth = new DateTime("$year-$month-$day");

        return (new DateTime())->diff($dateOfBirth)->y >= 12;
    }
}
