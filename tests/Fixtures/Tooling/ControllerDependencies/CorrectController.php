<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\ControllerDependencies;

final class CorrectController
{
    public function __invoke(Authorizer $authorizer, Validator $validator, \stdClass $model): void
    {
    }
}
