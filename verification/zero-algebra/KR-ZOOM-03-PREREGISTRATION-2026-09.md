# `KR-ZOOM-03` — **PRE-REGISTRATION** of the two blocking definitions

**Status:** **RATIFIED AND FROZEN 2026-09-05** (revised same day, **before any execution**). Execution authorized.
**Date:** 2026-09-05 · Theory v1.2 FROZEN · kernel NOT SELECTED

> **Neither definition below may be settled after seeing results.** That is the entire reason this
> document exists as a separate, dated artifact rather than a section of the design.

---

# A. The `Determine(Q | K, E)` contract

**The question it answers:** *what exactly must the system achieve before we say "the cause has
been determined"?*

## A.0 The machinery, common to all options

The investigation acquires evidence $E$ = the set of evidence links it actually probed. For a
candidate explanation $h = (\text{dim}, \text{factor})$:

$$\mathrm{support}(h \mid E) = \left|\{\,\ell \in E : \mathrm{dst}(\ell) = h \,\}\right|
\qquad
\mathrm{terminal}(h \mid E) \iff \nexists\, \ell \in E : \mathrm{src}(\ell) = h$$

*Terminal* = nothing acquired explains $h$ further — the investigation bottomed out there.

## A.1 The three options, with their failure modes

### Option 1 — **existence.** *One plausible explanation is enough.* ✅ **RATIFIED — the frozen primary contract**

$$\mathrm{Determine} \iff \exists h : \mathrm{support}(h \mid E) > 0$$

⚠️ **Predicted failure: saturation, and a decoy satisfies it.** The generator emits 3–8
same-shaped decoy links per case, so *some* explanation is nearly always supported. This measures
**reachability**, not determination — the exact fault `KR-ZOOM-02`'s `zero_for_inquiry()` had.
**A metric that is ≈ 1.000 cannot discriminate** (vocabulary §3a).

### Option 2 — **best explanation wins.**

$$\mathrm{Determine} \iff \exists h = \arg\max_{h'} \mathrm{support}(h' \mid E)$$

⚠️ **Predicted failure: it can never return "not determined."** An argmax always exists once any
evidence is acquired. Removing the true cause would simply promote a decoy, and the experiment
would report *"determination unchanged"* — **hiding exactly the effect it is built to detect.**

### Option 3 — **supported AND competitors excluded.** *(not selected; retained as the pre-registered SECONDARY — see §D)*

$$\mathrm{Determine} \iff \exists!\, h \;\text{ s.t. }\;
\underbrace{\mathrm{terminal}(h \mid E)}_{\text{investigation bottomed out}}
\;\wedge\;
\underbrace{\mathrm{support}(h \mid E) \ge \tau_s}_{\text{sufficiently supported}}
\;\wedge\;
\underbrace{\mathrm{support}(h) - \max_{h' \neq h}\mathrm{support}(h') \ge \tau_m}_{\text{competitors excluded}}$$

with $\tau_s = 1$, $\tau_m = 1$ — *at least one evidence item points at it, and it strictly beats
every competitor by at least one.*

**Why this one.** It is the only option that **can fail**. Removing the true cause leaves either
no terminal candidate, or a tie — and a tie is `NOT DETERMINED`, not "the runner-up wins". That is
what makes `Zero_Q(d | E)` a real intervention rather than a relabelling.

## A.2 ⚠️ A generator change this forces — declared now, not later

Under Option 3, decoys must be able to **accumulate competing support**, or the margin condition
is trivially satisfied and `Determine` saturates again. `KR-ZOOM-02`'s decoys are single links.

`[REC]` **The generator must emit decoy *chains* with variable support**, so that
`NOT DETERMINED` occurs at a non-trivial base rate. **This is a declared pre-condition, not a
post-hoc tuning.** Calibration target: base `Determine` rate in the range **0.30–0.80** before any
intervention. **If calibration cannot reach that band, the experiment does not run.**

---

# B. The effect floor

**The question it answers:** *how large must a change be before we call it meaningful?*

## B.1 The estimand

$$\Delta = \underbrace{P(\text{ZERO-FLIP} \mid \text{root-cause dimension})}_{\text{the claim}}
\;-\;
\underbrace{P(\text{ZERO-FLIP} \mid \text{decoy dimension})}_{\text{the control}}$$

adjudicated **within stratum** (chain length × cross/same dimension), on **both splits**.

## B.2 What noise looks like here

At $n = 4000$ per split, the standard error of a difference of two proportions is at most

$$\mathrm{SE} = \sqrt{\tfrac{p_1(1-p_1)}{n} + \tfrac{p_2(1-p_2)}{n}} \;\le\; \sqrt{\tfrac{2 \times 0.25}{4000}} \approx 0.011$$

so **statistical detectability begins around 0.022**. Statistical significance is therefore
**not** the criterion — at this $n$, a 2 % difference is detectable and meaningless.

## B.3 The proposal

| tier | floor | reading |
|---|---|---|
| **primary** | $\lvert\Delta\rvert \ge 0.10$ **on both splits** | the effect is **material** |
| secondary (reported, never adjudicated) | $0.05 \le \lvert\Delta\rvert < 0.10$ | **borderline** — reported, not claimed |
| below | $\lvert\Delta\rvert < 0.05$ | **negligible** regardless of significance |

**Why 0.10 and not lower.** The claim is *"the root-cause dimension matters more than a deliberately
irrelevant one."* If the mechanism is real, root-cause removal should destroy determination in a
large fraction of cases while decoy removal should almost never. **A real effect here should be
large. A marginal one would itself be evidence that the experiment is measuring something else.**

`[REC]` **Both splits must clear the floor** — the `KR-BRIDGE-02` lesson, where 14 cells
"replicated" on sign alone and every one collapsed at higher power.

---

# C. What ratifying these two does and does not do

| | |
|---|---|
| **does** | unblock `KR-ZOOM-03` execution; fix the meaning of *determined* and of *material* in advance |
| **does not** | modify Theory v1.2 · select a kernel · promote `P1`–`P3` · adopt the `Zoom` definition · authorize any further experiment |

**Once ratified, this document is frozen and any later change to $\tau_s$, $\tau_m$ or the floor
must be recorded as an amendment with its reason — never applied silently.**


---

# D. Ratification record — 2026-09-05

| decision | ratified value |
|---|---|
| `Determine(Q \| K, E)` **primary contract** | **Option 1 — existence.** $\mathrm{Determine} \iff \exists h : \mathrm{support}(h \mid E) > 0$ |
| effect floor | **0.10 primary on both splits**; 0.05–0.10 borderline (reported, never claimed); < 0.05 negligible |

## D.1 ⚠️ A DISSENTING PREDICTION, pre-registered

I recommended Option 3 and flagged Option 1 as likely to saturate. **The owner ratified Option 1.
That is the decision and it stands.** The correct way for me to disagree is to **predict, before
running**, so the data settles it rather than the argument:

> ### `[CONJ]` PRE-REGISTERED PREDICTION P-Z3
> Under Option 1, the base `Determine` rate will exceed **0.95**, and consequently
> $\Delta = P(\text{flip} \mid \text{root-cause}) - P(\text{flip} \mid \text{decoy})$ will fall
> **below the 0.05 negligible floor** — because $\exists h : \mathrm{support}(h) > 0$ survives the
> removal of almost any single dimension.
>
> **If P-Z3 holds**, the correct reading is **NOT** "relevance has no effect". It is
> **"the ratified contract cannot see relevance"** — a `[DEFECT]` of the instrument, reported in
> the DEGENERATE METRICS section, not a finding about `Zero`.
>
> **P-Z3 is refuted** if the base `Determine` rate lands below 0.95 **or** $|\Delta| \ge 0.05$.
> **I would be wrong, and the ratified contract vindicated.**

## D.2 Option 3 retained as a pre-registered SECONDARY

Both contracts are functions of the **same** acquired evidence $E$, so computing both costs
nothing extra. Option 3 is therefore recorded **now, before execution**, as a **secondary reported
measurement** — never adjudicated, never substituted for the primary.

`[REC]` **This is declared in advance precisely so it cannot serve as a post-hoc rescue.** If
`P-Z3` holds and the primary returns nothing, the secondary shows whether the *domain* carries a
relevance effect that the *contract* could not see. **That distinction is the whole value of
running it.**

## D.3 Declared generator change

Decoys emit **chains** rather than single links, so competing support exists. Declared here,
before execution. It makes the domain **harder**, not easier, and applies identically to both
contracts.

## D.4 Calibration gate

**Measure the base `Determine` rate before analysing anything.** It is reported whatever it is.
There is **no** value of it that stops the run — the rate is itself the test of `P-Z3`.


---

# E. RATIFICATION REVISED — 2026-09-05, **before any execution**

The owner initially ratified **Option 1**, then revised to **Option 3** with a substantive
sharpening. **Nothing had been run.** The revision is therefore uncontaminated by results, which
is the only condition that matters — recorded in full so the sequence is visible.

| | frozen value |
|---|---|
| **primary contract** | **Option 3 — supported + competitors EPISTEMICALLY excluded** |
| secondary (reported, never adjudicated) | **Option 1 — existence.** Retained so prediction `P-Z3` remains checkable at no cost |
| effect floor | **0.10 primary on both splits**; 0.05–0.10 borderline; < 0.05 negligible *(unchanged)* |
| decoy chains | **required** — declared precondition |
| calibration gate | base `Determine` rate must land in **0.30–0.80** |

## E.1 ⚠️ The owner's caveat — and my draft failed it

> *"'Competitors excluded' must mean **epistemically excluded under the frozen evidence/assessment
> rules**, not merely absent from the generated candidate list."*

**My §A.1 Option 3 did not satisfy this.** With no competitor generated,
$\max_{h'\neq h}\mathrm{support}(h') = 0$, so the margin condition
$\mathrm{support}(h) - 0 \ge \tau_m$ passes **trivially**. That is exactly the false exclusion
the caveat names: *"a false exclusion caused by the generator rather than genuine epistemic
determination."*

## E.2 The corrected contract — **FROZEN**

Let $\mathcal A(E)$ = the **assessed** candidate set — explanations the investigation actually
encountered under the frozen evidence rules.

$$\mathrm{Determine} \iff \exists!\, h \in \mathcal A(E) :$$

$$
\underbrace{\mathrm{terminal}(h \mid E)}_{\text{(a) bottomed out}}
\;\wedge\;
\underbrace{\mathrm{support}(h \mid E) \ge \tau_s}_{\text{(b) supported}}
\;\wedge\;
\underbrace{\left|\mathcal A(E) \setminus \{h\}\right| \ge 1}_{\textbf{(c) a rival was ACTUALLY ASSESSED}}
\;\wedge\;
\underbrace{\forall h' \in \mathcal A(E)\setminus\{h\} : \mathrm{support}(h) - \mathrm{support}(h') \ge \tau_m}_{\text{(d) every assessed rival is beaten}}
$$

with $\tau_s = 1$, $\tau_m = 1$.

> ### **Condition (c) is the caveat, encoded.** You cannot exclude what you never considered. A case in which no rival was assessed is **NOT DETERMINED** — never "determined by default".

**Exclusion is over $\mathcal A(E)$ — what the investigation assessed — not over the generator's
candidate list.** The generator's inventory is invisible to the contract.

## E.3 Status of prediction `P-Z3`

`P-Z3` predicted saturation **under Option 1**. Option 1 is now the **secondary**, so `P-Z3` is
**no longer a prediction about the adjudicated result** — but it is still measurable, and it will
be reported. **It is left standing rather than deleted**: I made it, and it should be scored.
