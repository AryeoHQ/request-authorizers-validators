<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Authorizers;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use Support\Http\Authorizer;
use Tooling\LaravelAuthorizerValidator\Concerns\ValidatesMethods;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
class AuthorizersMustHaveAuthorizeMethod extends Rule
{
    use ValidatesMethods;

    public function __construct(
        public ReflectionProvider $reflectionProvider,
    ) {}

    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inherits($node, Authorizer::class, $this->reflectionProvider)
            && ! $this->hasMethod($node, 'authorize');
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: 'Authorizers must have an authorize method.',
            line: $node->name->getStartLine(),
            identifier: 'authorizer.authorize.method'
        );
    }
}
