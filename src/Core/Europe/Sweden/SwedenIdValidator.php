<?php

namespace Reducktion\Socrates\Core\Europe\Sweden;

use DateTime;
use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class SwedenIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        $invalidIds = [
            '0000000000',
            '2222222222',
            '4444444444',
            '5555555555',
            '7777777777',
            '9999999999'
        ];

        if (in_array($id, $invalidIds, true)) {
            return false;
        }

        if (strlen($id) === 12) {
            $id = $this->convertToTenDigitId($id);
        }

        $dateString = substr($id, 0, 6);

        if (!$this->validateDate($dateString)) {
            return false;
        }

        $psn = array_reverse(str_split($id));
        $sum = 0;

        foreach ($psn as $key => $value) {
            if (!is_numeric($value)) {
                return false;
            }

            if ($key % 2) {
                $value *= 2;
            }

            $sum += ($value >= 10 ? $value - 9 : $value);
        }
        return ($sum % 10 === 0);
    }

    private function sanitize(string $id): string
    {
        $id = str_replace('-', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 10 && $idLength !== 12) {
            throw new InvalidLengthException('Swedish Personnummer', '10 or 12', $idLength);
        }

        return $id;
    }

    private function convertToTenDigitId(string $id): string
    {
        $tenDigitId = '';

        $length = strlen($id);
        for ($i = 0; $i < $length; $i++) {
            if ($i > 1) {
                $tenDigitId .= $id[$i];
            }
        }

        $id = $tenDigitId;
        return $id;
    }

    private function validateDate(string $dateString): bool
    {
        $date = DateTime::createFromFormat('ymd', $dateString);

        return $date && $date->format('ymd') === $dateString;
    }
}
