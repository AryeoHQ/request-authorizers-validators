<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Controllers;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Tooling\Concerns\GetsFixtures;

/** @extends RuleTestCase<AuthorizersMustPrecedeValidators> */
#[CoversClass(AuthorizersMustPrecedeValidators::class)]
final class AuthorizersMustPrecedeValidatorsTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new AuthorizersMustPrecedeValidators;
    }

    #[Test]
    public function it_fails_when_a_validator_parameter_precedes_an_authorizer_parameter(): void
    {
        $this->analyse([$this->getFixturePath('ControllerDependencies/ReversedController.php')], [
            [
                'Authorizer parameters must be declared before validator parameters.',
                9,
            ],
        ]);
    }

    #[Test]
    public function it_passes_when_authorizer_parameters_precede_validator_parameters(): void
    {
        $this->analyse([$this->getFixturePath('ControllerDependencies/CorrectController.php')], []);
    }

    #[Test]
    public function it_ignores_invokable_controllers_without_both_parameter_types(): void
    {
        $this->analyse([$this->getFixturePath('ControllerDependencies/ValidatorOnlyController.php')], []);
    }

    #[Test]
    public function it_ignores_non_invokable_controller_methods(): void
    {
        $this->analyse([$this->getFixturePath('ControllerDependencies/NonInvokableController.php')], []);
    }
}
