<?php

declare(strict_types=1);

namespace Support\Http;

use Illuminate\Foundation\Http\FormRequest;

abstract class Authorizer extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    /**
     * @return array<string, mixed>
     */
    final public function rules(): array
    {
        return [];
    }
}
