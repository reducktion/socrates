<?php

namespace Reducktion\Socrates\Tests;

use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Core\CitizenInformationExtractorFactory;
use RuntimeException;

class CitizenInformationExtractorFactoryTest extends TestCase
{
    /** @test */
    public function it_gets_extractor(): void
    {
        $extractor = CitizenInformationExtractorFactory::getExtractor('FI');
        $this->assertInstanceOf(CitizenInformationExtractor::class, $extractor);
    }

    /** @test */
    public function it_throws_for_unknown_country(): void
    {
        $this->expectException(RuntimeException::class);
        CitizenInformationExtractorFactory::getExtractor('XX');
    }
}
