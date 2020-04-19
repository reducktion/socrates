<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Facades\Socrates;

class BosniaAndHerzegovinaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'naser' => [
                'umcn' => '1502957172694',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1957-02-15'),
                'age' => Carbon::createFromFormat('Y-m-d', '1957-02-15')->age,
                'pob' => 'Sarajevo - Bosnia and Herzegovina'
            ]
        ];

        $this->invalidIds = [
            '1108291065212'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['umcn'], 'BA');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
            $this->assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'BA');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['umcn'], 'BA')
            );
        }

        foreach ($this->invalidIds as $umcn) {
            $this->assertFalse(
                Socrates::validateId($umcn, 'BA')
            );
        }
    }

}