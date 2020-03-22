<?php


namespace Reducktion\Socrates\Core\Italy;


use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Models\Citizen;

class ItalyCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        // SSS NNN YYMDD ZZZZ X
        // MRC DRA 01A13 A065 E
        // MRC DRA 01A13 A06R E
        // SSS => first three consonants in the family name
        // NNN => the first name consonants
        // YYMDD => dob (M => (A to E, H, L, M, P, R to T), DD => (Add 40 to day of month when female)
        // ZZZZ => are code where person was born
        // X => checksum

        $idLength = strlen($id);

        if ($idLength !== 16) {
            throw new InvalidLengthException("Italian FC must have 16 digits, got $idLength");
        }
    }

    private function getGender(string $id): string
    {
        $dayOfBirth = substr($id, 9, 2);
        return $dayOfBirth > 31? Gender::FEMALE : Gender::MALE;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $dayDigits = substr($id, 9, 2);
        $monthDigits = substr($id, 8);
        $yearDigits = substr($id, 6, 2);
        $months = 'ABCDEHLMPRST';

//        $day = $dayDigits > 31

//        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }

}