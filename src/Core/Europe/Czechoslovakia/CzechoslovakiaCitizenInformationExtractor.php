<?php

namespace Reducktion\Socrates\Core\Europe\Czechoslovakia;

use DateTime;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Constants\Gender;

class CzechoslovakiaCitizenInformationExtractor
{
    public static function extract(string $id): Citizen
    {
        $id = str_replace('/', '', $id);

        if (! (new CzechoslovakiaIdValidator())::validate($id)) {
            throw new InvalidIdException();
        }

        $gender = self::getGender($id);
        $dob = self::getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dob);

        return $citizen;
    }

    private static function getGender(string $id): string
    {
        $monthDigits = (int) substr($id, 2, 2);
        return ($monthDigits < 13) ? Gender::MALE : Gender::FEMALE;
    }

    private static function getDateOfBirth(string $id): DateTime
    {
        $day = substr($id, 4, 2);
        $month = (int) substr($id, 2, 2);
        $year = (int) substr($id, 0, 2);

        $year = $year > 54 ? 1900 + $year : 2000 + $year;

        $month = $month > 12 ? $month - 50 : $month;

        return new DateTime("$year-$month-$day");
    }
}
