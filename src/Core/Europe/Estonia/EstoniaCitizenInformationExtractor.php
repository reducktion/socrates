<?php

namespace Reducktion\Socrates\Core\Europe\Estonia;

use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;

class EstoniaCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (! (new EstoniaIdValidator())->validate($id)) {
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
        return ($id[0] % 2) ? Gender::Male : Gender::Female;
    }

    private function getDateOfBirth(string $id): DateTime
    {
        $dateDigits = substr($id, 1, 6);
        [$year, $month, $day] = str_split($dateDigits, 2);

        if ($id[0] < 3) {
            $year += 1800;
        }

        if ($id[0] > 2 && $id[0] < 5) {
            $year += 1900;
        }

        if ($id[0] > 4 && $id[0] < 7) {
            $year += 2000;
        }

        return new DateTime("$year-$month-$day");
    }
}
