<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\ControllerDependencies;

use Support\Http\Validator as BaseValidator;

final class Validator extends BaseValidator
{
    public function rules(): array
    {
        return [];
    }
}
