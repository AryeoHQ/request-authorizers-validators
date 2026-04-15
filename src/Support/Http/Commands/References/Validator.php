<?php

declare(strict_types=1);

namespace Support\Http\Commands\References;

use Illuminate\Support\Stringable;
use Tooling\GeneratorCommands\References\GenericClass;

final class Validator extends GenericClass
{
    public Stringable $stubPath {
        get => str(__DIR__.'/stubs/validator.stub');
    }
}
