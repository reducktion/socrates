<?php

namespace Reducktion\Socrates\Tests;

class NationalIdValidationRuleTest extends TestCase
{
    /** @test */
    public function a_valid_id_and_a_supported_country_passes():void
    {
        $data = [
            'id' => '93.05.18-223.61',
            'country' => 'BE'
        ];

        $rules = [
            'id' => 'national_id:' . $data['country']
        ];

        $validator = $this->app['validator']->make($data, $rules);
        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function an_invalid_id_and_a_supported_country_fails():void
    {
        $data = [
            'id' => '123',
            'country' => 'BE'
        ];

        $rules = [
            'id' => 'national_id:' . $data['country']
        ];

        $validator = $this->app['validator']->make($data, $rules);
        $this->assertTrue($validator->fails());
    }
}