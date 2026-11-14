<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\Ports\LockedLedger;
use App\Contexts\ReferenceGeography\Application\Ports\PublicationLedger;
use App\Contexts\ReferenceGeography\Domain\ApprovalDefects;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\PublicationGovernance;
use App\Contexts\ReferenceGeography\Domain\PublishedApproval;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;

/**
 * Applies Identity's merge and reversal notices to Geography (completion §5 IG-4, IG-5; G-4 interpretation §4) WITH
 * their D-90 consequences (G-6c design revision 3 §5, accepted 2026-11-14). Identity decides who is the same person;
 * Geography only records what that means for its own publications.
 *
 * One act, under the ONE publication lock, in one transaction (PublicationLedger::withLock):
 * 1. the roster entry (the consumer checkpoint: unique per merge id and kind). Already applied: null, nothing written;
 * 2. the affected persons: the components of the merge's persons before and after (ApprovalDefects, §5.1);
 * 3. the indexed second-person publications involving them, each with its counted approver, replayed from
 *    PublicationGovernance::mayPublish at its roster version (§17: published revisions are closed, the roster is
 *    versioned, so the replay is exact);
 * 4. their findings and closures (ApprovalDefects::evaluate), appended in position order.
 * Any failure rolls back all of it, so the notice is not consumed and redelivery applies it.
 * Local only: no transaction spans Identity and Geography.
 */
final class IdentityNoticeConsumer
{
    public function __construct(
        private readonly PublicationLedger $ledger,
        private readonly PublicationGovernance $governance,
    ) {
    }

    /** @param list<PersonReference> $persons */
    public function merged(string $mergeId, array $persons): ?int
    {
        return $this->apply(static fn (StewardRoster $r): ?int => $r->recordMerge($mergeId, $persons), static fn (): array => $persons, "merge:{$mergeId}");
    }

    public function reversed(string $mergeId): ?int
    {
        // the persons of the reversed merge, as stored; none if it never arrived (then nothing can change)
        return $this->apply(static fn (StewardRoster $r): ?int => $r->recordReversal($mergeId), static fn (StewardRoster $r): array => $r->mergedPersonsOf($mergeId), "reversal:{$mergeId}");
    }

    /**
     * @param \Closure(StewardRoster): ?int $write
     * @param \Closure(StewardRoster): list<PersonReference> $named
     */
    private function apply(\Closure $write, \Closure $named, string $change): ?int
    {
        return $this->ledger->withLock(function (LockedLedger $ledger) use ($write, $named, $change): ?int {
            $roster = $ledger->roster();
            $before = $roster->version();
            $w = $write($roster);
            if ($w === null) {
                return null;   // already consumed
            }
            $ledger->recordRoster($roster);

            $affected = ApprovalDefects::affectedPersons($roster, $before, $w, $named($roster));
            $candidates = array_map(fn (PublicationOfRevision $p): PublishedApproval => $this->publishedApproval($p, $roster), $ledger->publicationsInvolving($affected));
            $lifecycles = $ledger->lifecycleFor(array_map(static fn (PublishedApproval $a): int => $a->position, $candidates));
            foreach (ApprovalDefects::evaluate($roster, $w, $candidates, $lifecycles, $change) as $notice) {
                $ledger->appendNotice($notice);
            }

            return $w;
        });
    }

    private function publishedApproval(PublicationOfRevision $p, StewardRoster $roster): PublishedApproval
    {
        $decision = $this->governance->mayPublish($p->revision, $roster, $p->rosterVersion);
        $version = $p->revision->currentVersion();
        if ($decision->secondPerson === null || $version === null) {
            // an indexed publication must replay to its second person; anything else is an integrity fault: refuse the act
            throw new \RuntimeException("Publication {$p->position}: replaying its governance at roster version {$p->rosterVersion} gives no counted approver.");
        }

        return new PublishedApproval($p->position, $p->revision->id(), $version->number, $version->digest, $decision->secondPerson, $p->revision->excludedPersons());
    }
}
