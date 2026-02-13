<?php

declare(strict_types=1);

namespace Tests\Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Tooling\Concerns\GetsFixtures;
use PHPUnit\Framework\Attributes\CoversClass;
use Tooling\Rector\Rules\Provides\ParsesNodes;
use Tooling\Rector\Rules\Provides\ValidatesInheritance;
use Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers\AuthorizersAreFinal;

#[CoversClass(AuthorizersAreFinal::class)]
final class AuthorizersAreFinalTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ValidatesInheritance;

    #[Test]
    public function it_can_make_an_authorizer_final(): void
    {
        $rule = app(AuthorizersAreFinal::class);

        $classNode = $this->getClassNode($this->getFixturePath('AuthorizerNotFinal.php'));

        $this->assertFalse($classNode->isFinal());

        $result = $rule->refactor($classNode);

        $this->assertTrue($result->isFinal());
    }

    #[Test]
    public function it_does_not_make_an_authorizer_final_if_it_is_already_final(): void
    {
        $rule = app(AuthorizersAreFinal::class);

        $classNode = $this->getClassNode($this->getFixturePath('ValidAuthorizer.php'));

        $this->assertTrue($classNode->isFinal());

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }
}