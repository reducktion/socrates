<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Exceptions\UnsupportedOperationException;

class LatviaTest extends FeatureTest
{
    private $supportedExtractionPeople;
    private $unsupportedExtractionPeople;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->supportedExtractionPeople = [
            'Agnese' => [
                'pk' => '120673-10053',
                'dob' => Carbon::createFromFormat('Y-m-d', '1973-06-12'),
                'age' => Carbon::createFromFormat('Y-m-d', '1973-06-12')->age
            ],
            'Rainers' => [
                'pk' => '031098-10386',
                'dob' => Carbon::createFromFormat('Y-m-d', '1998-10-03'),
                'age' => Carbon::createFromFormat('Y-m-d', '1998-10-03')->age
            ],
            'Kin' => [
                'pk' => '250302-20559',
                'dob' => Carbon::createFromFormat('Y-m-d', '2002-03-25'),
                'age' => Carbon::createFromFormat('Y-m-d', '2002-03-25')->age
            ],
            'Anton' => [
                'pk' => '300863-10955',
                'dob' => Carbon::createFromFormat('Y-m-d', '1963-08-30'),
                'age' => Carbon::createFromFormat('Y-m-d', '1963-08-30')->age
            ],
            'Karlis' => [
                'pk' => '171210-20739',
                'dob' => Carbon::createFromFormat('Y-m-d', '2010-12-17'),
                'age' => Carbon::createFromFormat('Y-m-d', '2010-12-17')->age
            ]
        ];

        $this->unsupportedExtractionPeople = [
            'Barbara' => [
                'pk' => '326587-98143',
            ],
            'Rualds' => [
                'pk' => '328291-18212',
            ],
            'Safeena' => [
                'pk' => '320112-73198',
            ],
            'Martin' => [
                'pk' => '329121-01023',
            ],
            'Kristiana' => [
                'pk' => '328891-74201',
            ]
        ];

        $this->invalidIds = [
            '110801-87212',
            '240799-92123',
            '998822-97621',
            '412678-12362',
            '987212-17538',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->supportedExtractionPeople as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['pk'], 'LV');
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(UnsupportedOperationException::class);
        foreach ($this->unsupportedExtractionPeople as $person) {
            Socrates::getCitizenDataFromId($person['pk'], 'LV');
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('326587-981', 'LV');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->supportedExtractionPeople as $person) {
            $this->assertTrue(
                Socrates::validateId($person['pk'], 'LV')
            );
        }

        foreach ($this->invalidIds as $pk) {
            $this->assertFalse(
                Socrates::validateId($pk, 'LV')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('171210-2073', 'LV');
    }
}
