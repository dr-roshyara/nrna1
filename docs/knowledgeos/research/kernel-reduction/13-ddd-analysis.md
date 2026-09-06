# 13 — Domain-Driven Design Analysis (Part XVI)

Ten questions per operator. `DDD-necessary` is a **third, independent** notion of necessity — it may
disagree with formal and empirical necessity, and where it does, the disagreement is the result.

| Operator | genuine domain capability? | distinct responsibility? | owns an invariant? | distinct I/O contract? | independent reason to change? | another owner natural? | domain or mechanism? | policy? | predicate? | orchestration? | **DDD verdict** |
|---|---|---|---|---|---|---|---|---|---|---|---|
| `Observe` | yes | yes | "no empirical content without world contact" | `World → Observation` | sensor/source model changes | no | domain | no | no | no | **domain primitive** |
| `Interpret` | yes | yes | "meaning is context-relative" | `Observation × Context → SemanticContent` | context model changes | no | domain | no | no | no | **domain primitive** |
| `Represent` | yes | yes | "encoding ≠ assertion" | `SemanticContent → Representation` | notation changes | partially (`Interpret`) | domain, but thin | no | no | no | **domain primitive, weak evidence** |
| `Relate` | yes | yes | typed links | `Rep|Sem × Rep|Sem → Relation` | relation taxonomy changes | no | domain | no | no | no | **domain primitive** |
| `Discriminate` | yes | yes | *Buddhi*: difference is decided, not read off | `X × X → Discrimination` | discrimination vocabulary changes | no | domain | no | no | no | **domain primitive** — retain despite LOO |
| `Hypothesize` | yes | yes | "content not entailed is marked as such" | `Rep|Sem → Hypothesis` | generation strategy changes | no | domain | no | no | no | **domain primitive, weak evidence** |
| `Infer` | yes | yes | "entailment is rule-relative" | `Rep × Rule → Claim` | rule system changes | no | domain | no | no | no | **domain primitive** |
| `DetectGap` | **no** | **no — it is the union of two others** | none of its own | `K_t × I_t → Gap` (reproducible) | changes only when `Determine`/`Discriminate` change | **yes, two of them** | **derived evaluation `𝒪?`** | no | **YES — it is a predicate** | no | **NOT a kernel primitive** |
| `Challenge` | yes | yes | "unchallenged ≠ validated" | `Claim → Defeater` | defeater strategy changes | no | domain | no | no | no | **domain primitive** |
| `Validate` | yes | yes | "warrant requires evidence + assumptions" | `Claim × Evidence → Verdict` | warrant standard changes | no | domain | **partly** — the standard is policy | no | no | **domain primitive; its standard is policy** |
| `Revise` | yes | yes | "`K_{t+1}` preserves enough history to explain `K_t → K_{t+1}`" | `K_t → K_{t+1}` | persistence model changes | no | **borderline: mechanism** | no | no | **partly** | **AMBIGUOUS — see below** |
| `Determine` | yes | yes | "adequacy is inquiry-relative" | `NormDelta × Inquiry → Determination` | inquiry model changes | no | domain | **partly** — `EC` is a contract | no | no | **domain primitive; contract-parameterized** |
| `Select` | yes | yes | expected-value-under-cost | `ActionSet × Objective → Decision` | objective model changes | no | **domain, but a DIFFERENT domain** | **the objective is** | no | **yes** | **BOUNDARY — see below** |
| `Qualify` | yes | yes | "information ≠ evidence" | `Observation × Policy → Evidence` | admission policy changes | no | domain | **the policy is** | no | no | **domain primitive; policy-parameterized** |

## The three disagreements between the notions of necessity

### 1. `Discriminate` — formally derivable, **DDD-necessary**

Formal ablation says removable (only because `DetectGap` redundantly carries its atom). DDD says
keep: *Buddhi* — the power to decide `different / contradictory / more-specific / superseding /
compatible / incomparable / unknown` — is a first-class domain responsibility with its own vocabulary
and its own reasons to change (§02 MR-2, SD-2). Under variant V4 the formal result flips to
irreducible, confirming that the LOO result was an artifact of packaging.

**Resolution: retain `Discriminate`, drop `DetectGap`.** All three notions then agree.

### 2. `Select` — the boundary case the protocol anticipated

Formally irreducible in 7 of 8 variants (fails only V5). Empirically it fires in exactly **one**
scenario (S13), the only one supplying `ActionSet` and `Objective`. Corpus support is the weakest in
the table (3 role-uses), and the corpus places it in the **Lord/action lane, deliberately outside the
epistemic projection**.

`[EXP]` The evidence is consistent with `Select` being a **domain capability of a neighbouring
bounded context**, not of the epistemic kernel: it consumes an `Objective` the kernel does not own,
and it produces a `Decision` no epistemic capability consumes. Its structural isolation in the
scenario suite is not a weakness of the suite — it *is* the finding.

**Status: `UNRESOLVED`.** Not deleted, not promoted. This is a *context-boundary* question, and a
minimality experiment is the wrong instrument to settle it. `[OPEN]`

### 3. `Revise` — operation or mechanism?

`state-mutation` is exclusive and its removal costs C11, C15 and C24 — formally decisive. But DDD
question 7 ("domain concept or implementation mechanism?") is genuinely unclear: a commit to `K_t` is
what *every* epistemic result eventually does, and the corpus carries **seven** revision verbs
(Update, Revise, Supersede, Correct, Invalidate, Retract, Expire) that are *epistemically distinct*
though they share the mechanism.

`[EXP]` The experiment measured the *mechanism* and found it irreducible. It did **not** test whether
the seven epistemic revision kinds are distinguishable — the simulator cannot see them (§09).
**Status: `AMBIGUOUS`** — irreducible as a power, under-modelled as a domain concept. `[OPEN]`

## Zero · Ideal State · Inquiry · Governance · Authorization — tested, not assumed

| Construct | Hypothesis tested | Result |
|---|---|---|
| **Zero** | predicate, not primitive | **CONFIRMED** `[EXP]`. `Gap` is produced by `difference-decision ∘ norm-comparison`; `DetectGap` holds no exclusive power. `Zero(K_t, I_Q, EC)` behaves as a state predicate over a normative comparison. |
| **Ideal State** | state/target construct, not an operator | **CONFIRMED by construction and load-bearing**: `IdealState` is ambient; no `Determination` and no `Gap` is reachable without it. Scenarios S5, S8, S10, S14, S16 supply none and correspondingly demand none. |
| **Inquiry** | domain input, not an operator | **CONFIRMED**: ambient; `Determination` requires `NormDelta` **and** `Inquiry` — encoding inquiry-relative adequacy directly in the type rule. |
| **Governance** | external constraint | **CONFIRMED by modelling, not by test**: `Policy` and `Objective` are ambient carriers supplied to `Qualify` and `Select`. No operator produces them. `[INF]` — this was assumed in the model, so it is not independent evidence. |
| **Authorization** | governance mechanism | **NOT TESTED.** No scenario exercises it; no failure of class F18 was observed because none could be. `[NEG]` `[OPEN]` |
| **Select** | decision/action mechanism | see §2 above — `UNRESOLVED` |
| **Determine** | epistemic operation | irreducible in 7 of 8 variants; flips under V3. `[OPEN]` |
