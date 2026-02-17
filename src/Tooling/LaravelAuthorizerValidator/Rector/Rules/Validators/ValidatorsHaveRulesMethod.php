<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules\Validators;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Support\Http\Validator;
use Tooling\Rector\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
final class ValidatorsHaveRulesMethod extends Rule
{
    public function shouldHandle(Node $node): bool
    {
        return $this->inherits($node, Validator::class)
            && ! $this->hasMethod($node, 'rules');
    }

    public function handle(Node $node): Node
    {
        $node->stmts[] = $this->ensureMethodIsDefined($node, 'rules', 'array');

        return $node;
    }
}
