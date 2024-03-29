<?php

namespace Reducktion\Socrates\Core\Europe\Lithuania;

use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;

class LithuaniaCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (! (new LithuaniaIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        $gender = $this->getGender($id);
        $dateOfBirth = $this->getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dateOfBirth);

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

        $firstDigit = (int) $id[0];
        $calc = (int) floor(($firstDigit + 34) / 2);

        $century = ($firstDigit % 2) ?
            $firstDigit + $calc - ($firstDigit - 2) :
            $firstDigit + $calc - ($firstDigit - 1);

        $year = (($century - 1) * 100) + $year;

        return new DateTime("$year-$month-$day");
    }
}
