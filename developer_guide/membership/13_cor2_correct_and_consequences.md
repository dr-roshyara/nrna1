# 13: Correction slice C-2, Correct and its consequences in the kernel

**Story:** `PBDIGIT-327` · **Contract:** `docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md` §3.3, §10a Correct (1)–(10), §14.5 · **Plan:** `docs/plans/20261103-0222-membership-cor2-correct-and-consequences-tdd-plan.md` · **Namespace:** `App\Contexts\Membership\Domain\Organisational`

## Purpose

C-2 lets the organisation **correct a recorded date**: the admission date or the end date of a membership period. The original fact stays in the log, and so does every earlier "what did we know on date X" answer. The correction is a new fact.

If the corrected date makes the period overlap a neighbour, the overlap is **trimmed** from the neighbour (BD-REV = trim). The trim is recorded **on the correction** (FP §12.4, option C), so undoing the correction undoes the trim (CFC). A correction that would wipe out a whole period is refused (BD-REV-ER = refuse).

C-2 is kernel only. It has no application handler, no authority check (CQ-3), no persistence (COR-4) and no event *emission* (COR-3). **The accepted decision carries the event data:** `FactCorrected` from the `Correct` fact, one `PeriodTrimmed` per `TrimConsequence`, and `TrimRevived` from `Decision::revivedTrims()`.

## Where it fits

```text
Domain (pure PHP)  ← C-2: this guide (Member::decideCorrect, Resolution stages 2–6, InvariantSet ER)
Application        ← COR-3 (CorrectFact handler, authority, emitting events)
Infrastructure     ← COR-4 (storing Correct and its consequences; StorableValidTime for the corrected value)
```

## Key files

| File | Role |
|---|---|
| `Correct.php` | the fact: `id`, `target`, `value` (the corrected date), `provenance`, `decidedAt`, frozen `consequences()` |
| `TrimConsequence.php` | one trim: the trimmed `period()` and the `removed()` interval, as stated by the decision (P-2) |
| `TrimRevival.php` | `TrimRevived` data: `(cause, period)`, the identity of a trim |
| `Decision.php` | `revivedTrims()` on an accepted decision |
| `Member.php` | `decideCorrect`, its guards, g2 (`trimConsequences`) and `revivedTrims` |
| `Resolution.php` | stage 2 (active), stage 3 (`valueOf`), stage 4 (BaseClaim from values), stage 5 (LiveCut), stage 6 (lifetime); `rootOf`, `isVoided`, `periodOfChain`, `trimsInForce` |
| `InvariantSet.php` | ER, checked last → `PeriodAbsorbed` |
| `IntervalSet.php` | `intersectedWith()`, `without()` |
| `RefusalReason.php` | `CorrectionOfAnnulOrVoidRefused`, `CorrectOfVoidedFact`, `ValueUnchanged`, `PeriodAbsorbed` |

## Design decisions

- **Dates only (D-COR-3).** The decision takes a `TimePoint`, so nothing else can be corrected. `TargetNotCorrectable` and `HolderChangeNotAllowed` are therefore not needed.
- **Chains.** A Correct targets an Admit, an End or another Correct. The **root** of the chain (an Admit or End) decides which period it belongs to and which ordering rule applies.
- **Value precedence (§10a Correct (2)).** The value of a fact is the value of its latest **live** Correct, followed recursively along the chain. Otherwise it is the fact's own date.
- **An annulled target is accepted inert (§10a Correct (5); P-6, the act governs).** Only live corrections count, so a correction of an annulled fact takes effect only once the fact is live again (Annul(Annul) or Void(Annul)). No special case is needed.
- **Refusals (P-1, P-3, P-4, P-5):**
  - a voided target, or a chain whose root is voided → `CorrectOfVoidedFact`;
  - the same value as now → `ValueUnchanged`;
  - a corrected End at or before the period start, or a corrected Admit at or after its live End → `EndNotAfterPeriodStart`;
  - a value after the decision date → `EffectiveDateAfterDecisionDate`;
  - ∞ → `InvalidArgumentException`.
- **Trim on the cause (FP §12.4 C; P-2).** g2 runs **once, at decision time**, and its result is frozen on the `Correct`. Resolution never replays the policy. That keeps `knownAt` deterministic and makes CFC a matter of liveness.
- **ER is an invariant of G\* (BD-REV-ER record §3, canonical §6).** It is checked for **every** decision, last. So an End dated inside its own period's existing trim is refused too, and so is a **reversal** that would leave a live period absorbed, e.g. an appeal Annul(Annul) (review F-5). This reading is flagged to the PO in the plan.
- **One Correct can trim several periods** (FP §13.6 case B; review F-1). For example, after an Annul leaves a period with an empty BaseClaim, a correction can extend it on both sides. g2 keeps every consequence, one per trimmed period.
- **Not an `EffectiveDatedFact`.** `MembershipStorableValidTimeCoverageTest` pins the dated facts to Admit and End. The storable-range check for the corrected value (R-1a-6) belongs to COR-4.

## How it works

### The decision and its guard order

`Member::decideCorrect()` runs the guards in the order pinned pairwise by `CorrectDecisionTest::test_correct_guard_order`:

```php
self::requireFiniteDates($value, $decisionDate);                 // 1 ∞ → InvalidArgumentException
if ($this->usesFactId($id)) { … DuplicateFactId }                 // 2
if ($targetFact === null) { … TargetNotFound }                    // 3
if ($targetFact instanceof ReversalFact) { … CorrectionOfAnnulOrVoidRefused }   // 4
if (Resolution::isVoided($facts, $target) || Resolution::isVoided($facts, $root->id())) { … CorrectOfVoidedFact }  // 5 (P-1)
                                                                   // 6 an annulled target is NOT refused
if ($value->equals(Resolution::valueOf($facts, $target))) { … ValueUnchanged }   // 7 (P-3)
if (!$this->keepsPeriodOrdered($facts, $root, $value)) { … EndNotAfterPeriodStart }  // 8 (P-4)
if ($value->isAfter($decisionDate)) { … EffectiveDateAfterDecisionDate }          // 9 (P-5)
return $this->accept(new Correct(…, $this->trimConsequences($facts, $targetFact, $candidate)));  // 10 g2, then G* incl. ER
```

`TargetNotLive` never applies to a Correct: a target that is not live is either voided (→ 5) or annulled (accepted inert).

### Resolution, stage by stage

```text
1 live      no later live Annul / Void targets f
2 active    a live Correct is active iff its target is active; other live facts are active
3 value     latest live Correct of f (recursively), else f's own date
4 BaseClaim [value(Admit), max(start, value(latest live End)))   or [start, ∞)
5 LiveCut   ∪ over trims in force on the period: removed ∩ BaseClaim(cause's period)
6 lifetime  BaseClaim − LiveCut
```

A trim is **in force** when its cause (a Correct) is active and no live Correct supersedes it. It applies only within the **current** BaseClaim of its cause's period. So if the correcting period later shrinks, the trim shrinks with it.

### g2: what a correction trims

For a candidate Correct `c` on period `p`:

```text
Δ          = BaseClaim_after(p) − BaseClaim_before(p) − LiveCut_before(p)
own trims  = Δ ∩ lifetime_before(q)                for every other period q
inherited  = target's trims ∩ BaseClaim_after(p)   when c corrects a Correct
```

They are merged to at most one `TrimConsequence` per trimmed period. Only a Correct records consequences. An End only shrinks a claim. An Annul or Void that enlarges one (e.g. Annul of an End) creates none (D-COR-5 (A)), so G\* refuses the overlap instead.

### Example (from `CorrectConsequencesTest`)

```php
$h = MemberHistory::start();
$h->commit($h->admit('A0', 0, null, true));
$h->commit($h->end('E0', 'A0', 2));          // A0 = [0, 2)
$h->commit($h->admit('A1', 2, null, true));  // A1 = [2, ∞)

$decision = $h->correct('C1', 'E0', 4);      // the end of A0 was really 4
// accepted; Correct::consequences() = [TrimConsequence(A1, [2, 4))]  → PeriodTrimmed
$h->commit($decision);                       // A0 = [0, 4), A1 = [4, ∞)

$h->commit($h->annul('X1', 'C1'));           // CFC: A0 = [0, 2), A1 = [2, ∞)
$restore = $h->annul('X2', 'X1');            // revivedTrims() = [(C1, A1)]  → TrimRevived
```

### CFC+ and `TrimRevived`

`Member::accept()` compares `Resolution::trimsInForce()` before and after the fact. A trim in force after the fact but not before it, and not caused by the fact itself, is a revival. Typical causes:
- restoring a correction: Annul(Annul) or Void(Void);
- setting aside the correction that superseded it: Annul(C2) brings C1's trims back.

## How to use or extend

- **Deciding:** call `decideCorrect(FactId $id, FactId $target, TimePoint $value, TimePoint $decisionDate, Provenance $p, ?Instant $decidedAt)`. Append only an accepted decision, at `basedOnVersion() + 1` (Slice 1a).
- **Events (COR-3):**
  - `FactCorrected` comes from the `Correct` fact;
  - `PeriodTrimmed` comes from each `consequences()` item (cause = the Correct's id);
  - `TrimRevived` comes from `revivedTrims()`.
- **Persistence (COR-4):**
  - store `target`, `value` and the consequences (period + removed intervals) with the Correct;
  - extend `StorableValidTime` (and `MembershipStorableValidTimeCoverageTest`) for the corrected value;
  - **never recompute g2 on load**: the frozen consequences are the record;
  - meet the COR-4 entry criteria **V-13…V-15** in the roadmap verification table (`…20261016_2219_…_completion_plan.md` §3.2):
    - R-1a-6 for the corrected value;
    - INV-TGT at decode: a Correct that targets itself would make `Resolution::rootOf` recurse without end;
    - validation of the decoded consequences, including the two-period case.

## Testing

| Test | What it pins |
|---|---|
| `CorrectDecisionTest` (19) | refusals, P-1 / P-3 / P-4 / P-5, the annulled-inert acceptance, guard order pairwise, constructor guards |
| `CorrectResolutionTest` (8) | the corrected start or end, latest-wins and revert, chains, inert then revived, the term start, P-KNOWN |
| `CorrectConsequencesTest` (13) | g2 Δ and inheritance, clipping, CFC removal and revival, `TrimRevived` exactness, stage-2 activeness, **one Correct trimming two periods**, CX-B (INV-EP) |
| `CorrectAbsorptionTest` (7) | BD-REV-ER: the minimal case, the End-correction case, the End inside its own trim, a two-period trim that absorbs one, annul-first acceptance, the order in the enforcement set |
| `G6C2CorrectionOracleGoldenTest` | the PHP port reproduces the Python C-2 oracle stream (H = 5, L = 5) |
| `C2ExhaustiveDifferentialTest` | verdict, reason, lifetimes and consequences equal the port on all **277,835** decisions (139,722 accepted), 0 mismatches |
| `C2PropertyTest` | P-REV-V / P-REV-A, P-APPEAL, §5 (d), P-CFC by deletion (+ P-EQ), P-KNOWN vs the port; L = 4 |

Run them database-free:

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational
```

## Pitfalls

- **`isMember` means "in force"** (lifetime **and** term). A fixture admitted without a term is never a member. Use `admit('A1', $at, null, true)` for an open-ended term, or assert on `inLifetime`.
- **Live, not active, decides a value.** `valueOf` counts live corrections, so a correction of an annulled fact is already visible to the `ValueUnchanged` guard while it is inert.
- **A trim is clipped to its cause's current claim, not its own period's claim.** Do not subtract `lifetime` from `baseClaim` to get LiveCut; use `Resolution::liveCutOf()`.
- **An End or a reversal can be refused as `PeriodAbsorbed`.** That is ER as a G\* invariant, not a bug.
- **Bounded evidence has limits.** Both a stage-2 defect class (7 facts) and the two-period trim (6 facts) lie beyond the L = 5 differential. When you change g2 or LiveCut, add a targeted unit test from the oracle port; do not rely on the exhaustive test alone.
- **No future dates through a correction.** The decision date comes from the caller (D-5(a) Z1); the domain reads no clock.

## Traceability

PBDIGIT-327 · contract §3.3, §4, §5, §10a Correct (1)–(10), §14.4, §14.5, §14.7 · decisions BD-REV-ER (record §2–§3), D-COR-3, D-COR-2, D-COR-5 (A), P-1…P-6 (PO 2026-11-03) · FP §12.1 (CX-B), §12.3 (CFC), §12.4 (option C) · commits `58b89ab44` (step 3), `739875e1c` (step 4), `e5836a816` (step 5), `01fcbc9e3` (step 6), step 7 (this guide). Anonymity (ADR-T11) is untouched: membership facts carry no vote data.
