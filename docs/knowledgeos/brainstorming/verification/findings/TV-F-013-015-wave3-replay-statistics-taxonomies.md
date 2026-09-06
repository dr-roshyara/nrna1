# TV-F-013 · TV-F-014 · TV-F-015 — Wave 3: E3 replay boundary · E4 statistical floor · E5 uncertainty taxonomies

Date 2026-08-29 · Verifier analyses; every corpus anchor cites a verbatim-extracted location (A3X/A6 registers, full-file reads). Statuses per directive 1527 §11.

---

## TV-F-013 (E3) — Replay boundary: evaluator classification and the strongest justified replay theorem

**Classification (with corpus anchors):**

| Class | Example in corpus | Replay status |
|---|---|---|
| 1. Pure deterministic function of recorded state | blocking-gate check (025d §20); conjunction gates (042) | **Replayable** |
| 2. Deterministic rule engine, versioned rules | `RuleEngine(K,r,EC)` with Ω_v (025k Derive) | **Replayable-under-recorded-context** (rule/ontology version refs in event payload — the corpus's own proviso, Q20 §23) |
| 3. Versioned statistical evaluator | `StatisticalCriterion` + `InferenceResult=(H,Prob,Model,ModelVersion,InputKnowledgeSnapshot,Assumptions)` (025c-2 §21) | **Replayable-under-recorded-context** (model version + parameters + input snapshot; stochastic inference additionally needs recorded seeds or recorded outputs) |
| 4. Recorded human decision | `GovernanceApprovalObtained` as K-resident status (025d §7); `HumanAuthorization(K,r)` read as lookup (TV-F-004) | **Replayable** — replay reads the record, never re-invokes. *Conditional on adopting the recording rule, which the corpus implies but never states (TV-F-004)* |
| 5. Live human decision | `HumanAuthorization` read as invocation (025d §12's "invoke" language) | **Not-replayable** — trivial counterexample: the human answers differently on re-invocation |
| 6. LLM / non-deterministic evaluator | 025d §36's own rule: LLM outputs "must become governed artifacts before they affect deterministic evaluation" | **Not-replayable live**; converts to class 4 by the corpus's own artifact rule |
| 7. External live oracle | monitoring APIs, live telemetry | **Not-replayable**; record-then-replay converts to class 2/4 |

**Theorem (Replay, extended — PROVEN-UNDER-ASSUMPTIONS).** If every evaluator call occurring in a history's transitions is of class 1–4, with all version/context references and all class-3/4 outputs recorded in the event payloads, then `Replay(K₀,H)` is deterministic and equals the executed state (induction as T-K8; each step is a function of recorded data). **Counterexample to any stronger claim:** one class-5/6/7 live call in one transition makes two replays diverge.
**Corollary (REFUTED claim):** *"event sourcing automatically provides epistemic replay"* — refuted: recording events does not fix derived state if `Derive(H,Ω,EC)` consults live evaluators at replay time; the corpus's own provisos (policy/version refs) are necessary and, with the class discipline above, sufficient.
**Non-consequences:** feasibility at scale not addressed; nothing here adopts the recording rule (governance).
**Register updates:** LB-4 discharged into this theorem; TV-F-004's gap now has its precise closure condition.

---

## TV-F-014 (E4) — Statistical floor

### A. Dempster combination — **mathematically valid under assumptions the corpus never states; INAPPLICABLE to KnowledgeOS evidence as currently modeled**
Assumptions required: (i) fixed, exhaustive, mutually exclusive frame Θ (closed world — collides with the corpus's own open-hypothesis discipline, HA-S11/Freedman Invariant 6); (ii) **independence of belief sources** — exactly the property the corpus's central hazard says cannot be assumed (I₆₄, 027 §25); (iii) normalization by `1−K` (conflict mass) — discards conflict, colliding with contradiction-preservation (A₃/E-K6: "Conflict retained").
**Counterexamples:** (a) *dependence:* one source `m({A})=0.6, m(Θ)=0.4` combined with its own copy: `Bel(A) = 1−0.4² = 0.84 > 0.6` — duplicate inflation, the D-S analogue of T-K6a's theorem; (b) *Zadeh pathology:* `m₁({A})=0.99, m₁({C})=0.01`, `m₂({B})=0.99, m₂({C})=0.01` ⇒ combined `Bel(C)=1` — near-total conflict resolved into certainty about the alternative both sources nearly excluded, violating the corpus's "conflict is information" doctrine.
**Verdict:** rule itself PROVEN (standard); its KnowledgeOS use **UNVERIFIED-to-CONTRADICTED**: (iii) actively contradicts A₃; (i)–(ii) are unconstructed prerequisites. C-039 CONFIRMED as an assumption gap with teeth. D-S remains legitimate as *ignorance representation* (`[Bel,Pl]`, set support) — the combination *rule* is the defective import.

### B. Likelihood construction `P(E|H)` by evidence category — **the universal Bayesian layer is NOT currently constructible**

| Evidence category (corpus's own kinds) | Verdict |
|---|---|
| Instrument/telemetry measurement | constructible under explicit error model |
| Deterministic test execution | constructible (0/1 likelihood; flake-rate model if declared) |
| Documents / records | **not currently constructible** — no generative model; MNAR selection hazards recorded in-corpus (027 §43–46) |
| Human testimony / expert judgment | constructible only under an elicitation+calibration protocol (none exists) |
| LLM output | **not constructible** — requires executed calibration (none, see C) |
| Derived/aggregated evidence | undefined until dependency structure (≺) is instantiated |
**Verdict:** Bayes is usable in pockets (rows 1–2), UNVERIFIED elsewhere; the corpus's regime-relative stance ("Bayesian = specialized engine, not kernel", 025c-2) is **vindicated by this audit** — and any future universal-Bayes proposal is REFUTED as of the current corpus.

### C. Calibration — status audit
Proposed experiments: EXP-01 criterion (designed, never run) · 082 §26–27 definitions + expectations · SNF v0.4 proposal (Brier/log-loss/reliability). Executed experiments: **none**. Numerical results: none (SNF v0.1 pilot is synthetic and self-disclaims performance meaning). Theorems: none. **Verdict: calibration = PROPOSED throughout; every confidence-number semantics in the corpus is UNVERIFIED pending an executed calibration study.** Conceptual PASSes ≠ empirical evidence (X.31 discipline holds).

---

## TV-F-015 (E5) — Uncertainty taxonomy comparison — **no contradiction; five different constructs sharing one word; no universal representation required**

Taxonomies: **T1** 027 §13 (8-vector over a proposition's uncertainty types) · **T2** 027 §52 (5 additive contributions to *decision* uncertainty) · **T3** 199 §53 (7 tags incl. governance) · **T4** 082 §40 (epistemic/aleatory — reducibility split) · **T5** SNF (semantic/representational/epistemic — compiler stages).

| Pair | Classification |
|---|---|
| T1–T4 | **compatible (granularity):** T4 is a coarsening of two T1 axes |
| T1–T3 | **overlapping:** 6 shared tags; T3 adds orthogonal `governance`, drops T4's split — incompatible as *partitions*, compatible as *tag sets* |
| T1–T2 | **incompatible as classifications of the same thing:** T2 mixes source-typed axes (evidence, parameter) with T1's phenomenon-typed axes; neither refines the other |
| T2–T3 | overlapping (model/causal/semantic shared; rest disjoint) |
| T5–others | **orthogonal in scope:** T5 classifies pipeline *stages* (which system component is uncertain), not uncertainty *kinds* |

**Key result:** the five do not classify the same construct — proposition-uncertainty (T1), decision-uncertainty contributions (T2), assessment tags (T3), reducibility (T4), pipeline stage (T5). **C-007 is reclassified from "conflict" to G11 terminology overload**: the residual defects are the unqualified cross-references (no file states which construct it classifies) and T2's stated-then-withdrawn additivity. **Does the corpus require one universal uncertainty representation? NO — it explicitly requires the opposite** (universal scalar = anti-pattern, 027 §14; bounded-context representations, 027 §34/199 §54). Any future unification is at most a *tag algebra with declared construct per tag* — PROPOSED direction only, not performed here.
**Non-consequences:** none of the five taxonomies is thereby validated; each component's own domain remains undefined (D-register category 1/3 items stand).
