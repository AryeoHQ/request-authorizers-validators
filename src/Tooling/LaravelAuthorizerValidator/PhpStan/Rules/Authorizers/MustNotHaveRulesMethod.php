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
class MustNotHaveRulesMethod extends Rule
{
    use ValidatesMethods;

    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inheritsDirectly($node, Authorizer::class);
    }

    public function handle(Node $node, Scope $scope): void
    {
        if ($this->hasMethod($node, 'rules')) {
            $this->error(
                message: 'Authorizers must not have a rules method.',
                line: $node->name->getStartLine(),
                identifier: 'authorizer.rules.method'
            );
        }
    }
}
