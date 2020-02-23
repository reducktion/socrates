<?php

namespace Reducktion\Socrates\Contracts;

interface IdValidator
{
    public function validate(string $id): bool;
}
