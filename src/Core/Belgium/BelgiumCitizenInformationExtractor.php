<?php


namespace Reducktion\Socrates\Core\Belgium;


use DateTime;
use DateTimeInterface;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\Belgium\InvalidNrnException;
use Reducktion\Socrates\Exceptions\Belgium\InvalidNrnLengthException;
use Reducktion\Socrates\Models\Citizen;

class BelgiumCitizenInformationExtractor implements CitizenInformationExtractor
{

    public function extract(string $id): Citizen
    {
        $nrn = $this->sanitize($id);

        $gender = $this->getGender($nrn);
        $dateOfBirth = $this->getDateOfBirth($nrn);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dateOfBirth);

        return $citizen;
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

    private function getGender(int $nrn): string
    {
        return (substr($nrn, 6, 3) % 2) ? Gender::MALE : Gender::FEMALE;
    }

    private function getDateOfBirth(string $nrn): DateTimeInterface
    {
        $dateDigits = substr($nrn, 0, 6);
        [$year, $month, $day] = str_split($dateDigits, 2);

        $year = $this->isAfter2000($nrn) ? $year + 2000 : $year + 1900;

        return DateTime::createFromFormat('Y-m-d', "$year-$month-$day");
    }

    private function isAfter2000($nrn): bool
    {
        $checksumFromNrn = (int) substr($nrn, -2);
        $after2000 = false;
        $checksum = $this->calculateChecksum($nrn, $after2000);

        if ($checksum !== $checksumFromNrn) {
            $after2000 = true;
            $checksum = $this->calculateChecksum($nrn, $after2000);
            if ($checksum !== $checksumFromNrn) {
                throw new InvalidNrnException("The NRN introduced is invalid.");
            }
        }

        return $after2000;
    }

    private function calculateChecksum(string $nrn, bool $after2000): int
    {
        if ($after2000) {
            $nrn = '2' . $nrn;
        }

        $number = (int) substr($nrn, 0, -2);

        return 97 - ($number % 97);
    }
}