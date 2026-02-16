<?php

declare(strict_types=1);

namespace Support\Http;

use Illuminate\Foundation\Http\FormRequest;

abstract class Authorizer extends FormRequest
{
    abstract public function authorize(): bool;
}
