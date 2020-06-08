<?php

namespace Reducktion\Socrates\Core\Luxembourg;

use Carbon\Carbon;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;

class LuxembourgCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (! (new LuxembourgIdValidator())->validate($id)) {
            throw new InvalidIdException("Provided ID is invalid.");
        }

        $dob = $this->getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setDateOfBirth($dob);

        return $citizen;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $year = substr($id, 0, 4);
        $month = substr($id, 4, 2);
        $day = substr($id, 6, 2);

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }
}
