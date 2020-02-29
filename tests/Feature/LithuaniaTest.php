<?php


namespace Reducktion\Socrates\Tests\Feature;


use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Facades\Socrates;

class LithuaniaTest extends FeatureTest
{
    private $people;

    private $invalidIds;

    public function setUp(): void
    {
        parent::setUp();

        $this->people = [];

        $this->invalidIds = [];
    }

    public function test_extract_behaviour(): void
    {
        $citizen = Socrates::getCitizenDataFromId('38409152012', 'LI');
        $this->assertEquals(Gender::MALE, $citizen->getGender());
        $this->assertEquals(Carbon::createFromFormat('Y-m-d', '1984-09-15'), $citizen->getDateofBirth());
    }

    public function test_validation_behaviour(): void
    {
        $this->assertTrue(
            Socrates::validateId('38409152012', 'LI')
        );
    }

}