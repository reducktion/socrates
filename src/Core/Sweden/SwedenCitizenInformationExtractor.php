<?php

namespace Reducktion\Socrates\Core\Sweden;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Models\Citizen;

class SwedenCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        $id = $this->sanitize($id);

        if (strlen($id) === 12) {
            $id = $this->convertToTenDigitId($id);
        }

        $gender = $this->getGender($id);
        $dateOfBirth = $this->getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dateOfBirth);

        return $citizen;
    }

    private function sanitize(string $id): string
    {
        $id = str_replace('-', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 10 && $idLength !== 12) {
            throw new InvalidLengthException("Swedish Personnummer must have 10 or 12 digits, got $idLength");
        }

        return $id;
    }

    private function convertToTenDigitId(string $id): string
    {
        $tenDigitId = '';

        for ($i = 0; $i < strlen($id); $i++) {
            if ($i > 1) {
                $tenDigitId .= $id[$i];
            }
        }

        $id = $tenDigitId;
        return $id;
    }

    private function getGender(string $id): string
    {
        return $id[8] % 2 == 0 ? Gender::FEMALE : Gender::MALE;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $dateDigits = substr($id, 0, 6);
        [$twoDigitYear, $month, $day] = str_split($dateDigits, 2);

        $year = '19' . $twoDigitYear;

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }
}
