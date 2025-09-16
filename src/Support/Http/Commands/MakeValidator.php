<?php

declare(strict_types=1);

namespace Support\Http\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeValidator extends GeneratorCommand
{
    protected $name = 'make:validator';

    protected $description = 'Create a new validator class';

    protected $type = 'Validator';

    public function handle(): ?bool
    {
        parent::handle();

        if ($this->option('a') !== false) {
            $this->call(MakeAuthorizer::class, [
                'name' => $this->getNameInput(),
            ]);
        }

        return null;
    }

    protected function getStub(): string
    {
        return __DIR__.'/../stubs/validator.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Http';
    }

    /**
     * @return array<mixed>
     */
    protected function getOptions(): array
    {
        return array_merge(parent::getOptions(), [
            ['a', null, InputOption::VALUE_NONE, 'Create a corresponding authorizer class'],
        ]);
    }
}
