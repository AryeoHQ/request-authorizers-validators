<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Authorizers;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use Support\Http\Authorizer;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
class AuthorizersMustBeFinal extends Rule
{
    public function __construct(
        public ReflectionProvider $reflectionProvider,
    ) {}

    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inherits($node, Authorizer::class, $this->reflectionProvider)
            && ! $node->isFinal()
            && ! $node->isAbstract();
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: 'Authorizers must be final.',
            line: $node->name->getStartLine(),
            identifier: 'authorizer.final'
        );
    }
}
