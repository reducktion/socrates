<?php

namespace App\Socrates\Core;

use DateTimeInterface;

class Citizen
{
    private $gender;
    private $dateOfBirth;

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    public function setDateOfBirth(DateTimeInterface $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function __toString()
    {
        return 'Gender - ' . $this->gender . ' DoB - ' . $this->dateOfBirth->format('Y-m-d');
    }
}
