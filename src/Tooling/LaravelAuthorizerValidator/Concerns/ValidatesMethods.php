<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Concerns;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;

trait ValidatesMethods
{
    public function hasMethod(Class_ $node, string $methodName): bool
    {
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Node\Stmt\ClassMethod && $stmt->name->toString() === $methodName) {
                return true;
            }
        }

        return false;
    }

    public function createMethod(string $methodName, string $returnType, int $visibility = Modifiers::PUBLIC): Node\Stmt\ClassMethod
    {
        $node = match ($returnType) {
            'bool' => new Node\Stmt\Return_(
                new Node\Expr\ConstFetch(new Node\Name('true'))
            ),
            'array' => new Node\Stmt\Return_(
                new Node\Expr\Array_
            ),
            default => throw new \InvalidArgumentException('Invalid return type'),
        };

        return new Node\Stmt\ClassMethod($methodName, [
            'flags' => $visibility,
            'returnType' => new Node\Identifier($returnType),
            'stmts' => [$node],
        ]);
    }

    public function removeMethod(Class_ $node, string $methodName): void
    {
        $node->stmts = array_values(array_filter($node->stmts, function ($stmt) use ($methodName) {
            if ($stmt instanceof Node\Stmt\ClassMethod && $stmt->name->toString() === $methodName) {
                return false;
            }

            return true;
        }));
    }
}
