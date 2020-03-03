<?php


namespace Reducktion\Socrates\Core\Lithuania;


use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Models\Citizen;

class LithuaniaCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        $idLength = strlen($id);
        if ($idLength !== 11) {
            throw new InvalidLengthException("Lithuanian PC must have 11 digits, got $idLength");
        }

        $gender = $this->getGender($id);
        $dateOfBirth = $this->getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dateOfBirth);

        return $citizen;
    }

    private function getGender(string $id): string
    {
        return ($id[0] % 2) ? Gender::MALE : Gender::FEMALE;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $dateDigits = substr($id, 1, 6);
        [$year, $month, $day] = str_split($dateDigits, 2);

        $firstDigit = (int) $id[0];
        $calc = (int) floor(($firstDigit + 34) / 2);

        $century = ($firstDigit % 2) ?
            $firstDigit + $calc - ($firstDigit - 2) :
            $firstDigit + $calc - ($firstDigit - 1);

        $year = (($century - 1) * 100) + $year;

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }
}