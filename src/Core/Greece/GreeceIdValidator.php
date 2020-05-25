<?php

namespace Reducktion\Socrates\Core\Greece;

use Reducktion\Socrates\Contracts\IdValidator;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class GreeceIdValidator implements IdValidator
{

    public function validate(string $id): bool
    {
        $id = str_replace('-', '', $id);

        $idLength = mb_strlen($id);

        if ($idLength !== 8) {
            throw new InvalidLengthException("Greek id card number must have 8 digits, got $idLength");
        }

        $greekLetters = [ 'Α', 'Β', 'Γ', 'Δ', 'Ε', 'Ζ', 'Η', 'Ι', 'Κ', 'Λ', 'Μ', 'Ν', 'Ξ', 'Ο', 'Π', 'Ρ', 'Σ', 'Τ', 'Υ', 'Φ', 'Ω'];

        return in_array(mb_substr($id, 0, 1), $greekLetters, true) && in_array(mb_substr($id, 1, 1), $greekLetters, true);
    }

}