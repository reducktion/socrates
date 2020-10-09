<?php

namespace Reducktion\Socrates\Core\NorthAmerica\Mexico;

use Carbon\Carbon;
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

        if ($identifier === MexicoIdValidator::GENDERS[0]) {
            return Gender::MALE;
        }

        return Gender::FEMALE;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $year = intval(substr($id, 4, 2));
        $month = intval(substr($id, 6, 2));
        $day = intval(substr($id, 8, 2));

        $year += is_numeric($id[16]) ? 1900 : 2000;

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }
}
