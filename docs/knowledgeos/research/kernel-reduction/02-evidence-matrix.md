# 02 — Evidence Matrix (Part I)

**Method.** Primary corpus = `docs/knowledgeos/brainstorming/` **minus** the derived directories
`verification/ synthesis/ falsification/ corpus/ classification/`.
As executed: primary **1 452**, derived **330**, total **1 782** (`*.md`, recursive; snapshot
2026-09-01 ~22:28 local).

> ### CORRECTED — corpus boundary and denominator (2026-09-01, external audit; accepted)
>
> An earlier lane fixed `N_primary = 1,155` under **the same stated rule**
> (`three_model_convergence/14_decision-log/model-boundary-decisions.md` line 546). The gap is **not**
> corpus growth in general. Two causes, both verified:
>
> 1. **A derived analysis lane writes its output into the primary corpus.**
>    `three_model_convergence/` holds **325 `.md` files, every one created on 2026-09-01**, by a
>    currently-running sequential-analysis pass. It is derived material by construction, but it is
>    **not in the protocol's five-directory exclusion list**, so the stated rule sweeps it in.
>    Excluding it gives **1 153** — within 2 of the earlier lane's 1 155.
> 2. **The corpus was being written *during* the experiment.** 1 782 `.md` at 22:28; **1 808** at
>    23:02; `three_model_convergence/01_source-analysis/per-file/NNNN.md` appearing at roughly one
>    file per two minutes throughout. **`N` is a moving target and must carry a timestamp.**
>
> **Corrected boundary rule (v2):** primary = `brainstorming/` minus
> `verification/ synthesis/ falsification/ corpus/ classification/` **and minus
> `three_model_convergence/`** → **N_primary = 1 153 @ 2026-09-01 23:02**.
>
> **Effect on findings: none.** MR-1 and the full 13-operator role-count table were recomputed under
> the corrected rule. MR-1 returns the **identical six files**; every one of the 13 role counts is
> **unchanged (delta 0)**. Only the denominator moves — and it moves so as to make MR-1 *stronger*:
> **6 of 1 153**, not 6 of 1 452. Token counts change by at most 8 (`Challenge` 83→75), affecting no
> verdict.
>
> A residual discrepancy in the derived tiers (that lane recorded `EXCLUDED_VERIFICATION` = 438
> against 322 today) indicates the corpus was **reorganized** between measurements. **`[OPEN]`** — it
> is not explained by growth alone.

Two counts are reported per operator, because they disagree sharply:

* **token files** — files containing the bare word (English usage included);
* **role files** — files where the token is used *as an operator*: inside `\boxed{…}`, inside
  backticks, followed by `(`, as a typed signature `Op : Kind`, or as an `\xrightarrow{Op}` label.

The role count is the honest one. The token count is reported to show how large the gap is.

---

## MR-1 — The single most important Part I finding `[CORPUS]` `[NEG]`

**`C0` as a 13-element set has essentially no corpus provenance.**

Across the corpus, only **six** files contain ≥6 of the 13 operator tokens, and of those six:

| file | note |
|---|---|
| `mathematical_ideas_that_can_be_implemented/20260901-222111_prompt-kernel-reduction-and-minimality-experiment.md` | the experiment prompt itself |
| `…/20260901-221300_davidson-inquiries-into-truth-and-interpretation-…md` | `[EXT]` external source, dated 2026-09-01 |
| `…/20260901-210600_dretske-full-book-revision-…md` | `[EXT]` external source, dated 2026-09-01 |
| `phase_measure_theory/knowledgeos_kernel/prompts/20260901-023023_step_286_…` | operator-discovery prompt, 2026-09-01 |
| `phase_measure_theory/knowledgeos_kernel/prompts/20260901-024209_step_286_…` | same lane |
| `phase_measure_theory/20260828-103723_step-045-adaptive-learning-…md` | earliest, and only 6 of 13 |

Two of the three `Hypothesize` role-uses and two of the four `DetectGap` role-uses are the prompt
and one external extract. **`Hypothesize`, `Represent` and `Select` are the weakest-supported members.**

> `[INF]` The set `C0` is a *proposal of this lane*, not a corpus artifact. The experiment therefore
> tests a hypothesis about the corpus, not a summary of it. This is stated so no later reader
> mistakes `C0` for an established KnowledgeOS vocabulary.

---

## MR-2 — The corpus operator vocabulary is *different and larger* `[CORPUS]`

`step_286` operator discovery (2026-09-01) enumerates **nine operator families**, ~50 candidate names:

| Family | Corpus candidates |
|---|---|
| SD-1 Acquisition | Acquire, Observe, Receive, Detect, Discover |
| SD-2 Discrimination (*Buddhi*) | Distinguish, Compare, Classify, Separate, Accept, Reject, Defer, Contradict |
| SD-3 Construction | Create, Compose, Derive, Infer, Corroborate, Associate, Relate |
| SD-4 Revision | Update, Revise, Supersede, Correct, Invalidate, Retract, Expire |
| SD-5 Uncertainty / Zero | DetectGap, ExposeUnknown, IdentifyMissingEvidence, MarkUncertain, **Qualify**, RequestEvidence, Defer |
| SD-6 Relationship | Member, Relate, Compare, Contradict, Support, Depend, Supersede, Derive, Trace |
| SD-7 State transformation | `T_i : Σ → Σ` with closure/idempotence/commutativity questions |
| SD-8 Purification | `P : Σ_t → Σ_{t+1}` (blocked: not every axis is ordered) |
| SD-9 Action | `K_t →Buddhi→ Decision →Action→ W_{t+1} →Ω→ O_{t+1} →Qualify→ K_{t+1}` |

Question 15 (`20260826-183128`) supplies a **24-operation taxonomy with typed signatures** in eight
categories (Observation, Semantic, Epistemic, Update, Comparison, Resolution, Navigation, History).

---

## MR-3 — The corpus already classifies operations into three kinds `[CORPUS]`

Question 15 §4–§5 explicitly rejects one undifferentiated `Operation` set and gives:

| Class | Signature | Corpus members |
|---|---|---|
| `𝒪⁺` state-changing | `K_t → K_{t+1}` | Observe, Assert, AddEvidence, ReviseValue, Reframe, Resolve |
| `𝒪?` **derived evaluations** | `f(K_t) → Result` | Compare, **DetectGap**, DetectConflict, Evaluate |
| `𝒪^q` queries | `Query(K_t,q) → Result` | Query, Present |

> **`DetectGap` is already classified corpus-side as a derived evaluation, not a state-changing
> operator.** `[CORPUS]` This is independent of, and *prior to*, the ablation result in §10.

---

## The matrix

| Operator | token files | role files | First operator-role occurrence | Claimed responsibility (corpus wording) | Support | Counter-evidence |
|---|---:|---:|---|---|---|---|
| **Observe** | 172 | 66 | `20260826-155127_zero-findings-formal-summary` | `Observe(K_t,X_t,P,A,C,τ) → K_{t+1}`; SD-1 acquisition | **STRONG** | name is overloaded with the English verb; token count inflated |
| **Interpret** | 48 | 22 | `20260826-183128` Q15 §3.2.1 *Parse* | signal → semantic content; `Observation ≠ Proposition ≠ Knowledge` | **MODERATE** | corpus name is *Parse*, not *Interpret* |
| **Represent** | 52 | 5 | `20260827-162652_step-025` executable reference model | commit semantic content to a manipulable structure | **WEAK** | only 5 role-uses; 2 of 5 are this lane's own artifacts |
| **Relate** | 20 | 7 | `20260901-021321_step_286` | SD-6 relationship family; `Relation` **is** a ratified primitive | **WEAK–MOD** | the *primitive* is ratified; the *operator* is not |
| **Discriminate** | 40 | 12 | `20260901-005349_step_286` | SD-2 *Buddhi* = discrimination/determination power | **STRONG** (as a family) | the corpus family has 8 members; `Discriminate` is a lane coinage over them |
| **Hypothesize** | 3 | 2 | Q16 status table edge `Unknown → Hypothesized` | generate content not entailed by evidence | **WEAK** | 2 role-uses, both dated 2026-09-01 or the prompt |
| **Infer** | 34 | 17 | `20260826-183636` Q16 | `Infer(A₁,A₂,…,Rule) → A₃` over assertions | **MODERATE** | Q16 §*Recommendation 6* corrects an earlier over-narrow `Infer(Σ₁,Σ₂)` |
| **DetectGap** | 6 | 4 | Q15 §3.5.3 `DetectGap(K_t,I_t) → K_{t+1}` | identify missing knowledge relative to the Ideal State | **MODERATE** | **corpus itself reclassifies it into `𝒪?`** (MR-3) |
| **Challenge** | 82 | 12 | `20260823-112155` kernel consistency-boundary | produce a defeater; adversarial simulation | **MODERATE** | token count is dominated by governance prose |
| **Validate** | 101 | 39 | Q16 `Verify` / `Corroborate` edges | assign warrant given evidence + assumptions | **STRONG** | corpus splits it into Verify **and** Corroborate |
| **Revise** | 68 | 33 | SD-4; Q15 §2.2 history-preservation invariant | commit a change to `K_t` preserving history | **STRONG** | corpus has 7 distinct revision verbs, not one |
| **Determine** | 146 | 7 | Q15 §3.6.2 *Resolve Gap*; zero-findings #5 | inquiry-relative adequacy | **MODERATE** | 146 → 7 is the largest token/role gap in the table |
| **Select** | 31 | 3 | `20260827-183746_step-025g` `a* = Select(A_feasible,Objective)` | choose an action under an objective | **WEAK** | corpus places it in the **Lord/action** lane, deliberately *outside* the epistemic projection |
| **(Qualify)** | — | — | `Qualify : Observation × Policy ⇀ Evidence` | admit an observation *as evidence* under a policy | **STRONG** | recorded in the prior lane as **`G1` — irreducible gap**; **absent from `C0`** |

---

## MR-4 — `C0` omits an operator the corpus records as irreducible `[CORPUS]` `[NEG]`

`Qualify : Observation × Policy ⇀ Evidence` is carried in the prior kernel lane as `G1`
(irreducible), and the protocol's own constraint 9 asserts
`information ≠ evidence ≠ justification ≠ truth ≠ knowledge`.

**No operator in `C0` holds the power to turn an observation into evidence.**
This is not a modelling artifact of this experiment; it is a hole in the candidate list.
Its consequence is measured in §10 (the baseline fails before any ablation is run).

## Operators with no meaningful corpus support — recorded, not deleted

Per Part I.10, nothing is deleted. `Hypothesize` (2 role files), `Select` (3), `Represent` (5)
are carried forward with **UNSUPPORTED-BY-CORPUS** noted against them; their ablation results are
reported and then *discounted* accordingly in §13 and the final table.
