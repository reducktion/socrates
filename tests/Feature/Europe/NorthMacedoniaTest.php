<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
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
                'dob' => new DateTime('1944-08-24'),
                'age' => $this->calculateAge(new DateTime('1944-08-24')),
                'pob' => 'Prilep - North Macedonia',
            ],
            'Stefan' => [
                'jmbg' => '0705957463421',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1957-05-07'),
                'age' => $this->calculateAge(new DateTime('1957-05-07')),
                'pob' => 'Strumica - North Macedonia',
            ],
            'Amyntas' => [
                'jmbg' => '1610936414199',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1936-10-16'),
                'age' => $this->calculateAge(new DateTime('1936-10-16')),
                'pob' => 'Bitola - North Macedonia',
            ],
            'Dimitrov' => [
                'jmbg' => '1207942491481',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1942-07-12'),
                'age' => $this->calculateAge(new DateTime('1942-07-12')),
                'pob' => 'Štip - North Macedonia',
            ],
            'Kleitus' => [
                'jmbg' => '2808928401264',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1928-08-28'),
                'age' => $this->calculateAge(new DateTime('1928-08-28')),
                'pob' => 'North Macedonia',
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

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'MK');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['jmbg'], 'MK')
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            self::assertFalse(
                Socrates::validateId($jmbg, 'MK')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'MK');
    }
}
