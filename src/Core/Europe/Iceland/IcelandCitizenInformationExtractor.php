<?php

namespace Reducktion\Socrates\Core\Europe\Iceland;

use Carbon\Carbon;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;

class IcelandCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        $id = $this->sanitize($id);

        if (! (new IcelandIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        $dateOfBirth = $this->getDateOfBirth($id);

        $citizen = new Citizen();
        $citizen->setDateOfBirth($dateOfBirth);

        return $citizen;
    }

    private function sanitize(string $id): string
    {
        $id = str_replace('-', '', $id);

        return $id;
    }

    private function getDateOfBirth(string $id): Carbon
    {
        $centuryCheck = substr($id, 9, 1);
        $dateDigits = substr($id, 0, 6);
        [$day, $month, $twoDigitYear] = str_split($dateDigits, 2);

        if ($day > 31) {
            $day -= 40;
        }

        $year = $centuryCheck === '0' ? 20 . $twoDigitYear : 19 . $twoDigitYear;

        return Carbon::createFromFormat('Y-m-d', "$year-$month-$day");
    }
}
