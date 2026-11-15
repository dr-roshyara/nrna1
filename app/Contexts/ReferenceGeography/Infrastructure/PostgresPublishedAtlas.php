<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\ReferenceGeography\Domain\BeforeImage;
use App\Contexts\ReferenceGeography\Domain\ClaimKind;
use App\Contexts\ReferenceGeography\Domain\PublishedAtlas;
use App\Contexts\ReferenceGeography\Domain\SlotHistory;
use App\Contexts\ReferenceGeography\Domain\SlotKey;
use App\Contexts\ReferenceGeography\Domain\ValidInterval;
use Illuminate\Database\ConnectionInterface;

/**
 * The published atlas at p₀, from PostgreSQL (PublishedAtlas port; v3.11 §5, §6; completion §4 clarification A).
 *
 * - A claim is published iff its (revision, content version) is named by a refgeo_publications row; its position is
 *   that row's. Draft claims (refgeo_revision_claims) and frozen-but-unpublished versions are never read.
 * - Fixed at p₀ (the committed position when made, or a given one). Positions ≤ p₀ are committed, gap-free and
 *   immutable (G-4a), so every read here sees the same atlas, with no snapshot to hold open.
 * - familySlots has exactly InMemoryPublishedAtlas's meaning: slots of the kind with the fixed components whose
 *   effective value at p₀ equals $value somewhere within the interval. A slot can only qualify if a published claim
 *   of it carries $value, which the query uses as an indexed prefilter (refgeo_claims_kind_value).
 * The validator never sees this class; it reads through DependencyRecordingReader (§5.2).
 */
final class PostgresPublishedAtlas implements PublishedAtlas
{
    private const PUBLISHED = 'FROM refgeo_claims c JOIN refgeo_publications p ON p.revision_id = c.revision_id AND p.content_version = c.content_version';

    private function __construct(private readonly ConnectionInterface $db, private readonly int $p0)
    {
    }

    public static function at(ConnectionInterface $db, ?int $p0 = null): self
    {
        $committed = (int) $db->selectOne('SELECT last_position FROM refgeo_sequence WHERE id = 1')->last_position;
        if ($p0 !== null && ($p0 < 0 || $p0 > $committed)) {
            throw new \InvalidArgumentException("p0 {$p0} is beyond the committed position {$committed}.");
        }

        return new self($db, $p0 ?? $committed);
    }

    public function p0(): int
    {
        return $this->p0;
    }

    public function history(SlotKey $slot): SlotHistory
    {
        $history = SlotHistory::for($slot);
        foreach ($this->db->select('SELECT c.*, p.position ' . self::PUBLISHED . ' WHERE c.slot_key = ? AND p.position <= ? ORDER BY p.position, c.claim_id', [$slot->encoded(), $this->p0]) as $row) {
            $history = $history->with(PostgresRevisionRepository::claimOf($row), (int) $row->position);
        }

        return $history;
    }

    /**
     * PBDIGIT-355: every slot history of the kind at p₀ in ONE query: for each slot, exactly what history() gives (the
     * same published claims, added in the same order: position, claim id); slots ordered by their encoded key.
     *
     * @return list<SlotHistory>
     */
    public function historiesOfKind(ClaimKind $kind): array
    {
        $histories = [];
        foreach ($this->db->select('SELECT c.*, p.position ' . self::PUBLISHED . ' WHERE c.kind = ? AND p.position <= ? ORDER BY c.slot_key, p.position, c.claim_id', [$kind->value, $this->p0]) as $row) {
            $histories[$row->slot_key] = ($histories[$row->slot_key] ?? SlotHistory::for(SlotKey::decode($row->slot_key)))
                ->with(PostgresRevisionRepository::claimOf($row), (int) $row->position);
        }

        return array_values($histories);
    }

    public function familySlots(ClaimKind $kind, array $fixed, string $value, ValidInterval $interval): array
    {
        $members = [];
        foreach ($this->db->select('SELECT DISTINCT c.slot_key ' . self::PUBLISHED . ' WHERE c.kind = ? AND c.value = ? AND p.position <= ? ORDER BY c.slot_key', [$kind->value, $value, $this->p0]) as $row) {
            $slot = SlotKey::decode($row->slot_key);
            foreach ($fixed as $label => $expected) {
                if ($slot->component($label) !== $expected) {
                    continue 2;
                }
            }
            if (BeforeImage::of($this->history($slot), $interval, $this->p0)->hadValue($value, $interval)) {
                $members[] = $slot;
            }
        }

        return $members;
    }
}
