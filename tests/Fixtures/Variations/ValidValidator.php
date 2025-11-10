<?php

declare(strict_types=1);

namespace Tests\Fixtures\Variations;

use Support\Http\Validator as BaseValidator;

final class ValidValidator extends BaseValidator
{
    public function rules(): array
    {
        return [];
    }
}
