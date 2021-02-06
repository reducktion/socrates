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

    private function getGender(string $id): string
    {
        return (substr($id, 7, 3) % 2) ? Gender::MALE : Gender::FEMALE;
    }

    private function getDateOfBirth(string $id): DateTime
    {
        $dateDigits = substr($id, 0, 6);
        [$day, $month, $year] = str_split($dateDigits, 2);

        $century = $id[6];

        switch ($century) {
            case '+':
                $year += 1800;
                break;
            case '-':
                $year += 1900;
                break;
            case 'A':
                $year += 2000;
                break;
            default:
                throw new InvalidArgumentException("Unrecognised character $century in ID.");
        }

        return new DateTime("$year-$month-$day");
    }
}
