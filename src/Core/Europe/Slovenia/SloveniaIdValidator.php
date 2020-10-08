<?php

namespace Reducktion\Socrates\Core\Europe\Slovenia;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Core\Europe\Yugoslavia\YugoslaviaIdValidator;

class SloveniaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        try {
            $result = YugoslaviaIdValidator::validate($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('Slovenian EMSO', $e->getRequiredCharacters(), $e->getGivenCharacters());
        }

        $regionDigits = (int) substr($id, 7, 2);

        if ($regionDigits !== 50 && $regionDigits !== 5) {
            return false;
        }

        return $result;
    }
}
