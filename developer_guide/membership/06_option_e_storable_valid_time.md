# 06: Option E — storable valid time (R-1a-6)

**Story:** `PBDIGIT-264` (follow-on to F-1) · **Plan and gate register:** `docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md` § Option E · **Contracts:** Slice 1a §3–§5 + R-1a-6 amendment; Slice 1b §3, §4, §6, §8 + amendment · **Evidence:** `docs/publicdigit/membership/evidence/2026-10-10-option-e/`

## Purpose

A `TimePoint` is an unbounded day ordinal (it also represents ∞). The PostgreSQL adapter can only store finite dates from 0001-01-01 to 9999-12-31. Before Option E, the in-memory test double accepted any ordinal, while PostgreSQL threw during encoding and relied on a rollback. The two adapters therefore behaved differently, and the application could not tell the caller what went wrong.

Option E makes the limit part of the **repository contract**: rule R-1a-6, with the explicit outcome `ValidTimeNotStorable`. It is a technical persistence capability. It is **not** a business calendar, plausibility, backdating or scheduling rule, and `TimePoint` is unchanged.

## Where it fits

```text
app/Contexts/Membership/Application/Organisational/
  Ports/StorableValidTime.php     ← the range and admits(Fact) — a contract type beside AppendResult
  Ports/AppendOutcome.php         ← + ValidTimeNotStorable
  ApplicationRefusal.php          ← + ValidTimeNotStorable
  DecideAndAppend.php             ← exhaustive match, no default
app/Contexts/Membership/Infrastructure/Organisational/
  PostgresMemberRepository.php    ← check after answerFromKey, before the compare-and-set
  DateOrdinal.php                 ← uses StorableValidTime::FIRST/LAST; still throws (final safeguard)
tests/Doubles/InMemoryMemberRepository.php  ← the same check, after its key check
```

`StorableValidTime` is not a port (nothing implements it). It states what the repository port promises to store, so it lives with the port's other contract types (`AppendResult`, `AppendOutcome`, `CreateResult`). Infrastructure may depend on the application layer, so both adapters can use it; the architecture tests confirm this.

## Design decisions

All recorded in the Option E gate register (Human PO / ARB, 2026-10-10):

| Decision | Content |
|---|---|
| 1 | Order inside `append`: idempotency-key lookup → R-1a-6 → version comparison → writes |
| 2 | R-1a-6 wins over a stale version: the valid times come from the command, so re-deciding cannot fix them |
| 3 | F-3 limitation accepted: the double still checks `MemberNotFound` before the key |
| 5 | P1: the PostgreSQL check ships now; its behaviour is verified only in Part 2 |

The key comes first because the Slice 1a contract requires it (§7, F-3 clause), and because the application's own pre-check (`DecideAndAppend::answerFromKey`) already answers a known key before `append` is reached.

## How it works

```php
// StorableValidTime::admits()
$points = $fact instanceof EffectiveDatedFact ? [$fact->effectiveAt()] : [];
if ($fact instanceof Admit && $fact->initialTermUntil() !== null) {
    $points[] = $fact->initialTermUntil();
}
// each point: ∞ passes; a finite ordinal must lie in [-719_162, 2_932_896]
```

```php
// PostgresMemberRepository::appendInTransaction()
$seen = $this->answerFromKey($tenant, $key, $fingerprint);
if ($seen !== null) {
    return $seen;
}
// R-1a-6: before the compare-and-set, so no statement has modified state yet.
if (!StorableValidTime::admits($accepted->fact())) {
    return AppendResult::failed(AppendOutcome::ValidTimeNotStorable);
}
```

```php
// DecideAndAppend::run()
$outcome = match ($result->outcome()) {
    AppendOutcome::Appended, AppendOutcome::AlreadyApplied => CommandOutcome::ofAppended($result->appended()),
    AppendOutcome::IdempotencyKeyConflict => CommandOutcome::refused(ApplicationRefusal::IdempotencyKeyConflict),
    AppendOutcome::MemberNotFound => CommandOutcome::refused(ApplicationRefusal::MemberNotFound),
    AppendOutcome::ValidTimeNotStorable => CommandOutcome::refused(ApplicationRefusal::ValidTimeNotStorable),
    AppendOutcome::ConcurrencyConflict => null,   // the only outcome that is re-decided
};
```

Before Option E this was a `switch` without a default: an unmapped outcome fell through, was retried, and ended as `ConcurrentModification`. The `match` throws `UnhandledMatchError` instead. Completeness is proven by test E9, not by that exception.

`AlreadyApplied` maps to the same success as `Appended` on purpose: IDEM-1 says a retry returns the **original** `Appended` (same fact, seq and recordedAt).

## How to extend

- **A new fact kind with a valid time:** E10 fails. Extend `StorableValidTime::admits()` and add contract cases in `MemberRepositoryContract`. If it is missed anyway, `admits()` still checks `effectiveAt` of any `EffectiveDatedFact`, the adapter's `encode()` throws for an unknown fact kind, and `DateOrdinal` throws for an out-of-range value: an error, never corrupt data.
- **A new `AppendOutcome`:** E9's coverage check fails until the expected table has a row for it; add the `match` arm in `DecideAndAppend`.
- **A business date rule** (options B / C / D): a separate decision with its own identifier, in the domain decisions, not here.

## Testing

| Test | Where | What |
|---|---|---|
| E1a–d | `MemberRepositoryContract` | unstorable `effectiveAt` (−719163, 2932897), finite term end and End (2932897): `valid_time_not_storable`, version / log / key unchanged |
| E2a–d | same | 0001-01-01 and 9999-12-31 stored and read back |
| E3 | same | a known key with another fingerprint → `IdempotencyKeyConflict` before R-1a-6 |
| E4 | same | a known key with a **matching fingerprint** is answered without examining the new decision. The fingerprint is opaque at the port, and the new decision deliberately differs: this is not an identical command |
| E4-L | `InMemoryMemberRepositoryTest` | the genuine identical retry of a **legacy** record (written before R-1a-6, valid time 2932897, seeded with the test-only `seedPreOptionEAppend`): original outcome, no new fact, sequence unchanged. PostgreSQL variant in Part 2 |
| E5 | same | R-1a-6 before a stale version |
| E7, E8 | `MembershipCommandHandlersTest` | the application refusal, one `append` call; a reused key never reaches `append` |
| E9 | `AppendOutcomeMappingTest` | every enum case mapped; only a conflict retries, `MAX_ATTEMPTS` times |
| E10 | `tests/Architecture/MembershipStorableValidTimeCoverageTest` | the dated facts are exactly Admit and End |

Boundaries are literal ordinals, never the shared constants, so the contract is checked independently of the implementation. `DateOrdinalTest` is a second, independent oracle (literal date strings).

Three boundary cases are deliberately absent because a domain guard refuses them first: a term end ≤ −719162 (it must be after a storable start), an End ≤ −719162 (it must be after the period start), and a finite term end with start 2932896.

E1–E5 also run against PostgreSQL through `PostgresMemberRepositoryTest`, but **only in Part 2**, under its isolation gates.

**Showing a characterisation test can fail.** E4-L could not fail first (the behaviour already existed), so its value was shown with a temporary sensitivity change: moving the R-1a-6 check above the key lookup in the double made E4-L, E4 and E3 fail on assertions. The change was reverted (byte-identical, by checksum) and never committed; the evidence is in `docs/publicdigit/membership/evidence/2026-10-10-option-e-correction/`.

```bash
vendor/bin/phpunit tests/Unit/Contexts/Membership/Application/Organisational
vendor/bin/phpunit tests/Architecture/MembershipStorableValidTimeCoverageTest.php
```

## Pitfalls

- **The application banned-word check scans comments too.** `MembershipSlice1aBoundaryTest` rejects `DateTime` and `date(` / `time(` (also with a space) anywhere in `Application/Organisational`. Write "valid time", never "valid time (…)".
- **Don't name a test helper `run()`**: it collides with PHPUnit's final `TestCase::run()` (observed while writing E9).
- **9999-12-31 is storable.** Option E cannot detect it as a legacy "no end" placeholder; that is the Slice 13 migration contract (BD-NORM).
- **Parity (updated by F-3, guide 07).** Until F-3, R-1a-6's precedence over `MemberNotFound` was adapter-specific (the double checked the member first). Since F-3 the double checks the key first, F3c pins the missing-member case, and R-1a-6 precedes both `ConcurrencyConflict` and `MemberNotFound` in every adapter.
- **`seedPreOptionEAppend` is test-only.** It models legacy state for E4-L. Never copy it into a production adapter: R-1a-6 must not be bypassable.

**Traceability:** R-1a-6 (Slice 1a contract §4) · Slice 1b §6 step (1b) · PBDIGIT-264 F-1 / F-3 · gate register decisions 1–6, 8 · commits `0e4e5ac7a` (RED), `af495c838` (GREEN), `3ee8c2fe4` (contracts, gate register, this guide, evidence), `fb85c6e24` (correction: E4 renamed, E4-L; Human PO / ARB R1–R4). **Status:** implemented and locally tested; PostgreSQL unverified until Part 2; not formally accepted.
