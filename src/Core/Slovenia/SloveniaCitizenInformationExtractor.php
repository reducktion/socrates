<?php

namespace Reducktion\Socrates\Core\Slovenia;

use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaCitizenInformationExtractor;

class SloveniaCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (! (new SloveniaIdValidator())->validate($id)) {
            throw new InvalidIdException();
        }

        try {
            $citizen = YugoslaviaCitizenInformationExtractor::extract($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('Slovenian EMSO', $e->getRequiredCharacters(), $e->getGivenCharacters());
        }

        return $citizen;
    }
}
