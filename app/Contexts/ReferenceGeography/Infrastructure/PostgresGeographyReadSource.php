<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\ReferenceGeography\Application\Ports\GeographyReadSource;
use App\Contexts\ReferenceGeography\Application\Ports\PublicationRecord;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use Illuminate\Database\ConnectionInterface;

/**
 * The read side over PUBLISHED claims only (G-5a): a claim is visible iff a refgeo_publications row names its
 * (revision, content version), at a position ≤ the p asked. Drafts and frozen-but-unpublished versions never appear.
 * Positions ≤ the committed one are immutable (G-4a guards), so several statements at one p are consistent (PO-5).
 *
 * Known cost (stated, not hidden): subjectSlots matches the unit inside the encoded slot key with LIKE, a scan of
 * refgeo_claims. Adequate before G-8's data; an index on the slot's subject is the remedy when measured.
 */
final class PostgresGeographyReadSource implements GeographyReadSource
{
    private const PUBLISHED = 'FROM refgeo_claims c JOIN refgeo_publications p ON p.revision_id = c.revision_id AND p.content_version = c.content_version';

    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function committedPosition(): int
    {
        return (int) $this->db->selectOne('SELECT last_position FROM refgeo_sequence WHERE id = 1')->last_position;
    }

    public function positionAtTime(string $tau): int
    {
        return (int) $this->db->selectOne('SELECT coalesce(max(position), 0) AS p FROM refgeo_publications WHERE recorded_at <= ?::timestamptz', [$tau])->p;
    }

    public function publication(int $position): ?PublicationRecord
    {
        $row = $this->db->selectOne('SELECT position, recorded_at::text AS recorded_at, revision_id::text AS revision_id FROM refgeo_publications WHERE position = ?', [$position]);

        return $row === null ? null : new PublicationRecord((int) $row->position, $row->recorded_at, $row->revision_id);
    }

    public function unitsWithExistence(int $p): array
    {
        return array_map(
            static fn (object $row): ReferenceUnitId => SlotKey::decode($row->slot_key)->subject(),
            $this->db->select("SELECT DISTINCT c.slot_key " . self::PUBLISHED . " WHERE c.kind = 'EXISTENCE' AND p.position <= ? ORDER BY c.slot_key", [$p]),
        );
    }

    public function slotHistory(SlotKey $slot, int $p): SlotHistory
    {
        return PostgresPublishedAtlas::at($this->db, $p)->history($slot);
    }

    public function subjectSlots(ReferenceUnitId $unit, int $p): array
    {
        $needle = '%=' . strlen($unit->value()) . ':' . $unit->value() . '|%';
        $atlas = PostgresPublishedAtlas::at($this->db, $p);   // the accepted published-only mapping of claims (G-4c)
        $histories = [];
        foreach ($this->db->select('SELECT DISTINCT c.slot_key ' . self::PUBLISHED . ' WHERE c.slot_key LIKE ? AND p.position <= ? ORDER BY c.slot_key', [$needle, $p]) as $row) {
            $slot = SlotKey::decode($row->slot_key);
            if ($slot->subject()?->equals($unit) === true) {   // else the unit is another component (a successor), not the subject
                $histories[] = $atlas->history($slot);
            }
        }

        return $histories;
    }

    public function disputesForSubject(ReferenceUnitId $unit, int $p): array
    {
        $keys = [];   // the dispute slot of EVERY published claim of the subject: any of them may be in force at some t
        $needle = '%=' . strlen($unit->value()) . ':' . $unit->value() . '|%';
        foreach ($this->db->select('SELECT c.claim_id::text AS id, c.slot_key ' . self::PUBLISHED . ' WHERE c.slot_key LIKE ? AND p.position <= ?', [$needle, $p]) as $row) {
            if (SlotKey::decode($row->slot_key)->subject()?->equals($unit) === true) {
                $keys[SlotKey::dispute(ClaimId::fromString($row->id))->encoded()] = $row->id;
            }
        }
        if ($keys === []) {
            return [];
        }
        $atlas = PostgresPublishedAtlas::at($this->db, $p);
        $disputes = [];
        $marks = implode(',', array_fill(0, count($keys), '?'));
        foreach ($this->db->select("SELECT DISTINCT c.slot_key " . self::PUBLISHED . " WHERE c.kind = 'DISPUTE' AND c.slot_key IN ({$marks}) AND p.position <= ?", [...array_keys($keys), $p]) as $row) {
            $disputes[$keys[$row->slot_key]] = $atlas->history(SlotKey::decode($row->slot_key));
        }

        return $disputes;
    }

    /** G-5b: every published slot of the kind (names, codes), each history through the accepted atlas mapping. */
    public function slotsOfKind(ClaimKind $kind, int $p): array
    {
        $atlas = PostgresPublishedAtlas::at($this->db, $p);

        return array_map(
            static fn (object $row): SlotHistory => $atlas->history(SlotKey::decode($row->slot_key)),
            $this->db->select('SELECT DISTINCT c.slot_key ' . self::PUBLISHED . ' WHERE c.kind = ? AND p.position <= ? ORDER BY c.slot_key', [$kind->value, $p]),
        );
    }

    public function containedIn(ReferenceUnitId $parent, string $scheme, int $p): array
    {
        $children = [];
        foreach ($this->db->select("SELECT DISTINCT c.slot_key " . self::PUBLISHED . " WHERE c.kind = 'CONTAINMENT' AND c.value = ? AND p.position <= ? ORDER BY c.slot_key", [$parent->value(), $p]) as $row) {
            $slot = SlotKey::decode($row->slot_key);
            if ($slot->component('scheme') === $scheme) {
                $children[] = $slot->subject();
            }
        }

        return $children;
    }
}
