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
use Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers\AuthorizersHaveAuthorizeMethod;

#[CoversClass(AuthorizersHaveAuthorizeMethod::class)]
final class AuthorizersHaveAuthorizeMethodTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ValidatesInheritance;
    use ValidatesMethods;

    #[Test]
    public function it_can_add_an_authorize_method_to_an_authorizer(): void
    {
        $rule = app(AuthorizersHaveAuthorizeMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('AuthorizerNoAuthorizeMethod.php'));

        $this->assertFalse($this->hasMethod($classNode, 'authorize'));

        $result = $rule->refactor($classNode);

        $this->assertTrue($this->hasMethod($result, 'authorize'));
    }

    #[Test]
    public function it_does_not_add_an_authorize_method_to_an_authorizer_that_already_has_it(): void
    {
        $rule = app(AuthorizersHaveAuthorizeMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('ValidAuthorizer.php'));

        $this->assertTrue($this->hasMethod($classNode, 'authorize'));

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }

    #[Test]
    public function it_does_not_add_an_authorize_method_to_a_non_authorizer_class(): void
    {
        $rule = app(AuthorizersHaveAuthorizeMethod::class);

        $classNode = $this->getClassNode($this->getFixturePath('RegularClass.php'));

        $this->assertTrue($this->hasMethod($classNode, 'authorize'));

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }
}