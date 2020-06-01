<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Laravel\Facades\Socrates;

class DenmarkTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'julius' => [
                'cpr' => '090792-1395',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1992-07-09'),
                'age' => Carbon::createFromFormat('Y-m-d', '1992-07-09')->age,
            ],
            'naja' => [
                'cpr' => '070593-0600',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1993-05-07'),
                'age' => Carbon::createFromFormat('Y-m-d', '1993-05-07')->age,
            ],
            'rolla' => [
                'cpr' => '150437-3068',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1937-04-15'),
                'age' => Carbon::createFromFormat('Y-m-d', '1937-04-15')->age,
            ],
            'thomas' => [
                'cpr' => '160888-1995',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1988-08-16'),
                'age' => Carbon::createFromFormat('Y-m-d', '1988-08-16')->age,
            ],
            'mia' => [
                'cpr' => '040404-7094',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2004-04-04'),
                'age' => Carbon::createFromFormat('Y-m-d', '2004-04-04')->age,
            ],
        ];

        $this->invalidIds = [
            '234321-2454',
            '333694-0034',
            '088383-2313',
            '133232-1323',
            '040404-7054'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['cpr'], 'DK');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('324432-343', 'DK');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['cpr'], 'DK')
            );
        }

        foreach ($this->invalidIds as $cpr) {
            $this->assertFalse(
                Socrates::validateId($cpr, 'DK')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('21442-2411', 'DK');
    }
}
