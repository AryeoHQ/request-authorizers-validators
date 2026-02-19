<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules\Validators;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Support\Http\Validator;
use Tooling\Rector\Rules\Definitions\Attributes\Definition;
use Tooling\Rector\Rules\Rule;
use Tooling\Rector\Rules\Samples\Attributes\Sample;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[Definition('Add rules method to validator classes')]
#[NodeType(Class_::class)]
#[Sample('request-authorizers-validators.rector.rules.samples')]
final class ValidatorsHaveRulesMethod extends Rule
{
    public function shouldHandle(Node $node): bool
    {
        return $this->inherits($node, Validator::class)
            && ! $this->hasMethod($node, 'rules');
    }

    public function handle(Node $node): Node
    {
        $node->stmts[] = $this->addMethod($node, 'rules', 'array');

        return $node;
    }
}
