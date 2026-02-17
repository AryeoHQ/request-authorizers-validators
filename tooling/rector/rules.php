<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules;

use Tooling\LaravelAuthorizerValidator\Rector\Rules\Validators\ValidatorsAreFinal;
use Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers\AuthorizersAreFinal;
use Tooling\LaravelAuthorizerValidator\Rector\Rules\Validators\ValidatorsHaveRulesMethod;
use Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers\AuthorizersHaveAuthorizeMethod;
use Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers\AuthorizersDoNotHaveRulesMethod;
use Tooling\LaravelAuthorizerValidator\Rector\Rules\Validators\ValidatorsDoNotHaveAuthorizeMethod;

return [
    AuthorizersAreFinal::class,
    AuthorizersDoNotHaveRulesMethod::class,
    AuthorizersHaveAuthorizeMethod::class,
    ValidatorsAreFinal::class,
    ValidatorsDoNotHaveAuthorizeMethod::class,
    ValidatorsHaveRulesMethod::class,
];