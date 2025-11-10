<?php

declare(strict_types=1);

namespace Support\Http\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeValidator extends GeneratorCommand
{
    protected $name = 'make:validator';

    protected $description = 'Create a new validator class';

    protected $type = 'Validator';

    protected function getStub(): string
    {
        return __DIR__.'/../stubs/validator.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Http';
    }
}
