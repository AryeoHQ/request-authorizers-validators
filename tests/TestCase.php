<?php

namespace Tests;

use Orchestra\Testbench;
use Support\Http\Providers\AuthorizerValidatorServiceProvider;

abstract class TestCase extends Testbench\TestCase
{
    /** @var \Illuminate\Testing\TestResponse|null */
    public static $latestResponse = null;

    protected function getPackageProviders($app): array
    {
        return [
            AuthorizerValidatorServiceProvider::class,
        ];
    }
}
