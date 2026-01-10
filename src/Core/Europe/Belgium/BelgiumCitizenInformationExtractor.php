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
 * Foreign workers have a BIS number. More information about these types of numbers can be found here: https://testdatagenerator.bignited.be/.
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

        $citizen = new Citizen();

        // BIS numbers: month + 20 = gender unknown, month + 40 = gender known
        // Regular NRN: always has gender
        if (!$this->isBisNumber($id) || $this->isBisGenderKnown($id)) {
            $citizen->setGender($this->getGender($id));
        }

        // Extract DOB if birthdate is known (month is not 00, 20, or 40)
        if (!$this->isBirthdateUnknown($id)) {
            $citizen->setDateOfBirth($this->getDateOfBirth($id));
        }

        return $citizen;
    }

    private function sanitize(string $id): string
    {
        return str_replace(['-', ' ', '.'], '', $id);
    }

    private function isBisNumber(string $id): bool
    {
        $month = (int) substr($id, 2, 2);

        return $month > 12;
    }

    private function isBisGenderKnown(string $id): bool
    {
        $month = (int) substr($id, 2, 2);

        // Month + 40 means gender is known, month + 20 means gender is unknown
        return $month >= 40;
    }

    private function isBirthdateUnknown(string $id): bool
    {
        $month = (int) substr($id, 2, 2);

        // For BIS numbers only: month 20 or 40 means birthdate is unknown
        // Regular NRN with month 00 still extracts DOB (defaults to January)
        return $month === 20 || $month === 40;
    }

    private function getGender(string $id): Gender
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

        // BIS numbers have 20 or 40 added to the month
        if ($month >= 40) {
            $month -= 40;
        } elseif ($month >= 20) {
            $month -= 20;
        }

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
