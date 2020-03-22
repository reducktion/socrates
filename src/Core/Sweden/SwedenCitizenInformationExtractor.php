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
        $this->validateLength($id);

        $isOverOneHundredYearsOld = $this->checkIfCitizenIsOverOneHundredYearsOld($id);

        $id = str_replace(['-', '+'], '', $id);

        $idInTwelveCharacterFormat = strlen($id) === 12;
        if ($idInTwelveCharacterFormat) {
            $id = $this->convertToTenDigitId($id);
        }

        $gender = $this->getGender($id);

        $dateOfBirth = $this->getDateOfBirth($id, $isOverOneHundredYearsOld);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dateOfBirth);

        return $citizen;
    }

    private function validateLength(string $id): void
    {
        $idLength = strlen($id);

        if ($idLength !== 11 && $idLength !== 13) {
            throw new InvalidLengthException("Swedish Personnummer must have 10 or 12 digits, got $idLength");
        }
    }

    private function checkIfCitizenIsOverOneHundredYearsOld(string $id): bool
    {
        $idLength = strlen($id);

        $separatorIndex = $idLength === 13 ? 8 : 6;

        return $id[$separatorIndex] === '+';
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

    private function getDateOfBirth(string $id, bool $isOverOneHundredYearsOld): Carbon
    {
        $dateDigits = substr($id, 0, 6);
        [$twoDigitYear, $month, $day] = str_split($dateDigits, 2);

        if ($isOverOneHundredYearsOld) {
            $year = Carbon::createFromFormat('Y', "19$twoDigitYear")->format('Y');
        } else {
            $presentTwoDigitYear = (int) now()->format('y');

            $year = $twoDigitYear < $presentTwoDigitYear
                ? Carbon::createFromFormat('y', (string) $twoDigitYear)->format('Y')
                : Carbon::createFromFormat('Y', "19$twoDigitYear")->format('Y');
        }

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }
}
