<?php


namespace Reducktion\Socrates\Tests\Feature;


use Carbon\Carbon;
use Reducktion\Socrates\Constants\Gender;
use Reducktion\Socrates\Exceptions\InvalidLengthException;
use Reducktion\Socrates\Facades\Socrates;

class LithuaniaTest extends FeatureTest
{
    private $people;

    private $invalidIds;

    public function setUp(): void
    {
        parent::setUp();

        $this->people = [
            'janis' => [
                'pc' => '38409152012',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1984-09-15'),
                'age' => Carbon::createFromFormat('Y-m-d', '1984-09-15')->age
            ],
            'natas' => [
                'pc' => '31710058023',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1917-10-05'),
                'age' => Carbon::createFromFormat('Y-m-d', '1917-10-05')->age
            ],
            'daiva' => [
                'pc' => '44804129713',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '1948-04-12'),
                'age' => Carbon::createFromFormat('Y-m-d', '1948-04-12')->age
            ],
            'geta' => [
                'pc' => '60607279626',
                'gender' => Gender::FEMALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2006-07-27'),
                'age' => Carbon::createFromFormat('Y-m-d', '2006-07-27')->age
            ],
            'domynikas' => [
                'pc' => '50508199254',
                'gender' => Gender::MALE,
                'dob' => Carbon::createFromFormat('Y-m-d', '2005-08-19'),
                'age' => Carbon::createFromFormat('Y-m-d', '2005-08-19')->age
            ]
        ];

        $this->invalidIds = [
            '38409152011',
            '10501199256',
            '60607279621',
            '10602279622',
            '31744053021'
        ];
    }

    public function test_extract_behaviour(): void
    {
        foreach ($this->people as $person) {
            $citizen = Socrates::getCitizenDataFromId($person['pc'], 'LT');
            $this->assertEquals($person['gender'], $citizen->getGender());
            $this->assertEquals($person['dob'], $citizen->getDateOfBirth());
            $this->assertEquals($person['age'], $citizen->getAge());
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::getCitizenDataFromId('3050811811', 'LT');
    }

    public function test_validation_behaviour(): void
    {
        foreach ($this->people as $person)  {
            $this->assertTrue(
                Socrates::validateId($person['pc'], 'LT')
            );
        }

        foreach ($this->invalidIds as $pc) {
            $this->assertFalse(
                Socrates::validateId($pc, 'LT')
            );
        }

        $this->expectException(InvalidLengthException::class);

        Socrates::validateId('3050811811123', 'LT');
    }

}