<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Tooling\Concerns\GetsFixtures;
use Tests\TestCase;
use Tooling\Rector\Rules\Provides\ValidatesMethods;
use Tooling\Rector\Testing\ParsesNodes;
use Tooling\Rector\Testing\ResolvesRectorRules;

#[CoversClass(MustNotHaveRulesMethod::class)]
final class MustNotHaveRulesMethodTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ResolvesRectorRules;
    use ValidatesMethods;

    #[Test]
    public function it_can_remove_a_rules_method_from_an_authorizer(): void
    {
        $rule = $this->resolveRule(MustNotHaveRulesMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('AuthorizerWithRulesMethod.php'));

        $this->assertTrue($this->hasMethod($classNode, 'rules'));

        $result = $rule->refactor($classNode);

        $this->assertFalse($this->hasMethod($result, 'rules'));
    }

    #[Test]
    public function it_does_not_remove_a_rules_method_from_a_non_authorizer_class(): void
    {
        $rule = $this->resolveRule(MustNotHaveRulesMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('RegularClass.php'));

        $this->assertTrue($this->hasMethod($classNode, 'rules'));

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }
}
