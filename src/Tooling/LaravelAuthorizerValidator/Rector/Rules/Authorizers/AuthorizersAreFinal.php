<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Support\Http\Authorizer;
use Tooling\Rector\Rules\Definitions\Attributes\Definition;
use Tooling\Rector\Rules\Provides\ValidatesInheritance;
use Tooling\Rector\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[Definition('Add final modifier to authorizer classes')]
#[NodeType(Class_::class)]
final class AuthorizersAreFinal extends Rule
{
    use ValidatesInheritance;

    public function shouldHandle(Node $node): bool
    {
        return $this->inheritsDirectly($node, Authorizer::class) && ! $node->isFinal();
    }

    public function handle(Node $node): Node
    {
        $node->flags |= Modifiers::FINAL;

        return $node;
    }
}
