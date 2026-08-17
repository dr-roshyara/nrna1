# Step 01 — The Model A Operating Core: domain-only first increment

> ⚠️ **Provenance status (2026-08-17):** uncommitted (working tree only) pending the PO's fork ruling; **not yet independently verified** (C-5 pending, five inputs queued). This guide documents the files on disk; it certifies nothing.

## Purpose

Realizes the **qualified Model A business rules** — Committee-only acceptance with its recovery, vacancy, Inoperative and clock model — **without changing their business meaning** (the `EM-ARCH-001` commission's own objective). This is the pure domain core only: no application layer, no adapters, no persistence, no framework anywhere.

## Where it fits

`app/Contexts/Election/Domain/OperatingCore/` — **Domain layer, pure PHP** (repo layer rules: Domain holds no Laravel, no clock, no storage). It plugs into the Constitution-homed election lifecycle *conceptually* at the gate condition (`EM-GOV-052`); no lifecycle wiring exists in this increment (deliberately — later increment).

## Key files

| Path | What it is |
|---|---|
| `Committee/ElectionCommittee.php` | **AG-1** — the constituted body; invariants I-1…I-6 |
| `Gate/AcceptanceGateDecision.php` | **AG-2** — one gate's decision record; I-7…I-12 |
| `Recovery/RecoveryProcess.php` | **AG-3** — one governed period; I-13…I-16; interval clocks |
| `Policy/*.php` | **P-1…P-7** — stateless rule realizations, each header citing its rule |
| `Condition/*.php` | the four **derived** condition types — OPEN / halted / Inoperative / terminal — plus `ElectionLevelCancellation` kept as a **distinct type** from opportunity-level outcomes |
| `Event/*.php` | eleven recorded-fact types, every one implementing `NonCanonicalEventName` (names are placeholders — `EM-OPEN-045`) |
| `Port/*.php` | driven ports: `ProtocolAppend` (F-PROTO-1/P-2H), `ServicePolicySnapshot`, `InstantSource`, and the **operation-less** `OrganisationalAppointmentAuthority` |
| `Repository/*.php` | interfaces for the three aggregates only (repo Rule 9) |
| `Time/RecordedInstant.php` | the only time concept: a recorded instant, supplied by callers |

## Design decisions (with refs)

1. **Vacancy is an event, never an inference** (`EM-GOV-064`). `ElectionCommittee::recordVacancy()` is the *only* way a seat becomes vacant, grounds are the closed `VacancyGround` enum, and resignation without a reason throws. Temporary unavailability has **no representation at all** in AG-1 — deliberately: it changes nothing structural.
2. **The denominator never moves** (`EM-GOV-057`). `constitutedSize()` is fixed at constitution; `intervalState()` cross-checks it against the gate's bound denominator and throws on mismatch.
3. **A cast vote stands; the event is the truth** (`EM-GOV-066`, B-7). In AG-2, `expressPosition()` creates the `CommitteePositionExpressed` fact *first* and routes it through the single private writer `apply()`; `fromRecordedFacts()` rebuilds the aggregate from events alone. One seat, one position, enforced by key + `SeatAlreadyExpressedPosition`.
4. **Classifications are derived, never stored** (`EM-GOV-065`/`068`, DD-1). `unableToFunction()` and `intervalState()` are computations over recorded facts. There is no state column to drift from the record — and no determiner, because there is nothing to declare.
5. **Decided failure is a pure function of objections** (P-2 header + in-code algebra): `DecidedFailure ⟺ objections > constituted − required`. Every unexpressed seat — vacant or not — is credited as a potential future accept, so **vacancy arithmetic can never produce a decided failure**; it produces `Unachievable`, which restoration returns to OPEN (`EM-GOV-059`(c)). ⚠️ This exact branch is **verification-queue item ④** (a review dispute, pinned by test) — do not "fix" it pending C-5.
6. **OPEN has no time** (`EM-GOV-068`, G-3/D-4). AG-2 takes no time input except the `RecordedInstant` *on the fact being recorded*; structural guards forbid attachment mechanisms in `Gate/`.
7. **The D-1 wall is operation-less.** `OrganisationalAppointmentAuthority` declares **no methods**: declaring signatures would fix the external authority's form, which `EM-OPEN-049` leaves open. Its acts enter as driving calls (`fillSeat`) performed by a future adapter that deliberately does not exist. **Never write one.**
8. **The terminal state renders one way only.** `TerminalStatePlaceholder::businessRendering()` → `"Election Discontinued"` (`EM-GOV-069`); the type name stays technical per the registered DDD separation.

## How it works — the S-06 flow in code

```php
$committee = ElectionCommittee::constitute($electionId, $seatA, $seatB, $seatC); // I-1: <3 throws
$gate = AcceptanceGateDecision::establish($electionId, GateDesignation::First,
        ThresholdRule::twoThirdsOfCommitteeVotes(), $committee->constitutedSize());

$fact = $gate->expressPosition($seatA, AcceptancePosition::Accept, $recordedAt); // the fact IS the truth
// two members merely unavailable: NO vacancy events → nothing recorded, nothing changes
$gate->intervalState($committee);   // → GateIntervalState::Open — no clock exists to run

// vacancies instead:
$committee->recordVacancy($seatB, VacancyGround::ResignationWithReason, $reason, $t1);
$committee->recordVacancy($seatC, VacancyGround::DeathOrPermanentIncapacity, null, $t2);
$committee->unableToFunction($gate->requiredVotes()); // → true, at $t2 — arithmetic, nobody declares
$gate->intervalState($committee);   // seat A's cast accept STANDS (I-8) — see queue item ① (OPEN ∧ INOPERATIVE edge)
```

## How to use / extend

- **Consume policies, don't inline rule math** — threshold arithmetic lives in `ThresholdEvaluation`/`RequiredVotes` (⌈2n/3⌉, `EM-GOV-036`/`038`), nowhere else.
- **New recorded-fact types**: implement `NonCanonicalEventName` + the DomainEvent marker, `final readonly`, no ADR-T11 surface — the structural guard discovers and checks them; **do not** freeze counts in tests.
- **Never** add: an adapter/binding for the D-1 port · a timer/deadline/expiry hook in `Gate/` · a stored classification flag · a Committee abstention position · lifecycle transitions (Constitution-homed).

## Testing

`tests/Unit/Contexts/Election/OperatingCore/` — 6 files / 42 tests, framework-free, test doubles only. Notable: `StructuralGuardsTest` (D-1 wall = implementations/bindings scan; framework-freedom; OPEN time-freedom; event marking), the P-2H protocol contract against an in-memory double, and `test_decided_failure_is_a_pure_function_of_objections_never_of_vacancies` — the pinned dispute. Run: `php artisan test tests/Unit/Contexts/Election/OperatingCore` (never `migrate:fresh`; RefreshDatabase is irrelevant here — no DB is touched).

## Pitfalls

- **Do not treat `Unachievable` as failed.** It is recoverable — filling seats returns the gate to OPEN (`059`(c)). Only a *decided* failure halts permanently.
- **Do not surface `TerminalStatePlaceholder` as anything but "Election Discontinued"** — and never for opportunity-level ends (`EM-VOC-004` words stay unprefixed and separate).
- **Do not "complete" `RecoveryProcess`** — completed/cancelled/superseded are consequences owned by Election rules (P-6), not clock states (queue item ⑤).
- **Free-text `VacancyReason` is an ADR-T11-constrained surface** — never let voter/vote-linkable content in.
- **Five items are queued for independent verification** — touching the disputed branch, the Inoperative-onset event payload, or the §2b commentary edge before C-5 rules is scope drift.

## Acceptance status

**EM-IMPL-001 was ACCEPTED on 2026-08-17 after C-5 independent verification (8/8 constraint checks; every correctness dispute resolved in the code's favour), and the increment is CLOSED and BASELINED.**

Acceptance conditions:
- §2b commentary correction pending separate Architecture authorization (proven-half scope only).
- `OPEN ∧ INOPERATIVE` semantics deferred to `EM-OPEN-111` (both parts: clock/gloss · what Inoperative permits).
- N-3…N-6 tracked in `PBDIGIT-69`.
- **Behaviour freeze applies: no implementation may encode a decision on `OPEN ∧ INOPERATIVE` until governance resolution** — e.g. `if ($committee->unableToFunction()) { $gate->close(); }` is exactly the forbidden change.

> **The protective statement, verbatim:** *"EM-IMPL-001 acceptance does not establish the meaning of the OPEN ∧ INOPERATIVE region. It establishes only that the current implementation faithfully realizes the currently adopted rules and that the ambiguity is intentionally preserved pending EM-OPEN-111."*

*(This supersedes the provenance caveat in the header to this extent: the authority chain is attested and the increment accepted; the provenance defect and RED-ordering caveat remain permanently recorded as history.)*

## Baseline Freeze

EM-IMPL-001 is frozen as: **Accepted · Closed · Verified · Baselined.**

Reference (the cold-start entry point): `docs/publicdigit/implementation/2026-08-17-EM-IMPL-001-baseline-freeze.md`

Future work must enter through one of: **`EM-OPEN-111` adjudication · §2b correction (Architecture, own authorization) · `PBDIGIT-69` hygiene slices · Increment 2 authorization.** Nothing else touches this code.

## Traceability

`EM-ARCH-001` (approved design; §2c/§2e/§5c) · `EM-IMPL-001` (prepared act + readiness report G=YES; **authorization acts pending registration — fork ruling open**) · rules `EM-GOV-005`/`028`/`029`/`031`/`033`/`035`/`036`/`038`/`052`/`056`/`057`/`058`/`059`/`060`/`061`/`062`/`063`/`064`/`065`/`066`/`067`/`068`/`069` · ADR-T11 · `F-PROTO-1` · `P-2H` · verification queue items ①–⑤ (`.claude/sessions/2026-08-17.md`).
