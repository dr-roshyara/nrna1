<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Semantics;

use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCategory;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCode;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticCollection;
use App\Contexts\Membership\Domain\Committee\Constitutional\Semantics\GovernanceSemanticDefinition;
use PHPUnit\Framework\TestCase;

class GovernanceSemanticCollectionTest extends TestCase
{
    /**
     * @test
     * Collection can be constructed from array of definitions
     */
    public function test_collection_constructed_from_array(): void
    {
        $def1 = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_1'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test meaning 1',
            replayImplications: 'Test replay 1',
            legitimacyImplications: 'Test legitimacy 1',
            introducedInDoctrineVersion: '1.0',
        );

        $collection = new GovernanceSemanticCollection($def1);

        $this->assertCount(1, $collection);
    }

    /**
     * @test
     * Collection add method returns new instance (immutable)
     */
    public function test_collection_add_returns_new_instance(): void
    {
        $def1 = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_1'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test meaning 1',
            replayImplications: 'Test replay 1',
            legitimacyImplications: 'Test legitimacy 1',
            introducedInDoctrineVersion: '1.0',
        );

        $def2 = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_2'),
            category: GovernanceSemanticCategory::LEGITIMACY,
            canonicalMeaning: 'Test meaning 2',
            replayImplications: 'Test replay 2',
            legitimacyImplications: 'Test legitimacy 2',
            introducedInDoctrineVersion: '1.0',
        );

        $collection1 = new GovernanceSemanticCollection($def1);
        $collection2 = $collection1->add($def2);

        $this->assertNotSame($collection1, $collection2);
        $this->assertCount(1, $collection1);
        $this->assertCount(2, $collection2);
    }

    /**
     * @test
     * Collection prevents duplicate semantic codes
     */
    public function test_collection_prevents_duplicate_codes(): void
    {
        $def1 = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('duplicate_code'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test meaning 1',
            replayImplications: 'Test replay 1',
            legitimacyImplications: 'Test legitimacy 1',
            introducedInDoctrineVersion: '1.0',
        );

        $def2 = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('duplicate_code'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test meaning 2',
            replayImplications: 'Test replay 2',
            legitimacyImplications: 'Test legitimacy 2',
            introducedInDoctrineVersion: '1.0',
        );

        $collection = new GovernanceSemanticCollection($def1);

        $this->expectException(\InvalidArgumentException::class);
        $collection->add($def2);
    }

    /**
     * @test
     * Collection retrieves definition by code
     */
    public function test_collection_retrieves_by_code(): void
    {
        $code = GovernanceSemanticCode::fromString('test_code');
        $def = new GovernanceSemanticDefinition(
            code: $code,
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test meaning',
            replayImplications: 'Test replay',
            legitimacyImplications: 'Test legitimacy',
            introducedInDoctrineVersion: '1.0',
        );

        $collection = new GovernanceSemanticCollection($def);
        $retrieved = $collection->get($code);

        $this->assertNotNull($retrieved);
        $this->assertTrue($retrieved->code->equals($code));
    }

    /**
     * @test
     * Collection returns null for unknown code
     */
    public function test_collection_returns_null_for_unknown_code(): void
    {
        $def = new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_code'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test meaning',
            replayImplications: 'Test replay',
            legitimacyImplications: 'Test legitimacy',
            introducedInDoctrineVersion: '1.0',
        );

        $collection = new GovernanceSemanticCollection($def);
        $unknown = GovernanceSemanticCode::fromString('unknown_code');
        $retrieved = $collection->get($unknown);

        $this->assertNull($retrieved);
    }

    /**
     * @test
     * Collection returns all definitions in deterministic order
     */
    public function test_collection_all_returns_deterministic_order(): void
    {
        $collection = new GovernanceSemanticCollection();

        // Add in non-alphabetical order
        $collection = $collection->add(new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('zebra'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test',
            replayImplications: 'Test',
            legitimacyImplications: 'Test',
            introducedInDoctrineVersion: '1.0',
        ));

        $collection = $collection->add(new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('alpha'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test',
            replayImplications: 'Test',
            legitimacyImplications: 'Test',
            introducedInDoctrineVersion: '1.0',
        ));

        $collection = $collection->add(new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('middle'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test',
            replayImplications: 'Test',
            legitimacyImplications: 'Test',
            introducedInDoctrineVersion: '1.0',
        ));

        $all = $collection->all();
        $codes = array_map(fn ($def) => $def->code->toString(), $all);

        // Should be alphabetically sorted
        $this->assertSame(['alpha', 'middle', 'zebra'], $codes);
    }

    /**
     * @test
     * Collection canonical serialization is deterministic
     */
    public function test_collection_canonical_serialization_is_deterministic(): void
    {
        $collection = new GovernanceSemanticCollection();

        $collection = $collection->add(new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_1'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test meaning 1',
            replayImplications: 'Test replay 1',
            legitimacyImplications: 'Test legitimacy 1',
            introducedInDoctrineVersion: '1.0',
        ));

        $collection = $collection->add(new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_2'),
            category: GovernanceSemanticCategory::LEGITIMACY,
            canonicalMeaning: 'Test meaning 2',
            replayImplications: 'Test replay 2',
            legitimacyImplications: 'Test legitimacy 2',
            introducedInDoctrineVersion: '1.0',
        ));

        $serialization1 = $collection->canonicalSerialization();
        $serialization2 = $collection->canonicalSerialization();

        $this->assertSame($serialization1, $serialization2);
    }

    /**
     * @test
     * Collection serialization produces valid JSON
     */
    public function test_collection_serialization_produces_valid_json(): void
    {
        $collection = new GovernanceSemanticCollection();

        $collection = $collection->add(new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_code'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test meaning',
            replayImplications: 'Test replay',
            legitimacyImplications: 'Test legitimacy',
            introducedInDoctrineVersion: '1.0',
        ));

        $serialization = $collection->canonicalSerialization();

        // Should be valid JSON
        $decoded = json_decode($serialization, true);
        $this->assertIsArray($decoded);
        $this->assertCount(1, $decoded);
    }

    /**
     * @test
     * Empty collection is valid
     */
    public function test_empty_collection_is_valid(): void
    {
        $collection = new GovernanceSemanticCollection();

        $this->assertCount(0, $collection);
        $this->assertIsArray($collection->all());
        $this->assertEmpty($collection->all());
    }

    /**
     * @test
     * Collection count is accurate
     */
    public function test_collection_count_is_accurate(): void
    {
        $collection = new GovernanceSemanticCollection();

        $this->assertCount(0, $collection);

        $collection = $collection->add(new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_1'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test',
            replayImplications: 'Test',
            legitimacyImplications: 'Test',
            introducedInDoctrineVersion: '1.0',
        ));

        $this->assertCount(1, $collection);

        $collection = $collection->add(new GovernanceSemanticDefinition(
            code: GovernanceSemanticCode::fromString('test_2'),
            category: GovernanceSemanticCategory::AUTHORITY,
            canonicalMeaning: 'Test',
            replayImplications: 'Test',
            legitimacyImplications: 'Test',
            introducedInDoctrineVersion: '1.0',
        ));

        $this->assertCount(2, $collection);
    }
}
