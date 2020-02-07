<?php

namespace App\Socrates\Contracts;

use App\Socrates\Core\Citizen;

interface CitizenInformationExtractor
{
    public function extract(string $id): Citizen;
}
