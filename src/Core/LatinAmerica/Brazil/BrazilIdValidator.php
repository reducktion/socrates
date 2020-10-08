<?php

namespace Reducktion\Socrates\Core\LatinAmerica\Brazil;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class BrazilIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        $checksum = (int) substr($id, -2);
        $id = substr($id, 0, -2);

        $cpfArray = array_reverse(
            array_map(
                static function ($digit) {
                    return (int) $digit;
                },
                str_split($id)
            )
        );

        // source: https://pt.wikipedia.org/wiki/Cadastro_de_pessoas_f%C3%ADsicas#Algoritmo
        $v1 = 0;
        $v2 = 0;
        for ($i = 0; $i < 9; $i++) {
            $v1 = $v1 + $cpfArray[$i] * (9 - ($i % 10));
            $v2 = $v2 + $cpfArray[$i] * (9 - (($i + 1) % 10));
        }

        $v1 = ($v1 % 11) % 10;
        $v2 = $v2 + ($v1 * 9);
        $v2 = ($v2 % 11) % 10;

        return $checksum === (($v1 * 10) + $v2);
    }

    private function sanitize(string $id): string
    {
        $id = preg_replace('/[^0-9]/', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 11) {
            throw new InvalidLengthException('Brazil CPF', '11', $idLength);
        }

        return $id;
    }
}
