<?php

namespace Reducktion\Socrates\Core\France;

use Carbon\Carbon;
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

    private function getGender(string $id): string
    {
        return ((int) $id[0]) === 1 ? Gender::MALE : Gender::FEMALE;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $dateDigits = substr($id, 1, 4);
        [$year, $month] = str_split($dateDigits, 2);
        $currentYear = (int) now()->format('y');

        $year = (int) $year > $currentYear ? (int) $year + 1900 : (int) $year + 2000;

        return Carbon::createFromFormat('Y-m', "$year-$month");
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
