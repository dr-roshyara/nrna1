# 30: Schedule and withdraw a resignation, and the declaration store (lifecycle S2-4b), PBDIGIT-354

**Decisions:** D-201 · D-204 · D-208 §2 (`record-resignation`, its resign and withdraw shapes; GC-1, GC-2, GC-12) · D-238 (order, refusals) · D-239 · M-TDD-01 §5.1–§5.2.
**Builds on:** guide 29 (the `ScheduledResignation` domain), guide 27 (the authority contract).
**Plan:** `docs/plans/20261115-0510-release-1-build-slice-s2-membership-lifecycle-plan.md` §7c, step S2-4b.
**Evidence:** `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-4b/`.
**Not here:** execution on the date (S2-4c), the withdraw-vs-execute race and routing (S2-4d).

## Business purpose

- **A member, or an officer who records it for them, can say "I resign from 1 March"** and the system holds that declaration.
- **Until then nothing changes.** The person is still a member, and no membership feed shows the planned end (MI-4).
- **The member can change their mind** and withdraw it before the date begins. Who withdrew it, and when, is recorded.
- **Only one pending declaration per membership period.** After a withdrawal, a new one may be made.
- **Nothing is ever deleted or rewritten:** the full history of declaration, withdrawal and (in S2-4c) execution stays.

## Where it fits

| Layer | Files |
|---|---|
| Domain | `ScheduledResignation` (+ `withdraw($on, $by)`, `withdrawnBy()`, `fromStorage(...)`) |
| Application | `ScheduleResignation` / `…Handler`, `WithdrawScheduledResignation` / `…Handler`, `DecideAndRecordDeclaration` (the loop), `DeclarationOutcome`, `ApplicationRefusal::DeclarationNotFound` |
| Ports | `ScheduledResignationStorePort`, `DeclarationWriteOutcome`, `AppliedDeclarationRequest`; `AuthorityRequest::$declarationRef` (additive) |
| Infrastructure | `PostgresScheduledResignationStore`; migrations central `2026_11_15_360000_…`, organisation `2026_11_15_300700_…` (same SQL) |
| Test double | `tests/Doubles/InMemoryScheduledResignationStore` |

## How it works

### The two shapes of `record-resignation` (D-208 §2)

```php
// schedule: the resign shape, with the declared (later) effective date
new AuthorityRequest(MembershipAct::RecordResignation, $actor, $member?->holder(), $member?->resolve()->currentPeriod($on), $on, $from, $key);

// withdraw: the withdraw shape, naming the declaration, no effective date
new AuthorityRequest(MembershipAct::RecordResignation, $actor, $member?->holder(), $declaration?->period(), $on, null, $key, null, $command->declarationId);
```

`AuthorityRequest` refuses a `declarationRef` that belongs to another act, comes together with an effective date, or is empty.

### The loop (`DecideAndRecordDeclaration`): the same order as the ledger's (D-238)

1. **Idempotency pre-check:** a repeated request returns the declaration as stored now, and nobody is asked again. The same key with another command gives `IdempotencyKeyConflict`.
2. **find**, read-only.
3. **request.** A boundary refusal comes **before anyone is asked**: a schedule on the same day gives `ScheduledDateNotAfterDeclaration`, because that's resign now (S2-3b).
4. **authorise**, through `DecideAndAppend::authorisationRefusal()`, the one shared reading of the answer:
   - not authorised and undetermined are separate refusals (≠ No);
   - **`ownAct` counts only when the actor is the member.**
5. **Not found comes after the answer:** `MemberNotFound`, or `DeclarationNotFound` for a withdrawal. An unknown declaration is asked about with **no period**, so an unauthorised actor learns nothing.
6. **decide** (domain), then **write**. A `Conflict` from the store is decided again from a fresh read (≤ 3 attempts), then `ConcurrentModification`.

### The store (`PostgresScheduledResignationStore`)

- **`record`** runs in one transaction:
  1. key check;
  2. the membership row is locked (`SELECT … FOR UPDATE`) and its version compared, so a declaration is never decided on a stale membership;
  3. insert;
  4. key row.
  - A unique violation (the id, or `…_one_pending`) gives `Conflict`.
- **`transition`** runs `UPDATE … WHERE state = 'scheduled'`, the compare-and-set (MI-2). Zero rows gives `Conflict`.
- **The database itself protects the history:**

| Rule | Where |
|---|---|
| one `scheduled` declaration per period | partial unique index `org_membership_scheduled_resignations_one_pending` |
| effective date after the declaration; each state's fields consistent; a withdrawal before the date | CHECK `…_dates_check`, `…_state_check` |
| the period and the executed End are this member's own facts; the member is this database's own | foreign keys `…_period_fk`, `…_executed_fk`, `…_membership_fk` |
| declared fields never change; a final declaration never changes; nothing is deleted; key rows never change | trigger `org_membership_scheduled_resignation_guard()` (`SCHEDULED_RESIGNATION_DECLARED_FIELDS`, `…_FINAL`, `…_HISTORY`) |

- **Reloading checks every field** (`ScheduledResignation::fromStorage`). An inconsistent row is a `RuntimeException` (fail closed), never a valid declaration.
- **Organisation isolation:** every key starts with `organisation_id` and refers only to the database's own membership rows. In an organisation database, a declaration naming another organisation is refused (`OrganisationMigrationsTest` T8).

## How to use it

```php
$outcome = (new ScheduleResignationHandler($members, $store, $authority))->handle(
    new ScheduleResignation($org, 'M1', 'D1', declarationDate: 20_010, effectiveDate: 20_030, actor: 'P1', decidedAt: $decidedAtMicros, idempotencyKey: 'k1'));
$outcome->isRecorded();            // the declaration (state scheduled) — no ledger fact

(new WithdrawScheduledResignationHandler($members, $store, $authority))->handle(
    new WithdrawScheduledResignation($org, 'M1', 'D1', withdrawalDate: 20_020, actor: 'P1', idempotencyKey: 'k2'));
```

**Production:** nothing is bound; `AuthorityCheckPort` stays unbound until Governance S3, so neither handler can be built (fail closed).

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Application/Organisational/ScheduleAndWithdrawResignationTest.php
MEMBERSHIP_PG_IMAGE=… MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite MembershipPostgres --filter PostgresScheduledResignationTest
```

- **`ScheduleAndWithdrawResignationTest` (14):** both request shapes; self-service and recorder; `ownAct`; boundary-first; no existence leak; one per period; idempotency; withdrawal before / on the date; history; conflict retry.
- **`PostgresScheduledResignationTest` (7):** storage and reload; no ledger fact and no C1 event; idempotency; the store's compare-and-set; each database rule above.
- **Guards:**
  - `MembershipLedgerIsolationTest` allowlists `360000` and forbids both new tables outside Membership;
  - `OrganisationMigrationsTest` compares both tables (parity) and refuses another organisation (T8).

## Pitfalls

- **`CREATE OR REPLACE FUNCTION` in migrations.** `migrate:fresh` drops tables, not functions. The first draft's `CREATE FUNCTION` broke the next `migrate:fresh` (23 legacy tests).
- **In shared PostgreSQL tests, scope every statement by organisation.** The tables persist across tests; an unscoped `UPDATE` hits other tests' rows.
- **A declaration is not a fact:** don't use `MemberRepositoryPort::append` or its key table. The declaration store has its own keys (`org_membership_declaration_commands`), so a key used for a ledger command and one used for a declaration don't collide. They are separate spaces.
- **The withdrawal date is caller-supplied (D-5(a) Z1).** S2-4c adds the calendar (D-239). Until then, nothing in production can withdraw anyway.

**Traceability:** PBDIGIT-354 S2-4b · D-201 · D-204 · D-208 §2 (GC-1, GC-2, GC-12) · D-238 · D-239 · M-TDD-01 §5.1–§5.2 · MI-2 (store side), MI-4.
