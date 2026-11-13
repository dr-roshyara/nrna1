<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure;

use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\Ports\AccountDirectory;
use App\Contexts\Identity\Domain\AccountId;
use App\Contexts\Identity\Domain\PersonId;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

/**
 * PBDIGIT-346 G-0 (D-226 Q-G-2, §5a). A legacy users row is the Account.
 * - Active = not soft-deleted and e-mail verified (the closest reading of I-TDD-02's verified login).
 * - Its Person is created lazily, once. A transaction-scoped advisory lock per account serialises first requests
 *   without locking the legacy users row.
 * - A merged Person resolves to its survivor (bounded). Any database failure is IdentityUnavailable (fail closed).
 * The mapping is transitional: it is not proof that one human holds one account.
 */
final class PostgresAccountDirectory implements AccountDirectory
{
    private const MAX_MERGE_HOPS = 16;

    public function __construct(private readonly Connection $db)
    {
    }

    public function activePersonOf(AccountId $account): ?PersonId
    {
        try {
            $active = $this->db->selectOne(
                'SELECT 1 AS ok FROM users WHERE id = ? AND deleted_at IS NULL AND email_verified_at IS NOT NULL',
                [$account->value()],
            );
            if ($active === null) {
                return null;
            }
            $person = $this->db->transaction(function () use ($account): string {
                $this->db->select("SELECT pg_advisory_xact_lock(hashtextextended('identity-account:' || ?, 0))", [$account->value()]);
                $mapped = $this->db->selectOne('SELECT person_id FROM identity_account_persons WHERE account_id = ?', [$account->value()]);
                if ($mapped !== null) {
                    return (string) $mapped->person_id;
                }
                $id = (string) Str::uuid();
                $this->db->insert('INSERT INTO identity_persons (id) VALUES (?)', [$id]);
                $this->db->insert('INSERT INTO identity_account_persons (account_id, person_id) VALUES (?, ?)', [$account->value(), $id]);

                return $id;
            });

            return PersonId::fromString($this->survivorOf($person));
        } catch (QueryException $e) {
            throw new IdentityUnavailable('Identity could not resolve the account (' . $e::class . ').', previous: $e);
        }
    }

    private function survivorOf(string $person): string
    {
        $current = $person;
        for ($hops = 0; $hops < self::MAX_MERGE_HOPS; $hops++) {
            $next = $this->db->selectOne('SELECT merged_into FROM identity_persons WHERE id = ?', [$current])?->merged_into;
            if ($next === null) {
                return $current;
            }
            $current = (string) $next;
        }

        throw new IdentityUnavailable('Merge chain too long or cyclic.');
    }
}
