<?php

namespace Reducktion\Socrates\Tests\Feature;

use DateTime;
use Reducktion\Socrates\Tests\TestCase;

abstract class FeatureTest extends TestCase
{
    abstract public function test_extract_behaviour(): void;

    abstract public function test_validation_behaviour(): void;

    public function calculateAge(DateTime $dateOfBirth): int
    {
        return (new DateTime())->diff($dateOfBirth)->y;
    }
}
