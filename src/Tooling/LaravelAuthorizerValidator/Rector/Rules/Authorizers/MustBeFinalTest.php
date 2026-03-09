<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Tooling\Concerns\GetsFixtures;
use Tests\TestCase;
use Tooling\Rector\Testing\ParsesNodes;
use Tooling\Rector\Testing\ResolvesRectorRules;

#[CoversClass(MustBeFinal::class)]
final class MustBeFinalTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ResolvesRectorRules;

    #[Test]
    public function it_can_make_an_authorizer_final(): void
    {
        $rule = $this->resolveRule(MustBeFinal::class);

        $classNode = $this->getClassNode($this->getFixturePath('AuthorizerNotFinal.php'));

        $this->assertFalse($classNode->isFinal());

        $result = $rule->refactor($classNode);

        $this->assertTrue($result->isFinal());
    }

    #[Test]
    public function it_does_not_make_an_authorizer_final_if_it_is_already_final(): void
    {
        $rule = $this->resolveRule(MustBeFinal::class);

        $classNode = $this->getClassNode($this->getFixturePath('ValidAuthorizer.php'));

        $this->assertTrue($classNode->isFinal());

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }
}
