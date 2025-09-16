<?php

namespace Tests\Tooling\PHPStan\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tooling\PHPStan\Rules\AuthorizerValidatorRule;

#[CoversClass(AuthorizerValidatorRule::class)]
final class AuthorizerValidatorRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new AuthorizerValidatorRule;
    }

    private function getFixturePath(string $filename): string
    {
        return __DIR__.'/../../../Fixtures/Variations/'.$filename;
    }

    public function test_it_passes_valid_authorizer_class(): void
    {
        $this->analyse([$this->getFixturePath('ValidAuthorizer.php')], []);
    }

    public function test_it_passes_valid_validator_class(): void
    {
        $this->analyse([$this->getFixturePath('ValidValidator.php')], []);
    }

    public function test_it_fails_on_authorizer_without_authorize_method(): void
    {
        $this->analyse([$this->getFixturePath('AuthorizerNoAuthorizeMethod.php')], [
            [
                'Authorizers must have an authorize method.',
                9,
            ],
            [
                'Authorizers must not have a rules method.',
                9,
            ],
        ]);
    }

    public function test_it_fails_on_validator_without_rules_method(): void
    {
        $this->analyse([$this->getFixturePath('ValidatorNoRulesMethod.php')], [
            [
                'Validators must have a rules method.',
                9,
            ],
            [
                'Validators must not have an authorize method.',
                9,
            ],
        ]);
    }

    public function test_it_fails_on_authorizer_class_not_final(): void
    {
        $this->analyse([$this->getFixturePath('AuthorizerNotFinal.php')], [
            [
                'Authorizers and validators must be final.',
                9,
            ],
        ]);
    }

    public function test_it_fails_on_validator_class_not_final(): void
    {
        $this->analyse([$this->getFixturePath('ValidatorNotFinal.php')], [
            [
                'Authorizers and validators must be final.',
                9,
            ],
        ]);
    }
}
