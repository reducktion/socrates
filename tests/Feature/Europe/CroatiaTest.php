<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTestCase;

class CroatiaTest extends FeatureTestCase
{
    public static function peopleDataProvider(): array
    {
        return [
            'Ivana' => [
                'person' => [
                    'jmbg' => '1809988305313',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1988-09-18'),
                    'age' => self::calculateAge(new DateTime('1988-09-18')),
                    'pob' => 'Osijek, Slavonia region - Croatia'
                ],
            ],
            'Ana' => [
                'person' => [
                    'jmbg' => '0808928315425',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1928-08-08'),
                    'age' => self::calculateAge(new DateTime('1928-08-08')),
                    'pob' => 'Bjelovar, Virovitica, Koprivnica, Pakrac, Podravina region - Croatia'
                ],
            ],
            'Marija' => [
                'person' => [
                    'jmbg' => '1106961359224',
                    'gender' => Gender::Female,
                    'dob' => new DateTime('1961-06-11'),
                    'age' => self::calculateAge(new DateTime('1961-06-11')),
                    'pob' => 'Gospić, Lika region - Croatia'
                ],
            ],
            'Stjepan' => [
                'person' => [
                    'jmbg' => '1105951323209',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1951-05-11'),
                    'age' => self::calculateAge(new DateTime('1951-05-11')),
                    'pob' => 'Varaždin, Međimurje region - Croatia'
                ],
            ],
            'Ivan' => [
                'person' => [
                    'jmbg' => '2109971352638',
                    'gender' => Gender::Male,
                    'dob' => new DateTime('1971-09-21'),
                    'age' => self::calculateAge(new DateTime('1971-09-21')),
                    'pob' => 'Gospić, Lika region - Croatia'
                ],
            ],
            'unknown1' => [
                'person' => [
                    'oib' => '34562345678',
                ],
            ],
            'unknown2' => [
                'person' => [
                    'oib' => '12286373446',
                ],
            ],
            'unknown3' => [
                'person' => [
                    'oib' => '97230458182',
                ],
            ],
            'unknown4' => [
                'person' => [
                    'oib' => '08214881054',
                ],
            ],
            'unknown5' => [
                'person' => [
                    'oib' => '27446063711',
                ]
            ]
        ];
    }

    public static function invalidIdsDataProvider(): array
    {
        return [
            ['2182791212638'],
            ['27446182112'],
            ['27446062711'],
            ['1181818993013'],
            ['1821992971638'],
        ];
    }

    #[DataProvider('peopleDataProvider')]
    public function test_extract_behaviour(array $person): void
    {
        if (isset($person['jmbg'])) {
            $citizen = $this->socrates->getCitizenDataFromId($person['jmbg'], Country::Croatia);
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
            self::assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        } else {
            $this->expectException(InvalidLengthException::class);
            $citizen = $this->socrates->getCitizenDataFromId($person['oib'], Country::Croatia);
        }
    }

    #[DataProvider('peopleDataProvider')]
    public function test_validation_with_valid_ids_passes(array $person): void
    {
        self::assertTrue($this->socrates->validateId($person['jmbg'] ?? $person['oib'], Country::Croatia));
    }

    #[DataProvider('invalidIdsDataProvider')]
    public function test_validation_with_invalid_ids_fails(string $invalidId): void
    {
        self::assertFalse($this->socrates->validateId($invalidId, Country::Croatia));
    }

    public function test_validation_using_ids_with_invalid_length_throw_exception(): void
    {
        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('010597850', Country::Croatia);
    }
}
