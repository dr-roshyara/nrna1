# 04: Correction slice C-1, Annul and Void in the kernel

**Story:** `PBDIGIT-268` · **Contract:** `docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md` (approved by the PO 2026-10-10) · **Plan:** `docs/plans/20261010-1009-membership-c1-annul-void-tdd-plan.md` · **Namespace:** `App\Contexts\Membership\Domain\Organisational`

## Purpose

C-1 adds the first two correction facts to the existing kernel:
- **Annul** sets aside an earlier **decision** (for example, an appeal succeeds);
- **Void** records that an earlier fact **never reflected reality** (for example, a data-entry error).

Both change **fact liveness only** (D-COR-5 (A)). They carry no valid time and create no consequences. A reversed fact simply stops counting, and reversing the reversal makes it count again.

C-1 is kernel only. It has no application handler, no authority check, no persistence and no events. Those are C-3 and C-4. `Correct` and consequences are C-2.

## Where it fits

```text
Domain (pure PHP)  ← C-1: this guide (Member::decideAnnul / decideVoid, Resolution stage 1)
Application        ← C-3 (authority, emitting events)
Infrastructure     ← C-4 (persisting Annul / Void)
```

## Key files

| File | Role |
|---|---|
| `EffectiveDatedFact.php` | `effectiveAt(): TimePoint`, split out of `Fact` (PO condition P-1). Implemented by `Admit` and `End` only |
| `ReversalFact.php` | `target(): FactId`. Implemented by `Annul` and `VoidFact` |
| `Annul.php`, `VoidFact.php` | the two facts: `(id, target, provenance, decidedAt)`. **`VoidFact` is the ratified fact kind Void**; PHP reserves the class name `Void` |
| `Member.php` | `decideAnnul`, `decideVoid` and their guards, in the approved order |
| `Resolution.php` | stage 1 `live()` (one backward pass) and `isLive()` for one fact |
| `RefusalReason.php` | `TargetNotFound`, `DecidedAtRequired`, `AnnulOfVoidRefused`, `TargetHasNoDecisionProvenance`, `TargetNotLive` |

## Design decisions

- **Liveness (FP §16.3):** a fact is live iff no later live Annul or Void targets it. A reversal targets only an earlier fact, so one backward pass over the log decides every fact.
- **No implicit consequences (D-COR-5 (A), Human ARB 2026-10-10).** Annulling an End revives the period as it was claimed. If that overlaps a later period, **G\* refuses it** (INV-9); nothing is trimmed silently.
- **Liveness is not period activity (P-3).** The admission of an ended period is still a live fact and can be annulled.
- **Restoration (act §4, D-COR-2):** Annul(Annul), Void(Annul) and Void(Void) make the original target live again. **Annul(Void) is refused** (N-3); a Void is restored by Void(Void).
- **No invented valid time (P-1).** Annul and Void do not implement `EffectiveDatedFact`.
- **No clock (P-2).** An Annul without `decidedAt` is refused (`DecidedAtRequired`); the kernel never substitutes the current time.

### Guard order (plan, refusal triggers)

| Annul | Void |
|---|---|
| 1. `DuplicateFactId` (the new fact's id) | 1. `DuplicateFactId` |
| 2. `TargetNotFound` | 2. `TargetNotFound` |
| 3. `DecidedAtRequired` | — |
| 4. `AnnulOfVoidRefused` | — |
| 5. `TargetHasNoDecisionProvenance` (R-2) | — (Void accepts any provenance) |
| 6. `TargetNotLive` (D-COR-2) | 3. `TargetNotLive` |
| 7. G\*: `OverlappingMembershipPeriods`, … | 4. G\* |

## How it works

```php
// An admission that was decided in error is set aside by a later decision.
$decision = $member->decideAnnul(
    FactId::fromString('X1'),
    FactId::fromString('A1'),            // the target: an earlier decision fact
    Provenance::decision('registrar-1'),
    Instant::of($decidedAt),              // required; null → DecidedAtRequired
);

// A record that never reflected reality is voided, whatever its provenance.
$decision = $member->decideVoid(FactId::fromString('V1'), FactId::fromString('A1'), Provenance::decision('registrar-1'));
```

As with `decideAdmit` and `decideEnd`, both return a `Decision` and never change the `Member`. Resolution then skips facts that are no longer live:

```php
private static function live(array $facts): array
{
    $reversed = [];
    $live = [];
    for ($i = count($facts) - 1; $i >= 0; $i--) {
        $fact = $facts[$i];
        if (isset($reversed[$fact->id()->value()])) {
            continue;                       // a later live reversal targets it
        }
        $live[] = $fact;
        if ($fact instanceof ReversalFact) {
            $reversed[$fact->target()->value()] = true;   // only a LIVE reversal counts
        }
    }

    return array_reverse($live);
}
```

**Re-entry example (contract T8–T10):** Admit p0 at 1, End p0 at 5, Admit p1 at 10.
- Annul(End p0) is refused: p0 would become `[1, ∞)` and overlap p1.
- Annul(Admit p1) first, then Annul(End p0): both are accepted, and p0 = `[1, ∞)`.
- Restoring p1 afterwards (Annul of that annulment) is refused, because it overlaps again.

## How to extend

- **C-2 (`Correct`, consequences):** add the cases to stage 2 (`active`) and stage 5 (`liveCuts`), not new stages. Correct(Annul) and Correct(Void) are N-3 refusals.
- **C-3 (application):** pass caller input unchanged. The kernel refusals are the business answers, and events are emitted from accepted decisions.
- **C-4 (persistence):** `PostgresMemberRepository::encode(Fact)` reads `effectiveAt()`, so it must handle Annul and Void before they can be stored. See the implementation review, finding F-1.

## Testing

| Test | What it shows |
|---|---|
| `AnnulVoidTest` | K-A1…K-A5, K-V1, K-V2, T1–T11: each guard, restoration, INV-9 refusals, P-KNOWN |
| `G6CorrectionOracleGoldenTest` | the PHP port `Support/G6CorrectionOracle` reproduces the Python oracle's stream (fixture `Fixtures/g6_c1_golden.json`, 15530 decisions) |
| `C1ExhaustiveDifferentialTest` | K-X2: kernel = port on verdict, refusal and per-period lifetime for every decision, H = 5, L ≤ 5 |
| `C1PropertyTest` | P-REV-V, P-REV-A, P-APPEAL, the deletion counterfactual, P-EQ, P-COMM (+ counterexample), closed form |

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational/
```

Regenerate the fixture only if the oracle changes: `python3 -I docs/publicdigit/membership/architecture/models/g6_c1_golden.py > tests/Unit/Contexts/Membership/Domain/Organisational/Fixtures/g6_c1_golden.json`.

A pass means **verified within bounds**, not proof. The oracle is evidence, not the contract.

## Pitfalls

- **Do not name a class `Void`:** PHP rejects it as a reserved word. Use `VoidFact`.
- **Do not check "is the period active?" for TargetNotLive.** It is about fact liveness.
- **Do not trim a later period when an End is annulled.** That would be an implicit consequence, which D-COR-5 (A) forbids. Let G\* refuse.
- **`Resolution::live` relies on list keys** (`$facts[$i]`). Pass `array_values(...)` when filtering, as `knownAt` does.
- **Anonymity (ADR-T11):** reversal facts carry fact ids and provenance only, never a voter or a vote.

**Traceability:** PBDIGIT-268 · contract §3, §4, §14.2–§14.8 · D-COR-2, D-COR-5 (A) · PO conditions P-1, P-2, P-3 · commits `f7c251354` … `1e85fbfc0`.
