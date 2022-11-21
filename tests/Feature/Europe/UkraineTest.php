<?php

namespace Reducktion\Socrates\Tests\Feature\Europe;

use DateTime;
use Reducktion\Socrates\Constants\Country;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Tests\Feature\FeatureTest;

class UkraineTest extends FeatureTest
{
    private array $people;
    private array $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'petro' => [
                'id' => '4031090675',
                'gender' => Gender::Male,
                'dob' => new DateTime('2010-05-13'),
                'age' => $this->calculateAge(new DateTime('2010-05-13')),
            ],
            'vadym' => [
                'id' => '3292197434',
                'gender' => Gender::Male,
                'dob' => new DateTime('1990-02-18'),
                'age' => $this->calculateAge(new DateTime('1990-02-18')),
            ],
            'veronika' => [
                'id' => '2023599602',
                'gender' => Gender::Female,
                'dob' => new DateTime('1955-05-27'),
                'age' => $this->calculateAge(new DateTime('1955-05-27')),
            ],
            'ruslana' => [
                'id' => '4247134484',
                'gender' => Gender::Female,
                'dob' => new DateTime('2016-04-12'),
                'age' => $this->calculateAge(new DateTime('2016-04-12')),
            ],
            'zoya' => [
                'id' => '3771509002',
                'gender' => Gender::Female,
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
            $citizen = $this->socrates->getCitizenDataFromId($person['id'], Country::Ukraine);

            self::assertEquals($person['gender'], $citizen->getGender());
            self::assertEquals($person['dob'], $citizen->getDateOfBirth());
            self::assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->getCitizenDataFromId('37715090021', Country::Ukraine);
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            self::assertTrue(
                $this->socrates->validateId($person['id'], Country::Ukraine)
            );
        }

        foreach ($this->invalidIds as $id) {
            self::assertFalse(
                $this->socrates->validateId($id, Country::Ukraine)
            );
        }

        $this->expectException(InvalidLengthException::class);

        $this->socrates->validateId('37715090022', Country::Ukraine);
    }
}
