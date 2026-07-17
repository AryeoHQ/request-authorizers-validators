<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules\Controllers;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Tooling\Concerns\GetsFixtures;
use Tests\TestCase;
use Tooling\Rector\Testing\ParsesNodes;
use Tooling\Rector\Testing\ResolvesRectorRules;

#[CoversClass(AuthorizersMustPrecedeValidators::class)]
final class AuthorizersMustPrecedeValidatorsTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ResolvesRectorRules;

    #[Test]
    public function it_moves_authorizer_parameters_before_validator_parameters(): void
    {
        $rule = $this->resolveRule(AuthorizersMustPrecedeValidators::class);

        $method = $this->getInvokeMethod('ControllerDependencies/ReversedController.php');

        $this->assertSame(['validator', 'model', 'authorizer'], $this->parameterNames($method));

        $result = $rule->refactor($method);

        $this->assertInstanceOf(ClassMethod::class, $result);
        $this->assertSame(['authorizer', 'validator', 'model'], $this->parameterNames($result));
    }

    #[Test]
    public function it_does_not_change_correctly_ordered_parameters(): void
    {
        $rule = $this->resolveRule(AuthorizersMustPrecedeValidators::class);

        $method = $this->getInvokeMethod('ControllerDependencies/CorrectController.php');

        $this->assertSame(['authorizer', 'validator', 'model'], $this->parameterNames($method));

        $result = $rule->refactor($method);

        $this->assertNull($result);
    }

    #[Test]
    public function it_ignores_invokable_controllers_without_both_parameter_types(): void
    {
        $rule = $this->resolveRule(AuthorizersMustPrecedeValidators::class);

        $method = $this->getInvokeMethod('ControllerDependencies/ValidatorOnlyController.php');

        $result = $rule->refactor($method);

        $this->assertNull($result);
    }

    #[Test]
    public function it_ignores_non_invokable_controller_methods(): void
    {
        $rule = $this->resolveRule(AuthorizersMustPrecedeValidators::class);

        $classNode = $this->getClassNode($this->getFixturePath('ControllerDependencies/NonInvokableController.php'));
        $method = $classNode->getMethod('handle');

        $this->assertInstanceOf(ClassMethod::class, $method);

        $result = $rule->refactor($method);

        $this->assertNull($result);
    }

    private function getInvokeMethod(string $fixture): ClassMethod
    {
        $classNode = $this->getClassNode($this->getFixturePath($fixture));
        $method = $classNode->getMethod('__invoke');

        $this->assertInstanceOf(ClassMethod::class, $method);

        return $method;
    }

    /**
     * @return array<int, string>
     */
    private function parameterNames(ClassMethod $method): array
    {
        return array_map(
            fn (Param $param): string => $param->var instanceof Variable && is_string($param->var->name)
                ? $param->var->name
                : '',
            $method->params
        );
    }
}
