<?php

declare(strict_types=1);

namespace Tests\Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Authorizers;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Tooling\Concerns\GetsFixtures;
use PHPUnit\Framework\Attributes\CoversClass;
use Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Authorizers\MustHaveAuthorizeMethod;

#[CoversClass(MustHaveAuthorizeMethod::class)]
final class MustHaveAuthorizeMethodTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new MustHaveAuthorizeMethod;
    }

    #[Test]
    public function it_fails_on_authorizer_class_without_authorize_method(): void
    {
        $this->analyse([$this->getFixturePath('AuthorizerNoAuthorizeMethod.php')], [
            [
                'Authorizers must have an authorize method.',
                9,
            ],
        ]);
    }

    #[Test]
    public function it_passes_on_authorizer_class_with_authorize_method(): void
    {
        $this->analyse([$this->getFixturePath('ValidAuthorizer.php')], []);
    }

    #[Test]
    public function it_ignores_non_authorizer_classes(): void
    {
        $this->analyse([$this->getFixturePath('RegularClass.php')], []);
    }
}