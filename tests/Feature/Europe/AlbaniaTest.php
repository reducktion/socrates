<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class AlbaniaTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'bardhana' => [
                'nid' => 'I05101999I',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1980-01-01'),
                'age' => $this->calculateAge(new DateTime('1980-01-01')),
            ],
            'shufti' => [
                'nid' => 'I90201535E',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1989-02-01'),
                'age' => $this->calculateAge(new DateTime('1989-02-01')),
            ],
            'shyqe' => [
                'nid' => 'J45423004V',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1994-04-23'),
                'age' => $this->calculateAge(new DateTime('1994-04-23')),
            ],
            'elseid' => [
                'nid' => 'H71211672R',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1977-12-11'),
                'age' => $this->calculateAge(new DateTime('1977-12-11')),
            ],
            'hasna' => [
                'nid' => 'I85413200A',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1988-04-13'),
                'age' => $this->calculateAge(new DateTime('1988-04-13')),
            ],
        ];

        $this->invalidIds = [
            'I05101999Q',
            'J45423004Y',
            'I85413200J',
            'I90201535M',
            'H71211672A',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['nid'], 'AL');
            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('I051019992I', 'AL');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['nid'], 'AL')
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            self::assertFalse(
                Socrates::validateId($invalidId, 'AL')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('I0785101999I', 'AL');
    }
}
