<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling;

class RegularClass
{
    public function rules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        return true;
    }
}