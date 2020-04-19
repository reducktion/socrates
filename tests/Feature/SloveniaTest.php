<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class SloveniaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'luka' => [
                'umcn' => '0101006500006',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2006-01-01'),
                'age' => Carbon::createFromFormat('Y-m-d', '2006-01-01')->age,
                'pob' => 'Slovenia'
            ]
        ];

        $this->invalidIds = [
            '0105978500412'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['umcn'], 'SI');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
            $this->assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'SI');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['umcn'], 'SI')
            );
        }

        foreach ($this->invalidIds as $umcn) {
            $this->assertFalse(
                Socrates::validateId($umcn, 'SI')
            );
        }
    }

}