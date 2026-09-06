# TV-F-029 … TV-F-033 — Findings from deep verification of Steps 056–066 (Phase 2C)

**VERIFY SESSION · 2026-08-30.** Source: the 11 files of the 056–066 band, read in full. **TV-F-030 was re-checked by the lead verifier and the subagent's claim was CORRECTED downward** (see below) — per mandate §11, prior verifier output is provisional and is amended when wrong.

---

## TV-F-029 — **The frozen invariant set is violated ≥25 times; the corpus's only global correctness condition is provably weaker than its own invariant inventory**

**Baseline (step-048 §48.32, verbatim):** `𝓘 = {I₁, I₂, …, I₂₀}` with the core correctness requirement `∀s ∈ ReachableStates: ⋀_{i=1}^{20} Iᵢ(s)`.

**What steps 057–066 do:** mint at least **25 further named invariants**, none numbered, none added to the conjunction, with step-048 never cited:

| Source | New invariants |
|---|---|
| 057 §57.61 | `I_Liveness` — introduced with the words *"We should **add**"* |
| 058 §58.2, §58.34 | `I_E`, `I_K`, `I_EK`, `I_G` (whose own definition has **4 of 6 conjuncts undefined**) |
| 059 §59.57 | `I_Concurrency` |
| 062 §62.70–73 | `I_DecisionAuthority`, `I_DecisionBasis`, `I_ActionBoundary`, `I_ObjectiveIntegrity` |
| 063 §63.51–54 | `I_Causal`, `I_Intervention`, `I_Counterfactual`, `I_Identification` |
| 065 §65.25, §65.66 | `I_EpistemicIndependence`, `I_AI-Provenance`, `I_EvidenceNonAmplification`, `I_NoCircularJustification`, `I_AgentScope` |
| 066 §66.58 | `I_Observation`, `I_Measurement`, `I_Missing`, `I_Population`, `I_Observability`, `I_TemporalTruth` |

**Grep-confirmed across all 11 files:** zero occurrences of `𝓘`, `I_{21}`, `Step 48`, `48.32`, `INV-`, or any renumbering.

**Consequence (the substantive part).** A state satisfying `⋀_{i=1}^{20} Iᵢ(s)` can violate `I_Liveness`, `I_Concurrency`, `I_Causal`, `I_Observability` and twenty-one others, and still be certified correct by the corpus's own stated criterion. **The global correctness condition is not merely stale — it is demonstrably insufficient by the corpus's own subsequent work, and no document says so.**

**Redundancy within the new families (recorded, not repaired):** `I_ActionBoundary` ≡ `I_DecisionAuthority` ≡ §62.39's `Decision selection ≠ Authorization` ≡ §65.46's `AgentCapability ≠ AgentAuthority` ≡ §64.53's `Model performance ≠ Model authorization` — **one proposition under five names across three files**. `I_EpistemicIndependence` and `I_NoCircularJustification` are **logically identical**. `I_Causal` restates a rule already given at 056 §56.29, 058 §58.33 and 061 §61.29.

**Severity: MAJOR** (blocks any claim of a checked invariant system; feeds the joint-satisfiability question directly). **Register sync: AC C-073.**

---

## TV-F-030 — Step-060 asserts a partial order as a derived result; **antisymmetry FAILS and transitivity is NOT ESTABLISHED**

**Claim under examination.** §60.16: `K_A ⪯ K_B` iff `Claims(K_A) ⊆ Claims(K_B)` **and** *"the relevant existing claims retain compatible semantics"* — called *"a candidate ordering"*. §60.34 lists reflexivity / antisymmetry / transitivity and closes with *"**If** these hold, we have a partial order."* §60.70 then boxes as the file's central finding: *"KnowledgeOS knowledge is a versioned, provenance-aware, temporally-scoped, **partially ordered** collection of structured propositions."*

**Lead-verifier re-derivation (executed):**

- **Reflexivity — HOLDS** (trivially, for any reflexive reading of the compatibility conjunct).
- **Antisymmetry — FAILS on the source's own carrier.** §60.65 defines a state as `C=(P,E,S,T,U,V,M)` — states carry support values, provenance, versions and metadata *beyond* the claim set. Two states with identical `Claims` but different `Support⁺`/provenance satisfy `K_A ⪯ K_B ∧ K_B ⪯ K_A` while `K_A ≠ K_B`. Computed witness: `{claims:{p}, support⁺:0.9, prov:sensorA}` vs `{claims:{p}, support⁺:0.3, prov:hearsay}` — mutual inclusion holds, equality does not. The escape clause *"under the chosen equivalence"* rescues antisymmetry only by coarsening equality to bare claim-set identity, which **§60.50 (`ClaimIdentity ≠ TextIdentity`) and §60.51 (`Equivalent?` may return `Unknown`) explicitly forbid**. A relation whose equality test can return `Unknown` cannot be antisymmetric.
- **Transitivity — NOT ESTABLISHED.** ⊆ is transitive; the compatibility conjunct is **never defined**, so transitivity of the conjunction is not decidable from the source. Compatibility relations are canonically non-transitive (`p ~ q`, `q ~ ¬p`, `p ≁ ¬p`), so the burden of proof lies with the claim and the source discharges none of it.

> **CORRECTION TO A PRIOR VERIFIER OUTPUT (mandate §11).** The subagent report for this band asserted that transitivity **fails**. That overstates the evidence: failure cannot be demonstrated against an undefined predicate. The correct verdict is **NOT ESTABLISHED**. The subagent's antisymmetry finding stands and is confirmed by computation. This correction is recorded rather than silently applied.

**Verdict: §60.70's boxed "partially ordered" is NOT DERIVED** — it upgrades §60.34's explicit conditional into a result. **Credit where due:** the same file's refusal to declare a lattice (§60.37 `KnowledgeOS cannot yet be declared a lattice`; §60.72 `Lattice structure remains an open hypothesis`) is exemplary restraint. The restraint simply does not extend to the order itself.

**Compounding defect (VE-8):** step-032 §32.76 had already **committed** `⪯` as a member of the core algebra `𝔎 = (𝒦, ⪯, ∘, ⊕, Revision, Validate, Infer, Conflict)`, with §32.77 stamping the surrounding properties PASS. Step-060 demotes `⪯` to *"a candidate ordering"* **without naming step-032, without citing 𝔎, and without retracting §32.77**. The corpus now carries two incompatible statuses for the same symbol. **Register sync: AC C-074.**

---

## TV-F-031 — `X_t` denotes the system state and the world state in the same corpus, unnoticed

- **Step-051 §51.2 / 056 §56.3 / 057 §57.41:** `X_t` = the **KnowledgeOS reference/system state**; step-057 writes the safety invariant as `□I(X_t)`.
- **Step-066 §66.1:** `X_t` = the **hidden state of the world**, with `O_t ∼ P(O_t | X_t)` and `O_t ≠ X_t`.

The two readings are not merely different — they are the two sides of the corpus's most load-bearing distinction (`W ≠ K`, established at step-016 §9 and restated at 066 §66.60 as `W ≠ O ≠ K ≠ D`). **Under step-066's own §66.61 rule — *"No downstream computational layer may silently upgrade the epistemic status of its input"* — reusing the system-state glyph for the world state is precisely the forbidden move.** No file notices. **Severity: MAJOR for notation normalisation; MINOR mathematically** (no proof depends on the glyph). **Register sync: AC C-075.**

---

## TV-F-032 — Step-056 "Build and Execute the Reference Machine" builds and executes nothing, and its central predicate is undefined

- **Execution:** zero code blocks, zero command output, zero traces. All fences are `text`. §56.42 tabulates **30 experiments, all PASS**.
- **The honesty marker is partial.** §56.43 correctly demotes production correctness: *"These are currently **formal reference-machine experiments**, not evidence that a production implementation has passed."* But §56.44 then claims level 2 was *"specified and **exercised**"* — **nothing was exercised**; no machine exists to exercise. §56.42's 30 rows are never withdrawn.
- **The predicate `I` — on which §56.41, §56.45 and §56.46 (`□I(X_t)`) all rest — is never defined anywhere in the file**, and step-048's `𝓘` is not cited. The falsification criterion of §56.45 (*find `X_t` with `I(X_t)=True` and a valid transition to `I(X_{t+1})=False`*) is therefore **inoperable**.
- **First invalid inference: §56.5.** `X₀ = ∅`, then "Execute: Observe(O₁). Result: X₁" — no transition function `δ: X × Σ → X` is ever given, so `X₁` is a name, not a computed value. Every later `Xₙ` inherits the defect.
- **Retroactive consequence (VE-5):** step-058 §58.10 records `FAILURE DETECTED` for exactly the property step-056 §56.16 recorded as `PASS`. **056's PASS is invalidated six minutes after filing and is never retracted**; the matrix row "Stale knowledge | PASS" still stands.

**Genuine value in the same band, recorded fairly:** step-058 §58.16–18 (authorization must be valid *at the instant of the side effect*, not at check time) is a real TOCTOU finding that strictly strengthens step-050's AuthorityBinding contract — **the strongest single result in the band**. Step-066 §66.26 is **the only genuinely proved claim in the band**: if two states are observationally indistinguishable, no computation over observations can separate them — immediate from the non-injectivity of `𝒪: X → O`. **Register sync: AC C-076.**

---

## TV-F-033 — Citation collapse: two cross-step citations in ~22,000 lines

Across the 11 files, exactly **two** references to any other step exist (059 §59.13 → 058; 062 §62.35 → 061). Meanwhile the band re-derives, without citation: steps 014, 015, 019, 025C-2, 025C-3, 025P, 025Q, 025R, 025U, 032, 034, 041, 042, 043, 050, 051.

Sharpest instances:
- **Step-062 re-derives step-025R almost line for line** — including `argmax_{a ∈ A_admissible} EU(a)` re-presented as new with `admissible` renamed to `feasible`, and 025R's value-of-information construction re-presented as EVSI. Neither 025R, 025H, 041, 042, 034 nor 015 is named.
- **Step-063 re-derives steps 025P and 043 wholesale** — `do(X=x)`, `ATE`, the backdoor formula, even the subgroup-heterogeneity result — while §63.57 claims *"We are not adding mathematical concepts randomly. Each new layer emerged because the previous layer exposed a boundary."* **That claim is false for this file:** its layer already existed at 014/025P/043.
- **Step-065 re-derives step-025U's central result** (agreement ≠ independent evidence; the dependency graph `G_D`) under the new name `G_A`, uncited.
- **The evidence-independence result is derived four separate times in one phase** (060 §60.61–63, 061 §61.12–15, 064 §64.19, 065 §65.15–17) with zero cross-references among the four and zero citations of the three predecessors that already had it.

**VERIFIER OBSERVATION.** This is not a documentation defect. Uncited re-derivation is how the corpus's formal regressions (TV-F-028) and namespace collisions (TV-F-022) are *produced*: each re-derivation re-chooses symbols and re-selects formalisation depth with no obligation to match what already exists. **Register sync: AC C-077.**

---

## Further observations (recorded, not findings)

1. **Correctness-conjunction drift:** `Correctness(KOS)` grows 4 → 8 → 9 conjuncts across 70 minutes (059 §59.61 → 064 §64.65 → 065 §65.70), with `ConcurrencyIntegrity` → `Concurrency` → `Conc`. Step-057's `KOSContract = (S, L)` is orphaned and its `S`, `L` do not correspond to the later conjuncts of the same letters.
2. **Two model lifecycles inside step-064** (§64.12 six states vs §64.51 seven), silently resolved in favour of the second.
3. **§64.40 boxes `SameModelVersion ⇒ SemanticallySameModel`, then §64.41 refutes it** one section later on hardware/library/float grounds. The box is left standing.
4. **≥18 conditional PASSes are tabulated unconditionally** across the band (bodies say "provided/if/unless"; matrices say `PASS`).
5. **Step-059's concurrency theorem (§59.50) is vacuous as written** and contradicts step-058 §58.3's `CorrectContexts ⇏ CorrectComposition`, which it does not cite on that point.
6. **Filing order inverts authoring order again:** step-065 is filed 52 seconds *after* step-066, though 066 depends on 065 (§66.46). Third independent site confirming the timestamp≠authorship-order finding.
7. **Step-066 is the best-defined file in the band** — its five-way separation of `Unknown / Uncertain / Ambiguous / Non-identifiable / Unobservable` gives each a distinct truth condition, and none is a synonym. It is the strongest candidate material in this band for the survivor theory.
