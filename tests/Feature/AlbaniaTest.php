<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Laravel\Facades\Socrates;

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
                'dob' => Carbon::createFromFormat('Y-m-d', '1980-01-01'),
                'age' => Carbon::createFromFormat('Y-m-d', '1980-01-01')->age,
            ],
            'shufti' => [
                'nid' => 'I90201535E',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1989-02-01'),
                'age' => Carbon::createFromFormat('Y-m-d', '1989-02-01')->age,
            ],
            'shyqe' => [
                'nid' => 'J45423004V',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1994-04-23'),
                'age' => Carbon::createFromFormat('Y-m-d', '1994-04-23')->age,
            ],
            'elseid' => [
                'nid' => 'H71211672R',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1977-12-11'),
                'age' => Carbon::createFromFormat('Y-m-d', '1977-12-11')->age,
            ],
            'hasna' => [
                'nid' => 'I85413200A',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1988-04-13'),
                'age' => Carbon::createFromFormat('Y-m-d', '1988-04-13')->age,
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

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('I051019992I', 'AL');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['nid'], 'AL')
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            $this->assertFalse(
                Socrates::validateId($invalidId, 'AL')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('I0785101999I', 'AL');
    }
}