<?php

namespace Reducktion\Socrates\Core\Serbia;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaIdValidator;

class SerbiaIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        try {
            $result = YugoslaviaIdValidator::validate($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('The Serbian UMCN must have 13 digits, ' . $e->getMessage());
        }

        return $result;
    }

}