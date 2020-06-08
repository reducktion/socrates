<?php

namespace Reducktion\Socrates\Core\Albania;

use Carbon\Carbon;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;

class AlbaniaCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (!(new AlbaniaIdValidator())->validate($id)) {
            throw new InvalidIdException("Provided ID is invalid.");
        }

        $gender = $this->getGender($id);
        $dob = $this->getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dob);

        return $citizen;
    }

    private function getGender(string $id): string
    {
        $identifier = (int) $id[2];

        if ($identifier === 0 || $identifier === 1) {
            return Gender::MALE;
        }

        if ($identifier === 5 || $identifier === 6) {
            return Gender::FEMALE;
        }

        return Gender::FEMALE;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $dateDigits = substr($id, 0, 6);
        [$yearCode, $monthDigits, $day] = str_split($dateDigits, 2);

        $year = $this->getYearFromCode($yearCode);

        $monthDigits = (int)$monthDigits;

        $month = $this->getMonthFromDigits($monthDigits, $this->getGender($id));

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }

    private function getYearFromCode(string $yearCode): int
    {
        $yearCodeLetterMap = [
            'A' => 1900,
            'B' => 1910,
            'C' => 1920,
            'D' => 1930,
            'E' => 1940,
            'F' => 1950,
            'G' => 1960,
            'H' => 1970,
            'I' => 1980,
            'J' => 1990,
            'K' => 2000,
            'L' => 2010,
            'M' => 2020,
            'N' => 2030,
            'O' => 2040,
            'P' => 2050,
            'Q' => 2060,
            'R' => 2070,
            'S' => 2080,
            'T' => 2090,
        ];

        [$yearCodeLetter, $yearCodeDigit] = str_split($yearCode, 1);

        $yearCodeDigit = (int)$yearCodeDigit;

        return $yearCodeLetterMap[$yearCodeLetter] + $yearCodeDigit;
    }

    private function getMonthFromDigits(int $monthDigits, string $gender): int
    {
        return $gender === Gender::FEMALE
            ? $monthDigits - 50
            : $monthDigits;
    }
}
