<?php

declare(strict_types=1);

namespace Tooling\Rector\Rules;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Tooling\Concerns\ValidatesNodes;

class FixAuthorizorsAndValidators extends AbstractRector
{
    use ValidatesNodes;

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof Class_) {
            return null;
        }

        // Only process classes that extend Authorizer or Validator
        if (! $this->isAuthorizer($node) && ! $this->isValidator($node)) {
            return null;
        }

        $hasChanges = false;

        // Rule 1: If is authorizer, ensure authorize method is defined and rules is not defined
        if ($this->isAuthorizer($node)
            && (! $this->hasMethod($node, 'authorize') || $this->hasMethod($node, 'rules'))
        ) {
            $node = $this->fixAuthorizer($node);
            $hasChanges = true;
        }

        // Rule 2: If is validator, ensure rules method is defined and authorize is not defined
        if ($this->isValidator($node)
            && (! $this->hasMethod($node, 'rules') || $this->hasMethod($node, 'authorize'))
        ) {
            $node = $this->fixValidator($node);
            $hasChanges = true;
        }

        // Rule 3: If class implements Action or uses AsAction, ensure it's final
        if (! $node->isFinal()) {
            $node->flags |= Modifiers::FINAL;
            $hasChanges = true;
        }

        return $hasChanges ? $node : null;
    }

    private function fixAuthorizer(Class_ $node): Class_
    {
        if (! $this->hasMethod($node, 'authorize')) {
            $node->stmts[] = $this->createAuthorizeMethod();
        }

        if ($this->hasMethod($node, 'rules')) {
            $this->removeMethod($node, 'rules');
        }

        return $node;
    }

    private function fixValidator(Class_ $node): Class_
    {
        if (! $this->hasMethod($node, 'rules')) {
            $node->stmts[] = $this->createRulesMethod();
        }

        if ($this->hasMethod($node, 'authorize')) {
            $this->removeMethod($node, 'authorize');
        }

        return $node;
    }

    private function removeMethod(Class_ $node, string $methodName): void
    {
        $node->stmts = array_filter($node->stmts, function ($stmt) use ($methodName) {
            if ($stmt instanceof Node\Stmt\ClassMethod && $stmt->name->toString() === $methodName) {
                return false;
            }

            return true;
        });
    }

    private function createAuthorizeMethod(): Node\Stmt\ClassMethod
    {
        return new Node\Stmt\ClassMethod('authorize', [
            'flags' => Modifiers::PUBLIC,
            'returnType' => new Node\Identifier('bool'),
            'stmts' => [
                new Node\Stmt\Return_(
                    new Node\Expr\ConstFetch(new Node\Name('true'))
                ),
            ],
        ]);
    }

    private function createRulesMethod(): Node\Stmt\ClassMethod
    {
        return new Node\Stmt\ClassMethod('rules', [
            'flags' => Modifiers::PUBLIC,
            'returnType' => new Node\Identifier('array'),
            'stmts' => [
                new Node\Stmt\Return_(
                    new Node\Expr\Array_
                ),
            ],
        ]);
    }
}
