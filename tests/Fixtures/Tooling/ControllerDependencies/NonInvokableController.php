<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\ControllerDependencies;

final class NonInvokableController
{
    public function handle(Validator $validator, Authorizer $authorizer): void
    {
    }
}
