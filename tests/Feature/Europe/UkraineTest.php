<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use Carbon\Carbon;
use DateTime;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class UkraineTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'petro' => [
                'id' => '4031090675',
                'gender' => Gender::MALE,
                'dob' => new DateTime('2010-05-13'),
                'age' => $this->calculateAge(new DateTime('2010-05-13')),
            ],
            'vadym' => [
                'id' => '3292197434',
                'gender' => Gender::MALE,
                'dob' => new DateTime('1990-02-18'),
                'age' => $this->calculateAge(new DateTime('1990-02-18')),
            ],
            'veronika' => [
                'id' => '2023599602',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('1955-05-27'),
                'age' => $this->calculateAge(new DateTime('1955-05-27')),
            ],
            'ruslana' => [
                'id' => '4247134484',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('2016-04-12'),
                'age' => $this->calculateAge(new DateTime('2016-04-12')),
            ],
            'zoya' => [
                'id' => '3771509002',
                'gender' => Gender::FEMALE,
                'dob' => new DateTime('2003-04-05'),
                'age' => $this->calculateAge(new DateTime('2003-04-05')),
            ]
        ];

        $this->invalidIds = [
            '3771509022',
            '3771593429',
            '3771542376',
            '0023577230',
            '3000077230'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['id'], 'UA');

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals(Carbon::instance($person['dob']), $citizen->getDateOfBirth());
            self::assertEquals($person['dob'], $citizen->getDateOfBirthNative());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('37715090021', 'UA');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                Socrates::validateId($person['id'], 'UA')
            );
        }

        foreach ($this->invalidIds as $id) {
            self::assertFalse(
                Socrates::validateId($id, 'UA')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('37715090022', 'UA');
    }
}
