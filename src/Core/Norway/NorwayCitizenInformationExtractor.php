<?php

namespace Reducktion\Socrates\Core\Norway;

use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Models\Citizen;

class NorwayCitizenInformationExtractor implements CitizenInformationExtractor
{

    public function extract(string $id): Citizen
    {
        $idLength = strlen($id);

        if ($idLength !== 11) {
            throw new InvalidLengthException("Norwegian fødselsnummer must have 11 digits, got $idLength");
        }

        $individualNumber = (int) substr($id, 6, 3);

        $gender = $this->getGender($individualNumber);

        $citizen = new Citizen();
        $citizen->setGender($gender);

        return $citizen;
    }

    private function getGender(int $individualNumber): string
    {
        return ($individualNumber % 2) ? Gender::MALE : Gender::FEMALE;
    }
}