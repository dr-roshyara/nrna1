---
artifact: 9 · NEXT-FOUNDATIONAL-GAP-AUDIT
mandate: 20260830_1931 §12
date: 2026-08-30
status: **The next blocker is NOT a foundational object. It is Policy PROVENANCE.**
---

# Next Foundational Gap

## The dependency-driven search

With `Policy` reconstructed, the graph has no undefined foundational object. **So the search must move from
"which object is missing?" to "which arrow is unauthorised?"** Following the graph rather than step order:

```
Policy governs T.        Who governs Policy?
```

## G-P1 · POLICY PROVENANCE AND CHANGE AUTHORISATION — **the next blocker**

| Field | Value |
|---|---|
| **Question** | Who authored a policy, who may change it, and what authorises the change? |
| **Missing** | `Policy` has no author, no owner, no authorising act. `57.47 = (Rules, ValidityInterval, ResolutionBehavior)` — **three components, none of them provenance.** |
| **Why it matters** | **Policy governs every transformation. An unauthorised policy change silently re-authorises the entire history.** Every `Admissible` verdict ever computed depends on which policy version was in force — and nothing records who put it there. |
| **Depends on** | Authority, History |
| **Evidence** | 57.47 (no provenance field) · EKP: *"Bump `version` when the contract changes"* with **no authorisation rule** · `frozen_changed_without_adr` governs documents, **not the schema** |
| **Math status** | **UNRESOLVED** |
| **Eng status** | **UNRESOLVED** — the EKP has the identical hole |
| **Blocking?** | **YES for governance closure. NO for computation** — assessment computes without it |
| **Resolution route** | **DERIVABLE, not normative.** The theory already has the machinery: a policy change is a **transformation** requiring **authority**, exactly like an assertion. `Policy` gains `Π_policy`; policy changes append to History. **This is the assertion pattern applied one level up.** |
| **Test** | change a policy without authority → must be `REJECTED(authority)`; re-run a historical assessment under the superseded policy and confirm the original verdict reproduces |
| **Verdict** | **NEXT BLOCKER — derivable** |

> **The symmetry is exact and it is why this is derivable rather than a question for the author:**
> an **assertion** is a claim with evidence, origin and validity, admitted by a policy under authority.
> A **policy** is a rule with rationale, origin and validity, admitted by a meta-policy under authority.
> **The theory already knows how to type this. It simply has not applied the pattern to itself.**
>
> **Guard against infinite regress:** the corpus already supplies the stopping rule —
> *"Assurance does not recursively certify itself."* **The regress terminates at a constitutional policy
> that is adopted, not derived.** The EKP has one: `Knowledge-Constitution` (status `frozen`, authority
> `authoritative`) — **the only frozen document in 37.**

## The other surviving gaps — none foundational

| ID | Gap | Blocking? | Route |
|---|---|---|---|
| **G-P2** | Which admissibility law is canonical — 42.9's four conjuncts, 42.41's six, or gn-48's five? | no | **The corpus flags the mismatch itself, in executable form.** Adjudication, and the reference file already states the typing argument |
| **G-P3** | Semantic policy equality is **undecidable** | no | Accept it. Use structural + identifier equality, which is why versioning exists |
| **G-3** | Uncertainty; **no `(Ω,ℱ,P)` in the corpus** | no | **Evidence now leans strongly to DROP.** 42.10's no-averaging law and the ordinal `str` both argue against a numeric measure. Step 246's `KnowledgeOS = Probability Distribution` stands **unsupported** |
| **G-4** | The qualification predicate in `Evidence = QualifiedObservation` | no | Derivable from `EvidenceSet`'s discipline + policy gates |
| **G-6** | `circular_dependency` mis-typed as a warning, scoped to 2 of 6 families | no | **Engineering fix, specified** |
| **G-9** | `E`/`V` doubly bound | no | Rename |
| **G-5** | Is `Q` inside `K`? | no | **A falsifier exists** — does stored status diverge from recomputed? |

## What is NOT a gap any more

| Was | Now |
|---|---|
| `Policy` undefined | **CLOSED — corpus 57.47 + 42.9, executed** |
| `Assessment` uncomputable | **CLOSED — Policy supplies the missing parameter** |
| `Σ` underdetermined | **CLOSED — `(dir,str)` + `Γ`, PF-6 dissolved** |
| `Assurance` contradictory | **CLOSED — split four ways** |
| `P = (E,D,V)` undefined | **CLOSED — Q14** |

## The honest shape of the remaining work

> **The foundational object graph is closed. What remains is a REFLEXIVITY gap: the theory governs
> assertions but does not yet govern itself.** `Policy` is the first object that acts on the system without
> the system acting on it. **That is the next thing to close, and the theory already contains the pattern
> needed to close it.**
