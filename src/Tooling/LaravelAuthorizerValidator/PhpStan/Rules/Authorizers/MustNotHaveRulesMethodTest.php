<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Authorizers;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Tooling\Concerns\GetsFixtures;

/** @extends RuleTestCase<MustNotHaveRulesMethod> */
#[CoversClass(MustNotHaveRulesMethod::class)]
final class MustNotHaveRulesMethodTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new MustNotHaveRulesMethod;
    }

    #[Test]
    public function it_fails_on_authorizer_class_with_rules_method(): void
    {
        $this->analyse([$this->getFixturePath('AuthorizerWithRulesMethod.php')], [
            [
                'Authorizers must not have a rules method.',
                9,
            ],
        ]);
    }

    #[Test]
    public function it_passes_on_authorizer_class_without_rules_method(): void
    {
        $this->analyse([$this->getFixturePath('ValidAuthorizer.php')], []);
    }

    #[Test]
    public function it_ignores_non_authorizer_classes(): void
    {
        $this->analyse([$this->getFixturePath('RegularClass.php')], []);
    }
}
