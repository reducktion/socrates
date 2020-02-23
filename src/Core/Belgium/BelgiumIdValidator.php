<?php


namespace Reducktion\Socrates\Core\Belgium;


use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\Belgium\InvalidNrnLengthException;

class BelgiumIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        $nrn = $this->sanitize($id);

        $checksumFromNrn = (int) substr($nrn, -2);
        $after2000 = false;
        $checksum = $this->calculateChecksum($nrn, $after2000);

        if ($checksum !== $checksumFromNrn) {
            $after2000 = true;
            $checksum = $this->calculateChecksum($nrn, $after2000);
            if ($checksum !== $checksumFromNrn) {
                return false;
            }
        }

        if (! $this->validDateOfBirth($nrn, $after2000)) {
            return false;
        }

        return true;
    }

    private function sanitize(string $id): string
    {
        $nrn = str_replace('-', '', $id);
        $nrn = str_replace('.', '', $nrn);

        $nrnLength = strlen($nrn);

        if ($nrnLength !== 11) {
            throw new InvalidNrnLengthException("Belgium NRN must have 11 digits, got $nrnLength");
        }

        return $nrn;
    }

    private function calculateChecksum(string $nrn, bool $after2000): int
    {
        if ($after2000) {
            $nrn = '2' . $nrn;
        }

        $number = (int) substr($nrn, 0, -2);

        return 97 - ($number % 97);
    }

    private function validDateOfBirth(string $nrn, bool $after2000): bool
    {
        $dateDigits = substr($nrn, 0, 6);
        [$year, $month, $day] = str_split($dateDigits, 2);

        if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
            return false;
        }

        $year = $after2000 ? $year + 2000 : $year + 1900;

        if (strtotime("$year-$month-$day") > strtotime("-12 years")) {
            return false;
        }

        return true;
    }

}