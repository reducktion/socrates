<?php

namespace Reducktion\Socrates\Core\Europe\Germany;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

//Documentation can be found in Chapter 2.2 https://download.elster.de/download/schnittstellen/Pruefung_der_Steuer_und_Steueridentifikatsnummer.pdf
class GermanIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $idLength = strlen($id);
        if ($idLength !== 11) {
            throw new InvalidLengthException('German TIN', '11', $idLength);
        }

        // German TIN must not start with a '0'
        if ((int) $id[0] === 0) {
            return false;
        }

        // The first 10 numbers of the German TIN need to have 8 or 9 different numbers
        $digits = str_split($id);
        array_pop($digits);
        $countDigits = count(array_unique($digits));

        if (($countDigits !== 9 && $countDigits !== 8)) {
            return false;
        }

        // Checksum calculation
        $product = 10;
        for ($i = 0; $i <= 9; ++$i) {
            $sum = ((int) $id[$i] + $product) % 10;
            if ($sum === 0) {
                $sum = 10;
            }
            $product = ($sum * 2) % 11;
        }

        $checksum = 11 - $product;
        if ($checksum === 10) {
            $checksum = 0;
        }

        return $checksum === (int) $id[strlen($id) - 1];
    }
}
