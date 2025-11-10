<?php

namespace Tests\Support\Http\Commands;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Console\GeneratorCommand;
use Support\Http\Commands\MakeValidator;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(MakeValidator::class)]
final class MakeValidatorTest extends TestCase
{
    #[Test]
    public function command_is_instance_of_generator_command(): void
    {
        $this->assertInstanceOf(GeneratorCommand::class, app(MakeValidator::class));
    }

    #[Test]
    public function it_can_make_a_validator(): void
    {
        $this->artisan(MakeValidator::class, ['name' => 'TestValidator']);

        $validatorClass = file_get_contents(app_path('Http/TestValidator.php'));
        $this->assertFileExists(app_path('Http/TestValidator.php'), 'The validator was not created');
        $this->assertStringContainsString('final class TestValidator extends BaseValidator', $validatorClass, 'The validator does not define the class as final');
        $this->assertStringContainsString('use Support\Http\Validator as BaseValidator;', $validatorClass, 'The validator does not import the BaseValidator');
        $this->assertStringContainsString('extends BaseValidator', $validatorClass, 'The validator does not extend the BaseValidator');
    }
}
