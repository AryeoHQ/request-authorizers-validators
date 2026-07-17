<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\ControllerDependencies;

final class ValidatorOnlyController
{
    public function __invoke(Validator $validator, \stdClass $model): void
    {
    }
}
