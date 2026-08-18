# `EM-DOM-001` Phase 1 — Domain Decision Map, ownership, causal model, RED plan

**Lane:** `EM-DOM-001` Domain lane (designated 2026-08-18) · **Authorization:** PO/ARB, `2026-08-18-EM-DOM-001-authorization-request.md`
**Phase:** 1 — **ANALYSIS / DESIGN ONLY. No production code was written. `app/`, `tests/`, repositories and protocol access are untouched; the domain core remains byte-identical to `1f4b4c5f`.**
**Status:** ✅ **ACCEPTED by the PO/ARB on 2026-08-18 — as an ANALYSIS DELIVERABLE and evidence base for the Architecture/ARB referral.**

> ### The acceptance act, recorded verbatim
>
> *"accept the EM-DOM-001 Phase-1 Domain Decision Map as an analysis deliverable and evidence base for the Architecture/ARB referral.*
>
> *This acceptance does not approve R-1, R-2, or R-3, does not authorize implementation, and does not resolve BND-1, BND-2, or BND-3.*
>
> *The Domain lane's proposals remain proposals pending the Architecture/ARB rulings.*
>
> ***PO/ARB: ACCEPTED.***"
>
> **⚠️ What this acceptance does NOT do, per its own terms:** ⛔ does **not** approve **R-1 / R-2 / R-3** — §6 remains a **proposal set** · ⛔ does **not** authorize implementation · ⛔ does **not** resolve **BND-1, BND-2 or BND-3**.
>
> **Accordingly: §§0–5, §7 and §8 are ACCEPTED EVIDENCE. §6 is an ACCEPTED-AS-RECORDED PROPOSAL, not an approved design** — and per the referral, *a Domain-lane proposal is evidence, never a premise.* **Phase 2 remains unstarted.**

---

# 0 · The central discovery

**The frozen core contains a FOURTH modelled concept that the Rule-8 gate did not examine, and it is the missing producer the gate was looking for.**

```php
// Condition/ElectionOperationalStatus.php — EM-GOV-059(b)/(c)
final readonly class ElectionOperationalStatus {
    private function __construct(private ?HaltedAtGate $haltedAtGate,
                                 private OperationalCondition $condition) {}
    public static function operative(): self
    public static function operativeHalted(HaltedAtGate $haltedAtGate): self
    public function becameInoperative(): self   // EM-GOV-059(b): RETAINS the halt
    public function restored(): self            // EM-GOV-059(c): returns to prior condition
    public function haltedAtGate(): ?HaltedAtGate
}
```

**It holds the `?HaltedAtGate`. It owns the halt↔overlay transitions. It is authorized. And it is unreachable:** zero references in `app/` outside its own file · **no repository** · no producer. Its absence from `app/` is deliberate — the `W-2` guard scans handler source for its type names.

> ## The gate asked *"where can an authoritative `HaltedAtGate` legitimately come from?"* — **the answer already exists in the model and has no identity, no persistence and no owner.**

**AG-1, AG-2, AG-3 each have a repository. The operational overlay has none.** `EM-ARCH-001` modelled it as a *type* (§2b), not as something with identity. **That, and not a missing concept, is the substance of DEP-5b.** ⚠️ **This states a REACHABILITY gap and deliberately does not assert what the overlay's correct boundary is** — see §8, BND-3.

**Corroborating evidence — the domain already produces lifecycle events from exactly these ingredients:**

```php
// Policy/ExpiryConsequence.php (P-6) — a DOMAIN policy, already consuming the halt fact
public static function onHaltedRecoveryExpiry(
    ElectionId $electionId, HaltedAtGate $halt, RecoveryProcess $process,
    RecordedInstant $reportedAt, bool $recoverySucceeded,
    OperationalCondition $operational,
): RecoveryPeriodExpired
```

**Producer census of the four lifecycle events:**

| Event | Produced by | |
|---|---|---|
| `RecoveryPeriodExpired` | **P-6** (domain policy) | ✅ the pattern |
| `ElectionCancelledOnRestorationExpiry` | **P-6** (domain policy) | ✅ the pattern |
| `ElectionBecameInoperative` | `RecordVacancyEventHandler.php:142` | 🔴 **application** |
| `ElectionRestored` | `FillCommitteeSeatHandler.php:156` | 🔴 **application** (DEP-7) |
| `AppointmentsAwaited` | *nothing, anywhere* | ⚠️ unproduced |

**The domain has a working pattern for producing a lifecycle fact from `(halt fact, operational condition, recovery process)`. The two events the ADRs are about are precisely the two that bypass it.** The gap is not conceptual novelty; it is an **unfinished application of the model's own pattern**.

---

# 1 · Domain Decision Map — DEP-1 … DEP-6

Each dependency answers the ten commissioned questions. **Q3/Q4 are answered from the code, not from intent.**

## DEP-1 — absent `ElectionCommittee`

| | |
|---|---|
| **① invariant** | A command that acts upon the constituted body **requires that body to exist**. Its absence is a **violation of required-existence** (ADR-1 §6 row 1), never a lifecycle state — the Committee is constituted **once**, during Election Appointment (`EM-GOV-026`, `056`), so after appointment there is no legitimate window in which it is absent. |
| **② owner** | **`ElectionCommittee` (AG-1)** — it owns constitution, size and vacancy (I-1…I-5). Required-*existence* is the same context's invariant: nothing else knows when constitution has occurred. |
| **③ existing concept** | `ElectionCommittee::constitute()`; `CommitteeTooSmall`; `UnknownCommitteeSeat`. **`UnknownCommitteeSeat` is the intra-aggregate analogue** — an unknown *seat* already has a named domain meaning. |
| **④ authorized contract?** | 🔴 **No.** AG-1 exposes no statement about its own required existence, and `ElectionCommitteeRepository::find()` returns `?ElectionCommittee` with the meaning of `null` unstated. |
| **⑤ genuinely missing** | A **named domain meaning for "the constituted body this command requires does not exist"** — at the same level as `UnknownCommitteeSeat` is for a seat. |
| **⑥ legitimate states** | before appointment: **no Committee** (and no OperatingCore command is legitimate) · after appointment: **exactly one**, permanently. |
| **⑦ absence means** | **before appointment:** the election is not yet constituted — but **no OperatingCore command is reachable then** · **after appointment:** **impossible**; absence is a violation. |
| **⑧ causal need** | none. This is an existence invariant, not a causal one. |
| **⑨ representation** | A domain-named refusal of the same family as `UnknownCommitteeSeat`, raised **by the domain**, not by a handler's `InvalidArgumentException`. *(Proposal §6.)* |
| **⑩ RED** | *"Acting on an election whose Committee was never constituted is refused with the domain's own named meaning, not a generic argument error."* |

## DEP-2 — the two `AcceptanceDecision` meanings ⚠️ **contains a boundary question**

| | |
|---|---|
| **① invariant** | The **same** absent reference carries **two different meanings** discriminated by lifecycle phase: *required-but-absent* = **violation**; *phase-not-yet-reached* = **legitimate lifecycle state** (ADR-1 §6 rows 2–3). |
| **② owner** | ⚠️ **UNRESOLVED, and not resolvable inside this slice — see §8, BND-1.** The *decision* is AG-2's. **The discriminator is the election's lifecycle PHASE, and the OperatingCore contains no phase concept at all.** `ElectionLifecycleState` exists — in **`app/Domain/Election/Enum/`**, a different bounded context. |
| **③ existing concept** | `GateDesignation` (which gate), `GateIntervalState` (the gate's own verdict). **Neither is a lifecycle phase.** |
| **④ authorized contract?** | 🔴 **No** — and the *reason* is an ownership gap, not an omission. |
| **⑤ genuinely missing** | **The phase discriminator itself**, plus the named meanings that hang off it. |
| **⑥ legitimate states** | phase-not-yet-reached (**no decision may exist**) · phase-reached-and-established (**exactly one per gate per election**) · phase-reached-but-absent (**impossible**). |
| **⑦ absence means** | state 1 → **legitimate** · state 3 → **violation**. **The domain cannot currently tell these apart, which is why UC-3 guesses.** |
| **⑧ causal need** | none directly — but it is the **precondition** for DEP-6, because "which gate does restoration return to" presumes a decision legitimately exists. |
| **⑨ representation** | ⛔ **Deliberately not proposed.** With ownership unresolved, any representation I choose would *invent* the owner — the exact prohibition in the authorization. |
| **⑩ RED** | ⛔ **Not plannable yet.** A RED test would encode the ownership decision. **See BND-1.** |

## DEP-3 — a policy contract that speaks absence ⚠️ **the gate's wording was too strong**

| | |
|---|---|
| **⚠️ correction** | The gate said *"no policy P-1…P-7 accepts or returns an absence concept."* **Too strong.** **P-4 already returns absence:** `InoperativeOnset::onsetFor(...): ?RecordedInstant` — *"or null while the Committee remains able to function."* `ElectionOperationalStatus::haltedAtGate(): ?HaltedAtGate` likewise. |
| **① invariant** | **Absence must carry a NAMED domain meaning, not an untyped `null`** (ADR-1 constraint ⑥). The real gap is **not that policies are absence-blind — it is that every absence in the model is expressed as bare nullability, so its meaning lives in a docblock rather than in the type.** |
| **② owner** | Each policy's own subject context. **DEP-3 is not a separate concept; it is a property the DEP-1/DEP-2/DEP-6 representations must satisfy.** |
| **③ existing concept** | P-4's `?RecordedInstant`; the overlay's `?HaltedAtGate`. **Both are precedents for the shape and both leave the meaning unnamed.** |
| **④ authorized contract?** | 🟡 **Partially** — nullable returns are established and authorized. **No named absence meaning exists anywhere.** |
| **⑤ genuinely missing** | Named meanings, not new policies. |
| **⑥–⑧** | inherited from whichever dependency the policy serves. |
| **⑨ representation** | **DEP-3 is discharged BY DEP-1 and DEP-6, not separately.** ⛔ **Do not create an absence-policy of its own** — that would be a technical mechanism satisfying a checklist. |
| **⑩ RED** | No independent RED. Its satisfaction is asserted **within** the DEP-1 and DEP-6 tests. |

## DEP-4 — recovery-origin ⚠️ **substantially narrower than the gate stated**

| | |
|---|---|
| **⚠️ correction** | **`PeriodKind` already discriminates the causal class of a recovery process**, 1:1: `HaltedElectionRecovery` ⇒ arose from a **halt**; `CommitteeRestoration` ⇒ arose from the **Inoperative** condition. `RecoveryProcess::kind()` is public, and **P-6 already branches on it** to select the correct consequence and refuses the wrong pairing (`ExpiryConsequencePreconditionNotMet`). |
| **① invariant** | A recovery process's **causal class** must be recorded and must gate which consequences may fire. **✅ This invariant already exists and is already enforced.** |
| **② owner** | **`RecoveryProcess` (AG-3)** — uncontested. |
| **③ existing concept** | **`PeriodKind`** + P-6's precondition refusals. |
| **④ authorized contract?** | 🟢 **YES, for the causal class.** 🔴 **No, for the specific originating fact** (*which* gate this halted-recovery arose from). |
| **⑤ genuinely missing** | **Only the link to the specific originating halt** — and see ⑨, because that may be *correctly* missing. |
| **⑥ legitimate states** | halted-recovery (origin: a halt) · committee-restoration (origin: Inoperative). **No third kind exists.** |
| **⑦ absence means** | **no recovery process running = legitimate lifecycle state** (ADR-1 §6 row 4). **This is DEP-10 and it is protected.** |
| **⑧ causal need** | The halted-recovery process must be attributable to the halt it arose from. |
| **⑨ representation** | ⛔ **AG-3 must NOT carry a copy of the halt.** The overlay already holds the authoritative `?HaltedAtGate`; duplicating it onto AG-3 would create **two owners of one fact**, violating ADR-2 (g) — *"only its owning bounded context may create, validate, or change its meaning."* **Attribution is by collaboration at the transition (`PeriodKind` + the overlay's halt), exactly as P-6 already does — it takes both and stores neither.** |
| **⑩ RED** | *"A halted-recovery consequence cannot be evaluated without the halt fact, and the halt fact is supplied by the overlay — not stored on the recovery process."* (Partly already proven by `ConditionSemanticsTest::test_expiry_consequences_are_rule_evaluated_never_clock_produced`.) |

## DEP-5b — the producer for P-7 ⚠️ **re-attributed**

| | |
|---|---|
| **⚠️ correction** | The gate said the missing piece is *"an authoritative producer/persistence path for `HaltedAtGate`."* **Precisely: the missing piece is IDENTITY AND PERSISTENCE FOR THE OVERLAY THAT ALREADY HOLDS IT.** `HaltedAtGate` needs no producer of its own — `ElectionOperationalStatus::operativeHalted()` is that producer, already authorized, already written. |
| **① invariant** | The election's operational status — **the retained halt and the Operative/Inoperative condition as two orthogonal facts** — is **recorded domain truth**, not a value reconstructed per request. `EM-GOV-059(b)`: becoming Inoperative **retains** the halt. |
| **② owner** | **The operational overlay itself** — it is the only concept that owns the halt↔condition relationship, and `EM-ARCH-001 §2b/§5d` already assigns it that role. |
| **③ existing concept** | **`ElectionOperationalStatus`** + `OperationalCondition` + `HaltedAtGate`, and `ConditionSemanticsTest::test_halted_and_inoperative_are_representable_together_and_the_halt_is_retained` **already proves the semantics**. |
| **④ authorized contract?** | 🟡 **The TYPE is authorized and complete. It has no identity, no repository and no producer.** A value object no one can load is not a contract. |
| **⑤ genuinely missing** | **An election-scoped identity and a retrieval contract for the overlay**, so recorded operational truth can be loaded. ⚠️ **Two things are NOT decided here:** whether that standing is *aggregate* standing (**BND-3**), and whether this slice may introduce the contract at all (**BND-2** — the authorization forbids modifying repositories, and the lane will not read a NEW domain repository interface as inside or outside that prohibition). |
| **⑥ legitimate states** | `Operative` ∧ no halt · `Operative` ∧ halted · `Inoperative` ∧ no halt · `Inoperative` ∧ halted. **All four are legitimate** (`EM-GOV-070`: OPEN ∧ INOPERATIVE is valid). |
| **⑦ absence means** | ⚠️ **An election with no recorded operational status = never appointed.** For a constituted election, `Operative` ∧ no halt is the **positive** default and must be **recorded as such**, never inferred from a missing row. |
| **⑧ causal need** | P-7 requires a `HaltedAtGate`. **It must come from the overlay's recorded state and from nowhere else.** |
| **⑨ representation** | The overlay **requires aggregate-level standing, OR another explicitly authorized identity/persistence model** — election-scoped, retrievable — so that `haltedAtGate()` can feed P-7 unchanged. ⚠️ **The aggregate BOUNDARY is not settled by this analysis and is subject to architecture confirmation** (see §8, BND-3): the evidence establishes that the overlay needs *identity and retrieval* to be recorded truth, and **identity + persistence does not by itself imply "aggregate."** ⛔ **P-7 is consumed, never duplicated.** |
| **⑩ RED** | *"P-7's input is obtained from recorded operational status; a halt that was never recorded cannot be manufactured, and an election that is Inoperative retains its halt across `becameInoperative()` → `restored()`."* |

## DEP-6 — why Restoration is permitted, incl. `w8` ⚠️ **the state already represents it; the EVENT cannot**

| | |
|---|---|
| **① invariant** | **Two invariants, deliberately kept apart:** **(A-perm)** restoration is permitted **iff** the election is `Inoperative` **and** the condition that caused it has ended — i.e. the Committee is **no longer** unable to function (`EM-GOV-065`; `UnableToFunction`; P-4's mirror) · **(A-eff)** restoration **returns the election to its prior condition, retaining any halt** (`EM-GOV-059(c)`) and **never deems a gate decided** (`EM-GOV-060`). |
| **② owner** | **(A-perm): the overlay owns the condition; AG-1 owns the arithmetic.** A collaboration — and **not** AG-3's: a `RecoveryProcess` bounds *how long* restoration may take, and **never** licenses it. **(A-eff): the overlay, via `restored()`; the target via P-7.** |
| **③ existing concept** | `restored()` · `ElectionCommittee::unableToFunction()` · `UnableToFunction` (P-3) · P-7 · `ConditionSemanticsTest::test_resumption_returns_to_the_unresolved_gate_never_a_deemed_decision`. |
| **④ authorized contract?** | 🟢 **The STATE transition exists and is authorized** (`restored()`). 🔴 **No contract answers *permitted?*** — UC-3 currently asks AG-1 whether the Committee can function and then decides for itself. 🔴 **No producer policy for `ElectionRestored`** (contrast P-6). |
| **⑤ genuinely missing** | **(i)** a domain statement of *"restoration is permitted"* · **(ii)** a **domain producer** for `ElectionRestored`, analogous to P-6 · **(iii)** an **event shape able to express the `w8` path**. |
| **⑥ legitimate states** | see DEP-5b's four. Restoration applies **only** from `Inoperative`. |
| **⑦ absence means** | **no `RecoveryProcess` ⇒ nothing to pause — legitimate (DEP-10, PROTECTED)** · **no `HaltedAtGate` ⇒ the election was never halted; restoration returns it to plain `Operative` — legitimate, and this is `w8`.** |
| **⑧ causal need** | **`w8` decomposes.** ⚠️ **`Restoration → RecoveryProcess → originatingGate` is REFUTED by the model, not merely unproven:** `restored()` transitions the **overlay**, and the overlay's halt is **independently nullable**. `PeriodKind::CommitteeRestoration` exists **without** any halt. **So restoration is causally tied to the INOPERATIVE condition ending, and to the halt only for the *target* question.** |
| **⑨ representation** | The `w8` path **is already representable in state** — `haltedAtGate === null`, `condition: Inoperative` → `restored()` → `Operative`, no gate. **It is unrepresentable in the EVENT**: `ElectionRestored.$returnsToGate` is **non-nullable**, so a restoration with no halt cannot be recorded without fabricating a gate. **⛔ That is the whole of ADR-2 (h)'s gap, and it is an EVENT-SHAPE gap.** |
| **⑩ RED** | Four: *permitted only from `Inoperative`* · *permitted only once the Committee can function again* · *a halted election restores to its halt via P-7* · **`w8`: an Inoperative election that was never halted restores with NO gate, and no gate is fabricated.** |

---

# 2 · Bounded-context ownership map

```
                        ┌───────────────────────────────────────────┐
                        │  Election / OperatingCore  (this slice)   │
                        ├───────────────────────────────────────────┤
   AG-1 ElectionCommittee ── constitution · seats · vacancy · I-1..I-6
        owns: required-existence (DEP-1) · unableToFunction arithmetic
                        │
   AG-2 AcceptanceGateDecision ── positions · threshold · I-7..I-12
        owns: the decision. does NOT own lifecycle phase.  ⚠️ DEP-2
                        │
   AG-3 RecoveryProcess ── clocks · intervals · PeriodKind
        owns: causal CLASS of a recovery period (DEP-4 ✅ exists)
        does NOT own: restoration permission · the halt fact
                        │
   ⬛ OPERATIONAL OVERLAY  ElectionOperationalStatus     ← no identity today
        ⚠️ boundary NOT settled by this analysis — BND-3
        owns: the retained halt (?HaltedAtGate) · Operative/Inoperative
        owns: restoration EFFECT (restored())
        ⇒ the legitimate supplier of P-7's input          DEP-5b · DEP-6
                        │
   Policies P-1..P-7 ── stateless. P-6 is the precedent producer.
                        └───────────────────────────────────────────┘
                                        ▲
                    ⚠️ BND-1: lifecycle PHASE lives outside
                                        │
        ┌───────────────────────────────────────────────────────┐
        │ app/Domain/Election/ (legacy context)                 │
        │  ElectionConstitution (const RULES array)             │
        │  ElectionLifecycleState (enum)                        │
        │  ⚠️ contains NO halt / gate / operative vocabulary    │
        └───────────────────────────────────────────────────────┘
```

⚠️ **Documentation-vs-architecture discrepancy (recorded, not repaired).** `HaltedAtGate`'s own docblock asserts: *"Lifecycle transitions themselves stay canonically homed in `ElectionConstitution`."* **`app/Domain/Election/Constitution/ElectionConstitution.php` is a `const RULES` registry keyed on `ElectionLifecycleState` and contains zero halt, gate, operative or inoperative vocabulary** (verified by grep). **The asserted canonical home does not contain the concept.** Either the docblock names the wrong home, or OperatingCore's lifecycle owner is genuinely unassigned. **Governance/ARB owns this; the Domain lane records it.**

---

# 3 · Causal relationship model — Restoration / RecoveryProcess

**The three questions have three different answers, and collapsing any two is refuted by the code.**

```
   C. Why did a RecoveryProcess exist?          A. Why is Restoration permitted?
      owner: AG-3 (PeriodKind)                     owner: overlay condition + AG-1 arithmetic
      HaltedElectionRecovery ⇐ a halt              iff Inoperative AND Committee can function again
      CommitteeRestoration   ⇐ Inoperative                        │
                    │                                            │
                    │  bounds HOW LONG                            │ licenses the act
                    │  never licenses                             ▼
                    └──────────────────────────▶      overlay.restored()   (EM-GOV-059(c))
                                                                 │
                                                                 ▼
                                                  B. Where does Restoration return?
                                                     owner: P-7 on the RETAINED ?HaltedAtGate
                                                     halt present → that gate
                                                     halt absent  → NO gate   ← w8
```

**Path A (halt → recovery → restoration) and Path B (`w8`) are not two variants of one chain.** They differ in **whether a halt was ever recorded**, which the overlay tracks **independently** of the `Inoperative` condition. **`PeriodKind::CommitteeRestoration` exists with no halt whatsoever** — the model already contains a restoration lineage that never passed through a halt.

> **Therefore `Restoration → RecoveryProcess → originatingGate` is REFUTED as the universal causal model.** Not "unproven" — the model already exhibits a counter-instance.

---

# 4 · Explicit `w8` representation analysis

| Layer | Can it express `w8`? |
|---|---|
| **State** — `ElectionOperationalStatus` | 🟢 **YES, today.** `haltedAtGate === null` ∧ `Inoperative` → `restored()` → `Operative`, no gate. **Nothing to add.** |
| **Policy** — P-7 `ResumptionTarget` | 🟢 **Correctly inapplicable.** P-7 requires a `HaltedAtGate`; with no halt there is **no resumption target to resolve**. **Its signature is right and must not be loosened** — making it accept null would let it answer a question that has no answer. |
| **Event** — `ElectionRestored` | 🔴 **NO.** `public GateDesignation $returnsToGate` is **non-nullable**. A restoration with no halt **cannot be recorded** without fabricating a gate. |
| **Producer** | 🔴 **None.** Today `FillCommitteeSeatHandler:156` constructs the event and supplies `$decision->gate()` — **the fabrication ADR-2 (b) and (h) prohibit by name.** |

> ## `w8` is a two-line gap in the EVENT layer, not a missing domain concept. ADR-2 (h)'s *"domain-model gap"* is precisely: **the event shape cannot express a state the model can already hold.**

⛔ **Rejected representations, and why — each is a fabrication ADR-2 (b)/(h) names:** a sentinel `GateDesignation` · an *"unknown"* value · a synthesized `HaltedAtGate` · a synthesized `RecoveryProcess` · **and one more, added here: loosening P-7 to accept `null`** — that would move a *"why"* answer into a *"where"* policy and collapse questions A and B.

---

# 5 · P-7 dependency analysis

| | |
|---|---|
| **Exists?** | 🟢 Yes — `ResumptionTarget::resolve(HaltedAtGate): GateDesignation`, authorized (`EM-GOV-059(c)`, `060`; `EM-ARCH-001 §2e`). |
| **Correct?** | 🟢 **Yes, and its signature is load-bearing.** Requiring a `HaltedAtGate` is what makes *"restoration never deems a gate decided"* structurally true. |
| **Reachable?** | 🔴 **No.** Its only repository-wide call site is `ConditionSemanticsTest:175`. No `app/` code calls it. |
| **Why unreachable** | Its input lives on a type with **no identity and no persistence** (DEP-5b). |
| **Legitimate supplier** | **`ElectionOperationalStatus::haltedAtGate()`, and nothing else.** It is the only concept `EM-GOV-059(b)` charges with retaining the halt. |
| **Illegitimate suppliers** | ⛔ constructing one in Application (DEP-9) · ⛔ protocol reconstruction (DEP-8) · ⛔ copying the halt onto AG-3 (two owners — ADR-2 (g)) · ⛔ `$decision->gate()` (DEP-7). |
| **Action** | **CONSUME unchanged.** ⛔ No parallel resolver, no duplicate, no signature change, no null-tolerant overload. |

---

# 6 · Domain representation proposal — *only* what the invariants above require

**Offered as a proposal for review. Not implemented. Deliberately minimal: three changes, each traceable to an invariant established above.**

| # | Change | Discharges | Justification |
|---|---|---|---|
| **R-1** | Give the operational overlay **identity and a retrieval contract** — election-scoped — so recorded operational status can be loaded and saved. **The type itself is unchanged.** **The form of that standing (aggregate or otherwise) is left to architecture.** | DEP-5b, and unblocks DEP-6 | The halt must be *recorded truth*, not a per-request value (`EM-GOV-059(b)`). ⚠️ **Gated on BND-2 and BND-3.** |
| **R-2** | A **domain policy that produces `ElectionRestored`**, taking the recorded operational status and the Committee, **stating the permission invariant and resolving the target through P-7** — the shape P-6 already establishes. | DEP-6 (i)+(ii), DEP-3 | The model's own precedent; moves the decision out of the handler with no new mechanism. |
| **R-3** | Make **`ElectionRestored` able to express a restoration with no resumption target**, with the *no-target* case carrying a **named domain meaning** — never a sentinel, never *"unknown"*. | DEP-6 (iii) = ADR-2 (h), DEP-3 | The only identified way to record `w8` without fabrication. |

**Deliberately NOT proposed:** ⛔ nothing for **DEP-2** (ownership unresolved — BND-1) · ⛔ nothing for **DEP-4** beyond what exists (`PeriodKind` suffices; a copy would violate ADR-2 (g)) · ⛔ **no absence policy** for DEP-3 (discharged by R-2/R-3) · ⛔ **no change to P-7** · ⛔ **no change to AG-3** · ⛔ **nothing for DEP-1's *call sites*** — R-1..R-3 give DEP-1 its domain meaning; **rewiring UC-1/UC-2 is Application work and is not authorized.**

---

# 7 · Domain RED-test plan

**Every test below expresses a domain invariant established in §1. None targets an Application test.** ⛔ **`AbsentAggregateReferenceRedTest` is not in this plan.**

| # | RED test | Invariant | Dep |
|---|---|---|---|
| **T-1** | acting on an election whose Committee was never constituted is refused with the domain's **own named meaning** | required-existence | DEP-1 |
| **T-2** | recorded operational status is **retrievable**, and `Operative ∧ no halt` is a **positively recorded** state, never an inferred blank | recorded truth | DEP-5b |
| **T-3** | the halt **survives** `becameInoperative()` → `restored()` | `EM-GOV-059(b)/(c)` | DEP-5b |
| **T-4** | P-7's input comes from recorded status; **an unrecorded halt cannot be manufactured** | supplier exclusivity | DEP-5b |
| **T-5** | restoration is refused unless the election **is `Inoperative`** | (A-perm) | DEP-6 |
| **T-6** | restoration is refused while the Committee **remains unable to function** | (A-perm), `EM-GOV-065` | DEP-6 |
| **T-7** | a **halted** election restores **to its halted gate via P-7**, and **no gate is deemed decided** | (A-eff), `EM-GOV-060` | DEP-6 |
| **T-8** | **`w8`:** an `Inoperative` election that was **never halted** restores with **no resumption target**, and **no gate is fabricated** | `w8` | DEP-6 |
| **T-9** | a halted-recovery consequence is evaluated from `PeriodKind` **plus** the supplied halt; **AG-3 stores no halt** | one owner per fact | DEP-4 |
| **T-10** | **regression lock:** `RecoveryProcess` absence remains *"nothing to pause"* — **both handlers, all four sites, unchanged** | DEP-10 **PROTECTED** | DEP-10 |

**T-10 note — the protected set is larger than the authorization states.** The authorization named two files; **there are four guarded sites across them**, and all four are the legitimate-absence shape:

| File | Lines |
|---|---|
| `FillCommitteeSeatHandler.php` | **174** *(no allowance running → nothing to pause)* · **192** *(no halted period → nothing to resume)* |
| `RecordVacancyEventHandler.php` | **161** *(nothing to pause)* · **174** *(no allowance running)* |

**T-10 locks all four.** *(Reported per the boundary rule; not an extension — it protects more, not less.)*

---

# 8 · Scope / authorization check

## ✅ Inside the authorization

DEP-1, DEP-3, DEP-4, DEP-5b, DEP-6 analysed · P-7 consumed not duplicated · `w8` treated as first-class and its representation located · DEP-10 preserved and its protected set widened · no Application change · no repository change · no protocol read · no provenance reconstruction · no production code.

## 🛑 BOUNDARY — three items I am STOPPING on rather than deciding

### **BND-1 — DEP-2's discriminator is a lifecycle PHASE, which the OperatingCore does not own**

**Discovered requirement.** Discriminating *required-but-absent* from *phase-not-yet-reached* requires a lifecycle-phase concept. **The OperatingCore has none.** `ElectionLifecycleState` and `ElectionConstitution` live in **`app/Domain/Election/`** — a different context — and contain **no** halt/gate/operative vocabulary.

**Why outside:** resolving it needs one of — (a) a **new OperatingCore phase concept** (a new strategic concept, not a representation of an approved invariant); (b) a **Published-Language interaction** with the legacy context (a **context-map change**, requiring the Phase-2 Strategic-DDD answers); (c) a ruling that AG-2's *establishment* is itself the phase marker (a **governance** reading of ADR-1 rows 2–3, not a domain-lane choice).

**Depends on:** ADR-1 §6 rows 2–3 · the `EM-ARCH-001` context map · `AIP-14`.
**Additional authorization required:** a **strategic/ownership ruling naming the owner of lifecycle phase for the OperatingCore** — then DEP-2 becomes representable. ⛔ **I have proposed no representation and planned no RED test for DEP-2.** Any choice I made here would invent the owner.

### **BND-2 — R-1 requires a persistence contract, and the authorization forbids "modify repositories"**

The overlay needs **identity and retrieval** to be recorded truth. The prohibition list says *"modify repositories."* **Whether introducing a NEW domain repository interface for a NEWLY-identified aggregate is "modifying repositories" (forbidden) or "the domain representation required by ADR-1/ADR-2" (authorized) is not mine to read.**

**Depends on:** the authorization's prohibition list · ADR-2 (e) *"confirmation that the resulting contract can be consumed by the Application layer"* — which presupposes a loadable contract · `EM-ARCH-001 §2b`, which modelled the overlay as a type and not an aggregate.
**Additional authorization required:** an explicit statement that **R-1 (a new domain-side identity + retrieval contract for the operational overlay, with no infrastructure and no adapter) is in scope** — or a direction to solve DEP-5b another way.

**⚠️ Consequence, stated plainly: if BND-2 is answered "out of scope", then DEP-5b and DEP-6 cannot be discharged at all, because P-7's input has no other legitimate supplier.** Every alternative supplier is an already-prohibited DEP-8/DEP-9/DEP-7 path.

### **BND-3 — the overlay's aggregate BOUNDARY is not settled by this analysis**

**Raised on reviewer challenge and adopted: identity + persistence does NOT by itself imply "aggregate."**

This analysis establishes that the overlay must become **retrievable recorded truth** (`EM-GOV-059(b)` — the halt is *retained*, not recomputed). **It does not establish that the correct model is a fourth aggregate.** The earlier wording *"the overlay becomes the fourth aggregate"* asserted more than the evidence supports and has been withdrawn.

**Why it must not be settled here:** the overlay's boundary depends on **who owns lifecycle transitions for the OperatingCore** — and that is exactly the question the `HaltedAtGate`/`ElectionConstitution` discrepancy (§2) leaves open. **A boundary chosen before that ownership is resolved would fix the wrong seam.** Candidate readings the lane names and does **not** choose: a distinct aggregate · a part of an existing lifecycle aggregate not yet present in the OperatingCore · a projection over recorded lifecycle facts.

**Depends on:** the §2 discrepancy · `EM-ARCH-001 §2b/§5d` · `EM-GOV-059(b)/(c)`.
**Additional authorization required:** an **architecture confirmation of the overlay's boundary and lifecycle ownership** before R-1 takes any concrete form.

## Corrections this phase makes to the Rule-8 gate

| Gate statement | Phase-1 finding |
|---|---|
| *"no policy accepts or returns an absence concept"* (DEP-3) | **Too strong.** P-4 returns `?RecordedInstant`. The gap is **unnamed** absence, not absent absence. |
| *"no recovery-origin invariant"* (DEP-4) | **Too strong.** `PeriodKind` already records the causal class and P-6 already enforces it. Residue: the specific originating fact — **which must NOT be copied onto AG-3.** |
| *"an authoritative producer/persistence path for `HaltedAtGate`"* (DEP-5b) | **Re-attributed.** `ElectionOperationalStatus::operativeHalted()` **is** the producer. Missing: **the overlay's identity and persistence.** |
| *"no restoration-permission concept"* (DEP-6) | **Narrowed.** The **state** transition exists (`restored()`); the **permission statement** and the **producer** are missing, and `w8` fails **only** in the event shape. |
| *"`ElectionRestored.$returnsToGate` non-nullable is the gap"* | **Confirmed, and it is the WHOLE of the `w8` gap.** |
| DEP-10 protected sites: 2 files | **4 guarded sites** across those 2 files. T-10 locks all four. |

**Net effect: the slice is smaller and better-anchored than the gate projected — three changes (R-1…R-3), one blocked on ownership (DEP-2), one already satisfied (DEP-4's core). R-1's FORM remains open (BND-3).**

---

# STOP

**Phase 1 ends here, as authorized.** No production code · no RED test written · no domain file modified · core byte-identical to `1f4b4c5f`.

**Awaiting:** review and explicit acceptance of this analysis · rulings on **BND-1**, **BND-2** and **BND-3**. **Implementation begins only after all three.**

**Traceability:** ADR-1 §6 · ADR-2 §6 (a)–(h) · `EM-DOM-001` authorization + Annotations A/B · Rule-8 gates `7514f145`/`74fcf5e5`/`c4828cdd` · `EM-GOV-026`, `056`, `057`, `059(b)/(c)`, `060`, `062`, `063`, `065`, `066`, `068`, `069`, `070` · `EM-ARCH-001 §2b/§2c/§2e/§5d` · P-3/P-4/P-6/P-7 · `ConditionSemanticsTest` · `1f4b4c5f`.
