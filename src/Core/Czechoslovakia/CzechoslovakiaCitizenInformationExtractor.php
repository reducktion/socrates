<?php

namespace Reducktion\Socrates\Core\Czechoslovakia;

use Carbon\Carbon;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

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

    private static function getDateOfBirth(string $id): Carbon
    {
        $day = substr($id, 4, 2);
        $month = (int) substr($id, 2, 2);
        $year = (int) substr($id, 0, 2);

        $year = $year > 54 ? 1900 + $year : 2000 + $year;

        $month = $month > 12 ? $month - 50 : $month;

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }
}
