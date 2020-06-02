<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class LuxembourgTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Gabriel' => [
                'nin' => '1983081246783',
                'dob' => Carbon::createFromFormat('Y-m-d', '1983-08-12'),
                'age' => Carbon::createFromFormat('Y-m-d', '1983-08-12')->age,
            ],
            'Emma' => [
                'nin' => '2003042581931',
                'dob' => Carbon::createFromFormat('Y-m-d', '2003-04-25'),
                'age' => Carbon::createFromFormat('Y-m-d', '2003-04-25')->age,
            ],
            'Leo' => [
                'nin' => '1971110258746',
                'dob' => Carbon::createFromFormat('Y-m-d', '1971-11-02'),
                'age' => Carbon::createFromFormat('Y-m-d', '1971-11-02')->age,
            ],
            'Lara' => [
                'nin' => '2012051469336',
                'dob' => Carbon::createFromFormat('Y-m-d', '2012-05-14'),
                'age' => Carbon::createFromFormat('Y-m-d', '2012-05-14')->age,
            ],
            'Noah' => [
                'nin' => '1994092874551',
                'dob' => Carbon::createFromFormat('Y-m-d', '1994-09-28'),
                'age' => Carbon::createFromFormat('Y-m-d', '1994-09-28')->age,
            ],
        ];

        $this->invalidIds = [
            '1994789587182',
            '5971654782313',
            '2055101054879',
            '1997053045687',
            '2001111123654',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['nin'], 'LU');
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('1983046783', 'LU');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['nin'], 'LU')
            );
        }

        foreach ($this->invalidIds as $nin) {
            $this->assertFalse(
                Socrates::validateId($nin, 'LU')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('1983046783', 'LU');
    }
}
