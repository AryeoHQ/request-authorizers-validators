<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules\Validators;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Support\Http\Validator;
use Tooling\Rector\Rules\Definitions\Attributes\Definition;
use Tooling\Rector\Rules\Provides\ValidatesInheritance;
use Tooling\Rector\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[Definition('Add final modifier to validator classes')]
#[NodeType(Class_::class)]
final class ValidatorsAreFinal extends Rule
{
    use ValidatesInheritance;

    public function shouldHandle(Node $node): bool
    {
        return $this->inheritsDirectly($node, Validator::class) && ! $node->isFinal();
    }

    public function handle(Node $node): Node
    {
        $node->flags |= Modifiers::FINAL;

        return $node;
    }
}
