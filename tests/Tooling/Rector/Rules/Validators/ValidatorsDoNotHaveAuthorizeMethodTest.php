<?php

declare(strict_types=1);

namespace Tests\Tooling\LaravelAuthorizerValidator\Rector\Rules\Validators;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Tooling\Concerns\GetsFixtures;
use PHPUnit\Framework\Attributes\CoversClass;
use Tooling\Rector\Rules\Provides\ParsesNodes;
use Tooling\Rector\Rules\Provides\ValidatesInheritance;
use Tooling\LaravelAuthorizerValidator\Concerns\ValidatesMethods;
use Tooling\LaravelAuthorizerValidator\Rector\Rules\Validators\ValidatorsDoNotHaveAuthorizeMethod;

#[CoversClass(ValidatorsDoNotHaveAuthorizeMethod::class)]
final class ValidatorsDoNotHaveAuthorizeMethodTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ValidatesInheritance;
    use ValidatesMethods;

    #[Test]
    public function it_can_remove_an_authorize_method_from_a_validator(): void
    {
        $rule = app(ValidatorsDoNotHaveAuthorizeMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('ValidatorWithAuthorizeMethod.php'));

        $this->assertTrue($this->hasMethod($classNode, 'authorize'));

        $result = $rule->refactor($classNode);

        $this->assertFalse($this->hasMethod($result, 'authorize'));
    }

    #[Test]
    public function it_does_not_remove_an_authorize_method_from_a_non_validator_class(): void
    {
        $rule = app(ValidatorsDoNotHaveAuthorizeMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('RegularClass.php'));
        
        $this->assertTrue($this->hasMethod($classNode, 'authorize'));

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }
}