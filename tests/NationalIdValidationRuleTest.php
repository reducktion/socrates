<?php

namespace Reducktion\Socrates\Tests;

use Illuminate\Validation\ValidationException;

class NationalIdValidationRuleTest extends TestCase
{
    /** @test */
    public function it_passes_if_the_user_passes_a_valid_id_and_a_supported_country(): void
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
    public function it_throws_an_exception_if_the_user_passes_an_invalid_id_and_a_supported_country(): void
    {
        $data = [
            'id' => '123',
            'country' => 'BE'
        ];

        $rules = [
            'id' => 'national_id:' . $data['country']
        ];

        $this->expectException(ValidationException::class);
        $this->app['validator']->make($data, $rules)->validate();
    }

    /** @test */
    public function it_throws_an_exception_if_the_user_passes_an_unsupported_country(): void
    {
        $this->withoutExceptionHandling();

        $data = [
            'id' => '123',
            'country' => 'BB'
        ];

        $rules = [
            'id' => 'national_id:' . $data['country']
        ];

        $this->expectException(ValidationException::class);
        $this->app['validator']->make($data, $rules)->validate();
    }
}
