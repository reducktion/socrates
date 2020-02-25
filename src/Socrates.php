<?php

namespace Reducktion\Socrates;

use Illuminate\Support\Facades\App;
use Locale;
use Reducktion\Socrates\Core\CitizenInformationExtractorFactory;
use Reducktion\Socrates\Core\IdValidatorFactory;
use Reducktion\Socrates\Exceptions\InvalidCountryCodeException;
use Reducktion\Socrates\Exceptions\UnrecognisedCountryException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;
use Reducktion\Socrates\Models\Citizen;

class Socrates
{

    /**
     * Extracts the Citizen Data related to the Id provided according to the
     * country provided.
     *
     * @param  string  $id
     * @param  string  $countryCode
     *
     * @return \Reducktion\Socrates\Models\Citizen
     */
    public function getCitizenDataFromId(string $id, string $countryCode = ''): Citizen
    {
        $id = trim($id);

        $countryCode = $this->formatCountryCode($countryCode);

        $this->checkIfCountrySupportsCitizenData($countryCode);

        $citizenInformationExtractor = CitizenInformationExtractorFactory::getExtractor($countryCode);

        return $citizenInformationExtractor->extract($id);
    }

    /**
     * Validates the Id provided according to the country provided.
     *
     * @param  string  $id
     * @param  string  $countryCode
     *
     * @return bool
     */
    public function validateId(string $id, string $countryCode = ''): bool
    {
        $id = trim($id);

        $countryCode = $this->formatCountryCode($countryCode);

        $idValidator = IdValidatorFactory::getValidator($countryCode);

        return $idValidator->validate($id);
    }

    /**
     * Transforms the country code provided in a two letter code.
     *
     * @param  string  $countryCode
     *
     * @return string
     */
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

        if (! in_array($countryCode, config('socrates.all'), true)){
            throw new UnrecognisedCountryException("Could not find the country with the code '$countryCode'.");
        }

        return $countryCode;
    }

    /**
     * Verifies if a given country supports Citizen data extraction.
     *
     * @param  string  $countryCode
     */
    private function checkIfCountrySupportsCitizenData(string $countryCode): void
    {
        if (! isset(config('socrates.extractors')[$countryCode])) {
            $countryName = Locale::getDisplayRegion("-$countryCode", 'en');

            throw new UnsupportedOperationException("$countryName does not support extracting citizen data from the ID.");
        }
    }
}
