<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Authorizers;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use Support\Http\Authorizer;
use Tooling\LaravelAuthorizerValidator\Concerns\ValidatesMethods;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
class MustHaveAuthorizeMethod extends Rule
{
    use ValidatesMethods;

    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inheritsDirectly($node, Authorizer::class);
    }

    public function handle(Node $node, Scope $scope): void
    {
        if (! $this->hasMethod($node, 'authorize')) {
            $this->error(
                message: 'Authorizers must have an authorize method.',
                line: $node->name->getStartLine(),
                identifier: 'authorizer.authorize.method'
            );
        }
    }
}
