# 07: F-3 — key first, and a MemberId collision is an infrastructure failure

**Story:** `PBDIGIT-264` F-3 · **Plan and gate register:** `docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md` § F-3 (PO decisions F3-1 … F3-4, Path B) · **Contracts:** Slice 1a §4 R-1a-6 and §7 F-3 clause; Slice 1b amendment and §8 · **Evidence:** `docs/publicdigit/membership/evidence/2026-10-10-f3/`

## Purpose

The in-memory test double and the PostgreSQL adapter disagreed in two places (finding F-3 of the Slice 1b acceptance review):

1. **`append` for an unknown MemberId with a used idempotency key.** PostgreSQL answered from the key (`AlreadyApplied` / `IdempotencyKeyConflict`); the double answered `MemberNotFound`, because it checked the member first.
2. **`create` with a MemberId that already belongs to another holder.** PostgreSQL raised a primary-key violation; the double silently **overwrote** the first holder's Member.

A test double that behaves differently from production can let a defect pass the shared contract suite. F-3 makes the double conform to the policy adopted by the PO (F3-1): **the key lookup precedes every other check**, and **an id collision is an infrastructure failure with no state change**.

## Where it fits

Test code only. No production code changed: the PostgreSQL adapter already behaved this way (inferred from its code; verified only in Part 2).

```text
tests/Doubles/InMemoryMemberRepository.php                          ← append order; create collision
tests/Unit/Contexts/Membership/Application/Organisational/
  MemberRepositoryContract.php                                      ← F3a, F3b-1, F3b-2, F3c (shared, so PostgreSQL inherits them)
```

## Design decisions

| Decision | Content |
|---|---|
| F3-1 | Key first in every adapter (a key identifies a command, IDEM-1 / IDEM-3). An id collision is a caller defect (ids are caller-generated), so it is an infrastructure failure, not a new port outcome, and the port is unchanged |
| F3-2 | Path B: F-3 before F-5 (F-3 needs no database; F-5 needs Part 2) |
| F3-3 | R-1a-6 precedence over `MemberNotFound` is universal again |

The failure type is `\RuntimeException`, the project's convention for infrastructure failures (CLAUDE.md, rule 8). PostgreSQL's `UniqueConstraintViolationException` → `QueryException` → `PDOException` is one, so a single assertion covers both adapters. No new exception type was introduced.

## How it works

```php
// InMemoryMemberRepository::append()
$seen = $this->appliedCommand($tenant, $key);          // 1. key (IDEM-1 / IDEM-3)
if ($seen !== null) { /* AlreadyApplied or IdempotencyKeyConflict */ }
if (!StorableValidTime::admits($accepted->fact())) {   // 2. R-1a-6
    return AppendResult::failed(AppendOutcome::ValidTimeNotStorable);
}
if (!isset($this->members[$tenant->value()][$id->value()])) {   // 3. the member / version step
    return AppendResult::failed(AppendOutcome::MemberNotFound);
}
return $this->record($tenant, $id, $accepted, $key, $fingerprint);   // version check, then writes
```

```php
// InMemoryMemberRepository::create()
if (isset($this->members[$tenant->value()][$id->value()])) {
    throw new \RuntimeException("MemberId '{$id->value()}' already belongs to another holder in this tenant.");
}
```

**The member / version step.** PostgreSQL decides "member missing" and "version stale" in one compare-and-set (`UPDATE … WHERE version = ? RETURNING`; no row → the Member exists ? `ConcurrencyConflict` : `MemberNotFound`). The double checks the member, then the version. The outcomes are identical because a stale version presupposes an existing Member. The contract therefore orders three steps (key → R-1a-6 → member / version), not four.

## Testing

| Test | Proves |
|---|---|
| F3a | the collision throws a `RuntimeException`; holder P1, version and log unchanged; P2 not mapped; P1 still maps to M1; the same id in another tenant unchanged |
| F3b-1 | an unknown MemberId with a used key and the same fingerprint → `AlreadyApplied`, the original outcome, no new fact |
| F3b-2 | the same with another fingerprint → `IdempotencyKeyConflict`, nothing changed |
| F3c | an unknown MemberId, unused key, valid time 2932897 → `ValidTimeNotStorable` |

All four failed first on the double with assertion failures (F3a: no exception; the others: `member_not_found`), then passed after the fix. On PostgreSQL they run only in Part 2; they are predicted to pass without code change.

```bash
vendor/bin/phpunit tests/Unit/Contexts/Membership/Application/Organisational
```

## Pitfalls

- **PHPUnit's own failure is a `RuntimeException`.** `AssertionFailedError` extends `PHPUnit\Framework\Exception`, which extends `\RuntimeException`. A `$this->fail()` inside `try { … } catch (\RuntimeException)` is swallowed and the test can never fail. F3a captures the exception in a variable and asserts after the `try` (found while writing F3a; the plan's first draft had this flaw).
- **Don't use `expectException` when state must be checked afterwards**: the test stops at the throw.
- **PostgreSQL tests run without a per-test transaction** (`Tests\TestCase`), so the reads after the failed `INSERT` in F3a are valid there; under a wrapping transaction they would hit "current transaction is aborted".
- **Disclosure.** Key lookups are tenant-scoped. F3b-1 returns the original outcome (including another Member's id) only to a caller presenting the same key and the same fingerprint, that is, someone who already knows the full command.

**Traceability:** F-3 (ratification pack §2 / §10; Slice 1a §7) · PO decisions F3-1 … F3-4 · commits `d908f93c4` (RED), `d2a3f2b17` (GREEN). **Status:** implemented and tested on the double; PostgreSQL unverified until Part 2; not formally accepted.
