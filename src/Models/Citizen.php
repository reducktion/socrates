<?php

namespace Reducktion\Socrates\Models;

use Carbon\Carbon;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;

class Citizen
{
    private $gender;
    private $dateOfBirth;
    private $placeOfBirth;

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getDateOfBirth(): ?Carbon
    {
        return $this->dateOfBirth;
    }

    public function getAge(): ?int
    {
        if (!$this->dateOfBirth) {
            throw new UnsupportedOperationException('Citizen date of birth is null.');
        }

        return $this->dateOfBirth->age;
    }

    public function getPlaceOfBirth(): ?string
    {
        return $this->placeOfBirth;
    }

    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    public function setDateOfBirth(Carbon $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function setPlaceOfBirth($placeOfBirth): void
    {
        $this->placeOfBirth = $placeOfBirth;
    }
}
