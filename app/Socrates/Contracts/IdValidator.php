<?php

namespace App\Socrates\Contracts;

interface IdValidator
{
    public function validate(string $id): bool;
}
