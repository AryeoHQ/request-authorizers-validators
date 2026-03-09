<?php

declare(strict_types=1);

namespace Support\Http\Commands;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Http\Commands\References\Validator;
use Tests\TestCase;
use Tooling\GeneratorCommands\Testing\Concerns\CleansUpGeneratorCommands;
use Tooling\GeneratorCommands\Testing\Concerns\GeneratesFileTestCases;
use Tooling\GeneratorCommands\Testing\Concerns\RetrievesNamespaceTestCases;

#[CoversClass(MakeValidator::class)]
final class MakeValidatorTest extends TestCase
{
    use CleansUpGeneratorCommands;
    use GeneratesFileTestCases;
    use RetrievesNamespaceTestCases;

    public Validator $reference {
        get => new Validator(name: 'TestValidator', baseNamespace: 'App');
    }

    /** @var array<string, mixed> */
    public array $baselineInput {
        get => ['name' => 'TestValidator', '--namespace' => 'App\\'];
    }

    /** @var array<string, mixed> */
    public array $withNamespaceBackslashInput {
        get => $this->baselineInput;
    }

    /** @var array<string, mixed> */
    public array $withoutNamespaceBackslashInput {
        get => ['name' => 'TestValidator', '--namespace' => 'App'];
    }

    #[Test]
    public function it_can_make_a_validator(): void
    {
        $this->artisan($this->command, $this->baselineInput);

        $this->assertFileExists($this->expectedFilePath, 'The validator was not created');
        $validatorClass = file_get_contents($this->expectedFilePath);
        $this->assertStringContainsString('final class TestValidator extends BaseValidator', $validatorClass, 'The validator does not define the class as final');
        $this->assertStringContainsString('use Support\Http\Validator as BaseValidator;', $validatorClass, 'The validator does not import the BaseValidator');
        $this->assertStringContainsString('extends BaseValidator', $validatorClass, 'The validator does not extend the BaseValidator');
    }
}
