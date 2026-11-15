<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure;

use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\Ports\IssuedReferences;
use App\Contexts\Identity\Domain\LocalEntryRef;
use App\Contexts\Identity\Domain\OrganisationPersonRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;

/**
 * PBDIGIT-349 I-1 (I-TDD-02 §4; P-I2 = L1). The central table identity_issued_references holds (O, E) → R:
 * - an existing row for (O, E) is returned unchanged (INV-REF-2);
 * - otherwise a random 128-bit value is inserted with ON CONFLICT DO NOTHING. If nothing was inserted, either a
 *   concurrent issuance for (O, E) won (its committed R is returned) or the value collided with another R (a fresh
 *   value is tried; a value is never reused). The primary key on R gives INV-REF-1;
 * - the table's trigger refuses any change or deletion. Same pattern as PostgresContextPersonRefs (G-0).
 */
final class PostgresIssuedReferences implements IssuedReferences
{
    private const ATTEMPTS = 5;

    /** @var \Closure(): string */
    private readonly \Closure $generate;

    /** @param (\Closure(): string)|null $generate test seam; default: 16 random bytes as hex */
    public function __construct(private readonly Connection $db, ?\Closure $generate = null)
    {
        $this->generate = $generate ?? static fn (): string => bin2hex(random_bytes(16));
    }

    public function issue(TenantId $organisation, LocalEntryRef $entry): OrganisationPersonRef
    {
        try {
            for ($attempt = 0; $attempt < self::ATTEMPTS; $attempt++) {
                $existing = $this->existing($organisation, $entry);
                if ($existing !== null) {
                    return $existing;
                }
                $candidate = OrganisationPersonRef::fromString(($this->generate)());
                $inserted = $this->db->affectingStatement(
                    'INSERT INTO identity_issued_references (ref, organisation_id, entry_ref) VALUES (?, ?, ?) ON CONFLICT DO NOTHING',
                    [$candidate->value(), $organisation->value(), $entry->value()],
                );
                if ($inserted === 1) {
                    return $candidate;
                }
            }
        } catch (QueryException $e) {
            throw new IdentityUnavailable('Identity could not issue the reference (' . $e::class . ').', previous: $e);
        }

        throw new IdentityUnavailable('Identity could not issue a unique reference.');
    }

    public function isIssuedTo(OrganisationPersonRef $ref, TenantId $organisation): bool
    {
        try {
            return $this->db->selectOne(
                'SELECT 1 AS ok FROM identity_issued_references WHERE ref = ? AND organisation_id = ?',
                [$ref->value(), $organisation->value()],
            ) !== null;
        } catch (QueryException $e) {
            throw new IdentityUnavailable('Identity could not check the reference (' . $e::class . ').', previous: $e);
        }
    }

    public function find(TenantId $organisation, LocalEntryRef $entry): ?OrganisationPersonRef
    {
        try {
            return $this->existing($organisation, $entry);
        } catch (QueryException $e) {
            throw new IdentityUnavailable('Identity could not look up the reference (' . $e::class . ').', previous: $e);
        }
    }

    private function existing(TenantId $organisation, LocalEntryRef $entry): ?OrganisationPersonRef
    {
        $ref = $this->db->selectOne(
            'SELECT ref FROM identity_issued_references WHERE organisation_id = ? AND entry_ref = ?',
            [$organisation->value(), $entry->value()],
        )?->ref;

        return $ref === null ? null : OrganisationPersonRef::fromString(trim((string) $ref));
    }
}
