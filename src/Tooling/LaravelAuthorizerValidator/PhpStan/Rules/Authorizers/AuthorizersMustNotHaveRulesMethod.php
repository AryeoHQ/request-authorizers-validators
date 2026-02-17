<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Authorizers;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use Support\Http\Authorizer;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
class AuthorizersMustNotHaveRulesMethod extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inherits($node, Authorizer::class)
            && ! $node->isAbstract()
            && $this->hasMethod($node, 'rules');
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: 'Authorizers must not have a rules method.',
            line: $node->name->getStartLine(),
            identifier: 'authorizer.rules.method'
        );
    }
}
