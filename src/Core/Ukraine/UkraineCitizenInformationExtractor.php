<?php

namespace Reducktion\Socrates\Core\Ukraine;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;

class UkraineCitizenInformationExtractor implements CitizenInformationExtractor
{

    public function extract(string $id): Citizen
    {
        if (! (new UkraineIdValidator())->validate($id)) {
            throw new InvalidIdException("Provided ID is invalid.");
        }

        $gender = $this->getGender($id[8]);

        $dateOfBirth = $this->getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setGender($gender);
        $citizen->setDateOfBirth($dateOfBirth);

        return $citizen;
    }

    private function getGender(int $genderDigit): string
    {
        return $genderDigit % 2 ? Gender::MALE : Gender::FEMALE;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $birthDateDigits = (int) substr($id, 0, 5 );

        $days = $birthDateDigits - 2;

        return Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($days);
    }
}