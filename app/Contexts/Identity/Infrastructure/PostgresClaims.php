<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure;

use App\Contexts\Identity\Application\ClaimOutcome;
use App\Contexts\Identity\Application\ClaimRefusal;
use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\Ports\ClaimStore;
use App\Contexts\Identity\Application\RedemptionOutcome;
use App\Contexts\Identity\Application\RedemptionRefusal;
use App\Contexts\Identity\Domain\PersonId;
use App\Contexts\Identity\Infrastructure\Outbox\BindingRefusedHydrator;
use App\Contexts\Identity\Infrastructure\Outbox\ClaimRedeemedHydrator;
use App\Contexts\Identity\Domain\ClaimCode;
use App\Contexts\Identity\Domain\ClaimWindow;
use App\Contexts\Identity\Domain\OrganisationPersonRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;

/**
 * PBDIGIT-349 I-2: ordinary claims in the CENTRAL database (I-TDD-02 §5, §6.2, §14). Each operation is ONE transaction:
 * 1. READ COMMITTED (a FOR UPDATE that waited returns the row's latest committed version);
 * 2. locks in the global order: ClaimSlot (O, R) (inserted if absent, then FOR UPDATE), then the claim row;
 * 3. idempotency on (O, issuance id) — read after the lock, so a retry waiting behind its own first attempt sees it;
 * 4. ONE expiry sample t_check = clock_timestamp(), read after all locks; every expiry decision and issuedAt /
 *    expiresAt use it (never the transaction's start time: PI-24);
 * 5. state checks, then single use of the authority request (consumed in this transaction), then the change;
 * 6. a content-free audit row (§15).
 * `$afterDecision` is a test seam: it runs once the outcome is decided, still holding every lock, before commit.
 */
final class PostgresClaims implements ClaimStore
{
    public function __construct(private readonly Connection $db, private readonly ?\Closure $afterDecision = null)
    {
    }

    public function issue(TenantId $organisation, OrganisationPersonRef $ref, string $issuanceId, ClaimCode $code, ClaimWindow $window, string $authorityRequestId): ClaimOutcome
    {
        return $this->inTransaction(function () use ($organisation, $ref, $issuanceId, $code, $window, $authorityRequestId): ClaimOutcome {
            $o = $organisation->value();
            $this->lockSlot($o, $ref->value());
            $existing = $this->db->selectOne('SELECT claim_id FROM identity_claims WHERE organisation_id = ? AND issuance_id = ?', [$o, $issuanceId]);
            if ($existing !== null) {
                return ClaimOutcome::alreadyIssued($existing->claim_id);
            }
            $t = $this->tCheck();
            if ($this->activeClaimOf($o, $ref->value(), $t)) {
                return $this->refuse($o, null, ClaimRefusal::ActiveClaimExists);
            }
            if ($this->isBound($o, $ref->value())) {
                return $this->refuse($o, null, ClaimRefusal::ReferenceBound);
            }
            if ($this->isConsumed($authorityRequestId)) {
                return $this->refuse($o, null, ClaimRefusal::EvidenceReplayed);
            }

            return $this->create($o, $ref->value(), $issuanceId, $code, $window, $authorityRequestId, $t, null, 'claim-issued');
        });
    }

    public function replace(TenantId $organisation, string $oldClaimId, string $newIssuanceId, ClaimCode $code, ClaimWindow $window, string $authorityRequestId): ClaimOutcome
    {
        return $this->inTransaction(function () use ($organisation, $oldClaimId, $newIssuanceId, $code, $window, $authorityRequestId): ClaimOutcome {
            $o = $organisation->value();
            $old = $this->lockClaim($o, $oldClaimId);
            if ($old === null) {
                return $this->refuse($o, null, ClaimRefusal::UnknownClaim);
            }
            $existing = $this->db->selectOne('SELECT claim_id FROM identity_claims WHERE organisation_id = ? AND issuance_id = ?', [$o, $newIssuanceId]);
            if ($existing !== null) {
                return ClaimOutcome::alreadyIssued($existing->claim_id);
            }
            $t = $this->tCheck();
            if (!$this->isActive($old, $t)) {
                return $this->refuse($o, $oldClaimId, ClaimRefusal::NotActive);
            }
            if ($this->isConsumed($authorityRequestId)) {
                return $this->refuse($o, $oldClaimId, ClaimRefusal::EvidenceReplayed);
            }
            $this->db->update("UPDATE identity_claims SET state = 'revoked', ended_at = ?::timestamptz WHERE claim_id = ?", [$t, $oldClaimId]);

            return $this->create($o, trim($old->person_ref), $newIssuanceId, $code, $window, $authorityRequestId, $t, $oldClaimId, 'claim-replaced');
        });
    }

    public function revoke(TenantId $organisation, string $claimId, string $authorityRequestId): ClaimOutcome
    {
        return $this->inTransaction(function () use ($organisation, $claimId, $authorityRequestId): ClaimOutcome {
            $o = $organisation->value();
            $claim = $this->lockClaim($o, $claimId);
            if ($claim === null) {
                return $this->refuse($o, null, ClaimRefusal::UnknownClaim);
            }
            $t = $this->tCheck();
            if (!$this->isActive($claim, $t)) {
                return $this->refuse($o, $claimId, ClaimRefusal::NotActive);
            }
            if ($this->isConsumed($authorityRequestId)) {
                return $this->refuse($o, $claimId, ClaimRefusal::EvidenceReplayed);
            }
            $this->db->update("UPDATE identity_claims SET state = 'revoked', ended_at = ?::timestamptz WHERE claim_id = ?", [$t, $claimId]);
            $this->consume($authorityRequestId, $o, $claimId);
            $this->audit('claim-revoked', $o, $claimId, null);
            $this->decided();

            return ClaimOutcome::revoked($claimId);
        });
    }

    /** The unique key behind INV-BND-2 (one Person, at most one R of O); named by PostgreSQL for UNIQUE (organisation_id, person_id). */
    private const ONE_REFERENCE_PER_PERSON = 'identity_bindings_organisation_id_person_id_key';

    public function redeem(ClaimCode $code, PersonId $person): RedemptionOutcome
    {
        if ($this->db->transactionLevel() > 0) {
            throw new \LogicException('A redemption starts its own transaction.');
        }
        try {
            return $this->redeemOnce($code, $person);
        } catch (UniqueConstraintViolationException $e) {
            if (!str_contains($e->getMessage(), self::ONE_REFERENCE_PER_PERSON)) {
                throw new IdentityUnavailable('Identity could not complete the redemption (' . $e::class . ').', previous: $e);
            }
            // Review R-1 (INV-BND-2 "refused AND reported"): a concurrent redemption bound this Person to another R of O
            // and committed first; this transaction was rolled back whole. Decide once more, in a fresh transaction, which
            // now reads that binding and takes the normal refusal path (BindingRefused + audit). Only this violation.
            try {
                return $this->redeemOnce($code, $person);
            } catch (QueryException $again) {
                throw new IdentityUnavailable('Identity could not complete the redemption (' . $again::class . ').', previous: $again);
            }
        } catch (QueryException $e) {
            throw new IdentityUnavailable('Identity could not complete the redemption (' . $e::class . ').', previous: $e);
        }
    }

    /** One redemption transaction (§14): the lock order and decisions below; any database error rolls all of it back. */
    private function redeemOnce(ClaimCode $code, PersonId $person): RedemptionOutcome
    {
            return $this->db->transaction(function () use ($code, $person): RedemptionOutcome {
                $this->db->statement('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
                // 0. find the claim by its code hash: a plain read, to learn O and R. Nothing is decided on it.
                $found = $this->db->selectOne('SELECT claim_id, organisation_id, person_ref FROM identity_claims WHERE code_hash = ?', [$code->hash()]);
                if ($found === null) {
                    return RedemptionOutcome::refused(RedemptionRefusal::NotRedeemable);   // unknown (or cleaned-up) code
                }
                [$o, $r, $claimId] = [$found->organisation_id, trim($found->person_ref), $found->claim_id];
                // 1. ClaimSlot (O, R), 2. the claim row: the global lock order (INV-CLM-6); then ONE t_check
                $this->lockSlot($o, $r);
                $claim = $this->db->selectOne('SELECT claim_id, state, expires_at::text AS expires_at FROM identity_claims WHERE claim_id = ? FOR UPDATE', [$claimId]);
                if ($claim === null) {
                    return RedemptionOutcome::refused(RedemptionRefusal::NotRedeemable);
                }
                $t = $this->tCheck();
                $binding = $this->db->selectOne('SELECT person_id, claim_id FROM identity_bindings WHERE organisation_id = ? AND person_ref = ?', [$o, $r]);
                if ($claim->state === 'redeemed') {
                    // §14 case 3: the same Account re-presents the code it redeemed, while the row exists
                    return $binding !== null && $binding->person_id === $person->value() && $binding->claim_id === $claimId
                        ? RedemptionOutcome::alreadyBound($o)
                        : $this->redemptionRefused($o, $claimId, 'redeemed');
                }
                if (!$this->isActive($claim, $t)) {
                    return $this->redemptionRefused($o, $claimId, 'not-active');                   // expired or revoked
                }
                if ($binding !== null) {
                    return $this->redemptionRefused($o, $claimId, 'reference-bound');              // INV-ID-6
                }
                if ($this->db->selectOne('SELECT 1 FROM identity_bindings WHERE organisation_id = ? AND person_id = ?', [$o, $person->value()]) !== null) {
                    // INV-BND-2: a second of O's references for one Person is refused and reported to O (D-113)
                    $this->outbox(BindingRefusedHydrator::EVENT_TYPE, $o, $claimId, ['organisationId' => $o, 'personRef' => $r]);
                    $this->audit('binding-refused', $o, $claimId, 'person-already-bound');
                    $this->decided();

                    return RedemptionOutcome::refused(RedemptionRefusal::PersonAlreadyBound);
                }
                $this->db->insert('INSERT INTO identity_bindings (organisation_id, person_ref, person_id, bound_at, claim_id) VALUES (?, ?, ?, ?::timestamptz, ?)',
                    [$o, $r, $person->value(), $t, $claimId]);
                $this->db->update("UPDATE identity_claims SET state = 'redeemed', ended_at = ?::timestamptz WHERE claim_id = ?", [$t, $claimId]);
                $this->outbox(ClaimRedeemedHydrator::EVENT_TYPE, $o, $claimId, ['organisationId' => $o, 'personRef' => $r, 'claimId' => $claimId, 'boundAt' => $t]);
                $this->audit('claim-redeemed', $o, $claimId, null);
                $this->decided();

                return RedemptionOutcome::bound($o);
            });
    }

    public function recordRefusal(TenantId $organisation, ?string $claimId, ClaimRefusal $reason): void
    {
        try {
            $this->audit('claim-refused', $organisation->value(), $claimId, $reason->value);
        } catch (QueryException $e) {
            throw new IdentityUnavailable('Identity could not record the refusal (' . $e::class . ').', previous: $e);
        }
    }

    // ---- the protocol ----

    /** @param \Closure(): ClaimOutcome $operation */
    private function inTransaction(\Closure $operation): ClaimOutcome
    {
        if ($this->db->transactionLevel() > 0) {
            throw new \LogicException('A claim operation starts its own transaction.');
        }
        try {
            return $this->db->transaction(function () use ($operation): ClaimOutcome {
                $this->db->statement('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');

                return $operation();
            });
        } catch (QueryException $e) {
            throw new IdentityUnavailable('Identity could not complete the claim operation (' . $e::class . ').', previous: $e);
        }
    }

    private function lockSlot(string $o, string $ref): void
    {
        $this->db->insert('INSERT INTO identity_claim_slots (organisation_id, person_ref) VALUES (?, ?) ON CONFLICT DO NOTHING', [$o, $ref]);
        $this->db->selectOne('SELECT 1 FROM identity_claim_slots WHERE organisation_id = ? AND person_ref = ? FOR UPDATE', [$o, $ref]);
    }

    /** Finds the claim (plain read, to learn R), locks ClaimSlot (O, R), then the claim row: the global order. */
    private function lockClaim(string $o, string $claimId): ?object
    {
        $found = $this->db->selectOne("SELECT person_ref FROM identity_claims WHERE claim_id = ? AND organisation_id = ? AND kind = 'ordinary'", [$claimId, $o]);
        if ($found === null) {
            return null;
        }
        $this->lockSlot($o, trim($found->person_ref));

        return $this->db->selectOne('SELECT claim_id, person_ref, state, expires_at::text AS expires_at FROM identity_claims WHERE claim_id = ? FOR UPDATE', [$claimId]);
    }

    private function tCheck(): string
    {
        return $this->db->selectOne('SELECT clock_timestamp()::text AS t')->t;
    }

    private function isActive(object $claim, string $t): bool
    {
        return $claim->state === 'issued'
            && $this->db->selectOne('SELECT ?::timestamptz < ?::timestamptz AS active', [$t, $claim->expires_at])->active;
    }

    private function activeClaimOf(string $o, string $ref, string $t): bool
    {
        return $this->db->selectOne(
            "SELECT 1 FROM identity_claims WHERE organisation_id = ? AND person_ref = ? AND state = 'issued' AND expires_at > ?::timestamptz",
            [$o, $ref, $t],
        ) !== null;
    }

    private function isBound(string $o, string $ref): bool
    {
        return $this->db->selectOne('SELECT 1 FROM identity_bindings WHERE organisation_id = ? AND person_ref = ?', [$o, $ref]) !== null;
    }

    private function isConsumed(string $requestId): bool
    {
        return $this->db->selectOne('SELECT 1 FROM identity_consumed_authority_requests WHERE request_id = ?', [$requestId]) !== null;
    }

    private function consume(string $requestId, string $o, string $claimId): void
    {
        $this->db->insert("INSERT INTO identity_consumed_authority_requests (request_id, organisation_id, act_kind, claim_id) VALUES (?, ?, 'manage-claims', ?)",
            [$requestId, $o, $claimId]);
    }

    private function create(string $o, string $ref, string $issuanceId, ClaimCode $code, ClaimWindow $window, string $requestId, string $t, ?string $replaces, string $event): ClaimOutcome
    {
        $claimId = (string) Str::uuid();
        $expiresAt = $this->db->selectOne('INSERT INTO identity_claims
              (claim_id, organisation_id, person_ref, kind, code_hash, issued_at, expires_at, window_days, window_source, window_capped,
               state, issuance_id, authority_request_id, replaces_claim_id)
            VALUES (?, ?, ?, \'ordinary\', ?, ?::timestamptz, ?::timestamptz + make_interval(days => ?), ?, ?, ?, \'issued\', ?, ?, ?)
            RETURNING expires_at::text AS expires_at',
            [$claimId, $o, $ref, $code->hash(), $t, $t, $window->days, $window->days, $window->source, $window->capped, $issuanceId, $requestId, $replaces])->expires_at;
        $this->consume($requestId, $o, $claimId);
        $this->audit($event, $o, $claimId, $window->capped ? 'window-capped' : null);
        $this->decided();

        return ClaimOutcome::issued($claimId, $code, $expiresAt);
    }

    private function refuse(string $o, ?string $claimId, ClaimRefusal $reason): ClaimOutcome
    {
        $this->audit('claim-refused', $o, $claimId, $reason->value);
        $this->decided();

        return ClaimOutcome::refused($reason);
    }

    private function redemptionRefused(string $o, string $claimId, string $reason): RedemptionOutcome
    {
        $this->audit('redemption-refused', $o, $claimId, $reason);
        $this->decided();

        return RedemptionOutcome::refused(RedemptionRefusal::NotRedeemable);
    }

    /**
     * Identity's own outbox `identity_outbox_events` on the central database (multi-database boundary design §13.3
     * step 7), in the redemption's transaction. The aggregate of both ClaimRedeemed and BindingRefused is the CLAIM:
     * aggregate_type 'IdentityClaim', aggregate_id = the claim id (review R-8; never R, a PersonId or a code).
     *
     * @param array<string, string> $payload published language only (C-I3, C-I6): never a code, PersonId or Account
     */
    private function outbox(string $eventType, string $o, string $aggregateId, array $payload): void
    {
        $this->db->insert("INSERT INTO identity_outbox_events (id, event_id, organisation_id, aggregate_type, aggregate_id, event_type, payload, status)
            VALUES (?, ?, ?, 'IdentityClaim', ?, ?, ?, 'pending')",
            [(string) Str::uuid(), (string) Str::uuid(), $o, $aggregateId, $eventType, json_encode($payload, JSON_THROW_ON_ERROR)]);
    }

    private function audit(string $event, string $o, ?string $claimId, ?string $reason): void
    {
        $this->db->insert('INSERT INTO identity_claim_audit (event, organisation_id, claim_id, reason) VALUES (?, ?, ?, ?)', [$event, $o, $claimId, $reason]);
    }

    private function decided(): void
    {
        if ($this->afterDecision !== null) {
            ($this->afterDecision)();
        }
    }
}
