<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Facades\Socrates;

class IcelandTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'andi' => [
                'kt' => '0902862349',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1992-07-09'),
                'age' => Carbon::createFromFormat('Y-m-d', '1992-07-09')->age,
            ],
            'freyja' => [
                'kt' => '120174-3399',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1993-05-07'),
                'age' => Carbon::createFromFormat('Y-m-d', '1993-05-07')->age,
            ],
//            'nair' => [
//                'kt' => '150437-3068',
//                'gender' => Gender::FEMALE,
//                'dob' => Carbon::createFromFormat('Y-m-d', '1990-08-18'),
//                'age' => Carbon::createFromFormat('Y-m-d', '1990-08-18')->age,
//            ],
//            'eva' => [
//                'kt' => '040404-7094',
//                'gender' => Gender::FEMALE,
//                'dob' => Carbon::createFromFormat('Y-m-d', '2004-04-04'),
//                'age' => Carbon::createFromFormat('Y-m-d', '2004-04-04')->age,
//            ],
//            'hrafn' => [
//                'kt' => '160888-1995',
//                'gender' => Gender::MALE,
//                'dob' => Carbon::createFromFormat('Y-m-d', '1988-08-16'),
//                'age' => Carbon::createFromFormat('Y-m-d', '1988-08-16')->age,
//            ],
        ];

        $this->invalidIds = [
            '2343212454',
            '333694-0034',
            '1201743389',
            '0902862549',
            '0404047054'
        ];
    }

    public function test_extract_behaviour(): void
    {
//        foreach ($this->people as $person) {
//            $citizen = Socrates::getCitizenDataFromId($person['kt'], 'IS');
//
//            $this->assertEquals($person['gender'], $citizen->getGender());
//            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
//            $this->assertEquals($person['age'], $citizen->getAge());
//        }
//
//        $this->expectException(InvalidLengthException::class);
//
//        Socrates::getCitizenDataFromId('324432-343', 'IS');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['kt'], 'IS')
            );
        }

        foreach ($this->invalidIds as $kt) {
            $this->assertFalse(
                Socrates::validateId($kt, 'IS')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('21442411', 'IS');
    }
}