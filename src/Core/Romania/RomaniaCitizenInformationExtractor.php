<?php

namespace Reducktion\Socrates\Core\Romania;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Exceptions\UnrecognisedPlaceOfBirthException;
use Reducktion\Socrates\Models\Citizen;

class RomaniaCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (! (new RomaniaIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

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
        return ((int) substr($id, 0, 1)) % 2 ? Gender::MALE : Gender::FEMALE;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $yearCode = ((int) $id[1] * 10) + (int) $id[2];
        switch ((int) $id[0]) {
            case 1:
            case 2:
                $year = $yearCode + 1900;

                break;
            case 3:
            case 4:
                $year = $yearCode + 1800;

                break;
            case 5:
            case 6:
                $year = $yearCode + 2000;

                break;
            case 7:
            case 8:
            case 9:
                $year = $yearCode + 2000;

                if ($year > (int) date('Y') - 14) {
                    $year -= 100;
                }

                break;
            default:
                $year = 0;
        }

        $month = $id[3] . $id[4];

        $day = $id[5] . $id[6];

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }

    private function getPlaceOfBirth(string $id): string
    {
        $pobCode = substr($id, 7, 2);

        if (! isset(RomaniaRegionsList::$regions[$pobCode])) {
            throw new UnrecognisedPlaceOfBirthException(
                "The provided code '$pobCode' does not match any region codes."
            );
        }

        return RomaniaRegionsList::$regions[$pobCode];
    }
}
