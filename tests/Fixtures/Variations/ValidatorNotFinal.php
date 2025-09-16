<?php

declare(strict_types=1);

namespace Tests\Fixtures\Variations;

use Support\Http\Validator as BaseValidator;

class ValidatorNotFinal extends BaseValidator
{
    public function rules(): array
    {
        return [];
    }
}
