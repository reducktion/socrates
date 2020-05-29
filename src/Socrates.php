<?php

namespace Reducktion\Socrates;

use Locale;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Config\Countries;
use Reducktion\Socrates\Core\IdValidatorFactory;
use Reducktion\Socrates\Exceptions\InvalidCountryCodeException;
use Reducktion\Socrates\Core\CitizenInformationExtractorFactory;
use Reducktion\Socrates\Exceptions\UnrecognisedCountryException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;

class Socrates
{

    /**
     * Extracts the Citizen data related to the provided Personal Identification Number.
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
     * Checks if the provided Personal Identification Number is valid.
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
     * Transforms the provided country code to the ISO 3166-2 format.
     *
     * @param  string  $countryCode
     *
     * @return string
     */
    private function formatCountryCode(string $countryCode): string
    {
        $runningLaravel = class_exists(\Illuminate\Support\Facades\App::class);

        if (! $runningLaravel && $countryCode === '') {
            throw new InvalidCountryCodeException('No country code provided.');
        }

        if ($runningLaravel && $countryCode === '') {
            $countryCode = \Illuminate\Support\Facades\App::getLocale();
        }

        $countryCodeLength = strlen($countryCode);

        if ($countryCodeLength !== 2 && $countryCodeLength !== 5) {
            throw new InvalidCountryCodeException("The code '$countryCode' is not in ISO 3166-2 format.");
        }

        if ($countryCodeLength === 5) {
            $countryCode = substr($countryCode, -2);
        }

        $countryCode = strtoupper($countryCode);

        if (! in_array($countryCode, Countries::$all, true)){
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
        if (! isset(Countries::$extractors[$countryCode])) {
            $countryName = Locale::getDisplayRegion("-$countryCode", 'en');

            throw new UnsupportedOperationException("$countryName does not support extracting citizen data from the ID.");
        }
    }
}
