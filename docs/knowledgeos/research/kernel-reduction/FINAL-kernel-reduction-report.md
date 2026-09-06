# FINAL — KnowledgeOS Kernel Reduction & Minimality Experiment

**Experiment ID** `KR-2026-09-01` · **Model** `kr-model-1.0` · **Date** 2026-09-01
**Status** `[EXP]` — research experiment. **NOT canonical. NOT architecture. NOT governance.**
**Authorization** This lane was *not* authorized to canonize a KnowledgeOS kernel, and does not.

Code `research/kernel-reduction/` · results `research/kernel-reduction/results/*.json` ·
reproduce with `python3 run_all.py`.

---

## 1. Executive result

**What the experiment actually established:**

1. **The 13-operator candidate set `C0` is not adequate.** It achieves 21 of 25 required capabilities
   and 9 of 16 scenarios. It holds no power to turn an observation into evidence, so it cannot
   produce a verdict, cannot validate, cannot represent uncertainty and cannot represent assumptions.
   The missing power is exactly `Qualify : Observation × Policy ⇀ Evidence`, which the prior corpus
   lane had already recorded as `G1` — irreducible. `[EXP]` `[CORPUS]`

2. **The tested semantic function called `DetectGap` is representable as a derived evaluation
   under every algebra in the tested family** (directive §9 wording). On the repaired baseline
   `C0+ = C0 ∪ {Qualify}` (14 operators) it is the only operator derivable without semantic
   smuggling. **`[EXP]` strong derivability evidence — NOT a theorem of universal derivability:
   12/12 is a *sample* of representations, never `∀ R ∈ ℛ_admissible`.**
   `Gap := difference-decision( norm-comparison( K_t , I_t ) )`, donors `Determine` and
   `Discriminate`, neither redefined. This survived **all 8 model variants**, including one built
   specifically to protect it. Four independent instruments agree: ablation, composition, the corpus's
   own `𝒪?` classification, and DDD responsibility analysis. `[EXP]` **robust**

3. **This confirms prior constraint 4 by experiment:** `Zero(K_t, I_Q, EC)` behaves as a **state
   predicate over a normative comparison**, not as a kernel primitive.

4. **Relative to the declared capability model C1-C25, all 14 semantic powers are irreducible while
   only 12 of 14 operators are.** The candidate kernel is minimal in its *epistemics* and non-minimal
   in its *packaging*. Exhaustive search over all 16 384 subsets found exactly **two** minimal
   kernels, **both of cardinality 13**, differing only in which packaging of the same 14 powers is
   chosen. `[EXP]`
   **Read this as a statement about the tested model, never about KnowledgeOS itself.** V1 fuses two
   atoms with no capability loss (N-10), and §18 shows the minimal cardinality falls to **8** once a
   wider space of admissible representations is explored. **The count is not the finding; the
   asymmetry between powers and packaging is.**

5. **Nothing else can currently be reduced with confidence.** Three operators
   (`Interpret`/`Represent`, `Determine`, `Select`) change status under a *single* defensible change
   of semantic granularity. **The instability is in the granularity, not in the count.**

**What the experiment did NOT establish:** that any of the 12 retained operators is universally
necessary; that the capability model C1–C25 is right; that `Select` belongs to this kernel at all;
that provenance, governance or authorization behave as modelled — all three were *assumed*, not tested.

### On the protocol's critical stop condition

The protocol says: if the 13-operator formulation is too unstable for meaningful minimality testing,
**stop reduction** and report what must be stabilized. The honest verdict is **partial**:

> The formulation was stable enough to settle **one** operator (`DetectGap`, robust across every
> variant) and **is not stable enough to support a kernel freeze**. Reduction beyond `DetectGap`
> is **STOPPED**, not because the experiment failed, but because three further results turn on a
> granularity choice that no available evidence decides.

**What must be stabilized first** — in priority order: (i) whether `closure-judgment` is primitive
(Q-1); (ii) whether meaning and encoding are one atom or two (Q-2); (iii) whether `Select` is in this
bounded context at all (Q-3); (iv) whether `Revise` is a domain operation or a mechanism (Q-4).

## 2. Candidate operator set

```
C0  = { Observe, Interpret, Represent, Relate, Discriminate, Hypothesize, Infer,
        DetectGap, Challenge, Validate, Revise, Determine, Select }                    (13)
C0+ = C0 ∪ { Qualify }                                                                 (14)
```

`C0` is a **proposal of this lane**, not a corpus vocabulary: only 6 of 1 782 corpus files contain
≥6 of its 13 tokens, and half of those are this lane's own prompts or 2026-09-01 external extracts
(§02 MR-1). All conclusions inherit that limitation.

## 3. Capability set

25 capabilities — the protocol's C1–C24 plus **C25 (admit an observation as evidence under a policy)**,
added on corpus evidence. C18 (provenance) reclassified **non-discriminating** — structurally
guaranteed by the instrument, therefore untestable by it. Full model and every rejected modification:
§03.

## 4. Operator × capability matrix

§05. Computed, not asserted. Two all-zero rows (`Discriminate`, `DetectGap`) and two all-zero columns
(C5, C8) — the latter is why pairwise ablation was not optional.

## 5. Formal reduction model

```
Reach(S) = μ A . AMBIENT ∪ { k | ∃ o ∈ S : k ∈ derive(A, o.atoms) }

minimal(S)  ⟺  C_required ⊆ Reach(S)  ∧  ∀ o ∈ S : C_required ⊄ Reach(S \ {o})
```

An operator **is** its atom set; a carrier kind is derived from `(input kinds, atoms introduced)`.
Operator *names* are never consulted. Therefore reconstruction is decidable by exhaustive fixpoint,
and absorption ("redefine Y to also do X") is a *detectable edit to the model* rather than an
invisible line of code. Composition rules in full: §06.

## 6. Ablation results

| Operator | Retained? | Removable? | Derivable? | Evidence | Failure on removal | Smuggling | Confidence |
|---|---|---|---|---|---|---|---|
| `Observe` | yes | no | no | 17 capabilities, 13/16 scenarios, P1 P2 P5 P8 P9 | F1 F2 F5 F6 F9 F13 F16 F17 | FALSE | high |
| `Interpret` | yes | no | **only under V1** | 14 capabilities, 12/16 scenarios; 1.000 bit measured loss | F2 F13 F16 F17 | FALSE | medium |
| `Represent` | yes | no | **only under V1** | C3; 5 scenarios | F1 | FALSE | low (weak corpus) |
| `Relate` | yes | no | no (F-4 untested) | C4 C22; 2 scenarios | F3 | FALSE | medium |
| `Discriminate` | **yes** | yes (degenerately) | only via `DetectGap` | irreducible under V4; DDD-necessary | — | FALSE | medium |
| `Hypothesize` | yes | no | no | C6 C19 C20; 5 scenarios; P2 P8 | F5 F16 F17 | FALSE | low (weak corpus) |
| `Infer` | yes | no | no (F-7 open) | C7; S9 | F6 | FALSE | medium |
| **`DetectGap`** | **NO** | **yes** | **YES — 8/8 variants** | 0 capabilities, 0 scenarios, 0 properties; corpus `𝒪?` | none | **FALSE** | **high** |
| `Challenge` | yes | no | no (F-9 open) | C9; S9; **P10 = .594 with active guard**; causal experiment | F8 | FALSE | high |
| `Validate` | yes | no | vacuously "yes" under V7 only | C10 C16 C17; 3 scenarios | F9 | FALSE | medium |
| `Revise` | yes | no | no | C11 C15 C24; S4 S6; P1 P4 | F10 F14 | FALSE | medium (DDD ambiguous) |
| `Determine` | yes | no | **only under V3** | C12 C23; 3 scenarios | F11 | FALSE | medium |
| `Select` | yes | no | **only under V5** | C13; S13 only | F12 | FALSE | low (boundary case) |
| `Qualify` | yes | no | no | C10 C16 C17 C25; 7 scenarios | F9 | FALSE | high |

**Semantic-smuggling control:** 7 of 7 absorption probes restored full coverage and all 7 were
`REJECTED`. Reduction by absorption is always available and always worthless (§10 E, §16 N-9).

## 7. Pairwise results

4 of 91 pairs show synergistic loss:

* `{DetectGap, Determine}` → C8 — joint ownership of `norm-comparison`
* `{DetectGap, Discriminate}` → C5 C8 C19 C20 — joint ownership of `difference-decision`
* `{Hypothesize, Infer}` → C9 C10 C16 C17 — **the critical lane has no upstream content producer**
* `{Hypothesize, Represent}` → C7 C9 C10 C16 C17 — same, different route

The third and fourth are the interesting ones: `Hypothesize` and `Infer` are **substitutable as
suppliers** to challenge/validation while remaining **non-substitutable in warrant kind**. That is an
operator bundle, not an operator. No third-order interaction exists (§11).

## 8. Simulation results

| | |
|---|---|
| deterministic scenarios | 16; `C0` 9/16, `C0+` 16/16 |
| randomized trials | 5 seeds × 2 000 × 15 arms = **150 000** |
| seeds | 1, 7, 13, 101, 2718 — no arm's failure set varied across seeds |
| failure rates | §12, Wilson 95 % CIs |
| failure classes | F1–F18 observed; F19 (N-3) and F20 (N-7, N-8, N-12) recorded and **excluded from kernel conclusions** |
| **vacuity** | **the randomized suite is non-discriminating for capability loss** — guard-activation audit in §12 |

## 9. Mathematical interpretation

* **Compositionality.** `Reach` is a monotone least fixpoint over a finite carrier lattice —
  terminating, deterministic, name-independent. Reconstruction is *decided*, not argued.
* **Minimality.** Semantic and compositional minimality coincide here; cardinality minimality is
  degenerate (both minimal kernels are 13). The Pareto frontier is a **coupling/cohesion** frontier,
  not a size frontier.
* **Information loss.** Measured, not asserted: dropping `meaning-assignment` costs exactly
  **1.000 bit** (`I(X;S)=1.500` → `I(X;S′)=0.500`, `H(X)=2.000`). The data-processing inequality
  forbids **any transformation of `S′` alone** from increasing its mutual information with `X` — it
  does *not* forbid recovery by a downstream system that receives additional information such as
  `Context` (corrected 2026-09-01 per external audit §17). The residual 0.5-bit ambiguity belongs to
  the *signal*, not to any operator — the two are not conflated.
* **Sufficiency.** `C0` is insufficient; `C0+` is sufficient and redundant by one operator.
* **Uncertainty and non-identifiability.** Representable only when `content-generation` and
  `difference-decision` are both present; removing either produces **overreach** — the system emits a
  `Determination` while unable to represent the alternatives it is determining between (P2 = .242).
* **Shape.** 14 irreducible powers vs 12 irreducible operators — both *relative to C1-C25 and this
  algebra* — suggests
  `𝒦 = (𝒫, ℛ, δ)` — powers, admissible compositions, and one commit — with operators as a
  *packaging layer*. `[PROP]` `[OPEN]`

## 10. Statistical interpretation

* Failure rates are **properties of the generator**, not estimates of a population. `P8 = .800` is
  ≈ P(≥2 hypotheses) under the chosen generator and nothing more.
* CIs quantify Monte-Carlo error only. **They carry no information about whether the model is right.**
* **No significance test is reported and none is appropriate.** The central question is structural.
* **No single-number kernel score is produced.** "Kernel A is *n* % better than Kernel B" would be
  false precision, and a sensitivity analysis over invented weights would measure only the invention.
* **Seed reproducibility, not statistical robustness** (directive §24): failure *sets* were stable
  across all 5 seeds and rates agreed within Monte-Carlo error. This shows determinism given a seed,
  not `∀ seed` and not robustness to generator specification.
* **The design is paired, not independent** (directive §23, verified): every arm sees the identical
  world stream, so the experiment is 10 000 worlds × 15 paired arms, not 150 000 independent
  observations. The correct statistic for arm comparison is the paired difference; see §12 for the
  paired analysis of the `Challenge` result, where the conditional failure rate is **1.0000
  deterministically** and the only sampled quantity is the generator's guard-activation rate.
* **Sensitivity to the model dominates sensitivity to the sample.** Seven of the 14 verdicts are
  stable across all 8 variants; four flip under exactly one variant each. The statistical noise is
  negligible next to the modelling choice — which is the honest headline of §10 and §12 together.

## 11. DDD interpretation

* **`DetectGap` has no domain responsibility of its own.** No invariant, no independent reason to
  change, and two natural owners for its parts. The corpus reached the same conclusion first, by
  classifying it into `𝒪?` derived evaluations rather than `𝒪⁺` state-changers.
* **`Discriminate` is DDD-necessary although formally derivable.** *Buddhi* — the power to decide
  `different / contradictory / more-specific / superseding / compatible / incomparable / unknown` —
  is a first-class responsibility with its own vocabulary. Retaining it while dropping `DetectGap`
  makes all three notions of necessity agree.
* **`Select` is a boundary case.** It consumes an `Objective` the kernel does not own and produces a
  `Decision` no epistemic capability consumes. Formally irreducible, empirically confined to one
  scenario, weakest corpus support. Plausibly a capability of a *neighbouring bounded context*.
  `UNRESOLVED` — and a minimality experiment is the wrong instrument to settle it.
* **`Revise` is ambiguous.** Irreducible as a *power*; possibly a *mechanism* rather than a domain
  concept. The corpus's seven revision verbs are epistemically distinct but share the mechanism, and
  this simulator cannot see the difference.
* **`Validate`, `Determine`, `Qualify` are policy-parameterized domain operations** — the operation is
  domain, the *standard* (warrant threshold, epistemic contract `EC`, admission policy) is governance.
  Kept in the kernel; their parameters kept outside it.
* **Predicate vs operation:** `Zero` → **predicate** (confirmed); `IdealState`, `Inquiry` → **state /
  input constructs** (confirmed, and load-bearing: no `Gap` or `Determination` without them);
  `Governance`, `Authorization` → **assumed external, never tested** (§16 N-11).

## 12. Alternative kernel candidates — no winner selected

| | Members | Card. | Coupling | Cohesion | Evidence |
|---|---|---|---|---|---|
| **K_A** | `C0+ \ {Discriminate}` | 13 | higher | poor — `DetectGap` becomes the general discriminator | contradicted by corpus `𝒪?` |
| **K_B** | `C0+ \ {DetectGap}` | 13 | lower | good — matches SD-2 *Buddhi* | consistent with corpus |
| `C0+` | all 14 | 14 | — | — | redundant by one |
| `𝒦=(𝒫,ℛ,δ)` | 14 powers + rules + commit | — | — | different *shape*, not a smaller list | `[PROP]` `[OPEN]` |

`[PROP]` **`K_B` is the better-supported candidate** on four agreeing grounds (§14). It is a
**candidate**, not a selection, and §15 states what would falsify it.

## 13. Negative results

Fourteen, all in §16. The load-bearing ones: the **baseline failed** (N-1); removing `DetectGap`
broke nothing (N-2); `Discriminate`'s apparent removability is an **implementation artifact** (N-3);
five operators are individually removable but not jointly (N-4); the **randomized suite is
non-discriminating for capability loss** (N-6); provenance is **untestable** in this instrument (N-7);
**every smuggling probe succeeded** (N-9); **atom irreducibility is model-relative** — V1 fuses two
atoms and loses nothing (N-10); **authorization was never tested** (N-11); and `C0` has almost no
corpus provenance as a set (N-14).

## 14. Falsification conditions

Sixteen, in §15 — one per irreducibility claim plus two for the aggregate claims. Three are
**already conditionally falsified** by a variant in this very experiment (`Represent` under V1,
`Determine` under V3, `Select` under V5), and that is reported as a limit on those results, not
hidden. Three more are genuinely untested and important: `Relate` ← `Infer` + `Rule` (F-4),
`Challenge` ← `Hypothesize` + `Discriminate` (F-9), `Validate` ← `Discriminate` over defeaters (F-10).

## 15. Open questions

§17. Four blocking (Q-1 closure-judgment · Q-2 meaning/encoding granularity · Q-3 `Select`'s bounded
context · Q-4 `Revise` operation-vs-mechanism), ten substantive, four method-level.

## 16. Recommendation — next RESEARCH step only

**Do not freeze any kernel.** The evidence does not support it, and this lane is not authorized to.

`[PROP]` Recommended next research step, in order:

1. **Resolve Q-2 (granularity of meaning vs encoding) first.** It is the cheapest — one counterexample
   settles it — and it gates both the `Interpret`/`Represent` verdicts and the shape hypothesis.
2. **Then Q-1 (is `closure-judgment` primitive?)**, by deciding whether the epistemic contract `EC`
   reduces to a requirement set.
3. **Route Q-3 (`Select`) to strategic DDD, not to another ablation.** It is a context-map question
   and the minimality instrument cannot answer it.
4. **Record `Qualify` as a candidate operator** of the working list. Its absence caused the baseline
   failure and the corpus already carries it as `G1`.
5. **Record `DetectGap` as `DERIVABLE-CANDIDATE`** with the construction, the four converging
   evidence lines, and falsifier F-8. This is the one determination the experiment earned.
6. **Build a degree-sensitive instrument** for Q-4 and Q-12 — without reverting to a narrative
   simulator, in which smuggling becomes invisible again.

---

# Assumptions of the Experiment (Part XXVII)

| Area | Assumption | Exposed where |
|---|---|---|
| state model | `K_t` is an ambient carrier; the kernel is the operator set; the two are never conflated | §06 |
| state model | epistemic state is modelled by *reachable carrier kinds* + a derivation DAG, not by instances | §09 |
| operator semantics | an operator **is** its atom set; names carry no power | §04 |
| operator semantics | atoms may be shared; `difference-decision` and `norm-comparison` each have two holders | §04 |
| composition | a step applies exactly one operator and introduces exactly its atoms | §06 |
| composition | carrier kinds derive from `(input kinds, step atoms)` — never from the producing operator | §06 |
| capability defs | C1–C24 inherited from the protocol; C25 added; C18 reclassified | §03 |
| scenarios | ambient carriers vary per scenario; only S7/S11/S12 supply `IdealState`, only S13 supplies `Objective` | §08 |
| probability | randomization varies *ambient availability* and world features; optional carriers present with p = 0.6 | §12 |
| semantics | `Claim` is producible only via `entailment`, hence warrant kind DEDUCTIVE | §06 |
| semantics | `Verdict` requires `Evidence` | §06 |
| context | context is an **input** to the meaning rule, not an ownable power | §04 |
| truth | never modelled. No operator produces truth; `Verdict` is warrant, not truth | §06 |
| uncertainty | carried by `Verdict`; not modelled numerically | §03 |
| missing evidence | modelled as absence of the `Policy`/`Evidence` route, and as a `Gap` | §08 S7 |
| provenance | **guaranteed structurally** — hence untestable here | §16 N-7 |
| governance | `Policy` and `Objective` are ambient and no operator produces them — **assumed, not tested** | §16 N-11 |

# Sensitivity to Assumptions (Part XXVII)

| Conclusion | Survives alternative assumptions? |
|---|---|
| `C0` baseline is inadequate (no evidence power) | **Yes** — holds in every variant; it is a structural hole, not a modelling artifact |
| **`DetectGap` is derivable** | **Yes — 8/8 variants**, including V4 built to protect it. The single most robust conclusion |
| `Observe`, `Relate`, `Hypothesize`, `Infer`, `Challenge`, `Revise`, `Qualify` irreducible | **Yes — 8/8** |
| `Discriminate` is derivable | **No** — fails under V4. Reclassified as an implementation artifact (N-3) |
| `Interpret` and `Represent` both irreducible | **No** — fails under V1, where each derives the other |
| `Determine` irreducible | **No** — fails under V3 |
| `Select` irreducible | **No** — fails under V5; and its *context membership* is open independently |
| `Validate` irreducible | Yes, except the **degenerate** V7 case where nothing can produce a `Verdict` at all |
| All 14 atoms irreducible | **Conditionally** — V1 fuses two atoms with no capability loss. Model-relative (N-10) |
| Both minimal kernels have cardinality 13 | **Yes**, given the declared capability model; V1/V3/V5 each reduce it to 12 |
| Kernel shape is `(𝒫, ℛ, δ)` | **Untested** — a hypothesis, not a result (F-16) |

---

# Required Final Classification (Part XXIX)

| Operator | Current status | Evidence | Ablation result | Derivable? | Semantic smuggling? | Confidence | Next test |
|---|---|---|---|---|---|---|---|
| `Observe` | **IRREDUCIBLE-CANDIDATE** | STRONG corpus (66 role files) | 17 caps, 13/16 scenarios, 5 properties | no | FALSE | high | F-1: any composition yielding empirical content |
| `Interpret` | **IRREDUCIBLE-CANDIDATE** | MODERATE (Q15 *Parse*) | 14 caps, 12/16 scenarios; 1.000 bit loss | only under V1 | FALSE | medium | **Q-2** — one counterexample settles it |
| `Represent` | **IRREDUCIBLE-CANDIDATE** | WEAK (5 role files) | C3; 5 scenarios | only under V1 | FALSE | low | **Q-2** |
| `Relate` | **IRREDUCIBLE-CANDIDATE** | WEAK–MODERATE | C4 C22; 2 scenarios | no | FALSE | medium | F-4: `Relation` as `Infer` + relation-asserting `Rule` |
| `Discriminate` | **COUPLED** (with `DetectGap`) | STRONG as SD-2 family | none under LOO; irreducible under V4 | only via `DetectGap` | FALSE | medium | scope-test `DetectGap`'s atom (V4 vs V0) |
| `Hypothesize` | **IRREDUCIBLE-CANDIDATE** (evidence-weak) | WEAK (2 role files) | C6 C19 C20; 5 scenarios; P2 P8 | no | FALSE | low | corpus search for non-entailed content generation |
| `Infer` | **IRREDUCIBLE-CANDIDATE** | MODERATE (Q16) | C7; S9 | no | FALSE | medium | F-7: does KnowledgeOS need the DEDUCTIVE warrant kind? |
| **`DetectGap`** | **DERIVABLE-CANDIDATE** | MODERATE, and corpus classifies it `𝒪?` | **none — 0 caps, 0 scenarios, 0 properties, 8/8 variants** | **YES** | **FALSE** | **high** | F-8: a gap not expressible as a decided normative difference |
| `Challenge` | **IRREDUCIBLE-CANDIDATE** | MODERATE | C9; S9; P10 = .594 (guard active); causal experiment | no | FALSE | high | F-9: `Hypothesize` a competitor + `Discriminate` |
| `Validate` | **IRREDUCIBLE-CANDIDATE** | STRONG | C10 C16 C17; 3 scenarios | vacuously under V7 only | FALSE | medium | F-10: `Discriminate` over `{claim, defeaters}` |
| `Revise` | **AMBIGUOUS** | STRONG as a power; under-modelled as a concept | C11 C15 C24; S4 S6; P1 P4 | no | FALSE | medium | **Q-4**: are the 7 revision verbs epistemically distinct? |
| `Determine` | **UNRESOLVED** | MODERATE (146 tokens → 7 role uses) | C12 C23; 3 scenarios | only under V3 | FALSE | medium | **Q-1**: does `EC` reduce to a requirement set? |
| `Select` | **UNRESOLVED** | WEAK (3 role files); corpus places it outside the epistemic projection | C13; S13 only | only under V5 | FALSE | low | **Q-3**: strategic DDD context map, not ablation |
| `Qualify` | **IRREDUCIBLE-CANDIDATE** | STRONG; corpus `G1` | C10 C16 C17 C25; 7 scenarios | no | FALSE | high | F-14: is evidence status derivable from meaning + warrant? |

*Statuses drawn only from the permitted vocabulary. `CANONICAL`, `PROVEN`, `FINAL` and `TRUE KERNEL`
appear nowhere, because no formal proof exists.*

---

# Final Research Questions (Part XXX)

**Q1 — Which candidate operators appear genuinely irreducible?**
Robust across all 8 variants: `Observe`, `Relate`, `Hypothesize`, `Infer`, `Challenge`, `Revise`,
`Qualify` (7). Irreducible in the baseline but variant-sensitive: `Interpret`, `Represent`,
`Validate`, `Determine`, `Select` (5). At the **power** level all 14 atoms are irreducible relative
to the declared capability model.

**Q2 — Which are derivable compositions?**
`DetectGap`, robustly: `Gap := difference-decision( norm-comparison( K_t , I_t ) )`.
`Discriminate` only degenerately, via `DetectGap`, which is itself derivable — an implementation
artifact, not a result.

**Q3 — Which are merely convenient architectural names?**
`DetectGap` — the union of two powers already owned elsewhere, with no invariant of its own.
Watch-list on corpus grounds rather than structure: `Hypothesize`, `Select`, `Represent`
(2, 3 and 5 operator-role files respectively).

**Q4 — Which are predicates/state constructs rather than operations?**
`Zero`/`DetectGap` → **predicate over a normative comparison** (confirmed).
`IdealState`, `Inquiry`, `Policy`, `Objective` → **state/input constructs**, ambient, produced by no
operator, and load-bearing: no `Gap` or `Determination` exists without them.

**Q5 — Which operators are necessary only because of the chosen representation of `K_t`?**
`Revise` is the clearest candidate: `state-mutation` is irreducible as a *power*, but its necessity
follows from modelling `K_t` as a committed store rather than as a derived view. `Represent` is the
second: it is necessary only because `Claim` was typed to require an encoding (relaxed in V2).

**Q6 — Which results are robust to alternative representations?**
The `C0` inadequacy (8/8), `DetectGap` derivability (8/8), and the irreducibility of the seven listed
in Q1 (8/8). Everything else moves.

**Q7 — Which operators become necessary only when context, uncertainty, alternatives or semantic
interpretation are introduced?**
`Interpret` (context — the whole 1-bit loss is the context index), `Hypothesize` (alternatives —
C19/C20 and the P2 overreach), `Validate` + `Qualify` (uncertainty and assumptions — both ride on
`Verdict`, which requires `Evidence`), `Challenge` (the *distinction* between unchallenged and
validated, invisible until model criticism is demanded).

**Q8 — Are there operator clusters that should remain separate despite being mutually dependent?**
**Yes: `{Hypothesize, Infer}`.** They are substitutable as *suppliers* to the challenge/validation
lane, and non-substitutable in *warrant kind* (generated vs entailed). Merging them because they
interact would destroy the distinction `derivation ≠ corroboration`, which the corpus records as
already surviving the philosophical-source programme.

**Q9 — Operator set, algebra, transition system, or another formalism?**
The evidence favours **not** a flat operator set. 14 irreducible powers vs 12 irreducible operators
(relative to C1-C25 and this algebra),
and two minimal kernels of equal cardinality differing only in packaging, both point to
`𝒦 = (𝒫, ℛ, δ)` — primitive powers, admissible composition relations, one state-transition
commit — with operators as a cohesion/naming layer. `[PROP]` `[OPEN]`, not a result.

**Q10 — What is the smallest experimentally supported kernel candidate?**
**`K_B` = 13 operators** = `C0+ \ {DetectGap}`, i.e. `{Observe, Interpret, Represent, Relate,
Discriminate, Hypothesize, Infer, Challenge, Validate, Revise, Determine, Select, Qualify}`,
carrying 14 powers irreducible relative to C1-C25 and this algebra. **Candidate, not selection.** Note it is the same size as
the original `C0` — one operator was added and a different one removed. **The experiment did not make
the kernel smaller; it made it correct by one and redundant by one fewer.**

**Q11 — What would falsify that candidate?**
Any of F-1 … F-16 (§15). Most immediately: a counterexample to Q-2 collapses `Interpret`/`Represent`
to 12; resolving `EC` to a requirement set collapses `Determine` to 12; moving `Select` to a
neighbouring context collapses it to 12 *and* changes the kernel's boundary rather than its size.

**Q12 — What remains fundamentally unresolved?**
**The semantic granularity of an epistemic primitive.** Three of fourteen verdicts flip under a
single defensible change of granularity, all 14 atoms are irreducible only relative to a capability
model largely inherited rather than derived, and the packaging layer is underdetermined by every
piece of evidence gathered. The next research problem is therefore not *"which operator do we
delete?"* but **"what is the correct mathematical type and semantic granularity of an epistemic
primitive?"** — a question this experiment was able to sharpen but not answer.
