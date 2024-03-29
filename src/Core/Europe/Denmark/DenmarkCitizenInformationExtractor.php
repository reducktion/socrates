<?php

namespace Reducktion\Socrates\Core\Europe\Denmark;

use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;

class DenmarkCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        $id = $this->sanitize($id);

        if (! (new DenmarkIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        $gender = $this->getGender((int) $id);
        $dateOfBirth = $this->getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dateOfBirth);

        return $citizen;
    }

    private function sanitize(string $id): string
    {
        return str_replace('-', '', $id);
    }

    private function getGender(int $cpr): Gender
    {
        return ($cpr % 2) ? Gender::Male : Gender::Female;
    }

    private function getDateOfBirth(string $cpr): DateTime
    {
        $seventhDigit = (int) $cpr[6];
        $dateDigits = substr($cpr, 0, 6);
        [$day, $month, $twoDigitYear] = str_split($dateDigits, 2);

        $year = $twoDigitYear < 70
            ? DateTime::createFromFormat('Y', "19$twoDigitYear")->format('Y')
            : DateTime::createFromFormat('y', (string) $twoDigitYear)->format('Y');

        if (($seventhDigit === 4 || $seventhDigit === 9) && $year <= 1936) {
            $year += 100;
        } elseif ($seventhDigit > 4) {
            $year > 1957 ? $year -= 100 : $year += 100;
        }

        return new DateTime("$year-$month-$day");
    }
}
