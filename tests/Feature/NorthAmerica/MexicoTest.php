<?php

namespace Reducktion\Socrates\Tests\Feature\NorthAmerica;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Tests\Feature\FeatureTest;
use Reducktion\Socrates\Exceptions\InvalidLengthException;

class MexicoTest extends FeatureTest
{
    private $validIds;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'juan' => [
                'curp' => 'MAAR790213HMNRLF03',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1979-2-13'),
                'age' => Carbon::createFromFormat('Y-m-d', '1979-2-13')->age,
            ],
            'letitia' => [
                'curp' => 'HEGG560427MVZRRL04',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1956-4-27'),
                'age' => Carbon::createFromFormat('Y-m-d', '1956-4-27')->age,
            ],
            'augustin' => [
                'curp' => 'BOXW010820HNERXNA1',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2001-8-20'),
                'age' => Carbon::createFromFormat('Y-m-d', '2001-8-20')->age,
            ],
            'diego' => [
                'curp' => 'TUAZ080213HMNRLFA3',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2008-2-13'),
                'age' => Carbon::createFromFormat('Y-m-d', '2008-2-13')->age,
            ],
        ];

        $this->validIds = [
            'MAAR790213HMNRLF03',
            'HEGG560427MVZRRL04',
            'BOXW310820HNERXN09',
            'TUAZ790213HMNRLF02',
            'TUAZ040213MCLRLFA7',
            'JIAZ040213MCLRLFA6',
            'XOAZ980927MCLRLFA6',
        ];

        $this->invalidIds = [
            '1AAR790213HMNRLF03',
            'MRAR790213HMNRLF03',
            'MAARA90213HMNRLF03',
            'MAAR7A0213HMNRLF03',
            'MAAR79A213HMNRLF03',
            'MAAR790A13HMNRLF03',
            'MAAR7902A3HMNRLF03',
            'MAAR79021AHMNRLF03',
            'MRAR791313HMNRLF03',
            'MRAR791332HMNRLF03',
            'MRAR7913321MNRLF03',
            'MRAR791332AMNRLF03',
            'MRAR7902131MNRLF03',
            'MRAR790213ZMNRLF03',
            'MAAR790213HZORLF03',
            'MAAR790213HZ1RLF03',
            'MAAR790213HMNRLF04',
            'MAAR790213HMNRLF0A',
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['curp'], 'MX');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('69218938062', 'MX');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->validIds as $id) {
            $this->assertTrue(
                Socrates::validateId($id, 'MX')
            );
        }

        foreach ($this->invalidIds as $invalidId) {
            $this->assertFalse(
                Socrates::validateId($invalidId, 'MX')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('1621543419643', 'MX');
    }
}
