<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure;

use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\Ports\ContextPersonRefs;
use App\Contexts\Identity\Domain\ContextPersonRef;
use App\Contexts\Identity\Domain\PersonId;
use App\Contexts\Identity\Domain\PlatformContext;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;

/**
 * PBDIGIT-346 G-0 (D-225 IG-1, IG-3). One reference per (Person, context), issued on first request:
 * - an existing reference is returned unchanged;
 * - otherwise a random 128-bit value is inserted with ON CONFLICT DO NOTHING. If nothing was inserted, either a
 *   concurrent request issued this pair's reference (returned) or the value collided (a fresh value is tried; a
 *   value is never reused);
 * - no reverse lookup exists. The table's trigger refuses any change or deletion.
 */
final class PostgresContextPersonRefs implements ContextPersonRefs
{
    private const ATTEMPTS = 5;

    /** @var \Closure(): string */
    private readonly \Closure $generate;

    /** @param (\Closure(): string)|null $generate test seam; default: 16 random bytes as hex */
    public function __construct(private readonly Connection $db, ?\Closure $generate = null)
    {
        $this->generate = $generate ?? static fn (): string => bin2hex(random_bytes(16));
    }

    public function refFor(PersonId $person, PlatformContext $context): ContextPersonRef
    {
        try {
            for ($attempt = 0; $attempt < self::ATTEMPTS; $attempt++) {
                $existing = $this->existing($person, $context);
                if ($existing !== null) {
                    return $existing;
                }
                $candidate = ContextPersonRef::fromString(($this->generate)());
                $inserted = $this->db->affectingStatement(
                    'INSERT INTO identity_context_person_refs (ref, person_id, context) VALUES (?, ?, ?) ON CONFLICT DO NOTHING',
                    [$candidate->value(), $person->value(), $context->value],
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

    private function existing(PersonId $person, PlatformContext $context): ?ContextPersonRef
    {
        $ref = $this->db->selectOne(
            'SELECT ref FROM identity_context_person_refs WHERE person_id = ? AND context = ?',
            [$person->value(), $context->value],
        )?->ref;

        return $ref === null ? null : ContextPersonRef::fromString(trim((string) $ref));
    }
}
