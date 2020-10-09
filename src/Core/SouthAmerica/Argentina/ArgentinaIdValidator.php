<?php

namespace Reducktion\Socrates\Core\Argentina;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class ArgentinaIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        // source: https://es.wikipedia.org/wiki/Clave_%C3%9Anica_de_Identificaci%C3%B3n_Tributaria
        $id = $this->sanitize($id);

        $sum = 0;
        for ($i = 0; $i <= 9; ++$i) {
            $sum += $id[9 - $i] * (2 + ($i % 6));
        }

        $checksum = 11 - ($sum % 11);
        $checksum = $checksum === 11 ? 0 : $checksum;

        return (string)$checksum === $id[-1];
    }

    private function sanitize(string $id): string
    {
        $id = preg_replace('/[^0-9]/', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 11) {
            throw new InvalidLengthException('Argentina CUIL/CUIT', '11', $idLength);
        }

        return $id;
    }
}
