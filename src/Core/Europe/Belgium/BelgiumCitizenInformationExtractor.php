<?php

namespace Reducktion\Socrates\Core\Europe\Belgium;

use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;

/**
 * Class BelgiumCitizenInformationExtractor
 *
 * Algorithm adapted from: http://www.ibz.rrn.fgov.be/fileadmin/user_upload/nl/rr/instructies/IT-lijst/IT000_Rijksregisternummer.pdf.
 *
 * @package Reducktion\Socrates\Core\Belgium
 */
class BelgiumCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        $id = $this->sanitize($id);

        if (! (new BelgiumIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        $gender = $this->getGender($id);
        $dateOfBirth = $this->getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dateOfBirth);

        return $citizen;
    }

    private function sanitize(string $id): string
    {
        return str_replace(['-', ' ', '.'], '', $id);
    }

    private function getGender(int $id): Gender
    {
        return (substr($id, 6, 3) % 2) ? Gender::Male : Gender::Female;
    }

    private function getDateOfBirth(string $id): DateTime
    {
        $dateDigits = substr($id, 0, 6);
        [$year, $month, $day] = str_split($dateDigits, 2);

        $year = (int) $year;
        $month = (int) $month;
        $day = (int) $day;

        // use first day or month if unknown
        $month = $month === 0 ? 1 : $month;
        $day = $day === 0 ? 1 : $day;

        $year = $this->isAfter2000($id) ? $year + 2000 : $year + 1900;

        return new DateTime("$year-$month-$day");
    }

    private function isAfter2000($id): bool
    {
        $checksumFromId = (int) substr($id, -2);
        $after2000 = false;
        $checksum = $this->calculateChecksum($id, $after2000);

        if ($checksum !== $checksumFromId) {
            $after2000 = true;
        }

        return $after2000;
    }

    private function calculateChecksum(string $id, bool $after2000): int
    {
        if ($after2000) {
            $id = '2' . $id;
        }

        $number = (int) substr($id, 0, -2);

        return 97 - ($number % 97);
    }
}
