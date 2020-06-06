<?php

namespace Reducktion\Socrates\Core\Serbia;

use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaCitizenInformationExtractor;

class SerbiaCitizenInformationExtractor implements CitizenInformationExtractor
{

    public function extract(string $id): Citizen
    {
        if (! (new SerbiaIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        try {
            $citizen = YugoslaviaCitizenInformationExtractor::extract($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('Serbian JMBG', $e->getRequiredCharacters(), $e->getGivenCharacters());
        }

        return $citizen;
    }
}
