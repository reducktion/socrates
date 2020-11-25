<?php

namespace Reducktion\Socrates\Core\Europe\Poland;

use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;

class PolandCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (! (new PolandIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        $gender = $this->getGender($id);
        $dob = $this->getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dob);

        return $citizen;
    }

    private function getGender($id): string
    {
        return ($id[9] % 2) ? Gender::MALE : Gender::FEMALE;
    }

    private function getDateOfBirth($id): DateTime
    {
        $dateDigits = substr($id, 0, 6);
        [$year, $month, $day] = str_split($dateDigits, 2);

        if ($month > 80 && $month < 93) {
            $month -= 80;
            $year += 1800;
        }

        if ($month > 0 && $month < 13) {
            $year += 1900;
        }

        if ($month > 20 && $month < 33) {
            $month -= 20;
            $year += 2000;
        }

        if ($month > 40 && $month < 53) {
            $month -= 40;
            $year += 2100;
        }

        if ($month > 60 && $month < 73) {
            $month -= 60;
            $year += 2200;
        }

        return new DateTime("$year-$month-$day");
    }
}
