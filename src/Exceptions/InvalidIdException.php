<?php

namespace Reducktion\Socrates\Exceptions;

class InvalidIdException extends \LogicException
{
    /**
     * InvalidIdException constructor.
     */
    public function __construct()
    {
        parent::__construct('The provided National ID is invalid.');
    }
}
