<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Support\Http\Authorizer;
use Tooling\LaravelAuthorizerValidator\Concerns\ValidatesMethods;
use Tooling\Rector\Rules\Provides\ValidatesInheritance;
use Tooling\Rector\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
final class AuthorizersDoNotHaveRulesMethod extends Rule
{
    use ValidatesInheritance;
    use ValidatesMethods;

    public function shouldHandle(Node $node): bool
    {
        return $this->inheritsDirectly($node, Authorizer::class)
            && $this->hasMethod($node, 'rules');
    }

    public function handle(Node $node): Node
    {
        $this->removeMethod($node, 'rules');

        return $node;
    }
}
