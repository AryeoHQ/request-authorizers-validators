<?php

declare(strict_types=1);

namespace Tests\Tooling\PhpStan\Rules\Validators;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Tooling\Concerns\GetsFixtures;
use PHPUnit\Framework\Attributes\CoversClass;
use Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Validators\ValidatorsMustNotHaveAuthorizeMethod;
use PHPStan\Reflection\ReflectionProvider;

#[CoversClass(ValidatorsMustNotHaveAuthorizeMethod::class)]
final class ValidatorMustNotHaveAuthorizeMethodTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new ValidatorsMustNotHaveAuthorizeMethod(
            self::getContainer()->getByType(ReflectionProvider::class)
        );
    }

    #[Test]
    public function it_fails_on_validator_class_with_authorize_method(): void
    {
        $this->analyse([$this->getFixturePath('ValidatorWithAuthorizeMethod.php')], [
            [
                'Validators must not have an authorize method.',
                9,
            ],
        ]);
    }

    #[Test]
    public function it_passes_on_validator_class_without_authorize_method(): void
    {
        $this->analyse([$this->getFixturePath('ValidValidator.php')], []);
    }

    #[Test]
    public function it_ignores_non_validator_classes(): void
    {
        $this->analyse([$this->getFixturePath('RegularClass.php')], []);
    }
}