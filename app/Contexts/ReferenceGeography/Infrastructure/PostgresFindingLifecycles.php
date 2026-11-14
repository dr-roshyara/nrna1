<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\ReferenceGeography\Domain\FindingLifecycle;
use App\Contexts\ReferenceGeography\Domain\GovernanceNotice;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use Illuminate\Database\ConnectionInterface;

/**
 * Reads the D-90 lifecycle of publications from refgeo_publications (design revision 3 §8, §10). READS ONLY. The one
 * reader shared by the write side (the locked ledger: everything committed) and the read side (the read source: only
 * entries at positions ≤ the knowledge position, so a later entry can never change an earlier answer). The meaning of
 * the entries is FindingLifecycle's, the one definition.
 */
final class PostgresFindingLifecycles
{
    /**
     * @param list<int> $positions publication positions
     * @param int|null $upTo read only entries at positions ≤ $upTo (null: everything committed)
     * @return array<int, FindingLifecycle>
     */
    public static function read(ConnectionInterface $db, array $positions, ?int $upTo = null): array
    {
        if ($positions === []) {
            return [];
        }
        $bounded = static fn ($q) => $upTo === null ? $q : $q->where('position', '<=', $upTo);
        $notices = $corrections = [];
        $findings = $bounded($db->table('refgeo_publications')->where('notice_kind', 'finding')->whereIn('concerns_position', $positions))
            ->orderBy('position')->get(['position', 'concerns_position', 'roster_version', $db->raw('merge_basis::text AS merge_basis')]);
        $publicationOf = [];
        foreach ($findings as $f) {
            $publicationOf[(int) $f->position] = (int) $f->concerns_position;
            $notices[(int) $f->concerns_position][] = [(int) $f->position, GovernanceNotice::finding((int) $f->concerns_position, json_decode($f->merge_basis, true, 4, JSON_THROW_ON_ERROR), (int) $f->roster_version)];
        }
        if ($publicationOf !== []) {
            foreach ($bounded($db->table('refgeo_publications')->whereIn('notice_kind', ['confirmation', 'closure'])->whereIn('concerns_position', array_keys($publicationOf)))
                ->orderBy('position')->get(['position', 'notice_kind', 'concerns_position', 'roster_version', 'ended_by', 'confirmed_by', 'confirmed_digest']) as $r) {
                $finding = (int) $r->concerns_position;
                $notices[$publicationOf[$finding]][] = [(int) $r->position, $r->notice_kind === 'closure'
                    ? GovernanceNotice::closure($finding, $r->ended_by, (int) $r->roster_version)
                    : GovernanceNotice::confirmation($finding, PersonReference::fromString(trim($r->confirmed_by)), $r->confirmed_digest, (int) $r->roster_version)];
            }
        }
        foreach ($bounded($db->table('refgeo_publications')->where('kind', 'revision')->whereIn('corrects_position', $positions))->get(['position', 'corrects_position']) as $c) {
            $corrections[(int) $c->corrects_position][] = (int) $c->position;
        }
        $lifecycles = [];
        foreach ($positions as $p) {
            $lifecycles[$p] = FindingLifecycle::of($p, $notices[$p] ?? [], $corrections[$p] ?? []);
        }

        return $lifecycles;
    }
}
