# 19 — Directive Adoption and Research Restructure

**Date** 2026-09-01 · **Status** `[EXP]` `[PROP]` — research, not canon.
**Adopts** `…/mathematical_ideas_that_can_be_implemented/Yes. I have now read the three supplied`
(1 706 lines), read as a **directive** and applied.

---

## 1. The headline is replaced

The directive's §38 supersedes this experiment's own framing of its result. Adopted verbatim as the
lane's headline:

> `[EXP]` **The experiment falsified the assumption that kernel minimality can be determined
> independently of semantic representation.**

The evidence is internal and decisive: `|K_min| = 13` under the protocol's algebra `𝒜₀` and
`|K_min| = 8` under `𝒜₁₂`. **These are not conflicting measurements. They are solutions to two
different optimization problems**, and the disagreement is evidence that the admissible semantic
algebra is not yet fixed — which is more fundamental than kernel size.

The research sequence is therefore inverted:

```
   ADOPTED :  Representation → Capability → Reachability → Minimality
   REJECTED:  Operator list  → Ablation   → Kernel
```

## 2. Directive items adopted

| # | Directive | Action | Where |
|---|---|---|---|
| §9 | `DetectGap` conclusion must read *"the tested semantic function called `DetectGap` is representable as a derived evaluation under the tested family of algebras"* — `[EXP]`, not `[THEOREM]` | **applied** | `FINAL` §1.2, `00-INDEX` |
| §23 | The 15 arms are paired, not independent | **verified and applied** — §3 below | `12-…`, `FINAL` §10 |
| §24 | Relabel "statistical robustness" → **seed reproducibility** | **applied** | `12-…`, `FINAL` §10 |
| §26 | Standardize *"14 powers irreducible **relative to the tested capability model and semantic algebra**"* | **applied at every occurrence** | 4 files |
| §14 | `Represent` removed from any serious candidate kernel, retained in the research vocabulary | **applied** — status `UNSUPPORTED` | §18 §6 |
| §29 | `Reachability ≠ Epistemic adequacy` — record as the single most important limitation | **applied** — §4 below | this document |
| §33 | Seven-level research hierarchy replaces the flat kernel question | **adopted as programme structure** | §5 below |
| §35–36 | Kernel *shape* extends to `𝒦 = (𝒫, ℛ, δ, ℐ)` and possibly `𝔎 = (𝒫, ℛ, δ, ℐ, 𝒰)` — `[PROP]`, not `[EXP]` | **adopted, downgraded to `[PROP]`** | §6 below |
| §30–32 | Next experiment = Semantic Kernel Equivalence with behavioural equivalence `B` and preservation vector `P` | **adopted as the specification** | §7 below |
| §11 | The type of `D(K_t, I_t)` is the next open mathematical question | **recorded** | §8 below |
| §37 | *Do not run another giant random simulation until state semantics is addressed* | **adopted as a standing constraint on this lane** | §9 below |

## 3. The design was paired — verified, and it *sharpens* the one surviving result

`make_world` consumes a fixed 17 random draws regardless of the operator set. Re-seeding each arm
with the same seed therefore hands **every arm the identical world sequence**. Verified directly: two
arms at seed 1 produce bit-identical streams; one arm's 10 000 trials contain 10 000 distinct worlds.

> **The experiment is 10 000 distinct worlds × 15 perfectly paired arms — not 150 000 independent
> observations.** `[NEG]`

Re-analysing the only non-vacuous property result correctly, over the shared worlds:

| | count |
|---|---|
| ablated arm fails, baseline does not (`b`) | **5 944** |
| baseline fails, ablated does not (`c`) | **0** |
| both fail | 0 |
| neither fails | 4 056 |

```
P(P10 fails | guard active) = 5944 / 5944 = 1.0000    — exactly, and DETERMINISTICALLY
```

Given the world and the operator set the outcome is not sampled at all: `c = 0`, and `b` is *every*
guard-active world. **No test statistic is required and none is reported.**

> ### The confidence interval was on the wrong quantity `[NEG]`
> This lane published *"P10 fails at .5944, 95 % CI [.5847, .6040]"*. That placed a sampling interval
> on a quantity with **no sampling error** — the conditional failure rate is 1 by logic. The only
> quantity carrying genuine sampling error is the **guard-activation rate**, and it describes the
> chosen generator `G`, not KnowledgeOS: **0.5944, 95 % CI [0.5847, 0.6040]**.
>
> Read correctly: *"in ~59 % of worlds drawn from `G` the situation arises at all; whenever it
> arises, the ablated system fails — always."* **The second clause is the finding. The first is a
> fact about the generator.** The corrected statement is strictly stronger than the published one.

The pairing is not a defect — it is the right design for arm comparison, since it removes world-level
variance entirely. The defect was reporting independent-binomial intervals over a paired design.
No published conclusion is invalidated, because no between-arm test was ever run.

## 4. The single most important limitation, now named

> `[NEG]` **`Reachability ≠ Epistemic adequacy`** (directive §29)

The simulator answers *"can this carrier be generated?"* It does **not** answer *"does the resulting
epistemic state preserve semantic adequacy, uncertainty, provenance, calibration, model uncertainty,
alternative hypotheses, temporal identity, and inquiry-relative validity?"*

This subsumes and outranks the limitation recorded in `09-simulation-design.md` ("cannot see degree").
Every result in this lane is bounded by it, and it is the reason no amount of further ablation can
settle the kernel question.

## 5. Programme restructure — seven levels, kernel last

Adopted from directive §33. The kernel question moves from *first* to *seventh*.

| Level | Question | Status |
|---|---|---|
| **1 Ontology** | What objects exist? `Observation, Evidence, Proposition, Claim, Model, Alternative, Knowledge, …` | partly settled by the ratified 8 primitives; the operator-facing ontology is **open** |
| **2 State** | What is the mathematical *type* of `K_t`? | **OPEN — now the blocking question** |
| **3 Semantics** | When do two representations encode the same epistemic state, `R₁ ≡_sem R₂`? | **OPEN — no definition exists**; this is why `13 ≠ 8` was even possible |
| **4 Adequacy** | When is `K_t` sufficient for inquiry `Q`? | partly modelled (`Determination`), untested |
| **5 Transition** | How can `K_t → K_{t+1}` occur? | modelled as `state-mutation`; the corpus's 7 revision verbs are unmodelled (Q-4) |
| **6 Invariants** | What must survive every valid transition? | **the invariant-custody result (§18 §5.3) belongs here** |
| **7 Kernel** | Which transformations are irreducible? | *this experiment* — and it was run at level 7 with levels 2, 3 and 6 unsettled |

> `[INF]` This restructure explains the experiment's own result rather than merely replacing it.
> Minimality was computed at level 7 while level 3 was undefined; `13` and `8` are what an undefined
> level 3 looks like when you measure at level 7.

## 6. Kernel shape — extended, and downgraded to `[PROP]`

This lane proposed `𝒦 = (𝒫, ℛ, δ)`. The directive extends it, and the extension is motivated by
*this* lane's own finding:

```
𝒦 = ( 𝒫 , ℛ , δ , ℐ )                and possibly      𝔎 = ( 𝒫 , ℛ , δ , ℐ , 𝒰 )

  𝒫  primitive epistemic powers
  ℛ  admissible composition / typing relations
  δ  state-transition / commit semantics
  ℐ  protected epistemic invariants      ← required by the invariant-custody result (§18 §5.3)
  𝒰  uncertainty / assessment semantics  ← Brown–Hwang motivated; absent from this experiment
```

**`ℐ` is not an addition of taste.** §18 §5.3 showed that a reduction can decrease `|𝒦|` while
*increasing* `|custody(I, 𝒦)|`. A kernel formalism without an invariant component cannot express
that trade-off, and therefore cannot state when a reduction is an improvement.

**`𝒰` marks a genuine gap in this experiment:** uncertainty was modelled only as something *carried
by* a `Verdict`, never as a structured state component. Directive §28's
`𝓔_t = (K_t, U_t, M_t, ℋ_t, ℱ_t)` — content, uncertainty, active model, admissible alternatives,
accumulated evidence — is the right shape of the repair.

**Status: `[PROP]`, downgraded from this lane's earlier framing.** The experiment produced evidence
for the *powers-vs-packaging asymmetry* that motivates the shape; it did **not** test the shape.

## 7. Specification of the next experiment — Semantic Kernel Equivalence

Replaces operator-name ablation. Not to be run before level 2 and level 3 (§5) have answers.

**Behavioural equivalence.** For a kernel `𝒦`, define an externally observable behaviour

```
B(𝒦, W, Q, E, C) = ( K′ , Assessment , Alternatives , Gap , Revision , Decision-eligibility )
```

and call two kernels behaviourally equivalent iff `B(𝒦₁,·) = B(𝒦₂,·)` on every admissible test case
(distributional equality for stochastic systems). **Compare behaviours, never internal operator
traces** — the latter is what made this experiment's results representation-relative.

**Epistemic preservation.** Behavioural equality is *not sufficient*: `𝒦₂` may match `B` while
losing provenance or uncertainty. Require additionally

```
P = ( Meaning, Evidence, Warrant, Uncertainty, Alternatives,
      History, Identity, Context, Inquiry, Authorization )

           P(𝒦₁) = P(𝒦₂)   on every dimension declared essential
```

**Search discipline.** CEGAR rather than random sampling: propose a reduction → search adversarially
for a counterexample → keep the primitive if one is found, otherwise propose a further decomposition.
The universal quantifier is not computable; aggressive counterexample search is the honest substitute.

**Two additions this lane contributes to that specification:**

* **Capability-closure as a gate** (§18 §4.1): `∀ c ∈ C_required : ∃ o ∈ C reaching c` — decidable,
  and the check that actually found `Qualify`. It belongs between the capability-inventory and
  minimality stages of the adopted pipeline.
* **Invariant custody as a cost dimension** (§18 §5.3): a candidate reduction must report
  `custody(I, 𝒦)` for every `I ∈ ℐ` it claims to preserve, not only `|𝒦|`.

## 8. The type of the difference — recorded as the next mathematical object

Directive §11. The derivation `Gap := difference-decision(norm-comparison(K_t, I_t))` is the lane's
strongest result, and it leaves the *type* of the difference entirely open:

```
Δ_t = ( Δ^content , Δ^uncertainty , Δ^model , Δ^observability , Δ^requirement )
```

If `K_t` carries semantic content, uncertainty, provenance, alternatives, models and temporal state,
then `D(K_t, I_t)` **cannot be a scalar metric**. `[PROP]` This is a direct consequence of the
`DetectGap` result: having shown the operator is derived, the burden moves entirely onto the type of
what it derives.

## 9. Standing constraint on this lane

> **No further large randomized simulation until level 2 (state type) and level 3 (semantic
> equivalence) are addressed.** (directive §37, adopted)

Justified by this lane's own measurements, not only by instruction: across 150 000 trials the
randomized layer produced **exactly one** non-vacuous result, and §3 shows even that one is
deterministic given the world — it needed no sampling at all. Representation search (§18 §5) yielded
more per unit of effort by a wide margin.

## 10. Where this lane and the directive still differ

Two points, recorded rather than resolved.

| | Directive | This lane |
|---|---|---|
| **`Validate`** | 🟡 *"likely important; semantics of warrant need expansion"* | `IRREDUCIBLE-CANDIDATE`, derivable in only **1/12** representations and that one degenerate (N-8). **The warrant-semantics concern is accepted and is level-2/level-4 work; it does not by itself lower the structural status.** Recorded as a divergence. |
| **§7 "the 150 000 simulations were largely unnecessary"** | framed as a lesson from the result | Agreed for the *randomized* layer. But the **vacuity audit** — this experiment's most transferable methodological product, which the directive itself endorses (§4 of the audit) — was only discoverable *by running* the randomized layer and finding it non-discriminating. **A negative result about an instrument requires operating the instrument.** |

## 11. What is now settled, and what is not

**Settled within this lane's scope:**
`DetectGap` should not be treated as a primitive operator — within the tested representation family.
`C0` has a structural capability hole at `Observation → Evidence`. Representation determines
minimality. Operator minimality, functional necessity and domain responsibility are three different
things.

**Not settled, and not settleable by more ablation:**
The type of `K_t`. The definition of `≡_sem`. The independently derived capability inventory. Whether
any operator is a KnowledgeOS primitive.

**The real question, in the directive's sharpened form** (§38), superseding this lane's own closing
question:

> **What mathematical structure must an epistemic state preserve under all admissible representations
> and transitions?**
