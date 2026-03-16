<?php

declare(strict_types=1);

namespace Support\Http\Commands\References;

use Illuminate\Support\Stringable;
use Tooling\GeneratorCommands\References\Reference;
use Tooling\GeneratorCommands\References\TestClass;

final class Validator extends Reference
{
    public null|Stringable $subNamespace = null;

    public TestClass $test {
        get => new TestClass(
            name: $this->name->append('Test'),
            baseNamespace: $this->namespace,
        );
    }
}
