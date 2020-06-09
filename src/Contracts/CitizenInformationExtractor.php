<?php

namespace Reducktion\Socrates\Contracts;

use Reducktion\Socrates\Models\Citizen;

interface CitizenInformationExtractor
{
    /**
     * Extract information from a Personal Identification Number.
     *
     * @param  string  $id
     * @return Citizen
     */
    public function extract(string $id): Citizen;
}
