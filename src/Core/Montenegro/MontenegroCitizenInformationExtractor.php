<?php

namespace Reducktion\Socrates\Core\Montenegro;

use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaCitizenInformationExtractor;

class MontenegroCitizenInformationExtractor implements CitizenInformationExtractor
{

    public function extract(string $id): Citizen
    {
        if (! (new MontenegroIdValidator())->validate($id)) {
            throw new InvalidIdException('Provided ID is invalid');
        }

        try {
            $citizen = YugoslaviaCitizenInformationExtractor::extract($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('Montenegrin JMBG',$e->getRequiredCharacters(), $e->getGivenCharacters());
        }

        return $citizen;
    }
}
