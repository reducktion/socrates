<?php

namespace Reducktion\Socrates\Core\Europe\Ukraine;

use DateTime;
use DateInterval;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;

class UkraineCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (! (new UkraineIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        $gender = $this->getGender($id[8]);

        $dateOfBirth = $this->getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dateOfBirth);

        return $citizen;
    }

    private function getGender(int $genderDigit): string
    {
        return $genderDigit % 2 ? Gender::MALE : Gender::FEMALE;
    }

    private function getDateOfBirth(string $id): DateTime
    {
        $birthDateDigits = (int) substr($id, 0, 5) - 1;

        $date = new DateTime('1900-01-01');
        $date->add(new DateInterval("P{$birthDateDigits}D"));

        return $date;
    }
}
