<?php

declare(strict_types=1);

namespace Tooling\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Tooling\Concerns\ValidatesNodes;

/**
 * @implements Rule<Class_>
 */
final class AuthorizerValidatorRule implements Rule
{
    use ValidatesNodes;

    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @param  Class_  $node
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];

        // Only check classes that extend Builder
        if (! $this->isAuthorizer($node) && ! $this->isValidator($node)) {
            return $errors;
        }

        if ($this->isAuthorizer($node) && ! $this->hasMethod($node, 'authorize')) {
            $errors[] = RuleErrorBuilder::message('Authorizers must have an authorize method.')
                ->line($node->getStartLine())
                ->identifier('authorizer.authorize.method')
                ->build();
        }

        if ($this->isAuthorizer($node) && $this->hasMethod($node, 'rules')) {
            $errors[] = RuleErrorBuilder::message('Authorizers must not have a rules method.')
                ->line($node->getStartLine())
                ->identifier('authorizer.rules.method')
                ->build();
        }

        if ($this->isValidator($node) && ! $this->hasMethod($node, 'rules')) {
            $errors[] = RuleErrorBuilder::message('Validators must have a rules method.')
                ->line($node->getStartLine())
                ->identifier('validator.rules.method')
                ->build();
        }

        if ($this->isValidator($node) && $this->hasMethod($node, 'authorize')) {
            $errors[] = RuleErrorBuilder::message('Validators must not have an authorize method.')
                ->line($node->getStartLine())
                ->identifier('validator.authorize.method')
                ->build();
        }

        if ($node->isFinal() === false) {
            $errors[] = RuleErrorBuilder::message('Authorizers and validators must be final.')
                ->line($node->getStartLine())
                ->identifier('authorizer.validator.final')
                ->build();
        }

        return $errors;
    }
}
