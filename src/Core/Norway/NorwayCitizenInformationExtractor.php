<?php

namespace Reducktion\Socrates\Core\Norway;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;

class NorwayCitizenInformationExtractor implements CitizenInformationExtractor
{

    public function extract(string $id): Citizen
    {
        if (! (new NorwayIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        $individualNumber = (int) substr($id, 6, 3);

        $gender = $this->getGender($individualNumber);
        $dateOfBirth = $this->getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dateOfBirth);

        return $citizen;
    }

    private function getGender(int $individualNumber): string
    {
        return ($individualNumber % 2) ? Gender::MALE : Gender::FEMALE;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $day = (int) substr($id, 0, 2);
        $month = (int) substr($id, 2, 2);
        $twoDigitYear = (int) substr($id, 4, 2);

        if ($day > 31) {
            $day -= 40;
        }

        if ($day >= 80) {
            $day = 1;
        }

        if ($month > 30) {
            $month -= 40;
        }

        $individualNumber = (int) substr($id, 6, 3);
        $century = 2000;

        if ($individualNumber < 500) {
            $century = 1900;
        }

        if ($individualNumber >= 900) {
            $century = 1900;
        }

        $year = $century + $twoDigitYear;

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }
}
