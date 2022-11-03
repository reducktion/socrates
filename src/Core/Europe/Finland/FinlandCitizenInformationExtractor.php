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

        $century = $id[6];

        $year += match ($century) {
            '+' => 1800,
            '-' => 1900,
            'A' => 2000,
            default => throw new InvalidArgumentException("Unrecognised character $century in ID."),
        };

        return new DateTime("$year-$month-$day");
    }
}
