<?php

declare(strict_types=1);

namespace Tests\Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Validators;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Tooling\Concerns\GetsFixtures;
use PHPUnit\Framework\Attributes\CoversClass;
use Tooling\LaravelAuthorizerValidator\PhpStan\Rules\Validators\MustBeFinal;

#[CoversClass(MustBeFinal::class)]
final class MustBeFinalTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new MustBeFinal;
    }

    #[Test]
    public function it_fails_on_validator_class_not_final(): void
    {
        $this->analyse([$this->getFixturePath('ValidatorNotFinal.php')], [
            [
                'Validators must be final.',
                9,
            ],
        ]);
    }

    #[Test]
    public function it_passes_on_validator_class_final(): void
    {
        $this->analyse([$this->getFixturePath('ValidValidator.php')], []);
    }

    #[Test]
    public function it_ignores_non_validator_classes(): void
    {
        $this->analyse([$this->getFixturePath('RegularClass.php')], []);
    }
}