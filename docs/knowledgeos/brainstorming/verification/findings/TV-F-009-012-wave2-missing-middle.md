# TV-F-009 … TV-F-012 — Wave 2: the missing middle (transition unification · identity · η · policy floor)

Date 2026-08-29 · Verifier analyses (class C, with class-B formal arguments where marked). Inputs: the verbatim extractions (A3W/A3X, re-checked where load-bearing) — no new invention; every constructive step is labeled **PROPOSED RECONSTRUCTION**.

---

## TV-F-009 (P7) — Transition-system unification — **PARTIALLY OVERLAPPING; unifiable only as a PROPOSED RECONSTRUCTION**

**Variants compared** (statements held verbatim in A3W §3):
`δ: 𝒦×ℰ ⇀ 𝒦` (Q15-revised: partial, deterministic-by-replay, event-level, Pre/Post, 8 event types) · Q20's `δ` on `S_t` (2-place signature vs 3-place application, C-026) · `τ: S×C → S` (Step 204: total arrow, context-second-argument, 8-slot contract, composition/idempotency/commutativity/causal-order properties) · `ℛ(K_t,E_t,C_t,M_t,V_t,T_t)` (031: partial, constrained, epistemic outputs {Accepted…RequiresValidation}) · `Revise(K_t,e)` (031: non-monotonic) · `Update(K_t,E_t,Ω,EC)` (025k) · `T: S×E → S∪Error` (069) · `Learn` (049: uninterpreted) · `Evolve/Transition` (Q13/Q14: undefined args) · three federated machines `E_t→G_t→O_t` (189/203).

**Classification (mandate §7.4 categories):**
1. **Different abstraction levels, compatible in principle:** `δ` (event-level) vs `τ` (contract-level). τ's contract slots (Authority, Policy, Evidence, Lineage) correspond to components δ folds into `Pre` and the event payload. A refinement mapping exists *if* τ's context `C` is read as carrying the event plus its authorization record. Neither file defines or cites the other — the compatibility is **not established in-corpus**.
2. **Sub-family, compatible:** `Revise`/`ℛ`/`Update` are epistemic-event instances of a partial transition (non-monotone current state, monotone history) — consistent with δ restricted to evidence-bearing events. The epistemic *output vocabulary* of ℛ ({Accepted, Unknown, Underdetermined, Conflicted, Invalid, RequiresValidation}) types the *result status*, not the state — a dimension δ lacks entirely.
3. **Placeholders:** `Learn`, `Evolve`, `Transition(K,a,o,e)` — uninterpreted; nothing to unify.
4. **Genuine conflicts (stand):** totality (τ total vs δ/ℛ partial — τ's "valid only if Pre" prose effectively partializes it, but the signature says total: C-026-class defect); second-argument sort (event vs context vs evidence-tuple); determinism (asserted for δ via replay-provisos, never discussed for τ); the federated machines vs single-δ (Step 203's federation vs Q15/Q20's single function — the corpus's two most developed dynamics are structurally different objects).

**PROPOSED RECONSTRUCTION (labeled; not corpus content):** a single *partial deterministic labeled transition system* `Δ: 𝕊 × 𝕃 ⇀ 𝕊` over the federated state `𝕊 = S_D × S_E × S_G × S_Dec × S_X` **restricted to single-space steps** (each label touches one component space, honoring 203's federation), with label `ℓ = (event, actor, authorityRef, policyVersionRef, evidenceRefs, time)` making τ's contract slots first-class in the label; `Update/ℛ` = the restriction of Δ to `S_E`-labels; Replay defined over label sequences (T-K8 applies). This subsumes every variant except Q20's 3-argument application (which becomes reading `policyVersionRef` from ℓ). **Status: PROPOSED RECONSTRUCTION — equivalence to each variant provable only after the variants' own defects (C-026, τ-totality) are dispositioned.**
**Non-consequences:** does not establish that the corpus *intends* one model; does not resolve where Zero findings live (TV-F-002 must be dispositioned first — the Δ above deliberately omits 𝒵 from `S_E`'s knowledge component, which is a *choice* requiring governance sanction).

---

## TV-F-010 (P4) — Identity and equivalence: exact requirements — **structure FORCED by the theory; concrete relations NOT derivable (BLOCKING for the evidence layer)**

**Which results need which relation (class B analysis):**

| Relation | Needed by | Required structure (forced, not chosen) |
|---|---|---|
| Evidence content-equivalence `~` | T-K6b (N's quotient), I-5, duplicate tests | **equivalence relation** (reflexive/symmetric/transitive) — N's class-collapse is a quotient construction; anything weaker breaks well-definedness of representatives |
| Evidence dependency `≺` | T-K6b discount, A₄/E-K4, I-6 | **strict partial order / DAG** — a cycle makes the discount ill-founded; this is exactly why 025a-5's AcyclicDerivationGraph is *required*, not optional (its PROPOSED status is thereby upgraded to REQUIRED-BY the evidence pipeline) |
| Evidence token identity | idempotent ingestion (025k I10, 025a-5 I10) | identity finer than `~` (two tokens of one content are `~`-equal but distinct tokens); syntactic/registration identity suffices |
| Event identity | Replay (T-K8), history append | payload equality suffices (syntactic) — **no semantic identity needed**; cheap |
| Requirement identity | contract versioning, Zero-item tracking across time (025e) | stable identifiers + version poset; semantic identity NOT required |
| State equality (K_t = K_t′) | **NO rim theorem needs it** — only 𝒱-membership checking and verification claims | dispensable for the rim; needed only at Part I verification level, where component-wise equality relative to the chosen representation suffices |
| Frame equivalence | SNF/measurement layer only | blocked behind the measurement layer's own UNDER-SPECIFIED status; not on the critical path |

**Key structural result (class B):** the theory needs **a two-sorted identity structure on evidence** — `(content-class via ~, provenance-node via ≺)` — because its own axioms force duplicates (same class, different tokens) and derived items (different class, dependent provenance) to be *distinguished simultaneously* (T-K6a's impossibility shows strengths alone can't do it). This is **derivable as a requirement** from A₁–A₄ + the executed tests. **What is NOT derivable:** the concrete `~` (what counts as "same content" is a semantic/domain judgment — the corpus's own "cannot be Hash(Text)"), and the concrete provenance graph construction.
**Gap format:** Missing object: concrete `~`, `≺` constructions · Where required: N, I-5, idempotency, replay-safety of ingestion · Can it be derived? **Structure YES (done above); instances NO** · Required assumption: domain-supplied equivalence oracles with declared scope · Blocking: **BLOCKING** for the evidence pipeline's soundness claims; NON-BLOCKING for the rest of the rim · Resolution: part mathematical (the two-sorted structure — this finding), part governance/domain (the oracles).

---

## TV-F-011 (P5) — η: category classification — **η as a total mathematical function is a CATEGORY ERROR; a governed procedure with computable skeleton is the defensible object**

**Evidence:** the ruled form `EC = η(G, IdealState)` (v0.2 R-2) vs the corpus's own richer form `DeriveContract(G, S)` with `S = {Constitution, Policy, ADR, Rule, Scope, HumanInstruction, RiskModel, DomainModel, Law, Standard}` (025e) and the requirement-origin list (025d §25D.39). The two forms **differ in inputs**: η's two arguments cannot determine EC when requirements originate in *external normative sources* (law, standards, human instruction) that are neither G nor IdealState.
**Analysis (class B):** (i) as a function of `(G, IdealState)` alone, η is **not well-defined** — same (G, IdealState) under different law/policy environments must yield different ECs; the corpus's own 025e form concedes this by adding S. (ii) With S included, `DeriveContract(G,S)` decomposes into: source collection (governance oracle) → requirement extraction (computable for machine-readable sources; oracle for human/legal ones) → closure check `Closed(EC)` (computable, 025e gives the checklist) → versioned publication (governed act). Existence: **as a governed procedure, YES** (025e sketches it; L5 practice enacts it). Totality: only *relative to source-and-judgment oracles* — the "η-totality assumption" (OQ-1/AS-R01) is thus **a category mismatch as posed**: the meaningful claim is *"DeriveContract terminates and yields a Closed, versioned contract relative to its oracles"* — which is provable in the T-K3 style (finite source set, terminating per-source extraction). Uniqueness: **NO** — multiple legitimate contracts per goal (contract versioning presupposes it).
**Verdicts:** η(G, IdealState) total: **REFUTED as a mathematical claim** (input-insufficiency argument above); DeriveContract(G,S) oracle-relative termination: **DERIVABLE BUT CONDITIONAL** (proof deferred to Part J once the oracle interface is fixed); "η is a governance construction, not a mathematical function": **PROPOSED REFRAMING** for OQ-1's disposition — consistent with, and sharpening, GN-46's "totality relative to oracles" remark.
**Non-consequences:** none of this constructs any actual contract-derivation; OQ-1's *governance* question (does a G-residual exist inside Zero?) is untouched.

---

## TV-F-012 (P6) — Policy floor: minimum structure, uniqueness, genesis — **exact requirements stated; uniqueness NOT derivable; genesis requires an axiom or boundary condition**

**Minimum structure forced by I-11 + the gate frame (class B):**
1. **Policy identity** (F1-style stable ID) and a **version order**: per-lineage total order (versions of one policy form a chain — required by "versioned approval decision"); across lineages no order needed.
2. **Applicability** `Applicable(p, context, t)` — a declared predicate (currently undefined in 048/120; must be domain-supplied).
3. **Precedence** `⪰_C`: for I-11's guard to be *evaluable*, precedence must yield, for each context and time, a **unique maximal element of the applicable set**. Sufficient conditions: (a) totality of ⪰_C on every applicable set, or (b) an explicit tie-breaker/escalation rule (025f's escalation law is exactly (b) — but unratified). **Neither is derivable from the corpus**; ⪰_C's own order axioms were never stated (MV-F-12 re-confirmed). Without (a) or (b): `InForce(context,t)` is a *set*, and I-11's "the in-force policy" is ill-typed. **UNDER-SPECIFIED, blocking for multi-authority deployments; trivially satisfied in single-authority deployments.**
4. **Genesis:** by TV-F-007.4 (genesis-blindness theorem), no invariant of the change-guard shape can legitimize the first in-force policy. The theory therefore requires exactly one of (mandate P6's options): **(i) a Genesis axiom** — "initial force is conferred by a recorded external act" (matches L5 practice and MV-F-8's option (i)); **(ii) a boundary condition** typing the first act as outside the loop (L1/L5 event). Both are **governance decisions**; mathematics can only certify that *some* such device is necessary (proven) and that either device restores the induction (immediate, since the base case becomes stipulated). **Choice NOT derivable.**
**Gap format:** Missing: ⪰_C axioms · applicable-set uniqueness device · genesis device · `Governed(·)`, `authoritative`, `material` predicates (TV-F-008/#iii list) · Blocking: BLOCKING for I-11 semantics multi-authority; NON-BLOCKING single-authority · Resolution: mathematical for the structure theorems (done here), governance for every choice.
**Non-consequences:** nothing here legitimizes any *particular* precedence or genesis choice; nothing repairs I-10's recorded live violation (Step 121 finding — remains a repository fact, not a theory statement).

---

### Wave-2 net effect on the dependency graph
LB-1 → reframed (η category error; oracle-relative termination provable later) · LB-2 → structure supplied, instances remain BLOCKING-for-evidence-layer · LB-5 → structure supplied, choices remain governance-owned · transition unification → PROPOSED RECONSTRUCTION available, gated on TV-F-002 disposition and C-026/τ-totality dispositions. **The missing middle is now precisely specified: 2 governance devices (genesis, precedence/tie-break) + 2 domain oracles (~, ≺ instances) + 1 disposition (Zero's residence) + 1 notation repair (δ/τ typing) stand between the rim and a constructible canonical model.**
