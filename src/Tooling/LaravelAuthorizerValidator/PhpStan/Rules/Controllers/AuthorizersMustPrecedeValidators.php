<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Controllers;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use Support\Http\Authorizer;
use Support\Http\Validator;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<ClassMethod>
 */
#[NodeType(ClassMethod::class)]
class AuthorizersMustPrecedeValidators extends Rule
{
    private const AUTHORIZER = 'authorizer';

    private const VALIDATOR = 'validator';

    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $node->name->toString() === '__invoke'
            && $this->violatingAuthorizer($node, $scope) instanceof Param;
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: 'Authorizer parameters must be declared before validator parameters.',
            line: $this->violatingAuthorizer($node, $scope)?->getStartLine() ?? $node->getStartLine(),
            identifier: 'controller.authorizer.validator.order'
        );
    }

    private function violatingAuthorizer(ClassMethod $method, Scope $scope): ?Param
    {
        $validatorSeen = false;

        foreach ($method->params as $param) {
            $type = $this->requestParameterType($param, $scope);

            if ($type === self::VALIDATOR) {
                $validatorSeen = true;
            }

            if ($type === self::AUTHORIZER && $validatorSeen) {
                return $param;
            }
        }

        return null;
    }

    private function requestParameterType(Param $param, Scope $scope): ?string
    {
        $type = $this->namedType($param->type);

        if (! $type instanceof Name) {
            return null;
        }

        $className = $scope->resolveName($type);

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
        $className = ltrim($className, '\\');
        $expected = ltrim($expected, '\\');

        if (strcasecmp($className, $expected) === 0) {
            return true;
        }

        $reflection = (new ObjectType($className))->getClassReflection();

        return $reflection instanceof ClassReflection && $this->inherits($reflection, $expected);
    }
}
