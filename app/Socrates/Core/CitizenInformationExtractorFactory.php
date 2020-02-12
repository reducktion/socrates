<?php

namespace App\Socrates\Core;

use App\Socrates\Contracts\CitizenInformationExtractor;
use App\Socrates\Core\Denmark\DenmarkCitizenInformationExtractor;
use RuntimeException;

abstract class CitizenInformationExtractorFactory
{
    public static function getExtractor(string $countryCode): CitizenInformationExtractor {
        switch ($countryCode) {
            case 'DK':
                return new DenmarkCitizenInformationExtractor();

            default:
                throw new RuntimeException("Unknown or unsupported country code '$countryCode'");
        }
    }
}
