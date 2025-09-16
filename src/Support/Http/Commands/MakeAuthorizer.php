<?php

declare(strict_types=1);

namespace Support\Http\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeAuthorizer extends GeneratorCommand
{
    protected $name = 'make:authorizer';

    protected $description = 'Create a new authorizer class';

    protected $type = 'Authorizer';

    protected function getStub(): string
    {
        return __DIR__.'/../stubs/authorizer.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Http';
    }
}
