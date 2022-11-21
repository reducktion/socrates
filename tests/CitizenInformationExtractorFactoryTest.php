<?php

namespace Reducktion\Socrates\Tests;

use PHPUnit\Framework\TestCase;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Contracts\CitizenInformationExtractor;
use Reducktion\Socrates\Core\CitizenInformationExtractorFactory;

class CitizenInformationExtractorFactoryTest extends TestCase
{
    /** @test */
    public function it_gets_extractor(): void
    {
        $extractor = CitizenInformationExtractorFactory::getExtractor(Country::Finland);
        $this->assertInstanceOf(CitizenInformationExtractor::class, $extractor);
    }
}
