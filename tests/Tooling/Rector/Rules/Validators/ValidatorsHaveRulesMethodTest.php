<?php

declare(strict_types=1);

namespace Tests\Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Tooling\Concerns\GetsFixtures;
use PHPUnit\Framework\Attributes\CoversClass;
use Tooling\Rector\Rules\Provides\ParsesNodes;
use Tooling\Rector\Rules\Provides\ValidatesInheritance;
use Tooling\LaravelAuthorizerValidator\Concerns\ValidatesMethods;
use Tooling\LaravelAuthorizerValidator\Rector\Rules\Validators\ValidatorsHaveRulesMethod;
use Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers\AuthorizersHaveAuthorizeMethod;

#[CoversClass(ValidatorsHaveRulesMethod::class)]
final class ValidatorsHaveRulesMethodTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ValidatesInheritance;
    use ValidatesMethods;

    #[Test]
    public function it_can_add_an_rules_method_to_a_validator(): void
    {
        $rule = app(ValidatorsHaveRulesMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('ValidatorNoRulesMethod.php'));

        $this->assertFalse($this->hasMethod($classNode, 'rules'));

        $result = $rule->refactor($classNode);

        $this->assertTrue($this->hasMethod($result, 'rules'));
    }

    #[Test]
    public function it_does_not_add_an_rules_method_to_a_validator_that_already_has_it(): void
    {
        $rule = app(ValidatorsHaveRulesMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('ValidValidator.php'));

        $this->assertTrue($this->hasMethod($classNode, 'rules'));

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }

    #[Test]
    public function it_does_not_add_an_rules_method_to_a_non_validator_class(): void
    {
        $rule = app(ValidatorsHaveRulesMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('RegularClass.php'));

        $this->assertTrue($this->hasMethod($classNode, 'rules'));

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }
}