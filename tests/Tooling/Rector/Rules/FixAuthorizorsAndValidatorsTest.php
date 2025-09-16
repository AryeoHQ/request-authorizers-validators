<?php

namespace Tests\Tooling\Rector\Rules;

use PhpParser\Node\Stmt\Class_;
use PhpParser\ParserFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;
use Tooling\Rector\Rules\FixAuthorizorsAndValidators;

#[CoversClass(FixAuthorizorsAndValidators::class)]
final class FixAuthorizorsAndValidatorsTest extends TestCase
{
    private FixAuthorizorsAndValidators $rule;

    private $parser;

    protected function setUp(): void
    {
        $this->rule = app(FixAuthorizorsAndValidators::class);
        $this->parser = (new ParserFactory)->createForNewestSupportedVersion();
    }

    private function getFixture(string $filename): string
    {
        return file_get_contents(__DIR__.'/../../../Fixtures/Variations/'.$filename);
    }

    public function test_it_can_fix_an_authorizer(): void
    {
        $code = $this->getFixture('AuthorizerNoAuthorizeMethod.php');

        $nodes = $this->parser->parse($code);
        $classNode = $this->getClassNode($nodes);

        $this->assertNotNull($classNode, 'Should find a class node');

        $result = $this->rule->refactor($classNode);

        $this->assertNotNull($result, 'Should return a class node');

        $this->assertInstanceOf(Class_::class, $result);
        $this->assertTrue($this->rule->hasMethod($result, 'authorize'));
        $this->assertFalse($this->rule->hasMethod($result, 'rules'));
    }

    public function test_it_can_fix_a_validator(): void
    {
        $code = $this->getFixture('ValidatorNoRulesMethod.php');

        $nodes = $this->parser->parse($code);
        $classNode = $this->getClassNode($nodes);

        $this->assertNotNull($classNode, 'Should find a class node');

        $result = $this->rule->refactor($classNode);

        $this->assertNotNull($result, 'Should return a class node');

        $this->assertInstanceOf(Class_::class, $result);
        $this->assertTrue($this->rule->hasMethod($result, 'rules'));
        $this->assertFalse($this->rule->hasMethod($result, 'authorize'));
    }

    public function test_it_makes_validator_class_final(): void
    {
        $code = $this->getFixture('ValidatorNotFinal.php');

        $nodes = $this->parser->parse($code);
        $classNode = $this->getClassNode($nodes);
        $this->assertNotNull($classNode, 'Should find a class node');

        $result = $this->rule->refactor($classNode);

        $this->assertNotNull($result, 'Should return a class node');

        $this->assertInstanceOf(Class_::class, $result);
        $this->assertTrue($result->isFinal());
    }

    public function test_it_makes_authorizer_class_final(): void
    {
        $code = $this->getFixture('AuthorizerNotFinal.php');

        $nodes = $this->parser->parse($code);
        $classNode = $this->getClassNode($nodes);

        $this->assertNotNull($classNode, 'Should find a class node');

        $result = $this->rule->refactor($classNode);

        $this->assertNotNull($result, 'Should return a class node');

        $this->assertInstanceOf(Class_::class, $result);
        $this->assertTrue($result->isFinal());
    }

    private function getClassNode(array $nodes): ?Class_
    {
        foreach ($nodes as $node) {
            if ($node instanceof \PhpParser\Node\Stmt\Namespace_) {
                foreach ($node->stmts as $stmt) {
                    if ($stmt instanceof Class_) {
                        return $stmt;
                    }
                }
            }
        }

        return null;
    }
}
