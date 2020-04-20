<?php

namespace Reducktion\Socrates\Core\Belgium;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;

class BelgiumCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        $id = $this->sanitize($id);

        if (! (new BelgiumIdValidator())->validate($id)) {
            throw new InvalidIdException("Provided ID is invalid.");
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
        $id = str_replace(['-', '.'], '', $id);

        return $id;
    }

    private function getGender(int $id): string
    {
        return (substr($id, 6, 3) % 2) ? Gender::MALE : Gender::FEMALE;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $dateDigits = substr($id, 0, 6);
        [$year, $month, $day] = str_split($dateDigits, 2);

        $year = $this->isAfter2000($id) ? $year + 2000 : $year + 1900;

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
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
