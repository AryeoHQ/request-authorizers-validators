<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Validators;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Tooling\Concerns\GetsFixtures;

/** @extends RuleTestCase<MustNotHaveAuthorizeMethod> */
#[CoversClass(MustNotHaveAuthorizeMethod::class)]
final class MustNotHaveAuthorizeMethodTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new MustNotHaveAuthorizeMethod;
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
