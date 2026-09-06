# 17 — Independent Gap-Discovery Verdict

**Session:** independent verification and theory-gap discovery, 2026-08-30.
**Corpus snapshot:** commit `57d93b0e`, branch `knowelegeos-modelling`.
**Independence:** prior verification artifacts in `../` were not consulted while producing documents
01–15 (commitment INV-7). Where this session's conclusions coincide with theirs, the coincidence is
noted and is **not** treated as corroboration — see Q6 and finding EV-0.

**Evidence produced by this session:** 7 executable programs, 29 experiments, all reproducible
(`exec/`). Plus reproduction of the three pre-existing scripts and four commands against the running
system.

---

## THE VERDICT

$$\boxed{\textbf{NOT COMPLETE — SPECIFIC CLOSING WORK REMAINS}}$$

**Not** `FOUNDATIONAL GAP — THEORY MUST BE RECONSTRUCTED`. The distinction matters and is the main
result of this session.

The theory does not need to be rebuilt. Sixteen results survive falsification (register §E), the
central object is instantiated and running, and the largest single gap is not a wrong answer but a
**missing premise**: the mandatory operation set `𝒪` was never written down. Five of the thirteen
CRITICAL gaps are downstream of that one omission and close mechanically once it is fixed.

The corpus's own terminal verdicts —
`COMPUTABILITY: PARTIALLY ESTABLISHED — NOT CLOSED` (Step 266) and
*"the empirical bridge is partially populated but not closed"* (Step 267) — are **correct**. This
session reached the same conclusion independently, and then found that the corpus was wrong about
*which* parts are missing.

---

## The twenty questions

### 1. What is the smallest theory actually established by the evidence?

Two spaces kept apart (`Ω_D`, `Ω_E`); five primitive kinds
(`Observation`, `Proposition`, `Event`, `Time`, `Source`); one relation
(`Evidence(O,P,C,R)`); one record type (`Assertion = (id,P,e,c,t,Π)`); one graph (`K = (𝒜,ℛ)`); two
running status axes (lifecycle ⊥ source-trust); and a transformation `T` over an operation set that
has not been written down.

Full derivation with per-component evidence classes: `02-INDEPENDENT-THEORY-RECONSTRUCTION.md`.

**Notably absent from that list: a definition of *knowledge*.** Every operation the theory needs is
definable without one. Step 253 §253.40 leaves it unresolved; this session's reconstruction shows it
is **not on the critical path**.

### 2. What parts are mathematically proven?

Little, and the corpus is honest about it. Genuinely derived: `≡` must be a **congruence**, not
merely an equivalence (Step 260 §260.9); *semantic minimality ≠ syntactic compactness*; the `t=0`
argument that provenance cannot be derived from lineage; reflexivity/symmetry/transitivity of
observational equivalence (correct but trivial — they hold for any observational equivalence).

**Nothing in the corpus is a theorem in the strict sense**, because the objects theorems would
quantify over (`𝒯`, `≡`, `K*`) are not constructed.

### 3. What parts are computationally demonstrated?

Before this session: **three** — `Zero(K,EC)` computability, the evidence-operator recheck, and the
status-ladder/decision-contract reference, all from the Claude review thread, all re-run here and all
passing.

Added by this session: a total, deterministic, replayable `T` over a four-operation `𝒪`; lineage in
`O(n+m)`; the merge algebra (exhaustively searched); the Σ necessity decomposition; the dependency
graph and its cycle; the removal test Step 254 specified and never ran; and the EKP bridge.

The corpus itself — 550 files, 270 steps — contributed **zero** executable artifacts, including five
steps titled *"executable"*, *"execute"* or *"simulation"*.

### 4. What parts are empirically validated?

`K = (𝒜, ℛ)`: **40 assertions, 59 typed relations, 6 relation types, referential integrity holding,
37 documents linted green on every change.** Two orthogonal status axes, schema-enforced, with 7
distinct observed pairs. A typed provenance graph with branching, 4 tests. A constitutional
self-amendment rule, machine-enforced via `frozen`.

Nothing else. Four of five invariant checks pass **vacuously**.

### 5. What parts are only proposals?

Policy, Authority-as-formalism, Assessment, `Σ_epistemic`, `K*`, minimality, the evidence algebras
(five steps of them), uncertainty, all four quantitative formulas, the transition signature, and the
`ComputableCore`/`JudgementBoundary` architecture — which is the best proposal in the corpus and is
unbuilt.

### 6. What contradictions remain?

- 28 `K` definitions across 7 mathematical kinds, no morphism between kinds (G-09).
- 45 transition RHS strings, 11 named functions, arity 1–6 (G-16).
- Three non-identical `K` definitions inside Q14, a document titled *"the complete formal
  definition"* (G-51).
- Two non-identical documents titled *"Evidence Aggregation Algebra"* (G-44).
- **A methodological one that governs the rest (G-13):** after ~Step 258 the "primary" and
  "secondary" corpora are one interleaved conversation, each citing the other within minutes.
  Steps 269, 270 and 271 were written **during this session**. Agreement between the two trees is
  therefore not independent corroboration, and any claim resting on it must be demoted to a single
  source.

### 7. What concepts remain overloaded?

`Status` (≥5 orthogonal facts in one word — the worst case, uncaught for 270 steps because UL audits
looked for inconsistent usage rather than dimensional overload) · `Authority` (permission relation
vs. trust rank) · `Evidence` (relation vs. object) · `Provenance`/`Lineage` (swapped in several
steps) · `𝒦` (measurable space vs. meta-structure) · `Knowledge` (field / atom / state / capacity) ·
`Candidate` (evidence-insufficiency vs. pre-authority).

### 8. What mathematical symbols remain undefined?

`ρ` (in the most-repeated transition equation) · `Ω` (three incompatible readings) · `C`/`c`
(context — untyped, and it is a parameter of both the assertion and the qualification rule) ·
`Claim` · `Verdict` · `𝒯` (the operation set — **the keystone**) · `≡` (defined by quantification
over `𝒯`) · `V_D` (value space per dimension) · every quantity in the measurement inventory lacks a
scale type with an empirical relational structure.

### 9. What implementation mismatches remain?

The two largest are **in the corpus's favour and against it, respectively**:

- **In its favour:** `K = (𝒜,ℛ)` **is** implemented (`13` IR-1). Step 267 declared it
  `IMPLEMENTATION MISSING` because it searched PublicDigit's election domain instead of the
  Engineering Knowledge Platform. **`REFUTED`.**
- **Against it:** `Σ_epistemic` has **no instance and no design** in the running system — no field, no
  lint rule, nothing that records whether a claim is supported by evidence.

Also: `GovernanceLineageGraph` is an election-platform class cited as `History` (Type 3 analogy
reported as Type 1 realization, by Step 267's own taxonomy); `authorities.yaml`'s **trust rank** is
mapped to the theory's **permission** concept; the "47 tests" figure reproduces exactly and covers 18
unrelated classes, of which one (4 tests) is on-topic.

### 10. What DDD/UL mismatches remain?

The aggregate boundary is never fixed — `K` is argued as both Aggregate and projection, with opposite
consequences for concurrency, merge and invariants. `ℛ` edges have **no DDD classification at all**,
no identity, no provenance, no status — and are the component that makes the state sufficient.
`Regime`, the corpus's best strategic concept and cleanest ACL boundary, vanished after 2026-08-26
and was reinvented without the word on 2026-08-30.

**And one mismatch in the other direction: the implementation's vocabulary discipline is cleaner than
the theory's.** Controlled vocabularies, typed relations with declared inverses, explicit
orthogonality in the schema comments. The theory should adopt it, not the reverse.

### 11. What governance/reflexivity gaps remain?

The dependency graph is cyclic; Step 187 cuts it by **stipulating** that authority is exogenous. That
is architecturally necessary and normatively decided — **not** a mathematical result, though the
corpus presents it as a finding.

Empirically the running system does the opposite in both directions: it **internalizes**
constitutional self-amendment (correctly — ADR + supersession + ARB, machine-enforced) and
**externalizes** the vocabulary that defines every status and authority (incorrectly — all ten schema
files carry zero knowledge cards, no owner, no review, no lint). Editing `statuses.yaml` silently
changes the meaning of every governed document in the system.

And the check that would police exactly this — the structural profile's vocabulary-integrity slice —
is **132/132 INCONCLUSIVE**, because its config file does not exist.

### 12. What are the CRITICAL gaps?

**Thirteen:** G-01 (`𝒪` unenumerated) · G-02 (conditional minimality) · G-03 (`A∈K`, `K₁=K₂`
ill-defined) · G-04 (cyclic dependency graph) · G-05 (11/26 transitively non-computable) · G-06 (Σ is
≥5 axes) · G-07 (ordinal averaging) · G-08 (`Determination` absent) · G-09 (28 incomparable `K`s) ·
G-10 (ungoverned vocabulary) · G-11 (evidence layer has no inputs) · G-12 (no empirical relational
structure) · G-13 (corpus circularity).

### 13. What are the HIGH gaps?

**Twenty-five** — `16-MASTER-GAP-REGISTER.md` §B.

### 14. Which gaps can be resolved from the corpus?

G-15 (`Context` — Step 253 and the EKP's `bounded_context` between them supply the material) ·
G-25 (`Unknown` — `025d`'s nine-status set already has the arms) · G-27 (`Insufficient` — same) ·
G-30 (`Regime` — restore the 2026-08-25 formulation) · G-39, G-43, G-44, G-45, G-49, G-50, G-51 ·
and G-09 largely dissolves under the reframing in `04` KG-8.

### 15. Which gaps require mathematics?

G-03 and G-06 (once `𝒪` is fixed, a congruence is unique up to isomorphism and the Σ product falls
out) · G-12 (state the empirical relational structure and test the weak-order axioms) · G-20 (prove
the semilattice laws for the chosen merge rule) · G-05 (prove the `A_struct` split puts `K_struct` in
the computable core).

### 16. Which gaps require execution?

G-36 (the selection precision/recall experiment — specified on day 1, still runnable, never run) ·
G-07 (audit every existing rule for ordinal arithmetic) · G-21 (re-derive or relabel the two formulas)
· G-34 (exercise the supersession machinery or remove it).

### 17. Which gaps require actual implementation evidence?

G-10, G-32, G-33, G-34, G-35, G-38 — all six are about the running EKP, and all six are cheap:
create `vocabulary-integrity.yaml`; give the schema files knowledge cards; add a covering relation to
`statuses.yaml`; add a transition-legality lint rule; correct the "47 tests" figure in the fourteen
artifacts that carry it.

### 18. Which gaps genuinely require a human normative decision?

**Three.** They are set out in §D below, in the mandate's required format. Everything else in the
register is derivable, executable, or a corpus-resolvable ambiguity.

### 19. What exact sequence of work would close the remaining gaps?

See §C.

### 20. Can KnowledgeOS theory now honestly be called complete?

**No.** And no artefact in the corpus claims otherwise: Step 266 says
`PARTIALLY ESTABLISHED — NOT CLOSED` and Step 267 says the bridge is `not closed`. This session
confirms both independently and adds the sharper statement:

> **The theory is not incomplete because it is missing answers. It is incomplete because it is
> missing a premise.** `𝒪` — what KnowledgeOS is obliged to be able to do — was never written down,
> and five CRITICAL gaps are its shadow. A theory of *sufficient state* cannot be evaluated without
> the operations it must be sufficient for.

---

## C. The closing sequence

Ordered by dependency, not by importance. Steps 1 and 2 are prerequisites for most of the rest.

| # | Work | Closes | Kind |
|---|---|---|---|
| **1** | **Enumerate `𝒪`** — the mandatory operation set, with signatures, preconditions, postconditions and failure semantics. Start from the EKP's 16 lint rules plus the 4-operation reference kernel; both are real, running candidates. | G-01, G-02, G-16, G-17 | **HUMAN D-1**, then engineering |
| **2** | Ratify or revise the exogenous-authority stipulation, and govern the schema vocabulary either way. | G-04, G-10, G-38 | **HUMAN D-2** |
| **3** | Decide whether `Determination` and conditional structure are in scope. | G-08, G-26 | **HUMAN D-3** |
| **4** | Derive the congruence and `K*` for the fixed `𝒪`; settle `A∈K` and `K₁=K₂`. | G-03, G-09 | mathematics |
| **5** | Replace Σ with the ≥5-axis product; add `Insufficient`; move `Contested` and `Superseded` to `ℛ`. | G-06, G-25, G-27, G-40, G-41 | mathematics + engineering |
| **6** | Split `A` into `A_struct` + qualification layer; make `ℛ` edges first-class; restore `H` as `𝒦=(K,H)`. | G-05, G-19, G-24, G-31 | modelling |
| **7** | Type `Context`; define evidence identity, provenance and validity; give `Relevant` a procedure or move it across the judgement boundary. | G-11, G-14, G-15 | modelling |
| **8** | Audit every numeric rule for ordinal arithmetic; relabel the two formulas as declared heuristics; state the empirical relational structure for any quantity that is to remain. | G-07, G-12, G-21, G-22 | execution + mathematics |
| **9** | EKP repairs: `vocabulary-integrity.yaml`; knowledge cards on the schema files; covering relation for `statuses.yaml`; a transition-legality rule; correct the "47 tests" figure in 14 artifacts. | G-32…G-35, G-10 | engineering |
| **10** | Run the selection precision/recall experiment specified on 2026-08-25. | G-36 | execution |
| **11** | Before the next verification pass, **freeze one tree**. The two corpora can no longer check each other. | G-13 | method |

---

## D. HUMAN NORMATIVE DECISIONS REQUIRED

Three, after exhausting corpus reconstruction, formal derivation, counterexample, executable
experiment, implementation evidence and DDD analysis, as §23 requires.

---

### **D-1 — What is the mandatory operation set `𝒪`?**

**Question.** Which operations is KnowledgeOS obliged to support? Specifically: does `𝒪` contain
**history-sensitive predicates** — e.g. *"has this assertion ever been contested?"*, *"how many times
has it been revised?"*, *"was anything withdrawn?"*

**Why the corpus cannot decide.** It never enumerates `𝒪`. Step 266 marks the operation registry
missing; Step 259 and Step 260 both quantify over it.

**Why mathematics cannot decide.** `≡` is *defined* by `∀T ∈ 𝒯`. Mathematics can derive the
congruence, the quotient and the minimal state **for any given `𝒪`** — it cannot choose `𝒪`. That is
a statement about what the product must do.

**Why implementation cannot decide.** The EKP supplies one candidate (16 structural lint rules) and
it contains **no** history-sensitive predicate — but the EKP is one instance, and its silence on
epistemic status shows it is not yet the full target.

**Options.**

| | `𝒪` | Consequence |
|---|---|---|
| **A** | structural only — query, explain, relate, validate | `K=(𝒜,ℛ)` **is** sufficient and minimal. Everything closes cleanly. The system cannot answer *"was this ever contested?"* |
| **B** | + history-sensitive predicates | `K=(𝒜,ℛ)` is **insufficient**; `𝒦=(K,H)` is required; replay and operation versioning become mandatory |
| **C** | + policy/authority evaluation | `𝒪` enters Step 266's class C; the deterministic core shrinks to `A_struct` and the judgement boundary must be made formal |

**Advantages / risks.** A is computable, provable and small, and is what runs today — at the cost of
being unable to answer ordinary governance questions. B costs storage and replay discipline and buys
auditability. C is the most faithful to the corpus's ambition and is the least tractable.

**Recommendation: B**, with C's judgement boundary declared but out of the deterministic core —
i.e. Step 266 §266.31's own `ComputableCore` / `JudgementBoundary` split. Rationale: `exec/exp_provenance.py`
Test 6 shows four audit-relevant questions are unanswerable under A, and audit is what the platform is
for; C's contents are class C by the corpus's own audit and belong outside the core regardless.

**Invariant across all options.** The sixteen surviving results (register §E) hold under every
option. `K = (𝒜,ℛ)` remains the structural core in all three; only what surrounds it changes.

---

### **D-2 — Is authority exogenous to KnowledgeOS?**

**Question.** Step 187 stipulates *"the Kernel enforces authority claims; it does not originate
authority."* Ratify, or model authority endogenously?

**Why the corpus cannot decide.** It already decided — by stipulation, not derivation, and it
presents the decision as a finding (`10` GR-3).

**Why mathematics cannot decide.** Both are consistent. Exogeneity gives an acyclic graph relative to
an external oracle; endogeneity requires a fixed-point construction, which is standard for
self-amending systems and merely harder.

**Why implementation cannot decide — and why it is nonetheless decisive evidence.** The running
system does **both, inconsistently**: the constitution self-amends under a real, machine-enforced
rule (endogenous, and correct), while the ten schema vocabulary files that define every status and
authority are ungoverned (exogenous, and unprotected). **The status quo is not a third option; it is
the failure mode of not having chosen.**

**Options.**

| | | Consequence |
|---|---|---|
| **A** | Ratify exogeneity | Graph acyclic. Every theorem about `K` becomes conditional on an unspecified external oracle. **The schema files must then be moved out of `docs/knowledge/` and protected by a mechanism outside the system.** |
| **B** | Model authority endogenously | Authority grants become assertions with provenance, status and lineage. The cycle becomes a fixed point requiring a base case (a root grant). Everything is auditable inside one model. |
| **C** | Two-tier: a small frozen constitutional core (exogenous) governing an endogenous layer | Matches what the EKP already half-does. Requires stating exactly which artefacts are in the frozen core. |

**Recommendation: C.** It is the only option consistent with the evidence — the EKP already has a
constitutional core with a real amendment rule; it has simply not enumerated its members, which is
why the schema files fell outside it. Under C the immediate action is concrete: **declare the ten
schema files part of the constitutional core and govern them with the constitution's own ADR + ARB
rule.**

**Invariant across all options.** `Authority ⊆ Actor × Action × Context × Time` (Step 187 §187.14)
holds in all three. `Authority ≠ Evidence` holds in all three. Only the *placement* of the grant
changes.

---

### **D-3 — Is conditional determination in scope?**

**Question.** The corpus opened on *"determination is the missing mathematical object"* and closed on
a proposition type that cannot express a conditional. `Determination` occurs **0 times** in all six
terminal steps. Reopen it, or ratify the narrower scope?

**Why the corpus cannot decide.** No step decided this. The question was not answered and not
rejected — it stopped being asked across the Step-213 regime change, and nothing in the corpus's
machinery (contradiction registry, concept genealogy, gap registers) is designed to notice a question
that stops being asked.

**Why mathematics cannot decide.** Extending `P` with conditionals, negation and quantification is
routine — that is first-order logic. Mathematics cannot say whether KnowledgeOS is *supposed* to
carry rules or only their conclusions.

**Why implementation cannot decide.** The EKP stores conclusions, not rules. Its rules live in PHP
(the linter). That is a working answer to option A, and it is silent on whether it is the intended
one.

**Options.**

| | | Consequence |
|---|---|---|
| **A** | Ratify the narrow scope: `K` stores **determinations**, rules live outside in Policy | The current terminal model is correct as-is. `P=(E,D,V)` suffices. The system cannot explain *why* a determination holds — only *that* it does, and what it derives from. |
| **B** | Reopen: extend `P` to a logical language with conditionals, negation, quantification | Restores the founding problem. Requires proof theory, decidability analysis, and a much larger `𝒪`. |
| **C** | Hybrid: `K` stores determinations **plus a reference to the rule instance** that produced each one | Explanation becomes possible without putting a logic inside `P`. The rule stays external and versioned. |

**Recommendation: C.** It costs one field, preserves every surviving result, and makes explanation —
the thing the business example (`20260826-000501`) actually asked for — answerable. Option A is
defensible and cheap; it should be chosen deliberately rather than by default, which is what has
happened so far. Option B is a research programme, not a closing step.

**Invariant across all options.** `Evidence(O,P,C,R)`, `admission ≠ truth`, `P ≠ A`, and the whole of
register §E hold under all three.

---

## E. Method note, and what this session did not do

**Reproducibility.** Every number in these seventeen documents traces to a command. The programs are
in `exec/`; transcripts are in `exec/OUT-*.txt`; the corpus scan commands are in
`00-CORPUS-INVENTORY.md` §9.

**Corrections made in flight, recorded rather than hidden.** Three claims this session initially
formed and then withdrew on evidence: (i) that `K = (K,…)` was self-referential — it is `𝒦` vs `K`,
checked at source; (ii) that a latest-wins merge was non-associative on a hand-picked triple — it was
associative there, and an exhaustive search then found 4 genuine counterexamples; (iii) that the EKP
had a dangling relation target and two duplicate-authoritative violations — both were artefacts of
this session's own parser and are withdrawn.

**Not done.** Documents 01–15 were produced without reading the prior verification artifacts, so this
session cannot say which of its findings are new to the programme and which restate work already in
`FINAL-THEORY-GAP-REGISTER.md`, `THEORY-CLOSURE-AUDIT.md` and the rest. **That comparison is the
obvious next task, and it should be done by a reader who has both** — with G-13 in mind, since after
Step 258 the two trees are no longer independent witnesses.

**One thing the corpus should adopt regardless of the three decisions.** Five steps titled
*executable*, *execute* or *simulation* contain no program. The corpus's strongest results —
Step 265's `t=0` argument, Step 260's congruence requirement, Step 266's computability audit — are
its most rigorous *because* they could be checked by reading. Everything quantitative in it fails
when checked by running. **The cheapest single improvement to the research method is to require that
a step claiming execution ship the program.**

---

*End of the independent gap-discovery series. Documents 00–17 plus `exec/`.*
