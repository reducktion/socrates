<?php

namespace Reducktion\Socrates\Core\CzechRepublic;

use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Core\Czechoslovakia\CzechoslovakiaCitizenInformationExtractor;

class CzechRepublicCitizenInformationExtractor implements CitizenInformationExtractor
{
    public function extract(string $id): Citizen
    {
        if (! (new CzechRepublicIdValidator())->validate($id)) {
            throw new InvalidIdException('Provided ID is invalid');
        }

        try {
            $result = CzechoslovakiaCitizenInformationExtractor::extract($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('The Czech RC must have 10 digits, ' . $e->getMessage());
        }

        return $result;
    }
}
