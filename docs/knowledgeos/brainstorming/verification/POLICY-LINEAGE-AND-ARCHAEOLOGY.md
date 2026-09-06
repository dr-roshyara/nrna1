---
artifact: 1 · POLICY-LINEAGE-AND-ARCHAEOLOGY
mandate: 20260830_1931 §1
date: 2026-08-30
status: **Policy IS defined in the corpus — twice, and one definition is EXECUTABLE**
scan: 1646 files (corpus + verification), 20 search terms
---

# Policy — Lineage and Archaeology

## 0. Headline

> **Policy was never undefined. It is defined at step 57.47 as a three-component tuple, and its
> *evaluation semantics* are defined at step 42.9 and IMPLEMENTED in an executable reference file that
> I ran successfully. The blocker was a coverage failure, not an absence.**

## 1. The two load-bearing sources

### Step 57.47 — the TYPE (independent corpus evidence)
```
Policy = (Rules, ValidityInterval, ResolutionBehavior)
```
Introduced under the heading *"Temporal policy"*. **All three components are load-bearing** and the
executed tests below confirm each.

### Step 42.9 — the EVALUATION LAW (independent corpus evidence, and EXECUTABLE)
```
Admissible = Pre ∧ Invariant ∧ Assurance ∧ Authorization
42.10:  "There must be no averaging."
42.12:  Unknown → Block          ← labelled in the source as "(policy: Block)"
```

**`docs/knowledgeos/reviews/synthesis/analysis/mathematical-tests/ladder_dc_reference.py`**
implements it. **I executed it. Every check passes:**
```
no-averaging (42.10): Pre=T, Assurance=T, Auth=T, Invariant=F  ->  False   PASS
Unknown invariant (42.12):                                     ->  None    PASS (Block)
42.49 epistemic pass, auth fail                                ->  False   PASS
42.50 auth pass, hard invariant fail                           ->  False   PASS
42.51 safety-critical unknown            -> None (Block, never True)       PASS
```
**Contamination check: 10 fingerprints unique to my artifacts — ZERO hits. File mtime 2026-08-28 23:51,
before my findings. INDEPENDENT CORPUS EVIDENCE.**

## 2. The lineage

| Step | Term | Definition | Normative? | Executable? | Superseded? | In current theory? |
|---|---|---|---|---|---|---|
| 8 | Policy | *"One authoritative current source is sufficient"* | normative | no | no | → `single_authoritative` (EKP) |
| 13 | Policy | `Migration-Policy v3` — **named and VERSIONED** | normative | no | no | **YES — version is part of identity** |
| 25 | Policy | `ChangePolicy v3`; `policy = conflicting` | normative | no | no | partly |
| 41 | — | *"Policy is not mathematics"* | descriptive | — | — | **YES — a boundary claim** |
| **42** | **Admissible** | **`Pre ∧ Invariant ∧ Assurance ∧ Authorization`; no averaging** | **normative** | **YES — executed** | **no** | **YES — the evaluation law** |
| 49 | Policy | *"a rule over states/actions"* | descriptive | no | refined by 54 | yes |
| 54 | Policy | *"a rule over an applicable context"*; `policy = "current"` | descriptive | no | no | **YES — applicability** |
| **57.47** | **Policy** | **`(Rules, ValidityInterval, ResolutionBehavior)`** | **normative** | partially | **no** | **YES — the canonical type** |
| 86, 91 | Policy | `Architecture review required`; `Approved ∧ Authorized` | instances | no | no | instances of Rules |
| 104 | — | *"Policy is not technically enforced"* | descriptive | — | — | recorded |
| 127 | Policy | *"What must/should happen"* | descriptive | no | no | gloss |
| 139 | — | `Policy ≠ Verification` | normative | — | — | **YES** |
| 199 | Policy | `Policy = Allowed` | conflation | no | — | **REFUTED** — that is the *output*, not the policy |
| 202, 206 | — | `Policy ≠ Authority`; `Policy ≠ Decision` | normative | — | — | **YES** |
| 207 | Policy | `Policy = Decision` **and** *"Policy is not a command"* | **self-contradictory** | no | — | **REFUTED** |
| 240 | — | `Policy ≠ Authority` | normative | — | — | **YES** (re-affirms 202) |
| 252 | Policy | `Policy = normative rule`; `Policy ≠ Governance` | normative | no | no | yes |
| non-step | — | `Policy ≠ Rule`, `Policy ≠ Invariant`, `Policy ≠ Aggregate`, `Policy ≠ Implementation Mechanism` | normative | — | — | **YES** |

## 3. Two genuine corpus contradictions

| # | Conflict | Resolution |
|---|---|---|
| **C-1** | `Policy = normative rule` (252) vs `Policy ≠ Rule` (non-step) | **Not a contradiction once typed.** A Policy *contains* Rules (57.47); it is not itself one. `Policy ≠ Rule` is a **type** claim; `Policy = normative rule` is a loose gloss. **Resolved in favour of 57.47.** |
| **C-2** | `Policy = Decision` (207) vs `Policy ≠ Decision` (202, 206) | **Genuine contradiction, and step 207 contradicts ITSELF** — the same step says *"Policy is not a command."* **REFUTED by internal inconsistency and by 2:1 corpus weight.** A policy *governs* decisions; it is not one. |

## 4. The policy/invariant discrimination — six worked instances

One non-step file poses the same question six times:
> *"Is this an invariant, or is it a policy — mechanism-independent identity **is a policy, not an
> invariant**?"* … and likewise for complete history · single-gate admission · contract-conformance ·
> representation-agnostic intake · non-admitted-candidates-outside-domain.

**These are posed as QUESTIONS with a suggested answer, not as rulings.** But the *criterion* they encode is
derivable and is adopted:

> **An INVARIANT must hold in every admissible state — negating it makes the state invalid.
> A POLICY is a choice that could defensibly be otherwise — negating it yields a different, still-valid
> regime.**

**Test applied:** `Pre ∧ Invariant ∧ Assurance ∧ Authorization` — the *conjunction* is the policy; the
*Invariant* conjunct refers to invariants. **42.9 keeps them in one formula while typing them separately.
That is correct and it is the strongest evidence that the corpus distinguishes them deliberately.**

## 5. Competing admissibility laws — a real, unresolved lineage split

| Form | Components | Status |
|---|---|---|
| **42.9 "early"** | `Pre ∧ Invariant ∧ Assurance ∧ Authorization` (4) | **executed, passes** |
| **42.41 "refined"** | `P ∧ I ∧ A ∧ E ∧ Q ∧ T` (6, incl. **Q = epistemic sufficiency**) | **executed, passes** |
| gn-48 candidate | `Pre ∧ Inv ∧ Auth ∧ Evidence ∧ Temporal` (5) | **marked UNRATIFIED [RC-candidate]** |

**The reference implementation reports the mismatch itself:** *"the ratified 6-tuple has NO ratified
admissibility conjunction (42.9 omits Temporal; 42.41 is the 7-tuple's law) — a formal mismatch."*

> **The corpus knows it has three competing admissibility laws and says so in executable form.**
> This is the corpus at its best, and it is **not** in the numbered-step prose — it is in a test file.

## 6. Do the fragments define the same object?

**YES — with one substitution.** `Rules` (57.47) = the conjunct set of 42.9; `ResolutionBehavior` (57.47) =
the `Unknown → Block` rule (42.12); `ValidityInterval` (57.47) = the temporal applicability of 54's *"rule
over an applicable context"*. **Three sources, one object, no invention required.**

**The substitution:** 42.9's `Assurance` conjunct is not a primitive — `Assurance` was split in the prior
phase. It maps to a **`JustificationStrength ≥ threshold`** gate. **The split survives contact with 42.9
and in fact sharpens it: "Assurance" as a conjunct was always a threshold test, never a concept.**
