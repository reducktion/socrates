<?php

namespace Reducktion\Socrates\Core\Europe\Bulgaria;

use DateTime;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;

class BulgariaCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (! (new BulgariaIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        $gender = $this->getGender($id);
        $dob = $this->getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dob);

        return $citizen;
    }

    private function getGender(string $id): Gender
    {
        return ($id % 2) ? Gender::Male : Gender::Female;
    }

    private function getDateOfBirth(string $id): DateTime
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

        return new DateTime("$year-$month-$day");
    }
}
