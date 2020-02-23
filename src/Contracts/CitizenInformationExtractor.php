<?php

namespace Reducktion\Socrates\Contracts;

use Reducktion\Socrates\Models\Citizen;

interface CitizenInformationExtractor
{
    public function extract(string $id): Citizen;
}
