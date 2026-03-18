<?php

declare(strict_types=1);

namespace Support\Http\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Stringable;
use Support\Http\Commands\References\Authorizer;
use Symfony\Component\Console\Input\InputOption;
use Tooling\GeneratorCommands\Concerns\CreatesColocatedTests;
use Tooling\GeneratorCommands\Concerns\GeneratorCommandCompatibility;
use Tooling\GeneratorCommands\Concerns\RetrievesNamespace;
use Tooling\GeneratorCommands\Contracts\GeneratesFile;
use Tooling\GeneratorCommands\References\Contracts\Reference;

class MakeAuthorizer extends GeneratorCommand implements GeneratesFile
{
    use CreatesColocatedTests;
    use GeneratorCommandCompatibility;
    use RetrievesNamespace;

    protected $name = 'make:authorizer';

    protected $description = 'Create a new authorizer class';

    protected $type = 'Authorizer';

    public string $stub {
        get => __DIR__.'/../stubs/authorizer.stub';
    }

    public Stringable $nameInput {
        get => $this->nameInput ??= str($this->argument('name'));
    }

    public Reference $reference {
        get => $this->reference ??= resolve(Authorizer::class, [
            'name' => $this->nameInput,
            'baseNamespace' => $this->baseNamespace,
        ]);
    }

    public function handle(): null|bool
    {
        $this->resolveNamespace();

        return parent::handle();
    }

    protected function getOptions(): array
    {
        return [
            new InputOption('force', 'f', InputOption::VALUE_NONE, 'Create the class even if it already exists'),
            ...$this->getNamespaceInputOptions(),
        ];
    }
}
