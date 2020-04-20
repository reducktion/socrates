<?php

namespace Reducktion\Socrates\Core\Estonia;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;

class EstoniaCitizenInformationExtractor implements CitizenInformationExtractor
{

    public function extract(string $id): Citizen
    {
        if (! (new EstoniaIdValidator())->validate($id)) {
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
        return ($id[0] % 2) ? Gender::MALE : Gender::FEMALE;
    }

    private function getDateOfBirth(string $id): Carbon
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

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }
}