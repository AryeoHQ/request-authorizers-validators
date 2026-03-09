<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Validators;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Tooling\Concerns\GetsFixtures;

/** @extends RuleTestCase<MustHaveRulesMethod> */
#[CoversClass(MustHaveRulesMethod::class)]
final class MustHaveRulesMethodTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new MustHaveRulesMethod;
    }

    #[Test]
    public function it_fails_on_validator_class_without_rules_method(): void
    {
        $this->analyse([$this->getFixturePath('ValidatorNoRulesMethod.php')], [
            [
                'Validators must have a rules method.',
                9,
            ],
        ]);
    }

    #[Test]
    public function it_passes_on_validator_class_with_rules_method(): void
    {
        $this->analyse([$this->getFixturePath('ValidValidator.php')], []);
    }

    #[Test]
    public function it_ignores_non_validator_classes(): void
    {
        $this->analyse([$this->getFixturePath('RegularClass.php')], []);
    }
}
