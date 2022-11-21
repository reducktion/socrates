<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class SloveniaTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Luka' => [
                'emso' => '0101006500006',
                'gender' => Gender::Male,
                'dob' => new DateTime('2006-01-01'),
                'age' => $this->calculateAge(new DateTime('2006-01-01')),
                'pob' => 'Slovenia',
            ],
            'Zoja' => [
                'emso' => '0310933507830',
                'gender' => Gender::Female,
                'dob' => new DateTime('1933-10-03'),
                'age' => $this->calculateAge(new DateTime('1933-10-03')),
                'pob' => 'Slovenia',
            ],
            'Jaka' => [
                'emso' => '1408984500257',
                'gender' => Gender::Male,
                'dob' => new DateTime('1984-08-14'),
                'age' => $this->calculateAge(new DateTime('1984-08-14')),
                'pob' => 'Slovenia',
            ],
            'Lana' => [
                'emso' => '0205962509348',
                'gender' => Gender::Female,
                'dob' => new DateTime('1962-05-02'),
                'age' => $this->calculateAge(new DateTime('1962-05-02')),
                'pob' => 'Slovenia',
            ],
            'Mia' => [
                'emso' => '1201962509788',
                'gender' => Gender::Female,
                'dob' => new DateTime('1962-01-12'),
                'age' => $this->calculateAge(new DateTime('1962-01-12')),
                'pob' => 'Slovenia',
            ],
        ];

        $this->invalidIds = [
            '0105978500412',
            '2808928401264',
            '1302953216612',
            '0110951074616',
            '0105182719821',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['emso'], Country::Slovenia);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('010597850041', Country::Slovenia);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['emso'], Country::Slovenia)
            );
        }

        foreach ($this->invalidIds as $emso) {
            self::assertFalse(
                $this->socrates->validateId($emso, Country::Slovenia)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('010597850041', Country::Slovenia);
    }
}
