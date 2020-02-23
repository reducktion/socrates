<?php

namespace Reducktion\Socrates\Core\Denmark;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\Denmark\InvalidCprLengthException;
use Reducktion\Socrates\Models\Citizen;

class DenmarkCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        $cpr = $this->sanitize($id);

        $gender = $this->getGender((int) $cpr);
        $dateOfBirth = $this->getDateOfBirth($cpr);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dateOfBirth);

        return $citizen;
    }

    private function sanitize(string $id): string
    {
        $cpr = str_replace('-', '', $id);

        $cprLength = strlen($cpr);

        if ($cprLength !== 10) {
            throw new InvalidCprLengthException("Danish CPR must have 10 digits, got $cprLength");
        }

        return $cpr;
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
