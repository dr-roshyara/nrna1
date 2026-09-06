---
artifact: 01 · THEORY-CRITICALITY-REGISTER
step: 282
date: 2026-08-31
role: independent adversarial verifier — NOT the author of the theory for this step
---

# Theory-Criticality Register

**TC criteria applied explicitly. A gap is theory-critical ONLY if it fails at least one.**

## F14 — the decisive test, executed
*Remove each proposed resolution. Does a mandatory theoretical operation break?*

| Resolution removed | What breaks | Theory-critical |
|---|---|---|
| **`Q_t` (missingness repair)** | *"not asked"* collapses into *"absent"* — M1/M2 indistinguishable | **YES** |
| probability space | **NOTHING** — 0 of 13 mandatory constructs break | no |
| non-identifiability primitive | **NOTHING** — the predicate is derivable in two lines | no |
| `Authorize()` runtime | **NOTHING formal** — `Authorize_formal` stays typed and total | no |
| multi-node execution | **NOTHING** — no theory claim depends on distributed consistency | no |
| measurement executor | **NOTHING** — the measurement model is a parameter | no |
| real-environment observation | **NOTHING formal** — certification weakens, definitions do not | no |
| orphan primitive | **NOTHING** — `is_orphan` derivable from `ℛ` | no |

> **Exactly ONE resolution is load-bearing — and it was already made in Step 281.**

## Per-gap TC analysis

### T-3 — probability space
`Original claim:` no `(Ω,ℱ,P)` exists → theory incomplete.
`Current evidence:` **F21 executed.** No random experiment, no `Ω`, no `ℱ`, no `P`, no random variables, no
estimand, no estimator. Removing probability breaks **0 of 13** mandatory constructs.
`Affected construct:` none. `Dependency:` none.
**TC-1 PASS · TC-2 PASS · TC-3 PASS · TC-4 PASS · TC-5 PASS · TC-6 PASS · TC-7 PASS · TC-8 PASS**
**Theory-critical: NO.** `Primary classification: M (Measurement)`, secondary `S (Scope)`.
`Evidence level: [E]` executed. `Falsification: F21.` `Result: NOT a foundational dependency.`
`Required action:` declare probability out of scope; record step 246's `KnowledgeOS = Probability
Distribution` as an **unsupported SOURCE CLAIM**.

### T-4 — non-identifiability
`Current evidence:` **F15 executed.** `K₁ ≠ K₂` constructed with `K₁ ≈_O_core K₂` (differing only in `Π`).
`NonIdentifiable(K₁,K₂,O) := K₁ ≠ K₂ ∧ O(K₁) = O(K₂)` — two lines over existing constructs.
**All eight TC criteria PASS.** **Theory-critical: NO.** `Primary: F — derived, CLOSED.`
> **Correction to my own earlier claim:** I previously recorded non-identifiability as *inexpressible*.
> That **confused "O cannot observe it" with "the theory cannot say it."** The theory both represents the
> difference (`Π`) and expresses the unobservability. **My prior status was wrong.**

### I-1 / E-1 — real-environment observability
`Current evidence:` re-verified — lint exit 0, graph 39/70, unchanged. 15 of 24 constructs unobservable.
**TC-1..TC-8 all PASS** — every one of the 15 is *defined*; none is *undefinable*.
**Theory-critical: NO.** `Primary: E (Empirical)`, secondary `C`. `Evidence: [R]` for the absence itself.
`Result:` **merely limits empirical certification; does not contradict the theory.**

### I-2 — dependency cycle
`Current evidence:` **F19 executed.** 26-node definitional graph, **0 cycles.** The four suspected cycles
each tested and refuted. `Policy → T → Policy` is a **governance** dependency, not a definitional one.
**All TC PASS. Theory-critical: NO. CLOSED with evidence.** `Primary: F — closed.`

### I-3 — Authorize runtime
`Authorize_formal : Authority × Operation × Policy × Time → Verdict` — typed inputs, typed output,
deterministic, policy- and authority-dependent, executable interpretation demonstrated.
**Theory-critical: NO.** `Primary: C (Computational).`

### E-2 — multi-node
Not a formal assumption. **No theory claim depends on distributed consistency** — searched and confirmed;
propagation convergence is a *governance* property, not a semantic one. `Primary: E.` **Not critical.**

### E-3 — measurement executor
`MeasurementModel` (ordinal, parametric) ≠ `MeasurementExecutor`. The model is a **parameter** of
Assessment; statistical interpretation is external. `Primary: C + S.` **Not critical.**

### G-P1 — policy runtime
**F16 executed.** `GovernancePolicy ∉ K` **and** `KnowledgeAboutPolicy ∈ K` both hold simultaneously:
a policy governs `T` while two assertions *about* that policy carry `Σ`, `Π`, `ℛ` and History.
`Primary: G (Governance).` **Not theory-critical.**

### `Q_t` — replay / serialization
**F17/F18 executed, 10/10 PASS.** `Q_t = Replay_Q(Q₀,H_t)` ✓ · `Deserialize(Serialize(Q_t)) = Q_t` ✓ ·
idempotent · order-independent · unaffected by supersession.
**Classification: (2) event-derived operational state** — a projection of History, the same shape as `Σ`
being a projection of `e`. **Not a new kind of object.** **Not theory-critical.**
`Residual:` `unask` deletion semantics **definable but not decided** → `N (Normative)`.

## NEW gap exposed by Step 282

### C-NEW — evidence-ref hashing collision in the step-280/281 harness
`Discovered:` accidentally, during F21. Two assertions with the same proposition and same evidence
**references** but **opposite polarity** received the **same id**.
```
a1 polarity=supports    ref=33989a8e   id=c7b41c155d
a3 polarity=contradicts ref=33989a8e   id=c7b41c155d      IDS EQUAL, semantically opposite
StructuralValid -> (False,'id collision')
```
`Located:` `kosmodel.py` hashed only `x.ref`; the **canonical** Assertion hashes the Evidence tuple, in
which `polarity` is a field. Under the canonical definition: **`07c5019954` vs `e514390ddd` — distinct.**
**TC-3 for the THEORY: PASS. TC-3 for the HARNESS: FAIL.**
`Primary: C (Computational).` **Not theory-critical.** **Step 281's results re-verified under the
corrected id and all survive — 6/6 distinguishability, M7 orthogonal, StructuralValid ok.**
