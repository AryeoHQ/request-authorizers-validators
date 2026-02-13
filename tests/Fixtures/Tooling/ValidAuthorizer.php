<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling;

use Support\Http\Authorizer as BaseAuthorizer;

final class ValidAuthorizer extends BaseAuthorizer
{
    public function authorize(): bool
    {
        return true;
    }
}
