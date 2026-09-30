# Independent Review — Import of Smith's *Logic: The Laws of Truth* into KnowledgeOS

## Reviewer stance

Mathematician · Statistician · DDD architect · Computer-logic / ML specialist.

This turn introduces external material (Smith's book) rather than continuing the internal R-series. I will do six things:

1. **Verdict** on the import — is this the right book, and is the extraction accurate?
2. **What the import actually adds** to KnowledgeOS.
3. **Where the import overclaims or underclaims** — seven items.
4. **A typed Logic Vocabulary** — the chapter-by-chapter extraction the document itself proposes.
5. **Worked examples** showing how Smith's concepts plug into the existing R-series without breaking it.
6. **Architecture, ML positioning, and short bullet status.**

---

# Part I — Verdict

**The book is the correct choice**, and the extraction is broadly accurate. Smith's *Logic: The Laws of Truth* is a suitable foundational reference because:

- It is rigorous but not research-level; it covers propositional logic, predicate logic, identity, and metatheory in one coherent progression.
- Its central framing — **logic as the science of truth** — matches KnowledgeOS's own orientation, which is not "reasoning" but *dependence of truth status on evidence and transformations*.
- It correctly separates syntax, semantics, models, validity, proof, soundness, completeness, decidability, and expressive power — the exact vocabulary KnowledgeOS has been implicitly using since R574 without naming all of it.

**The extraction's strongest observation** is the identification of the *three-layer architecture*:

$$\boxed{\text{Formal Logic Layer} \;\subsetneq\; \text{Evidence \& Dependency Layer} \;\subsetneq\; \text{Determination / Governance Layer}}$$

This maps cleanly onto L0–L6 and clarifies which layer is responsible for which questions.

**The extraction's strongest caveat** is the one the document itself states at §36: *Smith's logic does not solve the epistemic dependency problem*. That is correct and important. The W1–W7 benchmark, the dependency calculus from R582–R589, and the completeness calculus from R584–R589 are all *beyond* what classical logic provides.

But the extraction also has **seven issues** that need correction before this can be frozen into the Logic Vocabulary the document proposes.

---

# Part II — What the Import Actually Adds

## 2.1 The vocabulary alignment

The book supplies precise definitions for terms KnowledgeOS has been using somewhat loosely:

| Smith term | KnowledgeOS was calling it | Alignment |
|---|---|---|
| Proposition | Claim / Assertion | ✅ Direct |
| Sentence ≠ Proposition | Text ≠ Meaning | ✅ Direct |
| Truth ≠ Knowledge of truth | `UNKNOWN ≠ FALSE` | ✅ Direct |
| Argument (premises + conclusion) | Determination structure | ✅ Close |
| NTP (necessary truth preservation) | Entailment | ✅ Direct |
| Validity (form-based) | Typed inference | ✅ Close |
| Satisfiability | Model existence | ✅ Direct |
| Countermodel | Counterexample certificate | ✅ Direct |
| Model | Admissible World / Model State Space | ✅ Already in R601 |
| Soundness (`Provable ⇒ Valid`) | I-A06, L4 firewall | ✅ Direct |
| Completeness (`Valid ⇒ Provable`) | Not yet formalized in KnowledgeOS | ⚠️ New |
| Decidability | `UNDEFINED` vs `UNKNOWN` distinction | ✅ Partially covered |
| Expressive power trade-off | Regime selection | ✅ Direct |

## 2.2 The three-layer separation

The document's §37 proposes:

```
Layer 3 — Determination / Governance
Layer 2 — Evidence & Dependency
Layer 1 — Formal Logic
```

**Correct.** This clarifies that classical logic is *not* the top of the stack — it is the foundation. Dependency, materiality, and completeness sit above it.

## 2.3 The three failure kinds

§40 proposes distinguishing:

- **Logical failure**: premises do not entail conclusion.
- **Epistemic/dependency failure**: premises look independent but share a hidden dependency.
- **Computational failure**: status may be determinate but uncomputable within the regime.

**Correct.** This is a genuinely useful refinement of what the R-series has been doing. It justifies the R582–R589 pipeline: logical entailment is not enough; we also need dependency and completeness.

## 2.4 The connection to the dependency calculus

§34 and §35 correctly note that Smith's logic does not solve the epistemic dependency problem. The book's logic is *local* (premises → conclusion); the dependency problem is *global* (are the premises really separate supports?).

**Correct.** This is exactly the separation established in R604.7–R604.9.

## 2.5 The metatheoretic questions

§23–§28 introduce soundness, completeness, decidability, expressive power. These are the *metatheoretic* questions that KnowledgeOS has been answering implicitly through its invariant catalogue and its L4 assurance layer.

**Correct.** The invariant catalogue is a *soundness-like* artifact: every L4 verification establishes that a claim is provable only if valid.

## 2.6 The formal pipeline

§33 gives:

$$\text{Evidence} \to \text{Claims} \to \text{Propositions} \to \text{LogicalForm} \to \text{Model} \to \text{Consequence} \to \text{Determination}$$

**Correct.** This is a clean restatement of the R574–R577 chain (evidence → interpretation → regime → admission → assessment → determination).

---

# Part III — Where the Import Overclaims or Underclaims

## Defect 1 — The import does not distinguish *formal* consequence from *material* consequence with enough care

§8 correctly notes that validity has two components (form and meaning), and §9 proposes distinguishing "logical consequence" from "domain-supported consequence." But this distinction is *asserted*, not typed.

**Corrected typing.**

$$\text{LogicalConsequence}(\Gamma, \varphi) \iff \forall M : M \models \Gamma \Rightarrow M \models \varphi$$

with $M$ ranging over *all* models of the language, versus:

$$\text{DomainConsequence}(\Gamma, \varphi, D) \iff \forall M \in \text{Models}(D) : M \models \Gamma \Rightarrow M \models \varphi$$

where $\text{Models}(D)$ restricts to models consistent with domain axioms $D$.

**Consequence.** The knowledge "water contains H₂O" is *not* a logical axiom; it is a domain axiom $D$. The inference relies on $\text{DomainConsequence}$, not $\text{LogicalConsequence}$.

**KnowledgeOS mapping.** Domain axioms belong in the Regime (Γ) or the Contract. They are not part of the Kernel.

## Defect 2 — The import does not correctly type the relationship between Smith's three-valued truth and KnowledgeOS's multi-valued status

§4 says Smith distinguishes "TRUE / FALSE / UNKNOWN." But Smith actually uses a **two-valued** semantics with epistemic uncertainty about which value holds:

$$\text{Truth}(P) \in \{T, F\}, \text{ but } \text{Known} \in \{T, F, U\}$$

These are different.

**Corrected distinction.**

- **Semantic value** (Smith): $v(P) \in \{T, F\}$ in classical semantics.
- **Epistemic status** (KnowledgeOS): $\{SUPPORTED, REFUTED, UNRESOLVED, CONDITIONAL\}$.

The KnowledgeOS status is *not* a truth value; it is a status *about* the truth value, relative to evidence and regime.

**Consequence.** Do not conflate Smith's bivalence with KnowledgeOS's multi-valued epistemic status.

## Defect 3 — Soundness and completeness are imported but their exact scope is not stated

§24–§25 import:

$$\text{Sound}(S) \iff \forall P : \text{Provable}_S(P) \Rightarrow \text{Valid}(P)$$
$$\text{Complete}(S) \iff \forall P : \text{Valid}(P) \Rightarrow \text{Provable}_S(P)$$

But Smith's theorems apply to **specific** systems (his tree proof system for PL, MPL, GPL, GPLI). The import does not state which of these KnowledgeOS adopts.

**Corrected typing.**

- For **propositional logic**: the tree system is sound and complete.
- For **monadic predicate logic**: sound and complete.
- For **general predicate logic**: sound but **not** complete in the sense of a decision procedure (undecidable).
- For **general predicate logic with identity**: sound, undecidable.

**KnowledgeOS mapping.** L4's assurance invariants are the soundness side; the completeness side requires *declaring* which fragment of logic the system uses. In the general case, completeness is not achievable.

## Defect 4 — Decidability is imported but the KnowledgeOS consequence is understated

§28 correctly states: PL decidable, MPL decidable, GPL undecidable.

**Understated consequence.** This means **any sufficiently expressive KnowledgeOS fragment will be undecidable in general**. Not "may sometimes be undecidable" — but *in general, undecidable*.

**Operational consequence.**

- KnowledgeOS must always carry `UNKNOWN`, `UNDEFINED`, and `UNDECIDABLE` as distinct statuses.
- No amount of computation resolves the general case.
- Only *bounded* fragments (propositional, monadic predicate) can be decided.

**Recommendation.** R-next must state which fragment each KnowledgeOS reasoning layer operates in:

- **Determination:** propositional + monadic predicate (decidable).
- **Dependency:** structural (finite graph, decidable).
- **Completeness:** finite universe (decidable); open universe (undecidable).

## Defect 5 — The three-layer architecture is not typed as a *separation of concerns*

§37 lists three layers but does not state which layer answers which question.

**Corrected typing.**

| Layer | Question | Fragment | Decidability |
|---|---|---|---|
| Formal Logic | Is the conclusion entailed by the premises? | PL + MPL + GPL | PL/MPL decidable; GPL undecidable |
| Evidence & Dependency | Are the premises independent supports? | Structural (finite graph) | Decidable (finite) |
| Determination / Governance | Is the determination authorized? | Contract-driven | Contract-dependent |

**Consequence.** Each layer has its own *decidability profile*. The overall system is only decidable when all three layers are.

## Defect 6 — The import does not resolve the interaction between the three failure kinds

§40 proposes three failure kinds (logical, dependency, computational). But it does not type their **interaction**.

**Corrected typing.**

- A conclusion may be **logically valid but dependency-invalid** (premises entailed, but the premises are not independent).
- A conclusion may be **dependency-valid but logically invalid** (premises independent but not entailing).
- A conclusion may be **logically and dependency-valid but computationally undecidable** (the entailment holds, the dependency holds, but no procedure finds it within the regime).

**Consequence.** The three failure kinds are *independent*. A determination is valid only if all three pass.

## Defect 7 — The Logic Vocabulary proposal is not formalized

§40's final section proposes:

> For every term, give: formal definition → plain-English definition → mathematical notation → concrete real-world example → KnowledgeOS mapping → what it does NOT mean.

This is correct in spirit, but the deliverable is not specified as a typed artifact.

**Corrected typing.**

$$LogicVocabularyEntry = (\text{Term}, \text{FormalDef}, \text{PlainDef}, \text{Notation}, \text{RealWorld}, \text{KOSMapping}, \text{NonMeaning}, \text{Regime}, \text{Decidability})$$

Each entry is a KnowledgeOS artifact with provenance.

---

# Part IV — A Typed Logic Vocabulary

Extending the document's proposal, here is the chapter-by-chapter extraction as **typed vocabulary entries**.

## Kernel terms

### Proposition
- **Formal:** Something that is true or false; a claim about how things are.
- **Plain:** A truth-apt assertion.
- **Notation:** $P, Q, R, \ldots$
- **Real-world:** "The server is running."
- **KOS mapping:** A `Claim` after interpretation, but not the text or the sentence.
- **Not:** A sentence, a token, a belief.
- **Regime:** Regime-independent (the proposition itself), but interpretation is regime-relative.
- **Decidability:** N/A.

### Sentence ≠ Proposition
- **Formal:** Sentence types, tokens, and propositions are distinct objects.
- **Real-world:** "I am hungry" said by different people expresses different propositions.
- **KOS mapping:** Text → Statement → Claim → Proposition. Evidence interpretation layer.
- **Not:** Treating a document as its proposition.

### Truth Value
- **Formal:** $v(P) \in \{T, F\}$ in classical semantics.
- **Plain:** Whether the proposition matches the world.
- **KOS mapping:** Semantic value; distinct from epistemic status.
- **Not:** Knowledge of the truth value.
- **Regime:** Classical ⟹ two-valued. Paraconsistent ⟹ four-valued (Belnap).

### Epistemic Status (KOS, not Smith)
- **Formal:** $\{SUPPORTED, REFUTED, UNRESOLVED, CONDITIONAL\}$.
- **KOS mapping:** L3 assessment status.
- **Not:** Truth value.

### Argument
- **Formal:** A sequence of propositions; last is conclusion, rest are premises.
- **Notation:** $\Gamma \vdash \varphi$.
- **Real-world:** Modus ponens.
- **KOS mapping:** L3 determination structure.
- **Not:** Automatically true. Not automatically valid.

### NTP (Necessary Truth Preservation)
- **Formal:** Impossible for premises all true while conclusion false.
- **Notation:** $\Gamma \models \varphi$.
- **KOS mapping:** Entailment in the declared regime.
- **Not:** Merely having a true conclusion.

### Validity
- **Formal:** NTP by form, not by accident.
- **Distinction:** Two kinds: formal, and domain-supported.
- **KOS mapping:** Formal validity → PL/MPL/GPL. Domain-supported validity → Regime-relative.

### Countermodel
- **Formal:** A model where premises are true and conclusion is false.
- **KOS mapping:** Counterexample certificate (I-A07).
- **Not:** A counterexample to a proposition (that would be a state where the proposition is false).

### Satisfiability
- **Formal:** Existence of a model in which the formula is true.
- **Notation:** $\text{Sat}(P) \iff \exists M : M \models P$.
- **KOS mapping:** Admissible World non-emptiness.

### Model
- **Formal:** An interpretation of non-logical symbols.
- **KOS mapping:** Admissible Model State Space $W_{M,O,A,C}$ (already in R601).

### Syntax vs Semantics
- **Formal:** Syntax = well-formed expressions. Semantics = meaning / truth conditions.
- **KOS mapping:** Well-formed claim vs interpreted claim.

### Soundness
- **Formal:** $\text{Provable}(P) \Rightarrow \text{Valid}(P)$.
- **KOS mapping:** L4 invariant catalogue.
- **KOS-side:** No determination is produced unless it is verifiable.

### Completeness
- **Formal:** $\text{Valid}(P) \Rightarrow \text{Provable}(P)$.
- **KOS mapping:** Not yet achieved in general. Requires fixing the logic fragment.
- **KOS-side:** Only achievable for bounded fragments.

### Effective Procedure
- **Formal:** Mechanical, deterministic, step-by-step.
- **KOS mapping:** L4's verification procedures.

### Decision Procedure
- **Formal:** A procedure that terminates with YES or NO for any input.
- **KOS mapping:** Only available for decidable fragments.

### Decidability
- **Formal:** Existence of a decision procedure.
- **Results:** PL decidable, MPL decidable, GPL undecidable.
- **KOS mapping:** Determines the reasoning regime.

### Expressive Power
- **Formal:** What can be expressed in the language.
- **Trade-off:** More expressiveness ⟹ less decidability.
- **KOS mapping:** Regime selection is a trade-off decision.

### Intension vs Extension
- **Formal:** Intension = meaning across possible worlds. Extension = the set of objects satisfying a predicate in a given model.
- **KOS mapping:** Semantic vs Evaluated.

### Identity
- **Formal:** $a = b$; permits uniqueness, counting, distinctness.
- **KOS mapping:** ID from the Kernel.

### Trees / Semantic Tableaux
- **Formal:** Proof method based on branch decomposition.
- **KOS mapping:** A candidate proof procedure for decidable fragments.

### Soundness Theorem (metatheory)
- **Formal:** For a specific system $S$: every $S$-provable formula is valid.
- **KOS mapping:** A specific L4 conformance result for the system.

### Completeness Theorem (metatheory)
- **Formal:** Every valid formula is $S$-provable.
- **KOS mapping:** A specific L4 conformance result; only achievable for bounded fragments.

---

# Part V — Worked Examples

## Example 1 — Logical consequence vs domain-supported consequence

**Setup.**

$$\text{Premise 1: Glass contains water.}$$
$$\text{Premise 2: Water contains H₂O.}$$
$$\text{Conclusion: Glass contains H₂O.}$$

**Smith's analysis.** Valid only if Premise 2 is a *domain axiom* (chemistry), not a logical axiom. Formally: valid in the *chemistry regime*.

**KOS mapping.** Premise 2 belongs to the Regime (Γ). The inference is `DomainConsequence`, not `LogicalConsequence`.

## Example 2 — Proposition vs sentence token

**Setup.** "I am hungry" said by Alice at $t_1$ and by Bob at $t_2$.

**Smith's analysis.** Same sentence type, different propositions: $P_1$ = Alice is hungry at $t_1$, $P_2$ = Bob is hungry at $t_2$.

**KOS mapping.** Evidence interpretation must extract the proposition from the sentence + context.

## Example 3 — Countermodel as counterexample certificate

**Setup.** Claim: `∀ x (Bird(x) → Flies(x))`.
Countermodel: $x$ = penguin; `Bird(penguin) = True`, `Flies(penguin) = False`.

**Smith's analysis.** The countermodel refutes the entailment.

**KOS mapping.** This is exactly the structure of the R604.3 counterexample certificate.

## Example 4 — Undecidability of general predicate logic

**Setup.** A quantified statement over an infinite domain.

**Smith's analysis.** GPL is undecidable in general (Church-Turing).

**KOS mapping.** KnowledgeOS cannot promise a decision procedure for such claims. The correct statuses are `UNKNOWN` or `UNDECIDABLE`.

**Consequence.** Any KOS reasoning layer invoking GPL must operate with bounded fragments or accept `UNKNOWN`.

## Example 5 — Soundness of L4

**Setup.** L4 verifies an assessment.

**Smith's analysis.** A sound L4 means: any assessment produced by L4 is valid under the declared regime.

**KOS mapping.** The invariant catalogue is a soundness artifact.

## Example 6 — Completeness of L4

**Setup.** L4 receives a valid assessment as input.

**Smith's analysis.** A complete L4 would prove every valid assessment.

**KOS mapping.** This is *not* achievable in general. It is achievable only for bounded fragments.

## Example 7 — The three failure kinds

**Setup A: Logical failure.** Premises $P, Q$; conclusion $R$. No entailment. Failure = logical.

**Setup B: Dependency failure.** Premises $P_1, P_2, P_3$ all derived from a common source. Entailment may hold, but premises are not independent.

**Setup C: Computational failure.** Entailment holds in GPL, but no decision procedure finds it within the regime.

**KOS mapping.** Three separate failure kinds; three separate metrics; three separate remedies.

## Example 8 — Extension vs intension across contexts

**Setup.** Predicate "adult" in jurisdiction $J_1$ (age ≥ 18) and $J_2$ (age ≥ 21).

**Smith's analysis.** Extension in $J_1$ ≠ extension in $J_2$. Intension captures how the meaning determines the extension across contexts.

**KOS mapping.** Context (Ctx) and Regime (Γ) determine extension. This directly supports R575's context-dependency distinction.

## Example 9 — Identity in KOS

**Setup.** "Mark Twain = Samuel Clemens."

**Smith's analysis.** Identity assertion; permits substitution.

**KOS mapping.** KOS's ID is exactly this. Two identities may refer to the same entity.

## Example 10 — Model vs possible world

**Setup.** A model $M$ assigns interpretations to symbols. A possible world $w$ describes a state of reality.

**Smith's analysis.** $M$ is a formal object; $w$ is a metaphysical one. $M \models P$ iff $P$ is true in $M$.

**KOS mapping.** $W_{M,O,A,C}$ is the set of admissible models; the actual world $w^*$ is a specific member.

---

# Part VI — Architecture, ML Positioning, Short Status

## VI.1 — Architecture (updated with Smith's layers)

Smith's three layers map onto L0–L6 as follows:

```
┌───────────────────────────────────────────────┐
│ L0 Kernel (ID, R*, Sem)                       │
│ — Identity in the sense of Smith              │
│ — Relations as typed                          │
│ — Semantics (interpretation)                  │
└───────────────────┬───────────────────────────┘
                    │
┌───────────────────┴───────────────────────────┐
│ L1 Semantic Fabric                            │
│ — Meaning, Context, Scope, Contract, Regime   │
│ — Includes domain axioms (chemistry, law)     │
└───────────────────┬───────────────────────────┘
                    │
┌───────────────────┴───────────────────────────┐
│ L2 Formal Fabric                              │
│ — Smith's formal logic                        │
│ — PL / MPL / GPL / GPLI fragments             │
│ — Models (W_{M,O,A,C})                        │
│ — TPP / Composition / Preservation / Loss     │
└───────────────────┬───────────────────────────┘
                    │
┌───────────────────┴───────────────────────────┐
│ L3 Epistemic Assessment                       │
│ — Evidence & Dependency layer                 │
│ — Admission, Belnap, Conflict                 │
│ — ImpactAssessment, CompletenessAssessment    │
│ — Not in Smith; extends classical logic       │
└───────────────────┬───────────────────────────┘
                    │
┌───────────────────┴───────────────────────────┐
│ L4 Assurance                                  │
│ — Soundness-like invariants                   │
│ — Verification / Counterexample               │
│ — Completeness is bounded                     │
└───────────────────┬───────────────────────────┘
                    │
┌───────────────────┴───────────────────────────┐
│ L5 Intelligence                               │
│ — Candidate generation                        │
│ — Not in Smith; ML is KOS-specific            │
└───────────────────┬───────────────────────────┘
                    │
┌───────────────────┴───────────────────────────┐
│ L6 Governance                                 │
│ — Authority, Permission, Decision             │
│ — Not in Smith; KOS-specific                  │
└───────────────────────────────────────────────┘
```

No L4.5. No L7. No L8. No new BC.

## VI.2 — ML positioning

Smith's logic has **no** ML layer. Everything Smith calls "reasoning" is deterministic (truth tables, trees). ML enters KnowledgeOS only at L5.

**Consequence.** Smith's logic reinforces the ML firewall: ML produces *candidates*; formal machinery (L2 + L4) *disposes*.

$$\boxed{MLCandidate \not\Rightarrow \text{Formal Consequence}}$$
$$\boxed{MLScore \not\Rightarrow \text{Valid}}$$
$$\boxed{MLConfidence \not\Rightarrow \text{Provable}}$$

## VI.3 — Short bullet status

### What Smith's book adds

- **Terminology**: proposition, argument, NTP, validity, satisfiability, model, countermodel, soundness, completeness, decidability, expressive power.
- **Three-layer separation**: Formal Logic / Evidence & Dependency / Determination.
- **Three failure kinds**: logical, epistemic/dependency, computational.
- **Metatheoretic vocabulary** for L4 assurance.
- **Decidability landscape** for fragment selection.
- **Soundness/completeness** as the correct framing for L4 invariants.

### What Smith's book does NOT add

- Dependency calculus.
- Materiality, minimality, interaction dependency.
- Impact assessment, completeness assessment, universe closure.
- Certificate lifecycle, selective revalidation.
- Regime admission, cross-regime composition.
- ML firewall.
- All of R574–R589.

### Achieved (cumulative)

- Kernel stable; L0–L6 stable; no new BC, no new layer.
- R575–R589 executed and passed finite tests.
- Dependency, materiality, impact, completeness, closure calculus all formalized.
- ML firewall preserved throughout.
- **Smith's logic imported as foundational vocabulary** without expanding the Kernel.
- Report discipline: still partial.

### Not yet done

- **R586.1 / R587.1 / R589.1** — report discipline + enumeration.
- **R590** — n-way composition counterexample.
- **Smith vocabulary freeze** as a typed LogicVocabulary artifact.
- **Fragment declaration** for each KOS reasoning layer (PL, MPL, GPL, GPLI).
- **Decidability profile** per layer.
- Step 545 full benchmark execution.
- ML revalidation-priority benchmark.
- Full invariant engine.
- Terminology freeze.
- Theory Specification v1.0.

### Standing rules (consolidated, with Smith's additions)

- No new Kernel primitive.
- No new BC, no new layer.
- No universal theorems from finite tests.
- Use the weakest sufficient method.
- Each round attempts to falsify the previous round.
- Report discipline: `ExecutionRunID` + `certificate.json` per test.
- ML cannot write to X, cannot bypass L4.
- `MLCapability ≤ InformationAvailable`.
- `Greedy ≠ Optimal` under synergy.
- All lattices (Admission, Belnap, Completeness, CompositionalClosure) stated explicitly.
- `Conflict ≠ Contradiction`; `RegimeDifference ≠ Conflict`.
- `Valid(B₁) ∧ Valid(B₂) ⇏ Valid(B₂ ∘ B₁)` without conditions.
- `Function ⊊ Typed ⊊ Epistemic` composition.
- `Undefined ≠ Rejected ≠ Unknown ≠ Undecidable`.
- `LocalTargetPreservation ⇏ GlobalChainPreservation`.
- `Loss(T₂ ∘ T₁) ≠ Loss(T₁) ∪ Loss(T₂)`.
- `Revision ⇒ HistoryPreservation ∧ SelectiveRevalidation`.
- `Affected ≠ Invalid`.
- `EpistemicState ≠ AssuranceLifecycleState ≠ GovernanceAuthority`.
- `Acquisition ≠ Transformation ≠ Evidence`.
- `CurrentValidity ≠ HistoricalValidity`.
- `UNKNOWN → FAIL ⇒ ExplicitFailAssessment`.
- `Terminal lifecycle states cannot resurrect`.
- `Closure_D(E) ≠ TrueImpact(E)` when $D$ incomplete.
- `Dependency ≠ Impact`.
- `No-edge ≠ proven-unaffected` without completeness.
- `ImpactAssessment ∈ {AFFECTED, NOT_AFFECTED, UNKNOWN}`.
- `PositiveDetection is existential; NegativeProof is universal`.
- `CompletenessClaim ≠ EstablishedCompleteness`.
- `MLConfidence ≠ CompletenessProof`.
- `FalseNegativeImpact is the most dangerous failure`.
- `D* ≠ D_O ≠ D_C ≠ D_E`.
- `HiddenDependency ≠ MaterialImpact`.
- `I-C19: NOT_AFFECTED requires established completeness basis`.
- `ExhaustiveSearch(U) ⇏ Complete(D_O)`.
- `CompletenessCertificate ⇒ VerifiedUniverseClosure`.
- `UC(U₁) ∧ UC(U₂) ⇏ UC(U₁ ∪ U₂)` without conditions.
- **Smith's additions:**
  - `Provable ⇒ Valid` (soundness).
  - `Valid ⇒ Provable` (completeness) — only for bounded fragments.
  - `Decidable(PL) ∧ Decidable(MPL) ∧ ¬Decidable(GPL)`.
  - `ExpressivePower ↑ ⇒ Decidability ↓`.
  - `Sentence ≠ Proposition`.
  - `Truth ≠ KnowledgeOfTruth`.
  - `LogicalConsequence ≠ DomainConsequence`.
  - `Countermodel ⇒ ¬Entailment`.

### The single most important next thing

$$\boxed{\text{Freeze the LogicVocabulary artifact, declare fragment per layer, then build R590 (n-way composition counterexample).}}$$

Because the Smith import is the right foundational vocabulary, and the correct way to use it is to *declare* which fragment each KOS reasoning layer operates in. Without that declaration, every completeness and decidability claim is ambiguous.

The report-discipline fix is now the single largest auditability gap and must be closed.

### Two open questions I flag

1. **Which logical fragment should each KOS layer commit to?**
   My recommendation:
   - **Determination**: PL + MPL (decidable).
   - **Dependency**: finite structural (decidable).
   - **Completeness**: finite universe (decidable) or explicit `UNKNOWN` for open universe.
   - **Full GPL**: only under declared `UNDECIDABLE` status.

2. **Should completeness of L4 be a stated goal or explicitly abandoned?**
   Smith shows completeness fails for GPL. My recommendation: L4 should state completeness *only for bounded fragments* and explicitly declare `¬Complete` for the general case. This preserves the invariant catalogue's integrity.