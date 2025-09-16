<?php

declare(strict_types=1);

namespace Support\Http\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeAuthorizer extends GeneratorCommand
{
    protected $name = 'make:authorizer';

    protected $description = 'Create a new authorizer class';

    protected $type = 'Authorizer';

    public function handle(): ?bool
    {
        parent::handle();

        if ($this->option('v') !== false) {
            $this->call(MakeValidator::class, [
                'name' => $this->getNameInput(),
            ]);
        }

        return null;
    }

    protected function getStub(): string
    {
        return __DIR__.'/../stubs/authorizer.stub';
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
            ['v', null, InputOption::VALUE_NONE, 'Create a corresponding validator class'],
        ]);
    }
}
