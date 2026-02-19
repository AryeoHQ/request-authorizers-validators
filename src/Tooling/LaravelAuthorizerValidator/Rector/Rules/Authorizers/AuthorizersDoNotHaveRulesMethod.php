<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers;

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
#[Definition('Remove rules method from authorizer classes')]
#[NodeType(Class_::class)]
#[Sample('request-authorizers-validators.rector.rules.samples')]
final class AuthorizersDoNotHaveRulesMethod extends Rule
{
    public function shouldHandle(Node $node): bool
    {
        return $this->inherits($node, Authorizer::class)
            && $this->hasMethod($node, 'rules');
    }

    public function handle(Node $node): Node
    {
        $this->removeMethod($node, 'rules');

        return $node;
    }
}
