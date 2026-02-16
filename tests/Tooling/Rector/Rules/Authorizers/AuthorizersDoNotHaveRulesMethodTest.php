<?php

declare(strict_types=1);

namespace Tests\Tooling\Rector\Rules\Authorizers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tooling\Rector\Testing\ParsesNodes;
use Tests\Tooling\Concerns\GetsFixtures;
use PHPUnit\Framework\Attributes\CoversClass;
use Tooling\Rector\Testing\ResolvesRectorRules;
use Tooling\LaravelAuthorizerValidator\Concerns\ValidatesMethods;
use Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers\AuthorizersDoNotHaveRulesMethod;

#[CoversClass(AuthorizersDoNotHaveRulesMethod::class)]
final class AuthorizersDoNotHaveRulesMethodTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ValidatesMethods;
    use ResolvesRectorRules;

    #[Test]
    public function it_can_remove_a_rules_method_from_an_authorizer(): void
    {
        $rule = $this->resolveRule(AuthorizersDoNotHaveRulesMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('AuthorizerWithRulesMethod.php'));

        $this->assertTrue($this->hasMethod($classNode, 'rules'));

        $result = $rule->refactor($classNode);

        $this->assertFalse($this->hasMethod($result, 'rules'));
    }

    #[Test]
    public function it_does_not_remove_a_rules_method_from_a_non_authorizer_class(): void
    {
        $rule = $this->resolveRule(AuthorizersDoNotHaveRulesMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('RegularClass.php'));
        
        $this->assertTrue($this->hasMethod($classNode, 'rules'));

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }
}