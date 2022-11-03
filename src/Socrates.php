<?php

namespace Reducktion\Socrates;

use Locale;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Exceptions\InvalidIdException;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnrecognisedPlaceOfBirthException;
use Reducktion\Socrates\Models\Citizen;
use Reducktion\Socrates\Config\Countries;
use Reducktion\Socrates\Core\IdValidatorFactory;
use Reducktion\Socrates\Core\CitizenInformationExtractorFactory;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;

class Socrates
{
    /**
     * Extracts the Citizen data related to the provided Personal Identification Number.
     *
     * @param  string  $id
     * @param  Country  $country
     * @return Citizen
     * @throws InvalidIdException if the provided National Identification Number is invalid.
     * @throws UnrecognisedPlaceOfBirthException if the encoded place of birth is wrong or invalid.
     * @throws UnsupportedOperationException if the version or format of the National Identification Number does
     * not support a given operation.
     */
    public function getCitizenDataFromId(string $id, Country $country): Citizen
    {
        $id = trim($id);

        $this->checkIfCountrySupportsCitizenData($country);

        $citizenInformationExtractor = CitizenInformationExtractorFactory::getExtractor($country);

        return $citizenInformationExtractor->extract($id);
    }

    /**
     * Checks if the provided Personal Identification Number is valid.
     *
     * @param  string  $id
     * @param  Country  $country
     * @return bool
     * @throws InvalidLengthException if the provided National Identification Number has the wrong length.
     */
    public function validateId(string $id, Country $country): bool
    {
        $id = trim($id);

        $idValidator = IdValidatorFactory::getValidator($country);

        return $idValidator->validate($id);
    }

    /**
     * Verifies if a given country supports Citizen data extraction.
     *
     * @param  Country  $country
     * @return void
     * @throws UnsupportedOperationException if the provided country does not support extracting data from
     * the Personal Identification Number.
     */
    private function checkIfCountrySupportsCitizenData(Country $country): void
    {
        if (! isset(Countries::$extractors[$country->value])) {
            $countryName = Locale::getDisplayRegion("-$country->value", 'en');

            throw new UnsupportedOperationException(
                "$countryName does not support extracting citizen data from the ID."
            );
        }
    }
}
