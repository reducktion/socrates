<?php

namespace Reducktion\Socrates\Core\Kosovo;

use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaCitizenInformationExtractor;

class KosovoCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (! (new KosovoIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        try {
            $citizen = YugoslaviaCitizenInformationExtractor::extract($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('Kosovan JMBG', $e->getRequiredCharacters(), $e->getGivenCharacters());
        }

        return $citizen;
    }
}
