<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Validators;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use Support\Http\Validator;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
class ValidatorsMustHaveRulesMethod extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inherits($node, Validator::class)
            && ! $this->hasMethod($node, 'rules');
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: 'Validators must have a rules method.',
            line: $node->name->getStartLine(),
            identifier: 'validator.rules.method'
        );
    }
}
