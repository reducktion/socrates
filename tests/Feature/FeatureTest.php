<?php

namespace Reducktion\Socrates\Tests\Feature;

use DateTime;
use PHPUnit\Framework\TestCase;
use Reducktion\Socrates\Socrates;

abstract class FeatureTest extends TestCase
{
    public Socrates $socrates;

    protected function setUp(): void
    {
        parent::setUp();

        $this->socrates = new Socrates();
    }

    abstract public function test_extract_behaviour(): void;

    abstract public function test_validation_behaviour(): void;

    public function calculateAge(DateTime $dateOfBirth): int
    {
        return (new DateTime())->diff($dateOfBirth)->y;
    }
}
