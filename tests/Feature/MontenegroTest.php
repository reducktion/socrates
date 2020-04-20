<?php

namespace Reducktion\Socrates\Tests\Feature;

use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Facades\Socrates;

class MontenegroTest extends FeatureTest
{
    private $people;
    private $invalidIds;

    protected function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'Crnoje' => [
                'jmbg' => '2106941231195',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1941-06-21'),
                'age' => Carbon::createFromFormat('Y-m-d', '1941-06-21')->age,
                'pob' => 'Budva, Kotor, Tivat - Montenegro'
            ],
            'Blažo' => [
                'jmbg' => '1502945264054',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1945-02-15'),
                'age' => Carbon::createFromFormat('Y-m-d', '1945-02-15')->age,
                'pob' => 'Nikšić, Plužine, Šavnik - Montenegro'
            ],
            'Diko' => [
                'jmbg' => '2007950274591',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1950-07-20'),
                'age' => Carbon::createFromFormat('Y-m-d', '1950-07-20')->age,
                'pob' => 'Berane, Rožaje, Plav, Andrijevica - Montenegro'
            ],
            'Ema' => [
                'jmbg' => '1302953216612',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1953-02-13'),
                'age' => Carbon::createFromFormat('Y-m-d', '1953-02-13')->age,
                'pob' => 'Podgorica, Danilovgrad, Kolašin - Montenegro'
            ],
            'Draginja' => [
                'jmbg' => '0204942275271',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1942-04-02'),
                'age' => Carbon::createFromFormat('Y-m-d', '1942-04-02')->age,
                'pob' => 'Berane, Rožaje, Plav, Andrijevica - Montenegro'
            ],
        ];

        $this->invalidIds = [
            '2106182718195',
            '1207942491481',
            '0110951074616',
            '1806998154160',
            '1102012374160'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['jmbg'], 'ME');

            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
            $this->assertEquals($person['pob'], $citizen->getPlaceOfBirth());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('010597850041', 'ME');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person) {
            $this->assertTrue(
                Socrates::validateId($person['jmbg'], 'ME')
            );
        }

        foreach ($this->invalidIds as $jmbg) {
            $this->assertFalse(
                Socrates::validateId($jmbg, 'ME')
            );
        }
    }

}