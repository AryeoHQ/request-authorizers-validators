<?php

declare(strict_types=1);

namespace Tooling\Concerns;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Support\Http\Authorizer;
use Support\Http\Validator;

trait ValidatesNodes
{
    public function isAuthorizer(Class_ $node): bool
    {
        return $node->extends?->toString() === Authorizer::class || in_array($node->extends?->toString(), ['Authorizer', 'BaseAuthorizer'], true);
    }

    public function isValidator(Class_ $node): bool
    {
        return $node->extends?->toString() === Validator::class || in_array($node->extends?->toString(), ['Validator', 'BaseValidator'], true);
    }

    public function hasMethod(Class_ $node, string $methodName): bool
    {
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Node\Stmt\ClassMethod && $stmt->name->toString() === $methodName) {
                return true;
            }
        }

        return false;
    }
}
