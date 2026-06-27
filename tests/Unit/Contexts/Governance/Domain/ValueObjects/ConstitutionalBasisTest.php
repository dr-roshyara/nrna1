<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\ValueObjects;

use App\Contexts\Governance\Domain\ValueObjects\ConstitutionalBasis;
use DomainException;
use PHPUnit\Framework\TestCase;

final class ConstitutionalBasisTest extends TestCase
{
    public function test_it_creates_constitutional_basis_with_article(): void
    {
        $basis = ConstitutionalBasis::from(
            article: 'Article 42.3',
            referenceText: null
        );

        $this->assertSame('Article 42.3', $basis->article());
        $this->assertNull($basis->referenceText());
    }

    public function test_it_creates_with_optional_reference_text(): void
    {
        $basis = ConstitutionalBasis::from(
            article: 'Article 15.2',
            referenceText: 'Governance authority delegation'
        );

        $this->assertSame('Article 15.2', $basis->article());
        $this->assertSame('Governance authority delegation', $basis->referenceText());
    }

    public function test_article_cannot_be_empty(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Article reference cannot be empty');

        ConstitutionalBasis::from(article: '', referenceText: null);
    }

    public function test_article_cannot_be_whitespace_only(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Article reference cannot be empty');

        ConstitutionalBasis::from(article: '   ', referenceText: null);
    }

    public function test_it_has_private_constructor(): void
    {
        $reflection = new \ReflectionClass(ConstitutionalBasis::class);
        $constructor = $reflection->getConstructor();

        $this->assertTrue($constructor->isPrivate(),
            'ConstitutionalBasis must have private constructor - use from() factory'
        );
    }

    public function test_it_is_immutable_readonly(): void
    {
        $basis = ConstitutionalBasis::from(
            article: 'Article 1',
            referenceText: 'Reference context'
        );

        $reflection = new \ReflectionClass($basis);

        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue(
                $property->isReadOnly(),
                sprintf('Property %s must be readonly', $property->getName())
            );
        }
    }

    public function test_value_equality_with_same_values(): void
    {
        $a = ConstitutionalBasis::from(
            article: 'Article 42',
            referenceText: 'Governance framework'
        );

        $b = ConstitutionalBasis::from(
            article: 'Article 42',
            referenceText: 'Governance framework'
        );

        $this->assertTrue($a->equals($b));
    }

    public function test_value_inequality_different_article(): void
    {
        $a = ConstitutionalBasis::from(
            article: 'Article 42',
            referenceText: 'Same text'
        );

        $b = ConstitutionalBasis::from(
            article: 'Article 43',
            referenceText: 'Same text'
        );

        $this->assertFalse($a->equals($b));
    }

    public function test_value_inequality_different_reference_text(): void
    {
        $a = ConstitutionalBasis::from(
            article: 'Article 42',
            referenceText: 'Text A'
        );

        $b = ConstitutionalBasis::from(
            article: 'Article 42',
            referenceText: 'Text B'
        );

        $this->assertFalse($a->equals($b));
    }

    public function test_value_inequality_one_with_text_one_without(): void
    {
        $a = ConstitutionalBasis::from(
            article: 'Article 42',
            referenceText: 'Some text'
        );

        $b = ConstitutionalBasis::from(
            article: 'Article 42',
            referenceText: null
        );

        $this->assertFalse($a->equals($b));
    }

    public function test_is_semantic_citation_only_no_logic_methods(): void
    {
        $reflection = new \ReflectionClass(ConstitutionalBasis::class);
        $publicMethods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => !$m->isStatic() && $m->getName() !== '__construct'
        );

        $allowedMethods = ['article', 'referenceText', 'equals'];
        foreach ($publicMethods as $method) {
            $this->assertContains(
                $method->getName(),
                $allowedMethods,
                sprintf('Method %s not allowed - ConstitutionalBasis is citation-only VO', $method->getName())
            );
        }
    }
}
