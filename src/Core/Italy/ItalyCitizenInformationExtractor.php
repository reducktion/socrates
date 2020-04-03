<?php

namespace Reducktion\Socrates\Core\Italy;

use Carbon\Carbon;
use Reducktion\Socrates\Exceptions\UnrecognisedPlaceOfBirthException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;

class ItalyCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        $idLength = strlen($id);
        if ($idLength !== 16) {
            throw new InvalidLengthException("Italian FC must have 16 digits, got $idLength");
        }

        $id = $this->omocodiaSwap($id);

        $gender = $this->getGender($id);
        $dateOfBirth = $this->getDateOfBirth($id);
        $placeOfBirth = $this->getPlaceOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dateOfBirth);
        $citizen->setPlaceOfBirth($placeOfBirth);

        return $citizen;
    }

    private function getGender(string $id): string
    {
        $dayOfBirth = (int) substr($id, 9, 2);
        return $dayOfBirth > 31? Gender::FEMALE : Gender::MALE;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $dayDigits = substr($id, 9, 2);
        $monthChar = $id[8];
        $yearDigits = substr($id, 6, 2);
        $months = 'ABCDEHLMPRST';
        $currentYear = (int) now()->format('y');

        $day = (int) $dayDigits > 31 ? (int) $dayDigits - 40 : (int) $dayDigits;
        $month = strpos($months, $monthChar) + 1;
        $year = (int) $yearDigits > $currentYear ? (int) $yearDigits + 1900 : (int) $yearDigits + 2000;

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }

    private function getPlaceOfBirth(string $id): string
    {
        $pobCode = substr($id, 11, 4);

        if (! isset(ItalyRegionsList::$regions[$pobCode])) {
            throw new UnrecognisedPlaceOfBirthException("The place of birth code provided '$pobCode' doesn't match any registered codes.");
        }

        return ItalyRegionsList::$regions[$pobCode];
    }

    private function omocodiaSwap(string $id): string
    {
        $omocodia = [
            0 => 'L',
            1 => 'M',
            2 => 'N',
            3 => 'P',
            4 => 'Q',
            5 => 'R',
            6 => 'S',
            7 => 'T',
            8 => 'U',
            9 => 'V'
        ];
        $numericalCharactersPosition = [ 6, 7, 9, 10, 12, 13, 14];

        foreach ($numericalCharactersPosition as $characterPosition) {
            if (! is_numeric($id[$characterPosition])) {
                $id[$characterPosition] = array_search($id[$characterPosition], $omocodia, true);
            }
        }
        return $id;
    }
}