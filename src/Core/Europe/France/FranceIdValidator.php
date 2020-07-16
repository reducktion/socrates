<?php

namespace Reducktion\Socrates\Core\Europe\France;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class FranceIdValidator implements IdValidator
{
    public function validate(string $id): bool
    {
        $id = $this->sanitize($id);

        $checksum = (int) substr($id, -2);
        $id = substr($id, 0, -2);

        $result = 97 - ((int) $id % 97);

        return $checksum === $result;
    }

    private function sanitize(string $id): string
    {
        $id = str_replace(' ', '', $id);

        $idLength = strlen($id);

        if ($idLength !== 15) {
            throw new InvalidLengthException('French INSEE', '15', $idLength);
        }

        $substitutions = [
            '2A' => 19,
            '2B' => 18
        ];

        foreach ($substitutions as $key => $substitution) {
            $id = str_replace($key, $substitution, $id);
        }

        return $id;
    }
}
