<?php

namespace Reducktion\Socrates\Exceptions;

class InvalidLengthException extends \LogicException
{
    /**
     * The name of the National Identification Number.
     *
     * @var string
     */
    private $designation;

    /**
     * Description of the number of characters the National Identification Number should have.
     *
     * @var string
     */
    private $requiredCharacters;

    /**
     * Description of the numbers of characters that were passed.
     *
     * @var string
     */
    private $givenCharacters;

    /**
     * Create a new InvalidLengthException instance.
     *
     * @param  string  $designation
     * @param  string  $requiredCharacters
     * @param  string  $givenCharacters
     * @return void
     */
    public function __construct(string $designation, string $requiredCharacters, string $givenCharacters)
    {
        $this->designation = $designation;

        $this->requiredCharacters = $requiredCharacters;

        $this->givenCharacters = $givenCharacters;

        parent::__construct("The $designation must have $requiredCharacters characters, but got $givenCharacters.");
    }

    /**
     * Get the required characters.
     *
     * @return string
     */
    public function getRequiredCharacters(): string
    {
        return $this->requiredCharacters;
    }

    /**
     * Get given characters.
     *
     * @return string
     */
    public function getGivenCharacters(): string
    {
        return $this->givenCharacters;
    }
}
