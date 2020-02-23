<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\Denmark\InvalidCprLengthException;
use Reducktion\Socrates\Facades\Socrates;

class DenmarkTest extends FeatureTest
{
    public function test_extract_behaviour(): void
    {
        $people = [
            'julius' => [
                'cpr' => '090792-1395',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1992-07-09'),
                'age' => 27,
            ],
            'naja' => [
                'cpr' => '070593-0600',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1993-05-07'),
                'age' => 26,
            ],
            'rolla' => [
                'cpr' => '150437-3068',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1937-04-15'),
                'age' => 82,
            ],
            'thomas' => [
                'cpr' => '160888-1995',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1988-08-16'),
                'age' => 31,
            ],
            'mia' => [
                'cpr' => '040404-7094',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2004-04-04'),
                'age' => 15,
            ],
        ];

        foreach ($people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['cpr'], 'DK');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidCprLengthException::class);

        Socrates::getCitizenDataFromId('324432-343', 'DK');

        $this->expectException(InvalidCprLengthException::class);
    }

    public function test_validation_behaviour(): void
    {
        $this->assertTrue(
            Socrates::validateId('251195-1448', 'DK')
        );
    }
}