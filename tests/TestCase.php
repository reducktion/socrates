<?php


namespace Reducktion\Socrates\Tests;


use Orchestra\Testbench\TestCase as Orchestra;
use Reducktion\Socrates\SocratesServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
          SocratesServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }

}