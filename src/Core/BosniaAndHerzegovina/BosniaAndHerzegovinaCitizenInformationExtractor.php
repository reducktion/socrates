<?php

namespace Reducktion\Socrates\Core\BosniaAndHerzegovina;

use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaCitizenInformationExtractor;

class BosniaAndHerzegovinaCitizenInformationExtractor implements CitizenInformationExtractor
{

    public function extract(string $id): Citizen
    {
        if (! (new BosniaAndHerzegovinaIdValidator())->validate($id)) {
            throw new InvalidIdException('Provided JMBG is invalid');
        }

        try {
            $citizen = YugoslaviaCitizenInformationExtractor::extract($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('The Bosnian JMBG must have 13 digits, ' . $e->getMessage());
        }

        return $citizen;
    }

}