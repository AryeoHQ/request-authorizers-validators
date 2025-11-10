<?php

declare(strict_types=1);

namespace Tests\Fixtures\Variations;

use Support\Http\Validator as BaseValidator;

final class ValidatorNoRulesMethod extends BaseValidator
{
    public function authorize(): bool
    {
        return true;
    }
}
