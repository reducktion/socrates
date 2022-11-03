<?php

namespace Reducktion\Socrates\Core\Europe\Hungary;

use DateTime;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;

class HungaryCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        $id = $this->sanitize($id);

        if (! (new HungaryIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        $citizen = new Citizen();
        $citizen->setGender($this->getGender($id));
        $citizen->setDateOfBirth($this->getDateOfBirth($id));

        return $citizen;
    }

    private function sanitize(string $id): string
    {
        return str_replace('-', '', $id);
    }

    private function getGender(string $id): Gender
    {
        return ($id[0] % 2) ? Gender::Male : Gender::Female;
    }

    private function getDateOfBirth(string $id): DateTime
    {
        $dateDigits = substr($id, 1, 6);
        [$twoDigitYear, $month, $day] = str_split($dateDigits, 2);

        if ((int) $id[0] === 3 || (int) $id[0] === 4) {
            $year = 2000 + $twoDigitYear;
        } else {
            $year = 1900 + $twoDigitYear;
        }

        return new DateTime("$year-$month-$day");
    }
}
