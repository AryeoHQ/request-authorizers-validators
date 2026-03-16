<?php

declare(strict_types=1);

namespace Support\Http\Commands;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Http\Commands\References\Authorizer;
use Tests\TestCase;
use Tooling\GeneratorCommands\Testing\Concerns\CleansUpGeneratorCommands;
use Tooling\GeneratorCommands\Testing\Concerns\GeneratesFileTestCases;
use Tooling\GeneratorCommands\Testing\Concerns\RetrievesNamespaceTestCases;

#[CoversClass(MakeAuthorizer::class)]
final class MakeAuthorizerTest extends TestCase
{
    use CleansUpGeneratorCommands;
    use GeneratesFileTestCases;
    use RetrievesNamespaceTestCases;

    public Authorizer $reference {
        get => new Authorizer(name: 'TestAuthorizer', baseNamespace: 'Workbench\\App');
    }

    /** @var array<string, mixed> */
    public array $baselineInput {
        get => ['name' => 'TestAuthorizer', '--namespace' => 'Workbench\\App\\'];
    }

    /** @var array<string, mixed> */
    public array $withNamespaceBackslashInput {
        get => $this->baselineInput;
    }

    /** @var array<string, mixed> */
    public array $withoutNamespaceBackslashInput {
        get => ['name' => 'TestAuthorizer', '--namespace' => 'Workbench\\App'];
    }

    #[Test]
    public function it_can_make_an_authorizer(): void
    {
        $this->artisan($this->command, $this->baselineInput);

        $this->assertFileExists($this->expectedFilePath, 'The authorizer was not created');
        $authorizerClass = file_get_contents($this->expectedFilePath);
        $this->assertStringContainsString('final class TestAuthorizer extends BaseAuthorizer', $authorizerClass, 'The authorizer does not define the class as final');
        $this->assertStringContainsString('use Support\Http\Authorizer as BaseAuthorizer;', $authorizerClass, 'The authorizer does not import the BaseAuthorizer');
        $this->assertStringContainsString('extends BaseAuthorizer', $authorizerClass, 'The authorizer does not extend the BaseAuthorizer');
    }
}
