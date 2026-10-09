# 01: Slice 0, the Membership semantic kernel

**Story:** `PBDIGIT-258` · **Contract:** `docs/publicdigit/reviews/2026-10-08-membership-slice0-semantic-design-contract.md` · **Namespace:** `App\Contexts\Membership\Domain\Organisational`

## Purpose

Slice 0 is the smallest piece of the ratified Membership model that runs. It covers:
- **one Member per (person, organisation)**, holding an append-only log of facts;
- **two facts:** `Admit` and `End`;
- **Resolution**, which derives periods and predicates from the facts;
- **G\***, which accepts a fact only if the resolved candidate satisfies the invariants.

There is no persistence, no framework and no application layer. The domain answers "what is true?" and "may this fact be recorded?".

## Where it fits

```text
Domain (pure PHP)  ← Slice 0: this guide
Application        ← Slice 1a+ (repository port, conflict re-decide, authority)
Infrastructure     ← Slice 1b (tables, transactions, outbox)
```

An architecture test (`tests/Architecture/MembershipSemanticKernelPurityTest.php`) keeps the namespace free of Laravel, the database, HTTP, clocks, randomness and other contexts' vocabulary.

## Key files

| File | Role |
|---|---|
| `Member.php` | aggregate: identity (MemberId, holder `PersonRef`, organisation `TenantId`), the log, `decideAdmit` / `decideEnd`, `resolve`, `knownAt`, `version` |
| `Admit.php`, `End.php`, `Fact.php` | the two facts; facts carry no holder (INV-1) |
| `RecordedFact.php` | a fact plus `seq` and `recordedAt` (assigned at commit, Slice 1a) |
| `Resolution.php` | facts → `ResolvedMember`, in the ratified stage order |
| `ResolvedMember.php`, `ResolvedPeriod.php` | derived state and predicates |
| `InvariantSet.php` | INV-9, INV-EP, INV-COV on a resolved state |
| `Decision.php`, `RefusalReason.php` | `Accepted(fact, basedOnVersion)` or `Refused(reason)` |
| `TimePoint.php`, `Instant.php`, `Interval.php`, `IntervalSet.php` | valid time (with ∞), decision / recording time, `[from, until)` algebra |
| `Provenance.php`, `ProvenanceKind.php`, `AuthorityEvidence.php`, `EndKind.php` | carried evidence; never read by Resolution |
| `MemberId.php`, `FactId.php`, `PeriodId.php`, `PersonRef.php` | identities. `PeriodId` is a view of the Admit's `FactId` |

## Design decisions

- **Status is derived, never stored.** There is no `status` field anywhere. `isMember`, `isLapsed` and `isEnded` are computed by Resolution from the facts (ratified act §2).
- **Decisions are pure.** `Member::decide*()` never changes the Member. They return a `Decision`; appending is the repository's job (Slice 1a). Ids are passed in, and nothing reads a clock.
- **`basedOnVersion` on every acceptance.** Slice 1a appends a fact only at the version it was decided against, so a stale decision cannot be reused after a conflict (EP-01 Rev 1, R3; contract §22).
- **Two time types.** `TimePoint` (effectiveAt; may be ∞) and `Instant` (decidedAt, recordedAt) cannot be mixed up. Their ordinal unit (day or instant) is still open (EP-Q6).
- **C-S0-1** (Principal Reviewer decision): an End at or before the period start is refused (`EndNotAfterPeriodStart`). An admission that never took effect is a later Annul / Void.
- **C-S0-2** (Principal Reviewer decision): an Admit without an initial term has an empty term. The person is *Lapsed*, not a member. There is no default term.
- **The lifetime is an `IntervalSet`,** so that INV-EP ("one contiguous episode") and INV-COV ("cuts never remove coverage") are real checks. They become reachable in Slice 7.

## How it works

```php
$member = Member::fromLog(MemberId::fromString($id), PersonRef::fromString($person), TenantId::fromString($org));

$decision = $member->decideAdmit(
    FactId::fromString($factId),
    TimePoint::of($effective),
    TimePoint::infinity(),            // initial term until ∞; null = no term (Lapsed)
    Provenance::decision('registrar-1'),
);

if (!$decision->isAccepted()) {
    $decision->refusal();             // e.g. RefusalReason::OverlappingMembershipPeriods (INV-9)
}

$resolved = $member->resolve();       // pure function of the log
$resolved->isMember(TimePoint::of($t));
$resolved->period(PeriodId::fromString($factId))?->isLapsed(TimePoint::of($t));
$member->knownAt(Instant::of($tau));  // what was known at τ
```

**Decision order** (`Member::accept`):
1. Guards: duplicate FactId, invalid initial term, unknown / ended period, End not after the start.
2. Build the candidate log F · f and resolve it.
3. Check `InvariantSet::violations`.
4. Return `Accepted` or `Refused` with the first violated rule.

**Admission rule** (contract Theorem 3): an Admit is accepted exactly when every existing period has ended at or before the new start. Re-entry on the same day as the end is allowed.

## How to extend (later slices)

- **New facts** (Renew, ChangeType, Suspend, Restore, Correct, Annul, Void) add **cases** to the existing Resolution stages (`live`, `active`, value, `liveCuts`). They never add stages.
- **The ConsequenceGenerator** arrives with Correct (Slice 7). `Resolution::lifetime()` deliberately throws if cuts appear before then.
- **Do not add** an AggregateRoot base class, an event framework, a state machine or a status enum. The purity test will fail.

## Testing

| Test | What it proves |
|---|---|
| `IntervalAlgebraTest` | the `[from, until)` algebra (contract §10) |
| `AdmissionTest`, `EndTest`, `MembershipPredicatesTest`, `InvariantSetTest` | the example semantics, identity, terms A / B / C, INV-2 / 3 / 9 / EP / COV, KnownAt |
| `ExhaustiveBoundedVerificationTest` | all histories for T ≤ 5 and ≤ 5 commands, against the independent `Support/PointwiseOracle` (no production code). The decision counts equal the Python research model's |
| `PropertyAndMetamorphicTest` | 300 seeded histories; determinism; time translation; id renaming; KnownAt prefix stability; End monotonicity |
| `tests/Architecture/MembershipSemanticKernelPurityTest` | purity, non-circularity (Resolution never references G\*), no frameworks or stored status |

Run them without the Laravel bootstrap (pure unit tests):

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational/
```

## Pitfalls

- **Do not test against Resolution's own output.** Use the pointwise oracle (contract §19).
- **Integer ordinals are a placeholder for the unit**, not a business statement about days or seconds (EP-Q6).
- **Resolution is total on logs that are unreachable through `decide`:** the latest End on a period wins, and an End on an unknown period is ignored. Do not rely on this as behaviour.
- **INV-M** (one Member per person and organisation) cannot be checked inside one aggregate. It is the creation constraint of Slice 1a / 1b.

**Traceability:** PBDIGIT-258 · contract §4–§27 · EP-01 Rev 1 · ratification act §2–§13 · implementation review `docs/publicdigit/reviews/2026-10-09-membership-slice0-tdd-implementation-review.md`.
