<?php

namespace Reducktion\Socrates\Core\Europe\Sweden;

use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;

class SwedenCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (! (new SwedenIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

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

    private function checkIfCitizenIsOverOneHundredYearsOld(string $id): bool
    {
        $idLength = strlen($id);

        $separatorIndex = $idLength === 13 ? 8 : 6;

        return $id[$separatorIndex] === '+';
    }

    private function convertToTenDigitId(string $id): string
    {
        $tenDigitId = '';

        $length = strlen($id);
        for ($i = 0; $i < $length; $i++) {
            if ($i > 1) {
                $tenDigitId .= $id[$i];
            }
        }

        $id = $tenDigitId;
        return $id;
    }

    private function getGender(string $id): string
    {
        return $id[8] % 2 === 0 ? Gender::FEMALE : Gender::MALE;
    }

    private function getDateOfBirth(string $id, bool $isOverOneHundredYearsOld): DateTime
    {
        $dateDigits = substr($id, 0, 6);
        [$twoDigitYear, $month, $day] = str_split($dateDigits, 2);

        if ($isOverOneHundredYearsOld) {
            $year = DateTime::createFromFormat('Y', "19$twoDigitYear")->format('Y');
        } else {
            $presentTwoDigitYear = (int) (new DateTime())->format('y');

            $year = $twoDigitYear < $presentTwoDigitYear
                ? DateTime::createFromFormat('y', (string) $twoDigitYear)->format('Y')
                : DateTime::createFromFormat('Y', "19$twoDigitYear")->format('Y');
        }

        return new DateTime("$year-$month-$day");
    }
}
