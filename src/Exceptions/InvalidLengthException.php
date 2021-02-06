<?php

namespace Reducktion\Socrates\Exceptions;

class InvalidLengthException extends \LogicException
{
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
