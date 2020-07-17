<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class NorthMacedoniaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Marko' => [
                'jmbg' => '2408944448442',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1944-08-24'),
                'age' => Carbon::createFromFormat('Y-m-d', '1944-08-24')->age,
                'pob' => 'Prilep - North Macedonia'
            ],
            'Stefan' => [
                'jmbg' => '0705957463421',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1957-05-07'),
                'age' => Carbon::createFromFormat('Y-m-d', '1957-05-07')->age,
                'pob' => 'Strumica - North Macedonia'
            ],
            'Amyntas' => [
                'jmbg' => '1610936414199',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1936-10-16'),
                'age' => Carbon::createFromFormat('Y-m-d', '1936-10-16')->age,
                'pob' => 'Bitola - North Macedonia'
            ],
            'Dimitrov' => [
                'jmbg' => '1207942491481',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1942-07-12'),
                'age' => Carbon::createFromFormat('Y-m-d', '1942-07-12')->age,
                'pob' => 'Štip - North Macedonia'
            ],
            'Kleitus' => [
                'jmbg' => '2808928401264',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1928-08-28'),
                'age' => Carbon::createFromFormat('Y-m-d', '1928-08-28')->age,
                'pob' => 'North Macedonia'
            ]
        ];

        $this->invalidIds = [
            '2408987648442',
            '0205962509348',
            '2007950274591',
            '2702937737434',
            '2102898012311'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['jmbg'], 'MK');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
            $this->assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'MK');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['jmbg'], 'MK')
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            $this->assertFalse(
                Socrates::validateId($jmbg, 'MK')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'MK');
    }
}
