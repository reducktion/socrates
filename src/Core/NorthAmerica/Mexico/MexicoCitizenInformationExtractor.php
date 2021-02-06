<?php

namespace Reducktion\Socrates\Core\NorthAmerica\Mexico;

use DateTime;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;

class MexicoCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (!(new MexicoIdValidator())->validate($id)) {
            throw new InvalidIdException();
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
        $identifier = $id[10];

        if ($identifier === 'H') {
            return Gender::MALE;
        }

        return Gender::FEMALE;
    }

    private function getDateOfBirth(string $id): DateTime
    {
        $year = (int) substr($id, 4, 2);
        $month = (int) substr($id, 6, 2);
        $day = (int) substr($id, 8, 2);

        $year += is_numeric($id[16]) ? 1900 : 2000;

        return new DateTime("$year-$month-$day");
    }
}
