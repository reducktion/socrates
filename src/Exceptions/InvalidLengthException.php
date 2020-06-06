<?php

namespace Reducktion\Socrates\Exceptions;

class InvalidLengthException extends \LogicException
{
    public $designation;
    public $requiredCharacters;
    public $givenCharacters;

    /**
     * InvalidLengthException constructor.
     *
     * @param $designation
     * @param $requiredCharacters
     * @param $givenCharacters
     */
    public function __construct(string $designation, string $requiredCharacters, string $givenCharacters)
    {
        $this->designation = $designation;
        $this->requiredCharacters = $requiredCharacters;
        $this->givenCharacters = $givenCharacters;
        parent::__construct("The $designation must have $requiredCharacters characters, but got $givenCharacters.");
    }

    /**
     * @return string
     */
    public function getDesignation(): string
    {
        return $this->designation;
    }

    /**
     * @return string
     */
    public function getRequiredCharacters(): string
    {
        return $this->requiredCharacters;
    }

    /**
     * @return string
     */
    public function getGivenCharacters(): string
    {
        return $this->givenCharacters;
    }
}
