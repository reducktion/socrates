<?php

namespace Reducktion\Socrates\Core\Europe\France;

use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Exceptions\UnrecognisedPlaceOfBirthException;
use Reducktion\Socrates\Models\Citizen;

class FranceCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        $id = $this->sanitize($id);

        if (! (new FranceIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        $gender = $this->getGender($id);
        $dob = $this->getDateOfBirth($id);
        $pob = $this->getPlaceOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dob);
        $citizen->setPlaceOfBirth($pob);

        return $citizen;
    }

    private function sanitize(string $id): string
    {
        $id = str_replace(' ', '', $id);

        return $id;
    }

    private function getGender(string $id): Gender
    {
        return ((int) $id[0]) === 1 ? Gender::Male : Gender::Female;
    }

    private function getDateOfBirth(string $id): DateTime
    {
        $dateDigits = substr($id, 1, 4);
        [$year, $month] = str_split($dateDigits, 2);
        $currentYear = (int) (new DateTime())->format('y');


        $year = $year > $currentYear ? $year + 1900 : $year + 2000;

        if ($month > 0 && $month < 13) {
            return DateTime::createFromFormat('Y-m', "$year-$month");
        }

        if ($month > 30 && $month < 43) {
            $month -= 30;
            return DateTime::createFromFormat('Y-m', "$year-$month");
        }

        return DateTime::createFromFormat('Y', $year);
    }

    private function getPlaceOfBirth(string $id): string
    {
        $twoDigitPob = substr($id, 5, 2);
        $threeDigitPob = substr($id, 5, 3);

        if (
            !isset(FranceRegionsList::$departments[$twoDigitPob])
            && ! isset(FranceRegionsList::$departments[$threeDigitPob])
        ) {
            throw new UnrecognisedPlaceOfBirthException(
                "The provided codes '$twoDigitPob' and '$threeDigitPob' do not match any region codes."
            );
        }

        return FranceRegionsList::$departments[$twoDigitPob] ??
            FranceRegionsList::$departments[$threeDigitPob];
    }
}
