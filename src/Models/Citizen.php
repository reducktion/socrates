<?php

namespace Reducktion\Socrates\Models;

use Carbon\Carbon;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;

class Citizen
{
    /**
     * The gender as a string.
     *
     * @var string|null
     */
    private $gender;

    /**
     * The date of birth as a Carbon instance.
     *
     * @var Carbon|null
     */
    private $dateOfBirth;

    /**
     * The place of birth as a string.
     *
     * @var string|null
     */
    private $placeOfBirth;

    /**
     * Get the gender.
     *
     * @return  string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * Get the date of birth.
     *
     * @return Carbon|null
     */
    public function getDateOfBirth(): ?Carbon
    {
        return $this->dateOfBirth;
    }

    /**
     * Get the age.
     *
     * @return int|null
     */
    public function getAge(): ?int
    {
        if (!$this->dateOfBirth) {
            throw new UnsupportedOperationException('Citizen date of birth is null.');
        }

        return $this->dateOfBirth->age;
    }

    /**
     * Get the place of birth.
     *
     * @return string|null
     */
    public function getPlaceOfBirth(): ?string
    {
        return $this->placeOfBirth;
    }

    /**
     * Set the gender.
     *
     * @param  string  $gender
     * @return void
     */
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * Set the date of birth.
     *
     * @param  Carbon  $dateOfBirth
     * @return  void
     */
    public function setDateOfBirth(Carbon $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * Set the place of birth.
     *
     * @param  string  $placeOfBirth
     * @return void
     */
    public function setPlaceOfBirth(string $placeOfBirth): void
    {
        $this->placeOfBirth = $placeOfBirth;
    }
}
