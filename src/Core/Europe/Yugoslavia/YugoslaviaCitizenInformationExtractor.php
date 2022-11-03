<?php

namespace Reducktion\Socrates\Core\Europe\Yugoslavia;

use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Exceptions\UnrecognisedPlaceOfBirthException;
use Reducktion\Socrates\Models\Citizen;

class YugoslaviaCitizenInformationExtractor
{
    public static function extract(string $id): Citizen
    {
        if (! (new YugoslaviaIdValidator())::validate($id)) {
            throw new InvalidIdException();
        }

        $gender = self::getGender($id);
        $dob = self::getDateOfBirth($id);
        $pob = self::getPlaceOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dob);
        $citizen->setPlaceOfBirth($pob);

        return $citizen;
    }

    private static function getGender(string $id): Gender
    {
        return ((int) substr($id, 9, 3)) < 500 ? Gender::Male : Gender::Female;
    }

    private static function getDateOfBirth(string $id): DateTime
    {
        $day = substr($id, 0, 2);
        $month = substr($id, 2, 2);
        $year = (int) substr($id, 4, 3);

        $currentYear = (int) (new DateTime())->format('Y');

        if ($year + 1000 < $currentYear && $year + 1000 > 1900) {
            $year += 1000;
        } else {
            $year += 2000;
        }

        return new DateTime("$year-$month-$day");
    }

    private static function getPlaceOfBirth(string $id): string
    {
        $pobCode = substr($id, 7, 2);

        if (! isset(YugoslaviaRegions::$regions[$pobCode])) {
            throw new UnrecognisedPlaceOfBirthException(
                "The provided code '$pobCode' does not match any region codes."
            );
        }

        return YugoslaviaRegions::$regions[$pobCode];
    }
}
