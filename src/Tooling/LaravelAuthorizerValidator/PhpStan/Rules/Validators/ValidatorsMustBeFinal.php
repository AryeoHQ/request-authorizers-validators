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
class ValidatorsMustBeFinal extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inherits($node, Validator::class)
            && ! $node->isFinal()
            && ! $node->isAbstract();
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: 'Validators must be final.',
            line: $node->name->getStartLine(),
            identifier: 'validator.final'
        );
    }
}
