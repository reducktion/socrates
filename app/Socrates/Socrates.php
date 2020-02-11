<?php

namespace App\Socrates;

use App\Socrates\Core\CitizenInformationExtractorFactory;
use App\Socrates\Core\Citizen;
use App\Socrates\Core\Countries;
use App\Socrates\Core\IdValidatorFactory;
use App\Socrates\Exceptions\UnsupportedOperationException;
use Illuminate\Support\Facades\App;
use Locale;

class Socrates
{
    public function getCitizenData(string $id, string $countryCode = ''): Citizen
    {
        $id = trim($id);

        if ($countryCode === '') {
            $countryCode = App::getLocale();
        }

        $countryCode = strtoupper($countryCode);

        $this->checkIfCountrySupportsCitizenData($countryCode);

        $citizenInformationExtractor = CitizenInformationExtractorFactory::getExtractor($countryCode);

        return $citizenInformationExtractor->extract($id);
    }

    public function validateId(string $id, string $countryCode = ''): bool
    {
        $id = trim($id);

        if ($countryCode === '') {
            $countryCode = App::getLocale();
        }

        $countryCode = strtoupper($countryCode);

        $idValidator = IdValidatorFactory::getValidator($countryCode);

        return $idValidator->validate($id);
    }

    private function checkIfCountrySupportsCitizenData(string $countryCode): void
    {
        if (!in_array($countryCode, Countries::COUNTRIES_THAT_SUPPORT_CITIZEN_DATA, true)) {
            $countryName = Locale::getDisplayRegion("-$countryCode", 'en');

            throw new UnsupportedOperationException("$countryName does not support extracting citizen data from the ID");
        }
    }
}
