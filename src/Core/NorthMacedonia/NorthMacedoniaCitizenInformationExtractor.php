<?php

namespace Reducktion\Socrates\Core\NorthMacedonia;

use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaCitizenInformationExtractor;

class NorthMacedoniaCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (! (new NorthMacedoniaIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        try {
            $citizen = YugoslaviaCitizenInformationExtractor::extract($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('Macedonian JMBG', $e->getRequiredCharacters(), $e->getGivenCharacters());
        }

        return $citizen;
    }
}
