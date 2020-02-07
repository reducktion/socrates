<?php

namespace App\Socrates;

use App\Socrates\Core\CitizenInformationExtractorFactory;
use App\Socrates\Core\Citizen;
use App\Socrates\Core\Countries;
use App\Socrates\Exceptions\UnsupportedOperationException;
use Locale;

class Socrates
{
    public function getCitizenData(string $id, string $countryCode = ''): Citizen
    {
        $id = trim($id);

        // get current country or fallback
        if ($countryCode === '') {
            $countryCode = 'DK'; // simulate current country
        }

        $countryCode = strtoupper($countryCode);

        $this->checkIfCountrySupportsCitizenData($countryCode);

        $citizenInformationExtractor = CitizenInformationExtractorFactory::getExtractor($countryCode);

        return $citizenInformationExtractor->extract($id);
    }

    private function checkIfCountrySupportsCitizenData(string $countryCode): void
    {
        if (in_array($countryCode, Countries::COUNTRIES_THAT_DO_NOT_SUPPORT_CITIZEN_DATA, true)) {
            $countryName = Locale::getDisplayRegion("-$countryCode", 'en');

            throw new UnsupportedOperationException("$countryName does not support extracting citizen data from the ID");
        }
    }
}
