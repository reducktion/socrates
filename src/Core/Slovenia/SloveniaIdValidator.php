<?php

namespace Reducktion\Socrates\Core\Slovenia;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaIdValidator;

class SloveniaIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        try {
            $result = YugoslaviaIdValidator::validate($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('The Slovenian EMSO must have 13 digits, ' . $e->getMessage());
        }

        $regionDigits = (int) substr($id, 7, 2);

        if ($regionDigits !== 50 && $regionDigits !== 5) {
            return false;
        }

        return $result;
    }
}
