<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules\Validators;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Tooling\Concerns\GetsFixtures;
use Tests\TestCase;
use Tooling\Rector\Rules\Provides\ValidatesMethods;
use Tooling\Rector\Testing\ParsesNodes;
use Tooling\Rector\Testing\ResolvesRectorRules;

#[CoversClass(MustNotHaveAuthorizeMethod::class)]
final class MustNotHaveAuthorizeMethodTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ResolvesRectorRules;
    use ValidatesMethods;

    #[Test]
    public function it_can_remove_an_authorize_method_from_a_validator(): void
    {
        $rule = $this->resolveRule(MustNotHaveAuthorizeMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('ValidatorWithAuthorizeMethod.php'));

        $this->assertTrue($this->hasMethod($classNode, 'authorize'));

        $result = $rule->refactor($classNode);

        $this->assertFalse($this->hasMethod($result, 'authorize'));
    }

    #[Test]
    public function it_does_not_remove_an_authorize_method_from_a_non_validator_class(): void
    {
        $rule = $this->resolveRule(MustNotHaveAuthorizeMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('RegularClass.php'));

        $this->assertTrue($this->hasMethod($classNode, 'authorize'));

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }
}
