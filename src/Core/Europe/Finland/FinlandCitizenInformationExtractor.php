<?php

namespace Reducktion\Socrates\Core\Europe\Finland;

use DateTime;
use InvalidArgumentException;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;

class FinlandCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (! (new FinlandIdValidator())->validate($id)) {
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
        return (substr($id, 7, 3) % 2) ? Gender::Male : Gender::Female;
    }

    private function getDateOfBirth(string $id): DateTime
    {
        $dateDigits = substr($id, 0, 6);
        [$day, $month, $year] = str_split($dateDigits, 2);

        $separator = $id[6];

        $century = match ($separator) {
            '+' => 1800,
            '-', 'Y', 'X', 'W', 'V', 'U' => 1900,
            'A', 'B', 'C', 'D', 'E', 'F' => 2000,
            default => throw new InvalidArgumentException("Unrecognised character $separator in ID."),
        };

        $year += $century;

        return new DateTime("$year-$month-$day");
    }
}
