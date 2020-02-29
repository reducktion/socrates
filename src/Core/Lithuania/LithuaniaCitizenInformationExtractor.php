<?php


namespace Reducktion\Socrates\Core\Lithuania;


use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Models\Citizen;

class LithuaniaCitizenInformationExtractor implements CitizenInformationExtractor
{
    // 3 840915 201
    // G YYMMDD NNN C
    // G => gender
    // YYMMDD => dob
    // NNN =>
    // C => control
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

        $firstDigit = $id[0];
        $year = ($firstDigit % 2) ?
            (($firstDigit + 16) * 100) + $year:
            (($firstDigit + 15) * 100) + $year;

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }
}