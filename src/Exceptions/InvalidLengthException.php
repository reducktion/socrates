<?php

namespace Reducktion\Socrates\Exceptions;

use LogicException;

class InvalidLengthException extends LogicException
{
    /**
     * Description of the number of characters the National Identification Number should have.
     *
     * @var string
     */
    public string $requiredCharacters {
        get {
            return $this->requiredCharacters;
        }
    }

    /**
     * Description of the numbers of characters that were passed.
     *
     * @var string
     */
    public string $givenCharacters {
        get {
            return $this->givenCharacters;
        }
    }

    /**
     * Create a new InvalidLengthException instance.
     *
     * @param string $designation The National Identification Number designation
     * @param string $requiredCharacters The correct number of characters as string
     * @param string $givenCharacters The characters that the user has given us
     */
    public function __construct(string $designation, string $requiredCharacters, string $givenCharacters)
    {
        $this->requiredCharacters = $requiredCharacters;

        $this->givenCharacters = $givenCharacters;

        parent::__construct("The $designation must have $requiredCharacters characters, but got $givenCharacters.");
    }

}
