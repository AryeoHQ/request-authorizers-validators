<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Validators;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use Support\Http\Validator;
use Tooling\LaravelAuthorizerValidator\Concerns\ValidatesMethods;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
class MustNotHaveAuthorizeMethod extends Rule
{
    use ValidatesMethods;

    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inheritsDirectly($node, Validator::class);
    }

    public function handle(Node $node, Scope $scope): void
    {
        if ($this->hasMethod($node, 'authorize')) {
            $this->error(
                message: 'Validators must not have an authorize method.',
                line: $node->name->getStartLine(),
                identifier: 'validator.authorize.method'
            );
        }
    }
}
