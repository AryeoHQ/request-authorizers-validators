<?php

declare(strict_types=1);

namespace Tests\Fixtures\Variations;

use Support\Http\Authorizer as BaseAuthorizer;

class AuthorizerNotFinal extends BaseAuthorizer
{
    public function authorize(): bool
    {
        return true;
    }
}
