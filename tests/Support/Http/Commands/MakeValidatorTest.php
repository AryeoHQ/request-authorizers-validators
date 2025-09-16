<?php

namespace Tests\Support\Http\Commands;

use Illuminate\Console\GeneratorCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use Support\Http\Commands\MakeValidator;
use Tests\TestCase;

#[CoversClass(MakeValidator::class)]
class MakeValidatorTest extends TestCase
{
    public function test_command_is_instance_of_generator_command(): void
    {
        $this->assertInstanceOf(GeneratorCommand::class, app(MakeValidator::class));
    }

    public function test_it_can_make_a_validator(): void
    {
        $this->artisan(MakeValidator::class, ['name' => 'TestValidator']);

        $validatorClass = file_get_contents(app_path('Http/TestValidator.php'));
        $this->assertFileExists(app_path('Http/TestValidator.php'), 'The validator was not created');
        $this->assertStringContainsString('final class TestValidator extends BaseValidator', $validatorClass, 'The validator does not define the class as final');
        $this->assertStringContainsString('use Support\Http\Validator as BaseValidator;', $validatorClass, 'The validator does not import the BaseValidator');
        $this->assertStringContainsString('extends BaseValidator', $validatorClass, 'The validator does not extend the BaseValidator');
    }

    public function test_it_can_make_an_authorizer_via_option(): void
    {
        $this->artisan(MakeValidator::class, ['name' => 'TestValidator', '--a' => true]);

        $authorizerClass = file_get_contents(app_path('Http/TestAuthorizer.php'));
        $this->assertFileExists(app_path('Http/TestAuthorizer.php'), 'The authorizer was not created');
    }
}
