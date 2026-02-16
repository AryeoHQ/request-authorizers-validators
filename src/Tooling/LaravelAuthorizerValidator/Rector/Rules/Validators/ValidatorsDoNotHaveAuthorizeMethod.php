<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules\Validators;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Support\Http\Validator;
use Tooling\LaravelAuthorizerValidator\Concerns\ValidatesMethods;
use Tooling\Rector\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
final class ValidatorsDoNotHaveAuthorizeMethod extends Rule
{
    use ValidatesMethods;

    public function shouldHandle(Node $node): bool
    {
        return $this->inherits($node, Validator::class)
            && $this->hasMethod($node, 'authorize');
    }

    public function handle(Node $node): Node
    {
        $this->removeMethod($node, 'authorize');

        return $node;
    }
}
