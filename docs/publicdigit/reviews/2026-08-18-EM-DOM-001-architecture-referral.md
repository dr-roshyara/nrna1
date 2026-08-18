# `EM-DOM-001` → Architecture / ARB referral — three questions the Domain lane will not answer

**Raised by:** the `EM-DOM-001` Domain lane · 2026-08-18 · **Source:** Phase-1 deliverable `docs/publicdigit/architecture/2026-08-18-EM-DOM-001-phase1-domain-decision-map.md` §8
**Status:** 🟡 **REFERRED — no lane designated. NOT a ruling. NOT an authorization.**

> ⚠️ **The Domain lane has stopped on all three. It proposes no answers here, and none of the three may be resolved by whoever implements.** Each would either invent an owner or fix a seam the evidence does not locate.

## Standing of Phase 1 — reviewed, NOT accepted

A reviewer assessed Phase 1 as *"a strong Domain-lane result"* and stated: ***"Approve the Phase-1 report as an analysis deliverable, but do not approve R-1/R-2/R-3 for implementation yet."*** **That is a recommendation in the reviewer's own framing (*"My recommendation"*), addressed to the PO/ARB.** Therefore:

⬜ **Phase 1 is NOT accepted** — acceptance is the PO/ARB's act (`EP-02`; engineering never accepts its own work).
⬜ **R-1, R-2, R-3 are NOT approved for implementation.**
✅ **One substantive challenge from that review WAS adopted** — see BND-3 below; the Phase-1 document was corrected.

## The three referred questions

| | Question | Owner named by the reviewer |
|---|---|---|
| **BND-1** | **Who owns "lifecycle phase" for the OperatingCore** — the discriminator that separates ADR-1 §6 row 2 (*required-but-absent* = violation) from row 3 (*phase-not-yet-reached* = legitimate)? | Architecture / ARB |
| **BND-2** | Is a **new domain identity + retrieval contract** for the operational overlay **inside** the `EM-DOM-001` authorization, given its *"do not modify repositories"* prohibition? | PO/ARB + Architecture |
| **BND-3** | What is the operational overlay's **correct boundary and lifecycle ownership**? *(Raised by the reviewer; the lane's earlier "fourth aggregate" claim is withdrawn.)* | Architecture / ARB |

## BND-1 — evidence

The OperatingCore contains **no lifecycle-phase concept**. `GateDesignation` names *which gate*; `GateIntervalState` is *the gate's own verdict*; neither is a phase. `ElectionLifecycleState` and `ElectionConstitution` exist in **`app/Domain/Election/`** — a different context — and **`ElectionConstitution` is a `const RULES` registry containing zero halt, gate, operative or inoperative vocabulary** (verified by grep).

**Three readings, none chosen by the lane:** (a) a new OperatingCore phase concept — a **new strategic concept**, not a representation of an approved invariant · (b) a **Published-Language** interaction with the legacy context — a **context-map change** requiring the Phase-2 Strategic-DDD answers · (c) a governance ruling that **AG-2's establishment is itself the phase marker** — a reading of ADR-1 rows 2–3, not a domain choice.

⛔ **The lane proposed no DEP-2 representation and planned no DEP-2 RED test.** Importing `ElectionLifecycleState` and declaring it the answer would settle ownership by convenience.

## BND-2 — evidence, and why it is blocking

```
No identity/retrieval for the overlay
        ↓
no retrievable recorded operational status
        ↓
no legitimate supplier of HaltedAtGate
        ↓
P-7 ResumptionTarget cannot be consumed
        ↓
DEP-5b and DEP-6 cannot be discharged
```

**Every alternative supplier is already prohibited:** app-side construction (DEP-9) · protocol reconstruction (DEP-8) · `$decision->gate()` (DEP-7) · copying the halt onto AG-3 (two owners — ADR-2 (g)).

**The tension is textual:** the authorization forbids *"modify repositories"*, while ADR-2 (e) requires *"confirmation that the resulting contract can be consumed by the Application layer"* — which presupposes a loadable contract. ⚠️ **If BND-2 is answered "out of scope", then DEP-5b and DEP-6 are not merely deferred — they are unachievable under the current prohibition set, and that should be an explicit decision rather than a discovered dead end.**

## BND-3 — what the evidence does and does not establish

**Establishes:** the overlay must be **retrievable recorded truth**, because `EM-GOV-059(b)` *retains* the halt across `becameInoperative()` and `restored()` — a value recomputed per request cannot retain anything.
**Does NOT establish:** that the correct model is a fourth aggregate. **Identity + persistence does not imply "aggregate."**

**Why it cannot be settled before BND-1:** the overlay's boundary depends on **who owns lifecycle transitions** — precisely what the `HaltedAtGate` / `ElectionConstitution` discrepancy leaves open. **A boundary chosen first would fix the wrong seam.** Candidate readings the lane names and does not choose: a distinct aggregate · part of a lifecycle aggregate not yet present in the OperatingCore · a projection over recorded lifecycle facts.

## The accompanying discrepancy (Architecture / ARB)

`Condition/HaltedAtGate.php`'s docblock asserts: *"Lifecycle transitions themselves stay canonically homed in `ElectionConstitution`."* **`app/Domain/Election/Constitution/ElectionConstitution.php` contains no halt, gate, operative or inoperative vocabulary.** **The asserted canonical home does not contain the concept.** Either the docblock names the wrong home, or the OperatingCore's lifecycle owner is unassigned. **BND-1 and BND-3 both depend on this being resolved first.**

## What is NOT referred

✅ **DEP-1, DEP-3, DEP-4, DEP-5b(analysis), DEP-6(analysis) are complete** and need no ruling — only acceptance.
✅ **DEP-4's core invariant already exists** (`PeriodKind` + P-6) and needs nothing.
✅ **P-7 is settled:** consume, never duplicate, never loosen to accept `null`.
✅ **DEP-10 is settled:** 4 protected sites across 2 files; planned T-10 locks all four.

## What the Domain lane requests

1. **Acceptance (or rejection) of Phase 1 as an analysis deliverable** — the PO/ARB's act.
2. **Rulings on BND-1, BND-2, BND-3**, and resolution of the discrepancy above.
3. **Then** the Domain lane may proceed to Phase 2 — RED → GREEN → independent verification. ⛔ **It will not guess any of the three.**

**Traceability:** Phase-1 §8 (BND-1/2/3) · §2 (ownership map) · ADR-1 §6 rows 2–3 · ADR-2 (e), (g), (h) · `EM-DOM-001` authorization + Annotations A/B · `EM-GOV-059(b)/(c)` · `EM-ARCH-001 §2b/§5d` · `AIP-14` · `EP-02` · `R-34`.
