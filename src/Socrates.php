<?php

namespace Reducktion\Socrates;

use Locale;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnrecognisedPlaceOfBirthException;
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
     * @return Citizen
     * @throws InvalidIdException if the provided National Identification Number is invalid.
     * @throws UnrecognisedPlaceOfBirthException if the encoded place of birth is wrong or invalid.
     * @throws UnsupportedOperationException if the version or format of the National Identification Number does
     * not support a given operation.
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
     * @return bool
     * @throws InvalidLengthException if the provided National Identification Number has the wrong length.
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
     * @return string
     * @throws InvalidCountryCodeException if the provided country code is not provided or is not in the right format.
     * @throws UnrecognisedCountryException if the provided country code does not correspond to any country.
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

        if (! in_array($countryCode, Countries::$all, true)) {
            throw new UnrecognisedCountryException("Could not find the country with the code '$countryCode'.");
        }

        return $countryCode;
    }

    /**
     * Verifies if a given country supports Citizen data extraction.
     *
     * @param  string  $countryCode
     * @return void
     * @throws UnsupportedOperationException if the provided country does not supported extracting data from
     * the Personal Identification Number.
     */
    private function checkIfCountrySupportsCitizenData(string $countryCode): void
    {
        if (! isset(Countries::$extractors[$countryCode])) {
            $countryName = Locale::getDisplayRegion("-$countryCode", 'en');

            throw new UnsupportedOperationException(
                "$countryName does not support extracting citizen data from the ID."
            );
        }
    }
}
