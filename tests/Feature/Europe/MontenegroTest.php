<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class MontenegroTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Crnoje' => [
                'jmbg' => '2106941231195',
                'gender' => Gender::Male,
                'dob' => new DateTime('1941-06-21'),
                'age' => $this->calculateAge(new DateTime('1941-06-21')),
                'pob' => 'Budva, Kotor, Tivat - Montenegro',
            ],
            'Blažo' => [
                'jmbg' => '1502945264054',
                'gender' => Gender::Male,
                'dob' => new DateTime('1945-02-15'),
                'age' => $this->calculateAge(new DateTime('1945-02-15')),
                'pob' => 'Nikšić, Plužine, Šavnik - Montenegro',
            ],
            'Diko' => [
                'jmbg' => '2007950274591',
                'gender' => Gender::Male,
                'dob' => new DateTime('1950-07-20'),
                'age' => $this->calculateAge(new DateTime('1950-07-20')),
                'pob' => 'Berane, Rožaje, Plav, Andrijevica - Montenegro',
            ],
            'Ema' => [
                'jmbg' => '1302953216612',
                'gender' => Gender::Female,
                'dob' => new DateTime('1953-02-13'),
                'age' => $this->calculateAge(new DateTime('1953-02-13')),
                'pob' => 'Podgorica, Danilovgrad, Kolašin - Montenegro',
            ],
            'Draginja' => [
                'jmbg' => '0204942275271',
                'gender' => Gender::Female,
                'dob' => new DateTime('1942-04-02'),
                'age' => $this->calculateAge(new DateTime('1942-04-02')),
                'pob' => 'Berane, Rožaje, Plav, Andrijevica - Montenegro',
            ],
        ];

        $this->invalidIds = [
            '2106182718195',
            '1207942491481',
            '0110951074616',
            '1806998154160',
            '1102012374160'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['jmbg'], Country::Montenegro);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('010597850041', Country::Montenegro);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['jmbg'], Country::Montenegro)
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            self::assertFalse(
                $this->socrates->validateId($jmbg, Country::Montenegro)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('010597850041', Country::Montenegro);
    }
}
