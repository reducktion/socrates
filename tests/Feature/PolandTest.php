<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class PolandTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Alesky' => [
                'pesel' => '91072592137',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1991-07-25'),
                'age' => Carbon::createFromFormat('Y-m-d', '1991-07-25')->age,
            ],
            'Adelajda' => [
                'pesel' => '02220826789',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2002-02-08'),
                'age' => Carbon::createFromFormat('Y-m-d', '2002-02-08')->age,
            ],
            'Izolda' => [
                'pesel' => '87050832348',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1987-05-08'),
                'age' => Carbon::createFromFormat('Y-m-d', '1987-05-08')->age,
            ],
            'Klaudiusz' => [
                'pesel' => '87012962775',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1987-01-29'),
                'age' => Carbon::createFromFormat('Y-m-d', '1987-01-29')->age,
            ],
            'Fryderyk' => [
                'pesel' => '64032906019',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1964-03-29'),
                'age' => Carbon::createFromFormat('Y-m-d', '1964-03-29')->age,
            ],
        ];

        $this->invalidIds = [
            '93112056526',
            '04301791498',
            '69042359891',
            '82081349523',
            '48101641888'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['pesel'], 'PL');
            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('5882764108', 'PL');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['pesel'], 'PL')
            );
        }

        foreach ($this->invalidIds as $pesel) {
            $this->assertFalse(
                Socrates::validateId($pesel, 'PL')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('4810164188', 'PL');
    }
}
