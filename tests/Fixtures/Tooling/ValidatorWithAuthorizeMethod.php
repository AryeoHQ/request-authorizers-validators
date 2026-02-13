<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling;

use Support\Http\Validator as BaseValidator;

final class ValidValidator extends BaseValidator
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
