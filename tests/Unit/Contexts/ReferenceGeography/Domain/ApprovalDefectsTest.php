<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\ReferenceGeography\Domain;

use App\Contexts\ReferenceGeography\Domain\ApprovalDefects;
use App\Contexts\ReferenceGeography\Domain\FindingLifecycle;
use App\Contexts\ReferenceGeography\Domain\GovernanceNotice;
use App\Contexts\ReferenceGeography\Domain\NoticeKind;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublishedApproval;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-346 G-6c-2 (design revision 3, accepted 2026-11-14: §5, §5.1, §6, §8.4 (a)): which publications a roster
 * change can affect, and the findings and closures it produces. Pure. Tests A1–A6, A8 (property, against a full-scan
 * oracle), B7, B8, B9 of the design.
 */
final class ApprovalDefectsTest extends TestCase
{
    private static function p(string $who): PersonReference
    {
        return PersonReference::fromString(md5('person:' . $who));
    }

    private static function approval(int $position, string $approver, string ...$excluded): PublishedApproval
    {
        return new PublishedApproval($position, RevisionId::fromString(sprintf('00000000-0000-4000-8000-%012d', $position)), 1, str_repeat('d', 64),
            self::p($approver), array_map(static fn (string $e) => self::p($e), $excluded));
    }

    /** @param list<PublishedApproval> $approvals @param array<int, FindingLifecycle> $lifecycles @return list<GovernanceNotice> */
    private static function evaluate(StewardRoster $r, array $approvals, array $lifecycles = [], string $change = 'change'): array
    {
        return ApprovalDefects::evaluate($r, $r->version(), $approvals, $lifecycles, $change);
    }

    /** A lifecycle for one publication with one finding at position $f (and optional resolutions). */
    private static function withFinding(int $publication, int $f, array $resolutions = []): FindingLifecycle
    {
        return FindingLifecycle::of($publication, [[$f, GovernanceNotice::finding($publication, ['m'], 1)], ...$resolutions], []);
    }

    public function test_a1_a_merge_after_publication_raises_a_finding_and_its_reversal_closes_it(): void
    {
        $r = StewardRoster::empty();
        $r->recordMerge('m1', [self::p('X'), self::p('P')]);
        $found = self::evaluate($r, [self::approval(5, 'X', 'P')]);
        $this->assertCount(1, $found);
        $this->assertSame([NoticeKind::Finding, 5, ['m1']], [$found[0]->kind, $found[0]->concerns, $found[0]->mergeBasis]);

        $r->recordReversal('m1');
        $closed = self::evaluate($r, [self::approval(5, 'X', 'P')], [5 => self::withFinding(5, 9)], 'reversal:m1');
        $this->assertSame([NoticeKind::Closure, 9, 'reversal:m1'], [$closed[0]->kind, $closed[0]->concerns, $closed[0]->endedBy]);
    }

    public function test_a2_a_chain_reversal_in_the_middle_reaches_both_ends(): void
    {
        $r = StewardRoster::empty();
        $r->recordMerge('ab', [self::p('A'), self::p('B')]);
        $r->recordMerge('bc', [self::p('B'), self::p('C')]);
        $r->recordMerge('cd', [self::p('C'), self::p('D')]);
        $before = $r->version();
        $this->assertSame(['ab', 'bc', 'cd'], self::evaluate($r, [self::approval(5, 'A', 'D')])[0]->mergeBasis, 'the merges joining the group');

        $r->recordReversal('bc');
        $affected = array_map(static fn ($x) => $x->value(), ApprovalDefects::affectedPersons($r, $before, $r->version(), $r->mergedPersonsOf('bc')));
        $this->assertContains(self::p('A')->value(), $affected, 'A is reached (through B, a person the reversed merge named)');
        $this->assertContains(self::p('D')->value(), $affected);
        $this->assertSame(NoticeKind::Closure, self::evaluate($r, [self::approval(5, 'A', 'D')], [5 => self::withFinding(5, 9)])[0]->kind);
    }

    public function test_a3_a4_two_paths_one_reversed_keeps_the_finding_the_other_closes_it(): void
    {
        $r = StewardRoster::empty();
        $r->recordMerge('ab', [self::p('A'), self::p('B')]);
        $r->recordMerge('bd', [self::p('B'), self::p('D')]);
        $r->recordMerge('ac', [self::p('A'), self::p('C')]);
        $r->recordMerge('cd', [self::p('C'), self::p('D')]);
        $r->recordReversal('bd');
        $this->assertSame([], self::evaluate($r, [self::approval(5, 'A', 'D')], [5 => self::withFinding(5, 9)]), 'still A~D through A-C-D');

        $r->recordReversal('cd');
        $this->assertSame(NoticeKind::Closure, self::evaluate($r, [self::approval(5, 'A', 'D')], [5 => self::withFinding(5, 9)])[0]->kind);
    }

    public function test_a5_a_reversal_before_its_merge_means_the_merge_never_counts(): void
    {
        $r = StewardRoster::empty();
        $r->recordReversal('m1');
        $r->recordMerge('m1', [self::p('X'), self::p('P')]);

        $this->assertSame([], self::evaluate($r, [self::approval(5, 'X', 'P')]));
        $this->assertSame([self::p('X')->value()], array_map(static fn ($x) => $x->value(), $r->componentAt(self::p('X'), $r->version())));
    }

    public function test_a6_one_change_affecting_several_publications_gives_one_notice_each_in_position_order(): void
    {
        $r = StewardRoster::empty();
        $r->recordMerge('m1', [self::p('X'), self::p('P')]);

        $found = self::evaluate($r, [self::approval(8, 'X', 'P'), self::approval(3, 'X', 'Q', 'P')]);
        $this->assertSame([3, 8], array_map(static fn ($n) => $n->concerns, $found));
    }

    public function test_cases_c_and_d_raise_nothing(): void
    {
        $r = StewardRoster::empty();
        $r->recordMerge('m1', [self::p('P1'), self::p('P2')]);   // two excluded persons merged: case D

        $this->assertSame([], self::evaluate($r, [self::approval(5, 'X', 'P1', 'P2')]));
    }

    public function test_b7_no_second_finding_while_one_is_open_and_b8_a_new_episode_after_closure(): void
    {
        $r = StewardRoster::empty();
        $r->recordMerge('m1', [self::p('X'), self::p('P')]);
        $this->assertSame([], self::evaluate($r, [self::approval(5, 'X', 'P')], [5 => self::withFinding(5, 9)]), 'open: no duplicate');

        $closed = self::withFinding(5, 9, [[10, GovernanceNotice::closure(9, 'reversal:m0', 2)]]);
        $this->assertSame(NoticeKind::Finding, self::evaluate($r, [self::approval(5, 'X', 'P')], [5 => $closed])[0]->kind, 'defective again: a new episode');
    }

    public function test_b9_after_a_confirmation_the_confirmer_is_the_counted_approver(): void
    {
        $r = StewardRoster::empty();
        $r->recordMerge('m1', [self::p('X'), self::p('P')]);
        $confirmed = self::withFinding(5, 9, [[10, GovernanceNotice::confirmation(9, self::p('Y'), str_repeat('d', 64), 1)]]);
        $this->assertSame([], self::evaluate($r, [self::approval(5, 'X', 'P')], [5 => $confirmed]), 'X\'s defect is resolved; Y is independent');

        $r->recordMerge('m2', [self::p('Y'), self::p('P')]);   // the confirmer turns out to be P (decision §8.4 (a))
        $again = self::evaluate($r, [self::approval(5, 'X', 'P')], [5 => $confirmed]);
        $this->assertSame([NoticeKind::Finding, 5], [$again[0]->kind, $again[0]->concerns]);
    }

    public function test_a_corrected_publication_is_no_longer_evaluated(): void
    {
        $r = StewardRoster::empty();
        $r->recordMerge('m1', [self::p('X'), self::p('P')]);
        $corrected = FindingLifecycle::of(5, [], [12]);

        $this->assertSame([], self::evaluate($r, [self::approval(5, 'X', 'P')], [5 => $corrected]));
    }

    /**
     * A8b (finding of the G-6c-2 mutation run, mutant D1): when ALL the persons a merge named are used, the components
     * BEFORE the change are contained in the union of those AFTER it (a former path through the merge reaches it at a
     * named person along a prefix that does not use it). The accepted before ∪ after union is therefore exact, and
     * equal to the after-union: this property test pins that equivalence.
     */
    public function test_a8b_before_components_are_contained_in_the_after_components_of_the_named_persons(): void
    {
        mt_srand(20261115);
        $people = ['A', 'B', 'C', 'D', 'E', 'F'];
        for ($round = 0; $round < 300; $round++) {
            $r = StewardRoster::empty();
            $ids = [];
            for ($i = 0; $i < mt_rand(1, 7); $i++) {
                $named = array_unique([$people[mt_rand(0, 5)], $people[mt_rand(0, 5)], $people[mt_rand(0, 5)]]);
                if (count($named) >= 2) {
                    $r->recordMerge("m{$round}_{$i}", array_values(array_map(static fn ($x) => self::p($x), $named)));
                    $ids[] = "m{$round}_{$i}";
                }
            }
            if ($ids === []) {
                continue;
            }
            $id = $ids[mt_rand(0, count($ids) - 1)];
            $before = $r->version();
            $r->recordReversal($id);
            $after = $r->version();
            $afterOnly = [];
            foreach ($r->mergedPersonsOf($id) as $s) {
                foreach ($r->componentAt($s, $after) as $x) {
                    $afterOnly[$x->value()] = true;
                }
            }
            foreach ($r->mergedPersonsOf($id) as $s) {
                foreach ($r->componentAt($s, $before) as $x) {
                    $this->assertArrayHasKey($x->value(), $afterOnly, "round {$round}");
                }
            }
        }
    }

    /** A8: for random rosters and changes, the affected persons cover every publication whose case flips (full-scan oracle). */
    public function test_a8_affected_persons_cover_every_flip_against_a_full_scan_oracle(): void
    {
        mt_srand(20261114);
        $people = ['A', 'B', 'C', 'D', 'E', 'F'];
        for ($round = 0; $round < 300; $round++) {
            $r = StewardRoster::empty();
            $merges = [];
            for ($i = 0; $i < mt_rand(1, 6); $i++) {
                [$a, $b] = [$people[mt_rand(0, 5)], $people[mt_rand(0, 5)]];
                if ($a !== $b) {
                    $id = "m{$round}_{$i}";
                    $r->recordMerge($id, [self::p($a), self::p($b)]);
                    $merges[] = $id;
                }
            }
            $approvals = [];
            for ($j = 0; $j < 4; $j++) {
                $approvals[] = self::approval($j + 1, $people[mt_rand(0, 5)], $people[mt_rand(0, 5)], $people[mt_rand(0, 5)]);
            }
            $before = $r->version();
            if ($merges !== [] && mt_rand(0, 1) === 1) {
                $id = $merges[mt_rand(0, count($merges) - 1)];
                $r->recordReversal($id);
                $named = $r->mergedPersonsOf($id);
            } else {
                [$a, $b] = [$people[mt_rand(0, 5)], $people[mt_rand(0, 5)]];
                if ($a === $b) {
                    continue;
                }
                $r->recordMerge("n{$round}", [self::p($a), self::p($b)]);
                $named = [self::p($a), self::p($b)];
            }
            $after = $r->version();
            $affected = array_flip(array_map(static fn ($x) => $x->value(), ApprovalDefects::affectedPersons($r, $before, $after, $named)));
            foreach ($approvals as $ap) {
                $defective = static fn (int $v): bool => (bool) array_filter($ap->excluded, static fn ($e) => $r->knownSameAt($ap->approver, $e, $v));
                if ($defective($before) !== $defective($after)) {
                    $this->assertArrayHasKey($ap->approver->value(), $affected, "round {$round}: a flipped publication's approver was not selected");
                }
            }
        }
    }
}
