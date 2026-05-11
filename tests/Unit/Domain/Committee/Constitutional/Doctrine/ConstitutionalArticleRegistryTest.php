<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Doctrine;

use App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine\ConstitutionalArticleReference;
use App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine\ConstitutionalArticleReferenceCollection;
use PHPUnit\Framework\TestCase;

class ConstitutionalArticleRegistryTest extends TestCase
{
    /**
     * @test
     * Article reference holds id and title
     */
    public function test_article_reference_holds_id_and_title(): void
    {
        $ref = ConstitutionalArticleReference::fromCode('art.1');

        $this->assertSame('art.1', $ref->articleId);
    }

    /**
     * @test
     * Collection add is immutable
     */
    public function test_collection_add_is_immutable(): void
    {
        $ref = ConstitutionalArticleReference::fromCode('art.1');
        $collection = new ConstitutionalArticleReferenceCollection();

        $newCollection = $collection->add($ref);

        $this->assertNotSame($collection, $newCollection);
    }

    /**
     * @test
     * Collection filters by scope
     */
    public function test_collection_filters_by_scope(): void
    {
        $ref1 = new ConstitutionalArticleReference(articleId: 'art.1', title: 'National provision', scopeContext: 'national');
        $ref2 = new ConstitutionalArticleReference(articleId: 'art.2', title: 'Regional provision', scopeContext: 'regional');

        $collection = new ConstitutionalArticleReferenceCollection();
        $collection = $collection->add($ref1);
        $collection = $collection->add($ref2);

        $nationalOnly = $collection->filterByScope('national');

        $this->assertSame(1, $nationalOnly->count());
    }

    /**
     * @test
     * Collection to article codes returns string array
     */
    public function test_collection_to_article_codes_returns_string_array(): void
    {
        $ref1 = ConstitutionalArticleReference::fromCode('art.1');
        $ref2 = ConstitutionalArticleReference::fromCode('art.2');

        $collection = new ConstitutionalArticleReferenceCollection();
        $collection = $collection->add($ref1);
        $collection = $collection->add($ref2);

        $codes = $collection->toArticleCodes();

        $this->assertContains('art.1', $codes);
        $this->assertContains('art.2', $codes);
    }
}
