<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules\Controllers;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use Support\Http\Authorizer;
use Support\Http\Validator;
use Tooling\Rector\Rules\Definitions\Attributes\Definition;
use Tooling\Rector\Rules\Rule;
use Tooling\Rector\Rules\Samples\Attributes\Sample;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<ClassMethod>
 */
#[Definition('Move authorizer parameters before validator parameters in invokable controllers')]
#[NodeType(ClassMethod::class)]
#[Sample('request-authorizers-validators.rector.rules.samples.controllers')]
final class AuthorizersMustPrecedeValidators extends Rule
{
    private const AUTHORIZER = 'authorizer';

    private const VALIDATOR = 'validator';

    public function shouldHandle(Node $node): bool
    {
        return $node->name->toString() === '__invoke'
            && $this->violatingAuthorizer($node) instanceof Param;
    }

    public function handle(Node $node): Node
    {
        $authorizersToMove = [];
        $validatorSeen = false;

        foreach ($node->params as $index => $param) {
            $type = $this->requestParameterType($param);

            if ($type === self::VALIDATOR) {
                $validatorSeen = true;
            }

            if ($type === self::AUTHORIZER && $validatorSeen) {
                $authorizersToMove[] = $param;
                unset($node->params[$index]);
            }
        }

        $params = array_values($node->params);
        array_splice($params, $this->firstValidatorIndex($params), 0, $authorizersToMove);

        $node->params = $params;

        return $node;
    }

    private function violatingAuthorizer(ClassMethod $method): ?Param
    {
        $validatorSeen = false;

        foreach ($method->params as $param) {
            $type = $this->requestParameterType($param);

            if ($type === self::VALIDATOR) {
                $validatorSeen = true;
            }

            if ($type === self::AUTHORIZER && $validatorSeen) {
                return $param;
            }
        }

        return null;
    }

    /**
     * @param  array<int, Param>  $params
     */
    private function firstValidatorIndex(array $params): int
    {
        foreach ($params as $index => $param) {
            if ($this->requestParameterType($param) === self::VALIDATOR) {
                return $index;
            }
        }

        return 0;
    }

    private function requestParameterType(Param $param): ?string
    {
        $type = $this->namedType($param->type);

        if (! $type instanceof Name) {
            return null;
        }

        $className = $this->getName($type);

        if (! is_string($className)) {
            return null;
        }

        if ($this->isA($className, Authorizer::class)) {
            return self::AUTHORIZER;
        }

        if ($this->isA($className, Validator::class)) {
            return self::VALIDATOR;
        }

        return null;
    }

    private function namedType(?Node $type): ?Name
    {
        if ($type instanceof NullableType) {
            return $this->namedType($type->type);
        }

        return $type instanceof Name ? $type : null;
    }

    private function isA(string $className, string $expected): bool
    {
        return is_a(ltrim($className, '\\'), ltrim($expected, '\\'), true);
    }
}
