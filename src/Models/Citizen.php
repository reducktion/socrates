<?php

namespace Reducktion\Socrates\Models;

use DateTime;
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
     * The date of birth as a DateTime object.
     *
     * @var DateTime|null
     */
    private $dateOfBirth;

    /**
     * The place of birth as a string.
     *
     * @var string|null
     */
    private $placeOfBirth;

    /**
     * Get the citizen's gender as a string.
     *
     * @return  string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * Get the citizen's date of birth as a Carbon instance.
     *
     * @deprecated v1.3.0 This method will be deprecated in the next release of Socrates.
     *                    Please use getDateOfBirthNative() instead.
     * @return Carbon|null
     */
    public function getDateOfBirth(): ?Carbon
    {
        return Carbon::instance($this->dateOfBirth);
    }

    /**
     * Get the citizen's date of birth as a DateTime object.
     *
     * @return DateTime|null
     */
    public function getDateOfBirthNative(): ?DateTime
    {
        return $this->dateOfBirth;
    }

    /**
     * Get the citizen's age as a number or null.
     *
     * @return int|null
     */
    public function getAge(): ?int
    {
        if (!$this->dateOfBirth) {
            throw new UnsupportedOperationException('Citizen date of birth is null.');
        }

        return (new DateTime())->diff($this->dateOfBirth)->y;
    }

    /**
     * Get the citizen's place of birth as a string or null.
     *
     * @return string|null
     */
    public function getPlaceOfBirth(): ?string
    {
        return $this->placeOfBirth;
    }

    /**
     * Set the citizen's gender.
     *
     * @param  string  $gender
     * @return void
     */
    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * Set the citizen's date of birth.
     *
     * @param  DateTime  $dateOfBirth
     * @return  void
     */
    public function setDateOfBirth(DateTime $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * Set the citizen's place of birth.
     *
     * @param  string  $placeOfBirth
     * @return void
     */
    public function setPlaceOfBirth(string $placeOfBirth): void
    {
        $this->placeOfBirth = $placeOfBirth;
    }
}
