<?php

namespace Reducktion\Socrates\Core\Denmark;

use Carbon\Carbon;
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
            throw new InvalidIdException("Provided ID is invalid.");
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
        $id = str_replace('-', '', $id);

        return $id;
    }

    private function getGender(int $cpr): string
    {
        return ($cpr % 2) ? Gender::MALE : Gender::FEMALE;
    }

    private function getDateOfBirth(string $cpr): Carbon
    {
        $seventhDigit = (int) $cpr[6];
        $dateDigits = substr($cpr, 0, 6);
        [$day, $month, $twoDigitYear] = str_split($dateDigits, 2);

        $year = $twoDigitYear < 70
            ? Carbon::createFromFormat('Y', "19$twoDigitYear")->format('Y')
            : Carbon::createFromFormat('y', (string) $twoDigitYear)->format('Y');

        if (($seventhDigit === 4 || $seventhDigit === 9) && $year <= 1936) {
            $year += 100;
        } else if ($seventhDigit > 4) {
            $year > 1957 ? $year -= 100 : $year += 100;
        }

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }
}
