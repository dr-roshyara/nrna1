# `KR-STATE-01` — **DESIGN**
## Is the epistemic state multidimensional, and is present neutrality a safe deletion criterion?

**Experiment ID:** `KR-STATE-01-EPISTEMIC-STATE-STRUCTURE-2026-09`
**Status:** `[DESIGN]` — **NOT RUN, NOT AUTHORIZED.** Design only.
**Date:** 2026-09-04 · Theory **v1.2 FROZEN** · **kernel NOT SELECTED** · **no algebra declared** · **no carrier declared**
**Statuses per** `docs/knowledgeos/governance/EPISTEMIC-STATUS-VOCABULARY.md`.

**Derived from** the corpus specification threads, whose corrections are folded in and credited:
`…041510_kr-state-01-baseline-the-object-of-study-is-the-trajectory` ·
`…040222_kr-state-01-working-spec-with-one-statistical-correction` ·
`…035733_four-corrections-independent-is-too-strong-for-the-zeros` ·
`…035641_zeros-as-projections-of-a-multidimensional-epistemic-state` ·
`…035255_multiple-non-equivalent-zeros-imply-kt-is-not-scalar` ·
`…033025_kr-state-transition-01-the-transition-as-an-epistemic-object`

---

# 1. Objective

Two questions, deliberately kept separate because they can be answered independently.

### Q-A — **Structure.** Is `Zero` one phenomenon or several?

Are there multiple `Zero` predicates that are **distinct, non-equivalent and not mutually
entailing** on the same states?

### Q-B — **Sufficiency.** Is present neutrality a safe deletion criterion?

$$
H_{\mathrm{RET}}:\quad
Z_t(x) = 0 \;\;\not\Rightarrow\;\;
\mathrm{Obs}\!\left(K^{\text{retain}}_{t+k}\right) = \mathrm{Obs}\!\left(K^{\text{delete}}_{t+k}\right)
$$

> **Q-B is the stronger half of the experiment** and the one with a decision consequence: if
> supported, **current observational neutrality is insufficient as a deletion criterion for a
> recursively evolving knowledge system.**

### The research order is fixed and must not be jumped

$$
\boxed{\text{State structure} \;\to\; \text{Probabilistic representation} \;\to\; \text{State-space cardinality}}
$$

`[REC]` **`KR-STATE-01` must NOT assume probability.** It establishes only whether the state
exhibits multiple distinguishable projections. Cardinality and probabilistic representation are
**out of scope** and are not to be inferred from it.

---

# 2. Objects

## 2.1 State

`[DEF]` $K_t$ = a finite set of **claims**. A claim is
$$c = (\mathrm{id},\ \mathrm{subject},\ \mathrm{polarity},\ \mathrm{weight},\ \mathrm{source},\ \mathrm{tag},\ t_{\mathrm{intro}},\ \mathrm{status})$$
with $\mathrm{status} \in \{\text{active},\ \text{superseded},\ \text{qualified}\}$.

⚠️ **This is an EXPERIMENTAL carrier. No carrier is declared** (theory doc 01 §5). It is chosen
so that projections can collide, not because it is a knowledge representation.

## 2.2 Transition — an epistemic object, not a set difference

$$\boxed{K_t \;\xrightarrow{\;\tau_t\;}\; (K_{t+1},\ \Delta_t,\ \Gamma_t)}$$

**Correction folded in (corpus `…033025` §1):** $\Delta_t \ne K_t \setminus K_{t+1}$. Set
difference captures removal but **not transformation** — "X is always true" → "X is true except
under condition C" is neither a removal nor an addition. Therefore

$$\Delta_t = \left(\Delta_t^{-},\ \Delta_t^{\circ},\ \Delta_t^{+}\right)$$

— **removed/superseded**, **transformed/qualified**, **newly acquired** — and $\Gamma_t$ is the
trace/justification record of the transition.

`[REC]` $\Delta_t^{\circ}$ is the component the naive formulation loses, and it is the one most
likely to matter. It must be measured separately, never folded into $\Delta^-$ or $\Delta^+$.

## 2.3 The `Zero` family under test

| # | name | predicate | status entering the experiment |
|---|---|---|---|
| $Z_{\mathrm{elim}}$ | **Elimination** | $\Pi(T(D)) = \Pi(T(E_S(D)))$ | `[DEF]`, established (KR-ZERO) |
| $Z_{\mathrm{det}}$ | **Determination** | $\mathrm{Determine}(Q, K_t) = \varnothing$ | `[DEF]` |
| $Z_{\mathrm{cont}}$ | **Containment Neutrality** | $\mathrm{Harm}_Q(c,K_t) = 0 \wedge \mathrm{Trace}(c,K_t) \neq \varnothing$ | `[DEF]` — **"Neutrality", NOT "Zero"** |
| $Z_{\mathrm{bal}}$ | **Balance** | $\Gamma_Q(C^+, C^-) = 0_{\mathcal C}$ | **`[CONJ]` CANDIDATE — assumes a contribution algebra we have not discovered** |

### Three safeguards carried in from the corpus, each preventing a specific error

**(a) `[NEG]` Do not force every `Zero` into the projection shape.** The elegant
$Z_i(K_t) \iff \Pi_i(K_t) = 0_{O_i}$ **does not fit** $Z_{\mathrm{elim}}$, which is a
**relational** predicate over $(D, E_S(D))$, not a projection to a zero element. The design uses
the neutral form
$$Z_i\left(K_t \mid Q, T, \Pi_i, \mathfrak C\right)$$
with **per-`Zero` typed semantics**, and treats *"does a common representation $Z_i = z_i \circ \Pi_i$ exist?"* as a **research question, not an axiom.**

**(b) `[REC]` `Balance Zero` is labelled CANDIDATE**, not established algebra. The experiment
asks whether $\Gamma_Q(C^+,C^-) = 0_{\mathcal C}$ **exists**; it does not presuppose it.

**(c) `[REC]` `Containment Neutrality` $\not\equiv$ `Containment Zero`** until tested. That
$\mathrm{Harm} = 0$ with a surviving trace is a *neutralized-effect state* does **not** establish
membership in the same mathematical family as $Z_{\mathrm{elim}}$. Naming it "Zero" before the
test would assume the experiment's own conclusion.

## 2.4 ⚠️ `Zero` stays relative — the safeguard that protects KR-ZERO

$$\boxed{Z_{\mathrm{elim}}\left(x \mid K_t, D, T, \Pi\right) \quad\text{— never an intrinsic flag on } x}$$

`[EXP]` `Zero` is contract-relative (495/4 049 verdicts flip) and reference-relative (541/4 260).

> **If `KR-STATE-01` reduces `Zero` to a property of $x$, it silently undoes one of the strongest
> findings of KR-ZERO.** Every stored verdict carries its full index or it is not a verdict.

---

# 3. Q-A — the structure test

## 3.1 The trajectory

`[DEF]` Instead of a fixed property $Z(x)$, the object of study is
$$\mathbf Z(x) = \langle Z_0(x), Z_1(x), \dots, Z_n(x)\rangle,
\qquad Z_t(x) = Z\!\left(x \mid K_t, Q_t, T_t, \Pi_t, \mathfrak C_t\right)$$

$$\boxed{\mathrm{Property}(x) \;\neq\; \mathrm{Property}(x, K_t)}$$

## 3.2 Pairwise output — the statistical correction, folded in

**Correction (corpus `…040222`):** $0 < M_{ij} < 1$ does **not** establish dependence. If
$P(Z_i{=}1) = 0.3$ and $P(Z_i{=}1 \mid Z_j{=}1) = 0.3$, the two are independent despite
$0 < M_{ij} < 1$. **The conditional must be compared with the marginal.**

For every ordered pair the experiment emits

$$\mathcal M_{ij} = \left(P_i,\ P_j,\ P_{ij},\ M_{ij},\ RD_{ij}\right),
\qquad RD_{ij} = P(Z_i{=}1 \mid Z_j{=}1) - P(Z_i{=}1 \mid Z_j{=}0)$$

$RD$ is used deliberately: it is the statistic KR-BRIDGE-01/02/03 were analysed with, so the
lanes remain comparable.

## 3.3 Entailment, association and independence are three different things

| relationship | empirical criterion | what it licenses |
|---|---|---|
| **Empirically entailed** $Z_j \Rightarrow Z_i$ | $P(Z_i{=}0, Z_j{=}1) = 0$ | `[EXP]` **empirical** implication in the tested corpus — **never a logical proof** |
| **Empirically incompatible** | $P(Z_i{=}1, Z_j{=}1) = 0$ | `[EXP]` |
| **Associated** | $P_{ij} \neq P_i P_j$ | statistical dependence only |
| **Approximately independent** | $P_{ij} \approx P_i P_j$ | one carries no information about the other **in this distribution** |
| **Non-entailing** | counterexamples in **both** directions | the Q-A target |
| **Undetermined** | too few informative observations | **reported as undetermined, never as independence** |

## 3.4 `[REC]` The word "independent" is banned from the conclusions

**Correction (corpus `…035733` §1).** Non-implication is not independence. The permitted
vocabulary for a Q-A positive result is

$$\boxed{\textbf{distinct} \;\cdot\; \textbf{non-equivalent} \;\cdot\; \textbf{not mutually entailing}}$$

"Independent" may be used **only** where $P_{ij} \approx P_i P_j$ is actually measured, and then
only as *approximately independent in the tested distribution*.

## 3.5 Stratification is mandatory — the KR-BRIDGE lesson

If $Z_{\mathrm{elim}}$ is generated mainly by one transformation while $Z_{\mathrm{cont}}$ arises
by a different mechanism, **pooled association is misleading.** KR-BRIDGE-01 found a pooled
$RD = -0.363$ that dissolved entirely under stratification.

$$\boxed{\text{pooled analysis} \;+\; \text{stratified analysis}} \qquad
\text{strata: } T,\ Q,\ \mathfrak C,\ \text{knowledge-state class}$$

Adjudication is on the **within-stratum** Mantel–Haenszel effect. **The marginal is reported and
never adjudicated** (theory doc 11 §1).

---

# 4. Q-B — the retention/deletion control

**This is the strongest part of the experiment.** It is where the hypothesis becomes
experimentally meaningful rather than definitional.

## 4.1 The fork

For $x$ with $Z_t(x) = 0$, construct two futures and drive them with the **same** input sequence:

$$K^{R}_t = K_t \qquad\qquad K^{D}_t = K_t \setminus \{x\}$$
$$\text{both exposed to } I_{t:t+k},\ R_{t:t+k},\ Q_{t:t+k} \text{ — identical, same seed}$$

$$\boxed{\mathrm{Obs}\!\left(Q, K^{R}_{t+k}\right) \;\stackrel{?}{=}\; \mathrm{Obs}\!\left(Q, K^{D}_{t+k}\right)}$$

`[REC]` **Compare contract-observable behaviour, not only determination.** Comparing
$\mathrm{Determine}(Q,K^R) \ne \mathrm{Determine}(Q,K^D)$ alone is weaker and misses the case
where both determine but observably differ. This connects Q-B to the semantic-equivalence work.

## 4.2 `[DEF]` Future Epistemic Necessity

$$FEN_k\!\left(x \mid K_t\right) \iff \mathrm{Obs}\!\left(K^{R}_{t+k}\right) \neq \mathrm{Obs}\!\left(K^{D}_{t+k}\right)$$

under identical admissible future inputs. Then the finding of interest is

$$Z_t(x) = 0 \;\wedge\; FEN_k\!\left(x \mid K_t\right)$$

> *"$x$ is currently neutral under the tested observation, but its retention changes future
> epistemic behaviour."* — strictly stronger than "$x$ might be useful someday."

## 4.3 ⚠️⚠️ **THE TRAP THAT WOULD MAKE Q-B TRIVIALLY TRUE**

**This is the single most important design decision in the experiment, and it is mine, not the
corpus's.**

If a future input **references $x$** — "supersede $x$", "qualify $x$", "cite $x$" — then in the
deleted branch that input is undefined. Whatever rule is chosen, the branches diverge **for a
purely bookkeeping reason**, and $FEN_k$ fires without saying anything epistemic at all.

**Rule.** An input referencing a deleted element is applied as a **no-op** in $K^D$, this is
**recorded per case**, and every case is classified:

| class | future sequence | status |
|---|---|---|
| **REF** | references $x$ | **EXCLUDED from the primary Q-B estimate.** Reported separately as the bookkeeping baseline. |
| **NO-REF** | never references $x$ | **the primary Q-B population** |

$$\boxed{H_{\mathrm{RET}} \text{ is adjudicated ONLY on NO-REF cases.}}$$

`[REC]` A Q-B result that does not report the REF/NO-REF split **is not interpretable**, and the
REF rate must be published whatever it is. If NO-REF has too few informative cases, the correct
outcome is **UNDETERMINED**, not a weakened claim.

## 4.4 Pairing — the `KR-ZERO-ORDER` defect, not repeated

`[DEFECT]` in `KR-ZERO-ORDER`: `O2` was **not paired**; different pools desynchronized the RNG,
so the compared samples differed in transformation mix too, and the comparison could not support
the claim made from it.

`[REC]` **Q-B is a fully paired design.** The retain and delete branches consume the **identical
input stream from the identical seed**. Any divergence in the stream is a defect, and a
`FT` check asserts stream identity **during** the run, not after.

## 4.5 Activation must be attributed, never conflated

`[DEF]` A latent activation event is
$$LA_k(x) \iff Z_t(x) = 0 \;\wedge\; Z_{t+k}(x) = 1 \;\wedge\; \Delta K_{t:t+k} \text{ contains an admissible activating relation}$$

**The third conjunct is the whole point.** Without it, $x$ "activates" merely because the
question changed. Every activation is classified into exactly one of:

| class | cause | |
|---|---|---|
| **state-induced** | $K_t \to K_{t+k}$ | the epistemically interesting one |
| **query-induced** | $Q_t \to Q_{t+k}$ | the question moved, not the knowledge |
| **transformation-induced** | $T_t \to T_{t+k}$ | the representation moved |

`[REC]` **These must not be conflated**, and the design holds $Q$ and $T$ **fixed** in the
primary Q-B arm so that only state-induced activation can occur there. Query- and
transformation-induced arms are run separately as **contrast arms**, not pooled in.

---

# 5. Controls — declared before execution

Controls at **both ends** of the instrument, because Q-A can fail in two opposite directions.

| # | control | requirement | catches |
|---|---|---|---|
| **S-A** | **duplicate `Zero`** — two $\Pi_i$ that are provably the same predicate | must show $M_{ij} = 1$ and entailment **both** ways | an instrument that cannot detect sameness |
| **S-B** | **independent-by-construction** — two `Zero`s on disjoint fields, generator independent | must show $P_{ij} \approx P_i P_j$ | an instrument that manufactures association |
| **S-C** | **degenerate `Zero`** — a $\Pi$ that always fires, and one that never fires | must be flagged **UNDETERMINED**, never "entailed" | vacuous predicates read as findings |
| **S-D** | **null fork** — $K^D = K^R$ (delete nothing) | $FEN_k$ must be **0** in every case | a fork that diverges on its own |
| **S-E** | **positive fork** — delete an element known to be determinative | $FEN_k$ must fire | a comparison that cannot see a real difference |
| **S-F** | **stream identity** | retain and delete branches consume identical inputs | the `O2` pairing defect |
| **S-G** | **provenance / read-disjointness** on every $(\Pi_i, Q, T)$ **triple**, verified by **perturbation** | no shared effective reads | `FR-003` — two hand-written maps were wrong |
| **S-H** | $\Delta^{\circ}$ **non-empty** in some transitions | the qualified/transformed component must actually occur | a generator that only adds and removes |

> **S-A and S-B together calibrate the instrument at both ends.** Without S-A a null result is
> uninterpretable (the instrument may detect nothing); without S-B a positive result is
> uninterpretable (it may detect everything). **Neither alone is sufficient.**

---

# 6. Falsification tests — run BEFORE any result is computed

| | assertion |
|---|---|
| `FT-1` | every $Z_i$ is computable on every state, or explicitly `UNDETERMINED` |
| `FT-2` | $\Delta_t = (\Delta^-,\Delta^\circ,\Delta^+)$ reconstructs $K_{t+1}$ from $K_t$ exactly |
| `FT-3` | the paired streams are byte-identical (S-F) |
| `FT-4` | the REF/NO-REF classifier is exhaustive and mutually exclusive |
| `FT-5` | every activation carries exactly one attribution class |
| `FT-6` | the provenance map passes perturbation on every $(\Pi_i, Q, T)$ (S-G) |
| `FT-7` | **positive control**: at least one $Z_i$ fires and at least one does not, on the same corpus |
| `FT-8` | no $Z_i$ verdict is stored without its full index $(K_t, D, T, \Pi_i, \mathfrak C)$ |

`[REC]` **`FT-7` is the "not blind" check** that `KR-REP-REDUCTION` used. Without it a null
result cannot be distinguished from a broken experiment.

---

# 7. Calibration BEFORE the run — the `KR-BRIDGE-03` lesson

`[NEG]` Informativeness is driven by **neither** population size **nor** distributional balance.
KR-BRIDGE-03 measured both to zero effect; the lever was **structural richness**, and two-thirds
of cells stayed uninformative for reasons no sampling could reach.

`[REC]` **`KR-STATE-01` calibrates first and runs second.** Before the main run, sweep the
generator and **measure**:

1. informative $(i,j,\text{stratum})$ count for Q-A,
2. the **NO-REF** informative-case count for Q-B,
3. per-$Z_i$ firing rates — **any $Z_i$ saturating at 0.00 or 1.00 is a dead projection**, and a
   dead projection cannot participate in an entailment claim.

**A regime is rejected if it buys informative strata by saturating a projection.**

---

# 8. Adjudication rules

| | rule |
|---|---|
| **estimand** | the **within-stratum** effect. The marginal is reported, never adjudicated |
| **replication** | independently seeded held-out split; **sign AND magnitude above the declared floor on BOTH splits**; at least one stratum CI excluding zero |
| **effect floor** | declared **before** inspection this time. If it is ever revised, the revision is recorded in the artifact |
| **entailment** | $P(Z_i{=}0,Z_j{=}1) = 0$ is **empirical implication in the tested corpus**, and the artifact must say so in the same sentence |
| **multiplicity** | with $m$ `Zero`s there are $m(m-1)$ ordered pairs. **The family is declared in advance** and the multiplicity is reported; no pair is promoted on an unadjusted single comparison |
| **labels** | pre-registered classifications, **verified** after execution. A mismatch is a finding; the predicate is never redefined to match its label (A1) |
| **balance** | the generator is **not** tuned to balance cells; natural imbalance is evidence (A2) |

---

# 9. Outcomes declared in advance

| outcome | condition | consequence |
|---|---|---|
| **A — multidimensional** | ≥ 2 `Zero`s non-entailing in **both** directions, replicating, S-A and S-B both pass | `[EXP]` the state is **not** scalar. **Does not** license "probabilistic" or any cardinality claim. |
| **B — collapse** | all pairs mutually entailing | `[EXP]` the `Zero`s are one phenomenon under different names in this corpus |
| **C — undetermined** | S-C fires widely, or too few informative strata | **report as undetermined.** Not a null result. |
| **D — retention necessary** | $H_{\mathrm{RET}}$ supported on **NO-REF** cases, replicating | `[EXP]` **present observational neutrality is insufficient as a deletion criterion** — the result with a decision consequence |
| **E — retention safe** | $FEN_k = 0$ throughout NO-REF, with S-E passing | `[EXP]` neutrality was sufficient **in this regime** — scope-bound, never general |

---

# 10. What this experiment **cannot** do

`[REC]` Stated in advance so the results document cannot drift into them.

- It **cannot** establish that the state is *probabilistic*. That is step 2 of the research order.
- It **cannot** establish state-space **cardinality**. That is step 3.
- It **cannot** prove non-entailment **universally** — only empirically, within the tested corpus.
- It **cannot** establish that $Z_i = z_i \circ \Pi_i$ exists. That is a research question it may
  only *inform*.
- It **cannot** select a kernel, declare a carrier, or open Theory v1.3.
- It **cannot** promote `Containment Neutrality` to `Containment Zero` by observing it; that
  requires the family test explicitly.

---

# 11. Governance

$$\text{EXPERIMENT} \to \text{AUDIT} \to \text{ADJUDICATION} \to \text{THEORY v1.3}$$

**This document is DESIGN only.** It is not authorized, not run, and creates no result.
`KR-STATE-01 ⊥ KR-ZERO ⊥ KR-REP-REDUCTION` — no lane result is modified by it.
**Theory v1.2 FROZEN · kernel NOT SELECTED · no carrier declared · no algebra declared.**

**Open before it can run:** the effect floor (§8), the concrete $\mathrm{Obs}$ contract for Q-B,
and the calibration sweep (§7). **`[OPEN]` None of the three may be settled after seeing results.**
