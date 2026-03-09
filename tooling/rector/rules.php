<?php

declare(strict_types=1);

namespace Tooling\LaravelAuthorizerValidator\Rector\Rules;

use Tooling\LaravelAuthorizerValidator\Rector\Rules\Authorizers;
use Tooling\LaravelAuthorizerValidator\Rector\Rules\Validators;

return [
    Authorizers\MustBeFinal::class,
    Authorizers\MustHaveAuthorizeMethod::class,
    Authorizers\MustNotHaveRulesMethod::class,
    Validators\MustBeFinal::class,
    Validators\MustHaveRulesMethod::class,
    Validators\MustNotHaveAuthorizeMethod::class,
];
