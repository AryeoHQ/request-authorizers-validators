<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling;

use Support\Http\Authorizer as BaseAuthorizer;

final class AuthorizerNoAuthorizeMethod extends BaseAuthorizer
{
    public function rules(): array
    {
        return [];
    }
}
