<?php

namespace Reducktion\Socrates\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Reducktion\Socrates\Laravel\Facades\Socrates;
use Reducktion\Socrates\Laravel\SocratesServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            SocratesServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Socrates' => Socrates::class
        ];
    }
}