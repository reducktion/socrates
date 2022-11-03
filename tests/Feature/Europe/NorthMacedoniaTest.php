<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class NorthMacedoniaTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Marko' => [
                'jmbg' => '2408944448442',
                'gender' => Gender::Female,
                'dob' => new DateTime('1944-08-24'),
                'age' => $this->calculateAge(new DateTime('1944-08-24')),
                'pob' => 'Prilep - North Macedonia',
            ],
            'Stefan' => [
                'jmbg' => '0705957463421',
                'gender' => Gender::Male,
                'dob' => new DateTime('1957-05-07'),
                'age' => $this->calculateAge(new DateTime('1957-05-07')),
                'pob' => 'Strumica - North Macedonia',
            ],
            'Amyntas' => [
                'jmbg' => '1610936414199',
                'gender' => Gender::Male,
                'dob' => new DateTime('1936-10-16'),
                'age' => $this->calculateAge(new DateTime('1936-10-16')),
                'pob' => 'Bitola - North Macedonia',
            ],
            'Dimitrov' => [
                'jmbg' => '1207942491481',
                'gender' => Gender::Male,
                'dob' => new DateTime('1942-07-12'),
                'age' => $this->calculateAge(new DateTime('1942-07-12')),
                'pob' => 'Štip - North Macedonia',
            ],
            'Kleitus' => [
                'jmbg' => '2808928401264',
                'gender' => Gender::Male,
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
            $citizen = $this->socrates->getCitizenDataFromId($person['jmbg'], Country::NorthMacedonia);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('010597850041', Country::NorthMacedonia);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['jmbg'], Country::NorthMacedonia)
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            self::assertFalse(
                $this->socrates->validateId($jmbg, Country::NorthMacedonia)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('010597850041', Country::NorthMacedonia);
    }
}
