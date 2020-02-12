<?php

namespace App\Socrates\Contracts;

use App\Socrates\Models\Citizen;

interface CitizenInformationExtractor
{
    public function extract(string $id): Citizen;
}
