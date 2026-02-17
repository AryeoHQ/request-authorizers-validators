<?php

declare(strict_types=1);

namespace Tests\Tooling\Rector\Rules\Validators;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tests\Tooling\Concerns\GetsFixtures;
use PHPUnit\Framework\Attributes\CoversClass;
use Tooling\Rector\Testing\ParsesNodes;
use Tooling\Rector\Testing\ResolvesRectorRules;
use Tooling\LaravelAuthorizerValidator\Rector\Rules\Validators\ValidatorsAreFinal;

#[CoversClass(ValidatorsAreFinal::class)]
final class ValidatorsAreFinalTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ResolvesRectorRules;

    #[Test]
    public function it_can_make_a_validator_final(): void
    {
        $rule = $this->resolveRule(ValidatorsAreFinal::class);

        $classNode = $this->getClassNode($this->getFixturePath('ValidatorNotFinal.php'));

        $this->assertFalse($classNode->isFinal());

        $result = $rule->refactor($classNode);

        $this->assertTrue($result->isFinal());
    }

    #[Test]
    public function it_does_not_make_a_validator_final_if_it_is_already_final(): void
    {
        $rule = $this->resolveRule(ValidatorsAreFinal::class);

        $classNode = $this->getClassNode($this->getFixturePath('ValidValidator.php'));

        $this->assertTrue($classNode->isFinal());

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }
}