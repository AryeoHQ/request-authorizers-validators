<?php

namespace Tests\Support\Http\Commands;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Console\GeneratorCommand;
use Support\Http\Commands\MakeAuthorizer;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MakeAuthorizer::class)]
final class MakeAuthorizerTest extends TestCase
{
    #[Test]
    public function command_is_instance_of_generator_command(): void
    {
        $this->assertInstanceOf(GeneratorCommand::class, app(MakeAuthorizer::class));
    }

    #[Test]
    public function it_can_make_an_authorizer(): void
    {
        $this->artisan(MakeAuthorizer::class, ['name' => 'TestAuthorizer']);

        $authorizerClass = file_get_contents(app_path('Http/TestAuthorizer.php'));
        $this->assertFileExists(app_path('Http/TestAuthorizer.php'), 'The authorizer was not created');
        $this->assertStringContainsString('final class TestAuthorizer extends BaseAuthorizer', $authorizerClass, 'The authorizer does not define the class as final');
        $this->assertStringContainsString('use Support\Http\Authorizer as BaseAuthorizer;', $authorizerClass, 'The authorizer does not import the BaseAuthorizer');
        $this->assertStringContainsString('extends BaseAuthorizer', $authorizerClass, 'The authorizer does not extend the BaseAuthorizer');
    }
}
