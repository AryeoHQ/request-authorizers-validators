<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling;

use Support\Http\Authorizer as BaseAuthorizer;

final class AuthorizerNoRulesMethod extends BaseAuthorizer
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
