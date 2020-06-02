<?php

namespace Reducktion\Socrates\Tests\Feature;

use Reducktion\Socrates\Tests\TestCase;

abstract class FeatureTest extends TestCase
{
    abstract public function test_extract_behaviour(): void;

    abstract public function test_validation_behaviour(): void;
}
