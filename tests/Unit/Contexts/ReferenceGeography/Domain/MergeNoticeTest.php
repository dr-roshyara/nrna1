<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\RosterEntryKind;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-3b (standalone completion §5 IG-4/IG-5; v3.11 §8 "Identity merges"; D-225; completion §5a):
 * - Geography records Identity's merge and reversal notices as roster writes; it never decides sameness itself;
 * - idempotent by (mergeId, kind): a replay writes nothing and takes no version;
 * - E(v) = transitive closure of the links of merges recorded ≤ v whose reversal is not recorded ≤ v;
 *   order-insensitive (a reversal arriving first neutralises its merge); a reversal drops only its own merge;
 * - unknown references are recorded anyway;
 * - distinct references mean "not known to be the same person", never "proven different".
 */
final class MergeNoticeTest extends TestCase
{
    private static function p(string $c): PersonReference
    {
        return PersonReference::fromString(str_repeat($c, 32));
    }

    public function test_a_merge_is_a_versioned_roster_write_and_a_replay_writes_nothing(): void
    {
        $roster = StewardRoster::empty();

        $this->assertSame(1, $roster->recordMerge('m-1', [self::p('a'), self::p('b')]));
        $this->assertNull($roster->recordMerge('m-1', [self::p('a'), self::p('b')]), 'a duplicate notice');
        $this->assertSame(1, $roster->version(), 'no new version');
        $this->assertSame(RosterEntryKind::PersonsMerged, $roster->entries()[0]->kind);
        $this->assertTrue($roster->knownSameAt(self::p('a'), self::p('b'), 1));
        $this->assertFalse($roster->knownSameAt(self::p('a'), self::p('b'), 0), 'before the notice: not known to be the same');
    }

    public function test_equivalence_is_transitive_across_overlapping_merges_and_a_reversal_drops_only_its_merge(): void
    {
        $roster = StewardRoster::empty();
        $roster->recordMerge('m-1', [self::p('a'), self::p('b')]);   // v1
        $roster->recordMerge('m-2', [self::p('b'), self::p('c')]);   // v2
        $roster->recordReversal('m-1');                              // v3

        $this->assertTrue($roster->knownSameAt(self::p('a'), self::p('c'), 2), '{a, b, c}');
        $this->assertFalse($roster->knownSameAt(self::p('a'), self::p('b'), 3), 'a is separate again');
        $this->assertTrue($roster->knownSameAt(self::p('b'), self::p('c'), 3), 'm-2 untouched');
        $this->assertTrue($roster->knownSameAt(self::p('a'), self::p('b'), 2), 'history at v2 is not rewritten');
    }

    public function test_a_reversal_arriving_before_its_merge_neutralises_it(): void
    {
        $roster = StewardRoster::empty();

        $this->assertSame(1, $roster->recordReversal('m-9'));
        $this->assertSame(2, $roster->recordMerge('m-9', [self::p('a'), self::p('b')]));
        $this->assertFalse($roster->knownSameAt(self::p('a'), self::p('b'), 2));
        $this->assertNull($roster->recordReversal('m-9'), 'a duplicate reversal writes nothing');
    }

    public function test_unknown_references_are_recorded_and_a_merge_never_widens_authority(): void
    {
        $roster = StewardRoster::empty();
        $roster->appointSteward(self::p('a'), self::p('0'));            // v1
        $roster->recordMerge('m-1', [self::p('a'), self::p('e')]);    // v2: e has never appeared

        $this->assertTrue($roster->knownSameAt(self::p('e'), self::p('a'), 2));
        $this->assertFalse($roster->isStewardAt(self::p('e'), 2), 'authority follows the appointed reference only (fail closed)');
    }

    public function test_a_merge_needs_two_distinct_references(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        StewardRoster::empty()->recordMerge('m-1', [self::p('a'), self::p('a')]);
    }
}
