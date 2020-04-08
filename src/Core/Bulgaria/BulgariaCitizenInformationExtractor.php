<?php

namespace Reducktion\Socrates\Core\Bulgaria;

use Carbon\Carbon;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;

class BulgariaCitizenInformationExtractor implements CitizenInformationExtractor
{

    public function extract(string $id): Citizen
    {
        $idLength = strlen($id);

        if ($idLength !== 10) {
            throw new InvalidLengthException("The Bulgarian uniform civil number must have 10 digits, got $idLength");
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
        return ($id % 2) ? Gender::MALE : Gender::FEMALE;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $dateDigits = substr($id, 0, 6);
        [$twoDigitYear, $month, $day] = str_split($dateDigits, 2);

        if ($month - 40 > 0) {
            $month -= 40;
            $year = 2000 + $twoDigitYear;
        } elseif ($month - 20 < 0) {
            $year = 1900 + $twoDigitYear;
        } else {
            $month -= 20;
            $year = 1800 + $twoDigitYear;
        }

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }

}