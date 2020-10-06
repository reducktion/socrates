<?php

namespace Reducktion\Socrates\Core\Europe\Belgium;

use Carbon\Carbon;
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

        $checksumFromId = (int) substr($id, -2);
        $after2000 = false;
        $checksum = $this->calculateChecksum($id, $after2000);

        if ($checksum !== $checksumFromId) {
            $after2000 = true;
            $checksum = $this->calculateChecksum($id, $after2000);
            if ($checksum !== $checksumFromId) {
                return false;
            }
        }

        if (! $this->validDateOfBirth($id, $after2000)) {
            return false;
        }

        return true;
    }

    private function sanitize(string $id): string
    {
        $id = str_replace(['-', '.'], '', $id);

        $idLength = strlen($id);

        if ($idLength !== 11) {
            throw new InvalidLengthException('Belgian NRN', '11', $idLength);
        }

        return $id;
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

        if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
            return false;
        }

        $year = $after2000 ? $year + 2000 : $year + 1900;

        $dob = Carbon::createFromFormat('Y-m-d', "$year-$month-$day");

        if ($dob->greaterThan($dob->subYears(12))) {
            return false;
        }

        return true;
    }
}
