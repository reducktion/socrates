<?php

namespace App\Socrates;

use App\Socrates\Core\CitizenInformationExtractorFactory;
use App\Socrates\Exceptions\InvalidCountryCodeException;
use App\Socrates\Exceptions\UnrecognisedCountryException;
use App\Socrates\Models\Citizen;
use App\Socrates\Constants\Countries;
use App\Socrates\Core\IdValidatorFactory;
use App\Socrates\Exceptions\UnsupportedOperationException;
use Illuminate\Support\Facades\App;
use Locale;

class Socrates
{
    public function getCitizenDataFromId(string $id, string $countryCode = ''): Citizen
    {
        $id = trim($id);

        $countryCode = $this->formatCountryCode($countryCode);

        $this->checkIfCountrySupportsCitizenData($countryCode);

        $citizenInformationExtractor = CitizenInformationExtractorFactory::getExtractor($countryCode);

        return $citizenInformationExtractor->extract($id);
    }

    public function validateId(string $id, string $countryCode = ''): bool
    {
        $id = trim($id);

        $countryCode = $this->formatCountryCode($countryCode);

        $idValidator = IdValidatorFactory::getValidator($countryCode);

        return $idValidator->validate($id);
    }

    private function formatCountryCode(string $countryCode): string
    {
        if ($countryCode === '') {
            $countryCode = App::getLocale();
        }

        $countryCodeLength = strlen($countryCode);

        if ($countryCodeLength !== 2 && $countryCodeLength !== 5) {
            throw new InvalidCountryCodeException("The code '$countryCode' is not in ISO 3166-2 format.");
        }

        if ($countryCodeLength === 5) {
            $countryCode = substr($countryCode, -2);
        }

        $countryCode = strtoupper($countryCode);

        if (!in_array($countryCode, Countries::ALL_COUNTRIES, true)){
            throw new UnrecognisedCountryException("Could not find the country with the code '$countryCode'.");
        }

        return $countryCode;
    }

    private function checkIfCountrySupportsCitizenData(string $countryCode): void
    {
        if (!in_array($countryCode, Countries::COUNTRIES_THAT_SUPPORT_CITIZEN_DATA, true)) {
            $countryName = Locale::getDisplayRegion("-$countryCode", 'en');

            throw new UnsupportedOperationException("$countryName does not support extracting citizen data from the ID.");
        }
    }
}
