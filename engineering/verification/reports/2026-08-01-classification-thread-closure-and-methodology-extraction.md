# Classification Thread — Closure, Weakened Claim, and Methodology Extraction

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Classification:** verification report · **Role:** Reference (evidence) · **Maturity:** research
**Repository Integrity Gate:** ✅ PASSED. **No standard amended · no registry value changed · nothing adopted.**

---

## 1. The `session-state` claim — weakened, and the reason matters

**I wrote:** *"`session-state` **is not** a scope … the registry has been carrying a Role value inside the Scope field."* **And I called correcting it a *defect fix*.**

**Both go too far.** **Recorded claim, at the strength the evidence supports:**

> **The current implementation treats `session-state` as if it were a Scope, while its observed semantics align more closely with the *Runtime* Role defined by ES-004.3. This indicates a possible modelling inconsistency requiring governance confirmation before correction.**

**And the reclassification of the remedy is the substantive part:**

| I called it | It is |
|---|---|
| a **defect fix** — configuration hygiene | a **candidate semantic correction** |

> **Changing a registry value changes the classification model.** The registry is not incidental configuration — **it is the executable form of the model**, which is precisely the property that made it valuable. **A change to it is a semantic act, and semantics are confirmed by governance.**
>
> **Architecture reports evidence; governance confirms semantics.** **I am probably right about `session-state` — and *probably* is not a mandate.**

**The three-quality report has been annotated rather than rewritten** (ES-004.3: corrections are annotations).

## 2. What was extracted, and where it went

**The lasting asset is the review discipline, not the `type`/`role` proposal.**

> ### The Model Integrity Rule
>
> **Whenever a new attribute or dimension is proposed, first determine whether it is a *new dimension*, an *overloaded existing dimension*, or *another value of an existing dimension*. Introduce a new dimension only if it is orthogonal, necessary and sufficient — all three, or the change does not proceed. If an existing dimension is found to contain mixed concepts, that is a modelling defect, to be surfaced before architectural evolution rather than as part of it.**

**It is general.** It applies to a proposed value object, aggregate, classification attribute, engineering capability or architectural dimension — **not only to documentation.**

**Placement, decided by classification before location:**

| Property | Value |
|---|---|
| Type | methodology rule |
| Role | Reference |
| **Scope** | **cross-product** — adoptable unchanged by any project |
| **Maturity** | **research** — one application |

```
php scripts/doc-placement.php --scope=cross-product --maturity=research
PENDING — placement unruled (rule: cross-product-research, ref: ADR:OQ-2).
```

> **Fourth arrival at the unruled cell.** **So it was recorded inside `docs/implementation/PublicDigit_Engineering_Protocol_Proposal.md`, which has a home, rather than given one of its own** — the same handling as the two earlier candidates.

**Also folded into that proposal, not created separately: Phases 4a–4d — Capability Integrity · Model Integrity · Classification Integrity · Governance Separation.** **Rules live once; the proposal already existed, so it was amended rather than duplicated.**

**4c is the check whose absence caused two misplacements today. 4d is the check whose absence let a recommendation and an architectural conclusion travel in the same sentence.**

## 3. Thread closed

**This documentation-classification thread is closed, and it achieved what it should have:**

| Outcome | |
|---|---|
| Exposed a modelling issue | ✅ `session-state`, now a candidate semantic correction awaiting confirmation |
| Produced a PKS observation | ✅ `docs/pks/2026-08-01-documentation-classification-gap-observation.md` |
| Refined the review discipline | ✅ the Model Integrity Rule and the four integrity checks |
| **Changed the architecture** | ❌ **and deliberately not** |

> **The exercise's value is that it stopped.** It produced a rule that **falsified a proposal I had already recommended and that looked reasonable** — and then it declined to amend anything. **A discipline that catches its author is worth more than the conclusion it overturned.**

## 4. What is deliberately deferred

**Repository model evolution waits for evidence, not for an attractive design:**

| Deferred | Waiting on |
|---|---|
| Adding `type` / `role` to ES-005 | **necessity**, which is currently 2 of 5 |
| Correcting `session-state` | **governance confirmation** of the semantics |
| Promoting the Model Integrity Rule | **another repository** producing the same finding |
| Adopting the engineering protocol | an Engineering-Process act |
| The three PENDING candidates | the **stewardship** decision |

**None of these blocks product engineering.** **The only product blocker remains the four governance decisions on Slice 7C.**

## 5. On "strengthen the architect before strengthening the architecture"

**Recorded because it names what this thread actually produced.** The thread began with a single misplaced file. **It ends with no architectural change, one candidate correction, one PKS observation, and a review discipline that has already been used to reject one of my own proposals.**

**The measurable claim, and its limit: the discipline has been applied once, and it worked once.** **That is a candidate, not a capability** — and by its own necessity test, one application is not evidence of general applicability.

---

**Traceability:** `2026-08-01-classification-inputs-three-quality-test.md` (annotated, not rewritten) · `docs/pks/2026-08-01-documentation-classification-gap-observation.md` · `docs/implementation/PublicDigit_Engineering_Protocol_Proposal.md` (amended: Model Integrity Rule + Phases 4a–4d) · `docs/knowledge/schema/documentation-placement.yaml` (unchanged — `session-state` untouched pending confirmation) · **ES-004.3** (roles; annotation over rewrite) · **ES-005.1 / ES-005.3** · `2026-08-01-artifact-classification-correction.md`. **No standard amended · no registry value changed · nothing adopted · nothing promoted.**
