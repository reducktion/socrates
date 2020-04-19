<?php

namespace Reducktion\Socrates\Core\Macedonia;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Core\Yugoslavia\YugoslaviaIdValidator;

class MacedoniaIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        try {
            $result = YugoslaviaIdValidator::validate($id);
        } catch (InvalidLengthException $e) {
            throw new InvalidLengthException('The Macedonian UMCN must have 13 digits, ' . $e->getMessage());
        }

        return $result;
    }

}