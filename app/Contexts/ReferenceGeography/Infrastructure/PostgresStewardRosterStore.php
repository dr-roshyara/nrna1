<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\ReferenceGeography\Application\Ports\StewardRosterStore;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Domain\RosterEntry;
use App\Contexts\ReferenceGeography\Domain\RosterEntryKind;
use App\Contexts\ReferenceGeography\Domain\StewardRoster;
use Illuminate\Database\ConnectionInterface;

/**
 * The roster in PostgreSQL (refgeo_roster_entries; v3.11 §8; completion §4; G-4 interpretation §2, B-6).
 *
 * A write:
 * 1. starts its own transaction, READ COMMITTED as the first statement (a waiter re-reads the committed row);
 * 2. takes the ONE Geography publication lock: refgeo_sequence FOR UPDATE (the same lock publication takes, G-4e);
 * 3. rebuilds the roster as last committed (StewardRoster::fromEntries, which refuses an inconsistent history);
 * 4. runs the domain write and inserts the entries it made; the insert trigger requires the next roster version and
 *    advances the row;
 * 5. commits, releasing the lock. Any exception rolls back everything.
 */
final class PostgresStewardRosterStore implements StewardRosterStore
{
    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function current(): StewardRoster
    {
        return StewardRoster::fromEntries($this->entries());
    }

    public function writeUnderLock(\Closure $write): mixed
    {
        if ($this->db->transactionLevel() > 0) {
            throw new \LogicException('A roster write starts its own transaction: the isolation level and the publication lock must be its first statements.');
        }

        return $this->db->transaction(function () use ($write): mixed {
            $this->db->statement('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
            $locked = (int) $this->db->selectOne('SELECT last_roster_version FROM refgeo_sequence WHERE id = 1 FOR UPDATE')->last_roster_version;
            $roster = $this->current();
            if ($roster->version() !== $locked) {
                throw new \RuntimeException("Roster integrity: {$roster->version()} entries stored, the sequence row says {$locked}.");
            }
            $result = $write($roster);
            foreach (array_slice($roster->entries(), $locked) as $entry) {
                $this->db->table('refgeo_roster_entries')->insert([
                    'roster_version' => $entry->version,
                    'kind' => $entry->kind->value,
                    'subject' => $entry->subject?->value(),
                    'by_person' => $entry->by?->value(),
                    'matter' => $entry->matter?->value(),
                    'merge_id' => $entry->mergeId,
                    'merged' => $entry->merged === [] ? null : json_encode(array_map(static fn (PersonReference $p): string => $p->value(), $entry->merged), JSON_THROW_ON_ERROR),
                    'interest' => $entry->interest,
                ]);
            }

            return $result;
        });
    }

    /** @return list<RosterEntry> */
    private function entries(): array
    {
        $person = static fn (?string $v): ?PersonReference => $v === null ? null : PersonReference::fromString(trim($v));

        return array_map(static fn (object $row): RosterEntry => new RosterEntry(
            (int) $row->roster_version,
            RosterEntryKind::from($row->kind),
            $person($row->subject),
            $person($row->by_person),
            $row->matter === null ? null : RevisionId::fromString($row->matter),
            $row->merge_id,
            $row->merged === null ? [] : array_map(static fn (string $v): PersonReference => PersonReference::fromString($v), json_decode($row->merged, true, 4, JSON_THROW_ON_ERROR)),
            $row->interest === null ? null : (bool) $row->interest,
        ), $this->db->select('SELECT roster_version, kind, subject, by_person, matter, merge_id, merged::text AS merged, interest FROM refgeo_roster_entries ORDER BY roster_version'));
    }
}
