<?php

declare(strict_types=1);

namespace Support\Http;

use Illuminate\Foundation\Http\FormRequest;

abstract class Validator extends FormRequest
{
    /**
     * @return true
     */
    final public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    abstract public function rules(): array;
}
