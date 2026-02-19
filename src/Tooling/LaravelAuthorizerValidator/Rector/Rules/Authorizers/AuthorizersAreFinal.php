<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Support\Http\Authorizer;
use Tooling\Rector\Rules\Definitions\Attributes\Definition;
use Tooling\Rector\Rules\Rule;
use Tooling\Rector\Rules\Samples\Attributes\Sample;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[Definition('Add final modifier to authorizer classes')]
#[NodeType(Class_::class)]
#[Sample('request-authorizers-validators.rector.rules.samples')]
final class AuthorizersAreFinal extends Rule
{
    public function shouldHandle(Node $node): bool
    {
        return $this->inherits($node, Authorizer::class)
            && ! $node->isFinal()
            && ! $node->isAbstract();
    }

    public function handle(Node $node): Node
    {
        $node->flags |= Modifiers::FINAL;

        return $node;
    }
}
