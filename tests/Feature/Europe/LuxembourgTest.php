<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use RuntimeException;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Tests\Feature\FeatureTest;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;

class LuxembourgTest extends FeatureTest
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_validation_behaviour(): void
    {
        $this->expectException(RuntimeException::class);

        Socrates::validateId('1983046783', 'LU');
    }

    public function test_extract_behaviour(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        Socrates::getCitizenDataFromId('1983046783', 'LU');
    }
}
