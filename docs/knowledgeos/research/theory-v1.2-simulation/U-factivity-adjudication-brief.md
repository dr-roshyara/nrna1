# U — Factivity: Adjudication Brief

**Status** `[NORMATIVE]` — **a decision brief.** **The decision has since been taken (see §0).**
This lane supplied evidence and options; it did not adjudicate. **Baseline** v1.2, unchanged.

---

# 0. OUTCOME — `R1` SELECTED *(governance, 2026-09-02)*

> **`K_t → A_t` (AttributedState)** · **`Knows(a,p,c,t) → True(p,c,t)` retained as an external,
> factive assertion** · **Verification kept separate.**

**Taken by human governance, not by this lane, and not on evidence** — §2 records that `R1` and `R2`
are behaviourally identical, so **no evidence could favour either**. That is precisely why it required
a decision. **The brief below stands unaltered as the input to that decision**; it is not
retro-edited to look like it predicted the outcome.

**What it settles:** the joint unsatisfiability of `DEF-1` and the attribution equation is dissolved —
the kernel's object is an **attribution**, and factivity survives intact **externally**, where truth is
available.

**What it does not settle** — §5 stands in full: **the 390 false attributions remain.** The decision
fixes *what may be claimed*, never *how often the system is right*.

**Consequent engineering, not research:** propagate `K_t → A_t` to `Δ_t`, `Zero`, adequacy, the kernel
definition and `I1`–`I9`; **no KnowledgeOS component may assert `Knows`.**

**On §4:** `ChannelRelation` is **not a precondition of `R1`**, which keeps Verification separate
without committing to a channel-consuming verifier. The constraint stands against **any future
verification component that reads evidence channels**.

**Queue now:** **`Contr` + evaluation domain** (next experiment) → `⪰`. The stop on further simulation
is **lifted**.

> Per review: *"factivity is a decision rather than another formula experiment… handled as an
> explicit architectural/epistemological adjudication, not buried inside another simulation."*
> **No experiment is run here.** Everything below is already-established evidence, assembled for a
> decision.

---

# 1. The decision

> **Theory v1.2 asserts two things that cannot both hold. Which one is amended?**

```
(1)  Knows(a,p,c,t) → True(p,c,t)                     DEF-1, factivity
(2)  K_{a,c,t} = Γ(E_{a,c,t}, Q_t, C_t, EC_t)         the v1.1/v1.2 attribution equation
```

**They are jointly unsatisfiable for any total `Γ` that attributes anything.**

## Why — the witness, not an argument

Two worlds differ in the truth of `p`. A source the admission policy rates reliable reports the same
value in both. The resulting epistemic states are **bit-identical** — verified by fingerprint over
observations, evidence, interpretations, assessments, determinations, hypotheses and rejections.
`Γ` is a function of `E`, so it returns an **identical** `K`. The attribution is true in one world
and false in the other.

**Truth is not in `Γ`'s domain.** No choice of `Γ` changes that, because it is a property of the
domain, not of the function.

**Policy sweep — the only escaping `Γ` attributes nothing:**

| attribution policy | attributes anything? | factivity violated |
|---|---|---|
| `justified-unique` | yes | **yes** |
| `justified-any` | yes | **yes** |
| `none` | **no** | no |

**At scale:** 420 factivity violations in 10 000 paired worlds; conditional rate **0.1237** given an
attribution was made. Diagnosis: **414 / 665** violations where a policy-trusted source reported a
falsehood, **0 / 2 607** otherwise.

**And the satisfaction layer confirms it independently:** **no `Sat_c` class in the eight-class family
requires factivity.** A state can satisfy all eight and be false. The family has **nowhere to attach
truth** — visible from the specification alone, before any execution.

---

# 2. Why this cannot be settled by experiment

Three repairs were tested. The results are in, and they do not select an option:

| repair | factivity violation | coverage | verdict |
|---|---|---|---|
| baseline | 0.1175 | 0.3320 | the obstruction |
| **R1 rename `K_t`** | n/a — 0 knowledge claims | 0.3320 | works |
| **R2 externalize factivity** | n/a — 0 knowledge claims | 0.3320 | works |
| **R3 partial `Γ`** *(as specified)* | **0.1181** | 0.1143 | **REFUTED** — no better than baseline, −66 % coverage |
| R3′ channel consulted | 0.0000 | 0.3497 | **not a repair** — 71.2 % of attributions bypass the pipeline |

> **R1 and R2 are behaviourally identical** — 3 320 attributions, 390 false, 0 knowledge claims in
> both. **The remaining choice is not empirical.** No further simulation can distinguish them,
> because they differ in *what the system claims*, not in *what it does*.

---

# 3. The options

## R0 — Amend `DEF-1`: KnowledgeOS's `Knows` is not factive

Keep the name `K_t`; weaken factivity. `Knows` becomes a **warranted-attribution** predicate.

| | |
|---|---|
| **commits to** | a non-factive knowledge predicate — a substantive epistemological position, departing from the standard analysis |
| **cost** | `DEF-1` is foundational in the corpus; amending it is the largest theoretical change on the table |
| **gain** | vocabulary unchanged; no new component |
| **risk** | the word "knowledge" continues to carry factive connotations for every reader; the amendment must be restated at every use or it will silently revert |

## R1 — Rename the object

`K_t → A_t` (**AttributedState**). `DEF-1` retained, governing a predicate the kernel never computes.

| | |
|---|---|
| **commits to** | KnowledgeOS never claims knowledge; it claims **attribution** |
| **cost** | vocabulary change wherever `K_t` appears — `Δ_t`, `Zero`, adequacy, the kernel, `I1–I9` |
| **gain** | the contradiction is removed; factivity is preserved *as a concept*; nothing else changes |
| **measurement** | none added |

## R2 — Externalize factivity

The kernel emits `ClaimToKnowledge`; an **external verifier** applies factivity.

| | |
|---|---|
| **commits to** | the same as R1, **plus**: verification is a **separate bounded context**, and **no kernel component may ever assert `Knows`** |
| **cost** | one new component outside the kernel |
| **gain** | R1's, **plus a measurable quantity that is otherwise invisible: 88.25 % of the kernel's claims survived external verification** |
| **constraint** | see §4 — the verifier must be on an **independent channel**, or it adds nothing |

## R3 — Partial `Γ` — **REFUTED, not an option**

Attribute only where verification is available. As specified it does **not repair factivity**
(0.1181 vs 0.1175) and costs 66 % of coverage. Its corrected form reaches 0 violations only by
reading the world directly — **71.2 % of its attributions are not products of the epistemic
pipeline**, and its `Γ` is no longer a function of `E` alone, so it leaves the theorem's hypotheses
rather than refuting it.

---

# 4. A constraint the earlier work supplies to this decision

If **R2** is chosen, `KR-M2O` §6 imposes a condition that is easy to miss:

| validation channel | detects a misleading source |
|---|---|
| **same channel** as the selection | **0.75 %** |
| **independent channel** | **99.3 %** |

> **Channel independence does the work, not verification per se.** A verifier reading the same
> evidence channel that produced the claim is **not** independent verification, whatever it is
> called. R2's 88.25 % figure is meaningful **only** under an independent channel.

This also means R2 carries a second, less obvious commitment: **KnowledgeOS must be able to state the
relation between two evidence channels** — `ChannelRelation(c₁,c₂) ∈ {same · independent-under-stated-
assumptions · partially-dependent · unknown · causally-coupled}`. Declaring a channel is not the same
as establishing independence.

---

# 5. What no option does

> **None of them removes the 390 false attributions.**

R1 and R2 both leave the system wrong in 11.75 % of the cases where it attributes. **A system that is
wrong 11.75 % of the time is not improved by relabelling its output; it is described more honestly.**

The decision determines **what the system may claim**, not **how often it is right**. Improving the
latter is a different problem — it is the evidence-channel problem of §4, not the factivity problem.

---

# 6. What is downstream of this decision

`K_t` appears in `Δ_t`, `Zero`, adequacy, the kernel definition, and invariants `I1–I9`. Until the
decision is made, **every one of those is conditional on an unsatisfiable pair of clauses.** That is
the reason factivity heads the queue and not its intrinsic difficulty.

**Not downstream, and not to be bundled in:** `Contr`, `⪰`, `≡_sem`, `N_eff`. Each is independently
open.

---

# 7. Recommendation

`[PROP]` **R2 — externalize factivity**, with the §4 channel constraint made explicit.

**Reasoning, and its limits.** R1 and R2 are behaviourally identical, so the recommendation rests on
one asymmetry only: **R2 makes a quantity observable that R1 leaves invisible.** Under R1 the system
emits attributions and nobody counts how many survive scrutiny. Under R2 that number exists, and this
programme's repeated experience is that **the numbers you cannot see are the ones that go wrong** —
the winner's curse, the vacuous property passes, the artifact separation, the misleading channel were
all invisible until something measured them.

**Against R2:** it adds a component, and it commits KnowledgeOS to representing channel relations,
which is unbuilt. **Against R0:** it is the largest theoretical change and the word will fight the
amendment. **R1 is the cheapest** and is a perfectly defensible choice if the cost of a verification
context is judged too high.

> **This is a recommendation, not a decision.** The lane supplies evidence; it does not adjudicate
> its own work.

---

# 8. What a decision would need to state

1. **Which clause is amended** — `DEF-1` (R0) or the attribution equation (R1/R2).
2. **What `K_t` is renamed to**, if anything, and whether the rename propagates to `Δ_t`, `Zero`,
   adequacy, the kernel and `I1–I9`.
3. **Whether any KnowledgeOS component may assert `Knows`** — under R1 and R2, no.
4. **If R2:** where the verifier lives, and how `ChannelRelation` is represented.
5. **What becomes of the 390** — explicitly, that the decision does not address them.

Once stated, the queue advances to **`Contr`**, which is a research problem rather than a decision.
