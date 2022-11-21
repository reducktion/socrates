<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class SerbiaTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Nikola' => [
                'jmbg' => '0101100710006',
                'gender' => Gender::Male,
                'dob' => new DateTime('2100-01-01'),
                'age' => $this->calculateAge(new DateTime('2100-01-01')),
                'pob' => 'Belgrade region (City of Belgrade) - Central Serbia',
            ],
            'Miloje' => [
                'jmbg' => '0110951074616',
                'gender' => Gender::Male,
                'dob' => new DateTime('1951-10-01'),
                'age' => $this->calculateAge(new DateTime('1951-10-01')),
                'pob' => 'foreigners in Serbian province of Vojvodina',
            ],
            'Teodora' => [
                'jmbg' => '2702937737434',
                'gender' => Gender::Female,
                'dob' => new DateTime('1937-02-27'),
                'age' => $this->calculateAge(new DateTime('1937-02-27')),
                'pob' => 'Niš region (Nišava District, Pirot District and Toplica District) - Central Serbia',
            ],
            'Jana' => [
                'jmbg' => '2606936778324',
                'gender' => Gender::Female,
                'dob' => new DateTime('1936-06-26'),
                'age' => $this->calculateAge(new DateTime('1936-06-26')),
                'pob' => 'Podrinje and Kolubara regions (Mačva District and Kolubara District) - Central Serbia',
            ],
            'Petra' => [
                'jmbg' => '1209992745266',
                'gender' => Gender::Female,
                'dob' => new DateTime('1992-09-12'),
                'age' => $this->calculateAge(new DateTime('1992-09-12')),
                'pob' => 'Southern Morava region (Jablanica District and Pčinja District) - Central Serbia',
            ]
        ];

        $this->invalidIds = [
            '2104108291012',
            '2808928401264',
            '2508995191483',
            '1201962509788',
            '1101987092078'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = $this->socrates->getCitizenDataFromId($person['jmbg'], Country::Serbia);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('010597850041', Country::Serbia);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['jmbg'], Country::Serbia)
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            self::assertFalse(
                $this->socrates->validateId($jmbg, Country::Serbia)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('010597850041', Country::Serbia);
    }
}
