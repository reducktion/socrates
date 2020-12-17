<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class SerbiaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Nikola' => [
                'jmbg' => '0101100710006',
                'gender' => Gender::MALE,
                'dob' => new DateTime('2100-01-01'),
                'age' => $this->calculateAge(new DateTime('2100-01-01')),
                'pob' => 'Belgrade region (City of Belgrade) - Central Serbia',
            ],
            'Miloje' => [
                'jmbg' => '0110951074616',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1951-10-01'),
                'age' => $this->calculateAge(new DateTime('1951-10-01')),
                'pob' => 'foreigners in Serbian province of Vojvodina',
            ],
            'Teodora' => [
                'jmbg' => '2702937737434',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1937-02-27'),
                'age' => $this->calculateAge(new DateTime('1937-02-27')),
                'pob' => 'Niš region (Nišava District, Pirot District and Toplica District) - Central Serbia',
            ],
            'Jana' => [
                'jmbg' => '2606936778324',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1936-06-26'),
                'age' => $this->calculateAge(new DateTime('1936-06-26')),
                'pob' => 'Podrinje and Kolubara regions (Mačva District and Kolubara District) - Central Serbia',
            ],
            'Petra' => [
                'jmbg' => '1209992745266',
                'gender' => Gender::FEMALE,
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
            $citizen = Socrates::getCitizenDataFromId($person['jmbg'], 'RS');

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'RS');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['jmbg'], 'RS')
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            self::assertFalse(
                Socrates::validateId($jmbg, 'RS')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'RS');
    }
}
