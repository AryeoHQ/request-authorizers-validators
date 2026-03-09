<?php

declare(strict_types=1);

namespace Support\Http\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Stringable;
use Support\Http\Commands\References\Validator;
use Symfony\Component\Console\Input\InputOption;
use Tooling\GeneratorCommands\Concerns\GeneratorCommandCompatibility;
use Tooling\GeneratorCommands\Concerns\RetrievesNamespace;
use Tooling\GeneratorCommands\Contracts\GeneratesFile;
use Tooling\GeneratorCommands\References\Contracts\Reference;

class MakeValidator extends GeneratorCommand implements GeneratesFile
{
    use GeneratorCommandCompatibility;
    use RetrievesNamespace;

    protected $name = 'make:validator';

    protected $description = 'Create a new validator class';

    protected $type = 'Validator';

    public string $stub {
        get => __DIR__.'/../stubs/validator.stub';
    }

    public Stringable $nameInput {
        get => $this->nameInput ??= str($this->argument('name'));
    }

    public Reference $reference {
        get => $this->reference ??= new Validator(
            name: $this->nameInput,
            baseNamespace: $this->baseNamespace,
        );
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
