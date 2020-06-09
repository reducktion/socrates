<?php

namespace Reducktion\Socrates\Contracts;

interface IdValidator
{
    /**
     * Validate a Personal Identification Number.
     *
     * @param  string  $id
     * @return bool
     */
    public function validate(string $id): bool;
}
