<?php

namespace Tests;

use Orchestra\Testbench;
use Support\Http\Providers\AuthorizerValidatorServiceProvider;

abstract class TestCase extends Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            AuthorizerValidatorServiceProvider::class,
        ];
    }
}
