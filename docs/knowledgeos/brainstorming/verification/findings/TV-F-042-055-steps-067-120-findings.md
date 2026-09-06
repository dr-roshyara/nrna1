---
artifact: TV-F-042 … TV-F-055
track: A (verification)
phase: 2C
covers: Steps 067–082, 083–100, 101–120 (54 theory-bearing documents)
date: 2026-08-30
status: DELIVERED
authority: verifier session (adversarial, independent)
sources:
  - spec/STEP-VERIFY-067-082.md
  - spec/STEP-VERIFY-083-100.md
  - spec/STEP-VERIFY-101-120.md
discipline: |
  SOURCE RESULT / VERIFIER OBSERVATION / POSSIBLE REPAIR are kept separate throughout.
  NO SILENT REPAIR: no repair is applied to the corpus. Repairs appear only under
  "POSSIBLE REPAIR — NOT ESTABLISHED BY CORPUS".
  Band-agent claims are HYPOTHESES. Every finding below marked [SUPERVISOR-VERIFIED]
  was re-checked first-hand against the corpus by the supervising verifier.
  Where a band agent overstated, the correction is recorded explicitly (TV-F-042).
---

# Findings TV-F-042 … TV-F-055

## Verification status legend

- `[SUPERVISOR-VERIFIED]` — the supervising verifier re-ran the check against the corpus first-hand.
- `[BAND-REPORTED]` — asserted by the band agent, plausible, not independently re-checked at supervisory level.

---

## TV-F-042 — Step-069's five-invariant back-reference: four are phantom, one is legitimate (SUBAGENT CORRECTION)

**Class:** citation to a non-existent object · **Severity:** load-bearing · `[SUPERVISOR-VERIFIED]`

**SOURCE RESULT.** Step-069 §69.6 asserts that `I_Type`, `I_Provenance`, `I_Causal`, `I_Authorization`,
`I_Conflict` were "previously established", and uses that assertion as the sole justification for the
transition rule `T(S,e) = Error` when an event would violate an invariant — i.e. the invariant-validation
branch of step-069's state machine.

**BAND CLAIM (067–082 agent, VF-1).** All five names were never minted; `I_Causal` is an *anachronistic
forward-reference* first minted at step-074 §74.62, five steps later.

**VERIFIER OBSERVATION — THE BAND CLAIM IS PARTLY WRONG AND IS CORRECTED HERE.**
First-hand corpus-wide grep (not band-scoped):

| Name | First occurrence in corpus | Status of 069's citation |
|---|---|---|
| `I_Type` | step-069 itself | **PHANTOM** — cited as established, never minted anywhere prior |
| `I_Provenance` | step-069 itself | **PHANTOM** |
| `I_Authorization` | step-069 itself | **PHANTOM** |
| `I_Conflict` | step-069 itself | **PHANTOM** |
| `I_Causal` | **step-063**, line 1448–1457, `We can now introduce: I_{Causal}: ObservedAssociation cannot be promoted to CausalRelation without an explicit causal basis.` | **LEGITIMATE** |

`I_Causal` was genuinely minted at step-063, six steps *before* step-069 cites it. Step-069's reference to
it is sound. The band agent reached the opposite conclusion because its scope began at step-067 and it had
established that no in-scope file cites below step 66 — a **scope artifact**, not a corpus fact. This is
recorded as a correction rather than applied silently, per the no-silent-repair rule.

**The residual defect is real and narrower than reported:** 4 of 5 names in a single load-bearing citation
do not exist. A separate defect surfaces from the same check — step-074 §74.62 **re-mints `I_Causal` with
different text** ("KnowledgeOS must not promote observational association, temporal precedence, or
computational dependency to causal knowledge without an explicit causal basis") from step-063's original.
Two texts, one name, no supersession note. That is a duplicate mint, not a forward reference.

**POSSIBLE REPAIR — NOT ESTABLISHED BY CORPUS.** Either mint the four missing invariants before step-069
or restate §69.6's justification over invariants that exist; and reconcile the two `I_Causal` texts.

---

## TV-F-043 — Step-070's minimal-kernel claim is false as written, and two incompatible kernels are boxed 20 lines apart

**Class:** false universal quantifier · **Severity:** load-bearing · `[BAND-REPORTED]`, arithmetic of the claim `[SUPERVISOR-VERIFIED]` by inspection

**SOURCE RESULT.** §70.19 boxes `K_OS = Artifact+Type+Provenance+Event+Invariant+State+Transformation+Policy`
with the justification "Each has survived a removal test."

**VERIFIER OBSERVATION.** The kernel has eight elements; the removal tests produce FAIL for seven. Element
**S (State)** received the corpus's unique verdict `PASS WITH PERFORMANCE FAILURE` (§70.14), and §70.20
concedes in the author's own words that "`State` is not necessarily fundamental in the mathematical sense."
The universal "each" is therefore false. Two of the seven FAILs (Exp 7 implicit transformation, Exp 8 no
policy) are additionally circular — they restate step-067's `I_5` and `I_6` as scenarios rather than
demonstrating an independent property breaking.

Compounding: §70.21 boxes a **seven**-element logical kernel `K_L = (A,T,P,E,I,X,R)` excluding S, twenty
lines after boxing the eight-element kernel including S. Both are live.

**Second-order defect.** Removal tests establish *necessity of each element individually*. They never
establish *sufficiency of the set*. "Minimal kernel" asserts both. The overstatement is exactly one
quantifier wide and is never noticed.

**Third sense of "kernel" in the same file.** §70.61/§70.64 use "Governed Semantic Kernel" / "SemanticKernel"
as an *architectural tier* opposed to computational providers — where §70.64's `SemanticKernel` is a summand
alongside `KnowledgeGraph` and `Governance`, although §70.1's `K_OS` already contains Provenance and Policy,
which are precisely graph and governance concerns. Three non-co-extensive senses, used interchangeably.

---

## TV-F-044 — Step-087's Condorcet counterexample does not exhibit a cycle

**Class:** invalid worked example certified by a boxed PASS · **Severity:** high · `[SUPERVISOR-VERIFIED]`

**SOURCE RESULT (verbatim, §87.2–§87.3).** "Three agents rank alternatives: `A≻B`, `B≻C` for two agents,
while the third prefers: `C≻A`. Pairwise majority can produce: `A≻B`, `B≻C`, but: `C≻A`. This creates a
cycle." → boxed PASS. §87.3: "We obtain: `A≻B≻C≻A`."

**VERIFIER COMPUTATION (first-hand).** With two voters holding `A≻B≻C` and a third holding `C≻A`:

| Pair | Voters 1–2 | Voter 3 | Majority |
|---|---|---|---|
| A vs B | A≻B | unspecified | **A≻B** (≥2–1) |
| B vs C | B≻C | unspecified | **B≻C** (≥2–1) |
| A vs C | A≻C (by their own ordering) | C≻A | **A≻C** (2–1) |

The majority relation is `A≻B`, `B≻C`, `A≻C` — a transitive total order with **A the Condorcet winner**.
The claimed `C≻A` is false: it is one voter's preference, not the pairwise majority. There is no cycle.
A Condorcet cycle requires three *distinct* orderings, e.g. `A≻B≻C`, `B≻C≻A`, `C≻A≻B`.

**Classification, stated precisely.** The *general proposition* (majority preference may be non-transitive)
is TRUE and is standard. The *witness offered for it* is INVALID. This is a different and lesser defect
class than TV-F-025 (steps 041–055), where the computed quantity itself was wrong by a factor of ten.
Here the conclusion survives; only the instance fails. §87.3 then treats the non-existent cycle as obtained
and builds §87.4 on it.

**POSSIBLE REPAIR — NOT ESTABLISHED BY CORPUS.** Replace the profile with the standard three-distinct-orderings
witness. One line. The corpus does not do this.

---

## TV-F-045 — Step-100's closure theorem is vacuous: `Valid` occurs exactly once in the file and is never defined

**Class:** unfalsifiable headline result · **Severity:** CRITICAL — this is the corpus's own strongest architectural proposition · `[SUPERVISOR-VERIFIED]`

**SOURCE RESULT (verbatim, §100.33).** Prose: "A KnowledgeOS architecture is closed when every material
organizational or software state transition can be represented as an authorized, versioned, observable
transition whose resulting knowledge and system state remain subject to the applicable invariants."
Symbolic, boxed:

```
∀T: Valid(T) ⇒ Authorized(T) ∧ Observable(T) ∧ Traceable(T) ∧ Verifiable(T) ∧ InvariantPreserving(T)
```

**VERIFIER OBSERVATION (first-hand grep).** The token `Valid(` occurs **exactly once in the entire
step-100 file** — at line 970, inside this formula. It is never defined, never used elsewhere, never
given a predicate, and no other section constrains it. Therefore:

- Read `Valid(T)` as "T satisfies the five conjuncts": the formula is `P ⇒ P`, a tautology.
- Read `Valid(T)` as anything else: it is undefined and the formula has no truth value.

Either way the boxed closure theorem establishes nothing about any architecture. **The prose and the
symbolic form are also not the same proposition:** the prose quantifies over *every material transition*;
the symbolic form quantifies only over transitions already satisfying `Valid`.

**SUPERVISORY REFINEMENT of the band's characterization — recorded, not applied silently.** The
083–100 band described §100.34–100.37 as "four negative controls scored as confirmations." That
overstates. Reading §100.34 first-hand: it exhibits `Traceable(Action)=False` and states
`Expected: The transition is not fully assured` before boxing PASS. The Expected and the verdict are
*mutually consistent* — the experiment is not self-contradictory. The accurate finding is a **scope
substitution**: §100.34–100.37 test a weaker proposition (that unassured transitions are recognisable
as unassured), never the closure theorem, and their PASSes are then read as supporting closure. Weaker
than the band's claim, and still a defect: the theorem has no negative control anywhere in the file.

**What step-100 does NOT establish** (each independently checked): that the ~175 invariants minted across
083–099 are mutually consistent (no satisfiability check exists); that they are individually well-defined
(see TV-F-046); that the five `D` and four `S` tuples are one object (§100.32 adds a memberless sixth `S_t`);
that the loop `K→R'` terminates or converges; that any arrow is realizable.

**To the corpus's genuine credit,** §100.49 states: "We have demonstrated: `Architectural coherence.` We have
**not yet demonstrated**: `Implementation completeness.` And we certainly have not demonstrated: `Production
correctness.`" with `Conceptually closed ≠ Empirically proven`. That self-limitation is correct and is
**contradicted by the unqualified `ARCHITECTURAL CLOSURE — PASS` box** one section later.

---

## TV-F-046 — `I_Security` is a conjunction over four conjuncts that do not exist, and is consumed downstream as evaluable

**Class:** composite invariant over undefined terms · **Severity:** high · `[BAND-REPORTED]`

Step-094 §94.51 defines `I_Security = I_Authentication ∧ I_Authorization ∧ I_Integrity ∧ I_Provenance ∧
I_Privilege ∧ I_TrustBoundary`. Four conjuncts (`I_Authentication`, `I_Authorization`, `I_Integrity`,
`I_Privilege`) are never defined anywhere in steps 083–100. A conjunction over undefined conjuncts has no
truth value. Step-099 §99.41's runtime dashboard nonetheless displays `I_Security = PASS`, and step-100
§100.22 treats it as a closed dimension.

Adjacent defect in the same dashboard: **`I_ArchitectureConformance` is referenced but never minted** — the
invariant actually defined at §99.80 is `I_RuntimeConformance`.

---

## TV-F-047 — The corpus's dominant structural pattern: uncited re-derivation, sometimes with net information loss

**Class:** provenance failure at corpus scale · **Severity:** structural · `[SUPERVISOR-VERIFIED]` for the citation-floor claim

**Finding.** Across steps 067–082, **no file cites any step below 66.** The corpus behaves as a sliding
1–3-step memory window. Every overlap with the earlier corpus is therefore an uncited re-derivation.
Eight independent probes of earlier content all returned the same result:

| Later step | Re-derives | Earlier step(s) | Net effect |
|---|---|---|---|
| 068 | four-valued epistemic state, paraconsistency | 009 | **LOSS** — drops step-009's AGM-insufficiency critique and its naming of Priest/LP; reproduces the 4-cell lattice without the `𝔹` bitvector encoding |
| 074 | SCM, `do()`, potential outcomes | 014, 025P | duplicate |
| 075 | expected utility, risk, decision/authorization | 015, 021, 025H | **LOSS** — carries EVPI only, where EVSI existed |
| 073, 078 | contextual trust, source reliability | 019 | triple minting (019→073→078); only 073→078 acknowledged |
| 081 | observational equivalence, identifiability, observability | 026, 031 | **NOTATIONAL CHURN** — `∼_O` → `∼_H`, `W` → `S`; step-031's functional `Identifiability(g,Ω)` not carried forward |
| 082 | uncertainty propagation, `Var(X+Y)=…+2Cov` | 011, 020, 027, 033, 071 | **third independent derivation** of one textbook identity; step-011's title is near-identical to step-082's |
| 078 | multi-agent boundary | 037 (adversarial sources) | **REGRESSION** — no Byzantine agent, no lying agent, no collusion anywhere in 078 |
| 094 | Integrity ≠ Truth, Authenticity ≠ Authority | 022 §59–61, 073 | **REGRESSION** — §94.23 introduces a bare `h=H(X)` with no chaining, weaker than step-022's existing hash-chain machinery |

The same pattern continues in 083–100 (090's VOI/degradation/cache material reappears wholesale in 098
under four renamed invariant families, uncited) and in 101–120 (109–111 rebuild 102's inventory; 113
rebuilds 103's semantic chain as a "hypothesis"; 118–119 rebuild 104's governance loop).

**Consequence for the theory reconstruction.** Chronology is not supersession. Where a later step
re-derives an earlier result *more weakly*, treating the later text as current silently discards
established content. Four instances of net loss are recorded above.

---

## TV-F-048 — Step-079's interaction algebra: the sole worked example contradicts its own notation

**Class:** arithmetic inconsistent with stated formula · **Severity:** medium · `[SUPERVISOR-VERIFIED]`

**SOURCE RESULT.** §79.3: `U_org = Σ_i U_i(a_i) + Σ_{i,j} I_{ij}(a_i,a_j)`.
§79.4: `U_1(A)=100`, `U_2(B)=100`, `I_{12}(A,B)=−250`, therefore `U_org(A,B) = −50`.

**VERIFIER COMPUTATION.** `100 + 100 − 250 = −50` reads `Σ_{i,j}` as a sum over **unordered pairs i<j**.
Under the literal written form — `Σ_{i,j}` over ordered pairs — the sum includes both `I_{12}` and `I_{21}`,
giving `100 + 100 − 250 − 250 = −300`. The index set is never specified: ordered or unordered is not
stated, whether `i=j` contributes is not stated, and whether `I_{ij} = I_{ji}` is not stated.

**Aggravating.** `I_{ij}` itself is never defined — it is glossed as "effects created by combinations of
actions," a description of the referent, not a definition. No domain, codomain, construction, estimation
procedure, or identifiability condition. And the symbol collides: `I_{ij}` (utility interaction) and
`I_local`/`I_k` (invariants) use the same letter for unrelated objects 21 sections apart in one file,
typographically indistinguishable in subscript.

**Internal tension.** §79.3 establishes that `U_org` is non-separable; §79.54 asserts "the optimization can
be distributed." Non-separability of the objective is precisely what blocks naïve distribution.

---

## TV-F-049 — An additive `+` is applied three times to quantities the corpus itself proves non-additive

**Class:** ill-typed operator, self-contradicted · **Severity:** high · `[BAND-REPORTED]`, step-082 internal contradiction `[SUPERVISOR-VERIFIED]` by inspection

Three independent occurrences:

1. **077 §77.8** — `DecisionDiff = KnowledgeDiff + ModelDiff + PolicyDiff + ContextDiff`, and
   `ΔD = D(K_2,M_2,P_2,C_2) − D(K_1,M_1,P_1,C_1)`. `Decision` is a semantic type (067's 𝒯) and an
   11-field tuple (075 §75.49). Subtraction is undefined on that codomain. The file disclaims the `+`
   in the next line ("Not necessarily arithmetically") without withdrawing the equation.
2. **079 §79.3 / §82.44** — additive decomposition over undefined interaction terms (TV-F-048).
3. **082 §82.44** — `Uncertainty(D) = U_1 + U_2 + ⋯`. This appears **37 sections after §82.7–82.12
   establishes that standard deviations do not add** (only variances, and only under independence).
   The file contradicts its own central result.

**Cross-step tension.** 077 §77.8 *assumes* additive decomposition of a joint effect; 079 §79.3 *refutes*
additive decomposition of a joint effect. Neither cites the other.

---

## TV-F-050 — Steps 090 and 098 duplicate one body of content under four renamed invariant families

**Class:** silent duplication · **Severity:** medium · `[BAND-REPORTED]`

Step-098 never cites step-090, yet §98.12–98.15 (VOI), §98.31 (adaptive verification), §98.37 (graceful
degradation), §98.64–98.67 (cache freshness vs cost) and §98.75 (protected budgets) reproduce
§90.37–90.65 nearly point-for-point under new names:

| Idea | 090 name | 098 name | also |
|---|---|---|---|
| graceful degradation | `I_GracefulDegradation` | `I_DegradationHonesty`, `I_ResourceHonesty` | — |
| cache freshness | `I_CacheValidity` | `I_FreshnessPolicy` | — |
| criticality-proportional effort | `I_CriticalityBudget` | `I_RiskProportionalAssurance` | `I_RetrievalAssurance` (088) |
| constrained optimization | `I_AssuranceOptimization` | `I_HardConstraints` | `I_FeasibleActionSpace` (086) |

**Additional defect in 098's own mathematics.** §98.13 defines `VOI(I) = ExpectedDecisionImprovement −
Cost(I)` — i.e. **net of cost**. §98.40 then compares "Option A: `Cost=10, VOI=5`" against "Option B:
`Cost=50, VOI=40`", treating VOI as **gross of cost**. The same symbol carries two meanings in one file,
and the comparison double-counts cost.

---

## TV-F-051 — Step-088 defines statistical sufficiency correctly, then equivocates on it for the rest of the file

**Class:** equivocation on an imported technical term · **Severity:** high · `[BAND-REPORTED]`

§88.13 states the Fisher–Neyman factorization criterion correctly: a statistic `T(X)` is sufficient for
parameter `θ` iff `p(X|θ) = g(T(X),θ)·h(X)`. §88.15 correctly relativizes it as `SufficientFor(X,θ,M)`.

Every subsequent use is a **different, undefined notion**: §88.19 `Decision-sufficient`; §88.20
`DecisionSufficientFor(R)`; §88.56 `K_sufficient(Q)` ("the minimum knowledge required to answer a
particular question Q within a specified assurance level"); §88.73 `C ⊇ K_required(A)`. None has a
parameter `θ`, a likelihood, or a factorization. No theorem and no argument connects decision-sufficiency
to statistical sufficiency — the word simply carries over. The factorization criterion is load-bearing
scaffolding for a claim it does not support.

The **first invalid inference** is §88.19: the slide from statistical sufficiency to "decision-sufficient"
is made by juxtaposition alone, with no argument that a decision rule plays the role of a parameter.

---

## TV-F-052 — Step-101's architecture triple silently becomes a quadruple at step-106, and is retro-attributed

**Class:** unrecorded mutation of a foundational object · **Severity:** load-bearing · `[SUPERVISOR-VERIFIED]`

**SOURCE RESULT.** Step-101 §101.1 defines exactly three architectures — intended `A_I`, implemented `A_C`,
runtime `A_R(t)` — and boxes the governing question `A_I ≅ A_C ≅ A_R(t)`.

**VERIFIER OBSERVATION (first-hand grep).** `A_D` occurs **0 times in step-101** and **6 times in step-106**.
Step-106 §106.2 introduces the deployed architecture `A_D` and boxes `A_I → A_C → A_D → A_R` with no note
that the arity changed. Step-107 then attributes the quadruple to step-106 ("Step 106 established: `A_I →
A_C → A_D → A_R`") — a correct attribution — but no file anywhere records that step-101's governing question
was a **triple** and has been superseded. The band's foundational object changed arity without a
supersession record.

---

## TV-F-053 — Three incompatible bindings of the `E`-scale, and three of the `C`-scale, with verdicts issued under the superseded bindings

**Class:** symbol collision across the corpus · **Severity:** high · `[SUPERVISOR-VERIFIED]`

**First-hand check of the `E3` binding:**

| Step | §  | `E3` means |
|---|---|---|
| 108 | §108.6 (line 262) | `E_3 = Automated test` |
| 109 | §109.3 (line 116) | `E3 — Verification evidence` |
| 116 | §116.2 (line 78) | `Level E3 — Behavioral evidence` |

Three distinct bindings of one symbol. Step-116 §116.2 is the **only** file that states an order
(`E0<E1<E2<E3<E4<E5<E6`) — and it does so on the rebound symbols, which makes the collision worse rather
than better. Step-109 boxes `E2` twice **as a verdict**; those verdicts silently change meaning at step-116.
A fourth, unrelated binding appears at 118 §118.55, where `E_1…E_6` are ordinal positions in one incident's
evidence chain.

The `C`-scale collides three ways: 108 §108.2 `C0–C6` = conformance states; 119 §119.38 `C0–C6` =
control-loop stages; 120 §120.66 `C1–C7` = the constitution. Any matrix keyed on "C3" is ambiguous three ways.

**Scale proliferation, measured.** The 101–120 band introduces **49 distinct epistemic-status /
maturity / evidence scales** (enumerated in `spec/STEP-VERIFY-101-120.md` §(a)). **Zero explicit
mappings between any pair exist in the 20 files.**

---

## TV-F-054 — Step-120's constitutional dependency graph is refuted by step-120's own reduction argument

**Class:** internal contradiction in the constitution · **Severity:** high · `[BAND-REPORTED]`

Step-120 reduces ~50 accumulated invariants to seven (C1–C7) — the band's single most valuable act. It then
draws a "Constitutional dependency graph" (§120.53) whose edges contradict the reduction that produced it:

- **`Provenance → Epistemic` contradicts §120.30**, which argues *independence* verbatim: "Could epistemic
  status be part of provenance? **No.** A source can be known while the statement remains: `Inference`.
  Therefore: `Provenance ≠ EpistemicStatus`." The counterexample given is itself a witness that Epistemic
  does not depend on Provenance.
- **`Provenance → Validity` contradicts §120.29**: "`CreatedAt ≠ Validity`. Keep it separate."
- The graph has **eight nodes for a seven-rule constitution** — `Evidence` appears as a node and is not one
  of C1–C7.
- §120.54 gives a **second, incompatible topology** (`Provenance → Evidence → Verification → Decision`)
  in which `Decision` is not a node of §120.53's graph.
- The graph is an ASCII drawing in a ```text``` block: no edge set, no edge semantics (prerequisite?
  enables? derives-from?), no acyclicity claim, no proof. **Definition verdict: NOT_DEFINED.**

**Companion defect — §120.61's integrity formula does not type-check.**
`KnowledgeOSIntegrity = P ∩ A ∩ E ∩ T ∩ V ∩ R ∩ F`, where P…F are named as *properties*, not sets. The
intended semantics is conjunction over predicates, for which `∧` would type-check and `∩` does not; the
name is scalar/boolean while the definiens is a set; and **C5 is deliberately a SHOULD, not a MUST**
(§120.38 verbatim), so `V` is not a crisp set and the intersection is modally inhomogeneous. The same
category error occurs unlinked at 117 §117.50 (`Conformance = Technical ∩ Governance`).

**Operational definitions delivered: 0 of 7.** No C1–C7 invariant receives a predicate plus inputs plus an
evaluation procedure. C4 (Temporal Validity) comes closest; C3/C5/C6/C7 are names plus prose.

---

## TV-F-055 — Steps 101–120 announce the transition to empirical work twenty times and perform zero empirical acts

**Class:** announced-but-unperformed method · **Severity:** CRITICAL for the corpus's central claim · `[BAND-REPORTED]`, the `.claude/` cross-check `[BAND-VERIFIED against this repository]`

**SOURCE RESULT.** Step-101 §101.66 boxes `STOP DESIGNING IN THE ABSTRACT.` / `START MEASURING KNOWLEDGEOS
AGAINST THE MODEL.` Steps 109–120 each promise to produce a specific empirical artifact.

**VERIFIER OBSERVATION.** A corpus-wide scan of all 20 files for commit SHAs, shell prompts,
`git`/`ls`/`rg`/`find`/`tree` invocations, real repository paths, test-runner output, timing data and
realistic artifact counts returned **0 hits in every file**. Step-109 mandates five artifacts
(`ActualComponentInventory`, `ActualDependencyGraph`, `ActualDataFlow`, `ActualControlFlow`,
`EvidenceLedger`); **zero of five are produced**, and Artifact A is re-promised at 111 §111.73 and again
downstream. Every matrix in the band is printed with all cells `?`: 108 (four matrices), 112 (8×6),
114 (8×6 = 48 cells), 116 (8×4), 117 (ten Evidence cells).

**The sharpest instance.** Step-105 Experiment 3 (§105.5) boxes **FAIL** on the hypothesis that `.claude/`
contains authoritative engineering architecture rules. That hypothesis is **true of the repository this
corpus lives in**: `.claude/CLAUDE.md` exists at 36,210 bytes and carries "Layer Rules with Laravel
Pragmatism" (l. 64) and "Domain layer: Zero Laravel dependencies" (l. 158); `.claude/MEMORY.md` exists at
190,970 bytes. The finding was available for the cost of one `ls` and was instead asserted as fiction.
Step-111 §111.3 compounds this by stipulating `KnowledgeService.java` — **a Java filename in a
Laravel/PHP repository** — which alone establishes that no repository was consulted.

**Where the corpus is honest, and where the honesty stops.** Every step-level verdict in 102–119 is
explicitly scoped to a *model* or *method* by its own token text (`SEMANTIC MODEL: PASS`,
`RECONSTRUCTION METHOD: PASS`, `TRACEABILITY EXPERIMENT: DEFINED`, `SEMANTIC CORE TEST: READY FOR
EMPIRICAL EXECUTION`), and each carries an in-file honesty marker disclaiming any result-level claim.
Steps 111, 113 and 120 issue no step-level verdict at all. **On the narrow question — do this band's
step verdicts certify methods or results — the corpus labels itself correctly.**

The honesty fails one level down. The ~470 per-experiment PASS tokens are unhedged and self-confirming:
each stipulates a premise, stipulates the expected classification, and boxes PASS when the two agree.
**Most consequentially, step-120 issues six constitutional VIOLATION verdicts** (`K1 VIOLATED`,
`K2 VIOLATION if promoted`, `K3 VIOLATION`, `K4 VIOLATION`, `K6 VIOLATION`, `K7 VIOLATION`) — every one
on a stipulated scenario, with KnowledgeOS never opened. A reader scanning boxed tokens would reasonably
conclude the system violates six of seven constitutional rules. Nothing of the sort was measured.

---

# Cross-band aggregate (Steps 067–120)

## Execution evidence

| Band | Files | Experiments | Boxed PASS | FAIL verdicts | Executed |
|---|---|---|---|---|---|
| 067–082 | 16 (+2 byte-identical duplicates) | ~350 | ~336 | ~14, all stipulated | **0** |
| 083–100 | 18 | ~636 | 627 | **0** | **0** |
| 101–120 | 20 | ~500 | 470 | 7, all stipulated | **0** |

**Corpus-wide across 54 files: zero interpreter invocations, zero command lines, zero tool output, zero
test-runner output, zero timestamps tied to a real run, zero file references to the KnowledgeOS or EKS
repositories, zero measured quantities.** Every numeric value in all 54 files is stipulated by the author.
Uniform TEST VERDICT: **CONCEPTUAL-ONLY**, except step-100 and step-096 §96.76, which are
**PROCESS-STATUS-ONLY**, and steps 109–120, which are **NOT_EXECUTED** against their own declared method.

**The structural point.** A ~1,490-experiment suite with a ~99% pass rate, no negative control, and no
experiment whose observed value can differ from its stated expectation is not evidence of correctness.
It is evidence that the suite cannot fail.

## Invariant inventory

| Band | Names minted | Genuinely defined (decidable predicate over named objects) |
|---|---|---|
| 067–082 | 64 (63 unique) | **1** — `I_Dependency` (080 §80.53): `∀e∈E, Source(e)=Domain ⇒ Target(e)∉Infrastructure` |
| 083–100 | ~175 | **0** |
| 101–120 | ~50, reduced to 7 at step-120 | **0 of the 7** |

**Dominant failure mode.** Invariants are English normative sentences gated on undefined hedges. In the
067–082 band alone, 21 of 62 turn on "silently" and 14 on "material" — **neither term is defined anywhere
in the corpus.** Every invariant whose predicate turns on them is undecidable as written.

**Load-bearing consequence for step-100.** `InvariantPreserving(T)` — one of the five conjuncts of the
closure theorem — would require evaluating ~175 invariants of which 0 are defined.

## The corpus's own best work (recorded so the critique is not mistaken for dismissal)

These survive verification and should be preserved by any reconstruction:

- **080 §80.53 `I_Dependency`** — the one executable invariant in 54 files, derived from a prose principle
  by an explicit pipeline (`ArchitecturePrinciple → FormalInvariant → ExecutableCheck → Observation →
  GovernanceAction`) and instantiated against a concrete graph. It demonstrates the corpus's own methodology
  *can* produce definitions, which makes the named-only status of the other ~285 a choice, not a limitation.
- **080 §80.12–80.13 and 081 §81.11–81.12** — two explicit refusals to fabricate an aggregate metric:
  "A number does not become mathematically meaningful merely because it is precise." A corpus declining
  to fake a measurement is doing measurement theory correctly.
- **076** — the only unqualified VALID derivation in 54 files. Pareto dominance and the frontier are
  correctly stated, the worked examples check out, and §76.3's result (dominated alternatives can be
  eliminated *without any stakeholder weighting*) is a correct, useful, preference-free theorem.
- **073 §73.32** — "Authenticity is orthogonal to truth," correctly argued.
- **071 §71.11** — "This does not prove that the system is correct with respect to reality. It proves:
  The system preserves its specified invariants." Exemplary and rare.
- **109 §109.70** — `NotFound ≠ DoesNotExist`, with `NegativeSearchEvidence` as a first-class record.
  Directly anti-hallucination and the strongest formal object in the 101–120 band.
- **089 §89.5–89.48** — decidability, the halting problem, safety/liveness in temporal-logic form, and
  the five-way decomposition of `Unknown`: the strongest definitional file in the corpus.
- **091 §91.48–91.50** — architecture as a *refinement boundary* rather than boxes-and-arrows, plus `ProofSurface`.
- **095 §95.55** — reframing RAG as `Query → Identity → Purpose → Policy → AuthorizedRetrieval → Evidence
  → Context`; the most actionable architectural output in the 083–100 band.
- **107 §107.2** — the D1–D6 discrepancy classification, the cleanest single contribution of the 101–120 band.
- **117 §117.49** — `TechnicalVerification=PASS` / `GovernanceConformance=FAIL`: "technical correctness
  does not imply organizational correctness."
- **120's reduction** — ~50 invariants to 7 is the right *act*, independent of the defects in its execution.
