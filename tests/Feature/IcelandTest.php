<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Laravel\Facades\Socrates;

class IcelandTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'andi' => [
                'kt' => '0902862349',
                'dob' => Carbon::createFromFormat('Y-m-d', '1986-02-09'),
                'age' => Carbon::createFromFormat('Y-m-d', '1986-02-09')->age,
            ],
            'freyja' => [
                'kt' => '120174-3399',
                'dob' => Carbon::createFromFormat('Y-m-d', '1974-01-12'),
                'age' => Carbon::createFromFormat('Y-m-d', '1974-01-12')->age,
            ],
            'nair' => [
                'kt' => '1808905059',
                'dob' => Carbon::createFromFormat('Y-m-d', '1990-08-18'),
                'age' => Carbon::createFromFormat('Y-m-d', '1990-08-18')->age,
            ],
            'eva' => [
                'kt' => '2008108569',
                'dob' => Carbon::createFromFormat('Y-m-d', '1910-08-20'),
                'age' => Carbon::createFromFormat('Y-m-d', '1910-08-20')->age,
            ],
            'hrafn' => [
                'kt' => '100303-4930',
                'dob' => Carbon::createFromFormat('Y-m-d', '2003-03-10'),
                'age' => Carbon::createFromFormat('Y-m-d', '2003-03-10')->age,
            ],
        ];

        $this->invalidIds = [
            '2343212454',
            '333694-0034',
            '1201743389',
            '0902862549',
            '0404047054'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['kt'], 'IS');

            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('324432-343', 'IS');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['kt'], 'IS')
            );
        }

        foreach ($this->invalidIds as $kt) {
            $this->assertFalse(
                Socrates::validateId($kt, 'IS')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('21442411', 'IS');
    }
}