<?php

namespace Reducktion\Socrates\Tests\Feature;

use DateTime;
use PHPUnit\Framework\TestCase;
use Reducktion\Socrates\Socrates;

abstract class FeatureTestCase extends TestCase
{
    public Socrates $socrates;

    protected function setUp(): void
    {
        parent::setUp();

        $this->socrates = new Socrates();
    }

    abstract public function test_extract_behaviour(array $person): void;

    abstract public function test_validation_with_valid_ids_passes(array $person): void;

    abstract public function test_validation_with_invalid_ids_fails(string $invalidId): void;

    abstract public function test_validation_using_ids_with_invalid_length_throw_exception(): void;

    public static function calculateAge(DateTime $dateOfBirth): int
    {
        return new DateTime()->diff($dateOfBirth)->y;
    }
}
