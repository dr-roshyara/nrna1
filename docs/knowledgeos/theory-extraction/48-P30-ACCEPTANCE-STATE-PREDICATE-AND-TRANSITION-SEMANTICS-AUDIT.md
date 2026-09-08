# `P-30` — Acceptance State, Predicate & Transition Semantics Audit

**2026-09-08 · Lane T.** After [`47` `P-29`](./47-P29-EFFECTIVE-POLICY-REFERENCE-NECESSITY-AUDIT.md).

> **Single question:** *is acceptance a persisted state, a historical event/transition, a computed
> predicate over persisted state, or a combination — and what exactly does `K2` persist in relation to
> acceptance?*
>
> ⛔ **`P-08`, `P-18`–`P-29`, the 11-cell kernel, Schema v2, 3MC and Lane M FROZEN · no Schema v3 · no
> `K12` · no Acceptance aggregate · no architecture · no implementation · policy and policy-version
> persistence NOT reopened · provenance-chain questions NOT reopened · well-foundedness / terminality /
> acyclicity remain DEFERRED · notation is never corpus evidence.**

# 0. ⭐⭐⭐⭐ The commission's prior is right, and my own `P-29` closing sentence was too strong

$$\boxed{\begin{array}{ll}\mathbf{[EMP]}\ \textit{step-008 §28 A9} & \boxed{Historical\ acceptance\ must\ remain\ auditable.}\\[4pt] \mathbf{[EMP]}\ \textit{step-008 §15} & \textbf{"Acceptance is a domain event"} \;\cdot\; \boxed{AssertionAccepted}\ \textbf{"creates an auditable transition"}\\[4pt] \mathbf{[EMP]}\ \textit{step-008 §24} & K_t \xrightarrow{\ AssertionAccepted\ } K_{t+1}\\[4pt] \mathbf{[EMP]}\ \textit{theory-part-04} & \boxed{K \neq \text{set of currently accepted propositions}}\end{array}}$$

⭐⭐⭐ **Both halves of the two-level structure are corpus-attested.** Current acceptance is **not**
the knowledge state *(the corpus denies the identification outright, twice)*; historical acceptance
**must remain auditable** *(a boxed invariant)*.

⭐⭐ **And a witness `P-29` never found independently corroborates `P-29`'s own verdict:** the
`AssertionAccepted` payload carries **`PolicyId`** and **`PolicyVersion`** — a policy-version reference
**on a transition**, exactly where `P-29` placed it.

**The measured asymmetry, over 1 859 admissible files:** `Accepted(` **25** · `currently accepted`
**16** · `acceptance` 202 · `accepted` 445 — against ⭐ `acceptance event` **0** · `acceptance
transition` **0** · `acceptance history` **0** · `acceptance retained` **0** · `acceptance timestamp`
**0** · `accepted status` **0** · `acceptance warrant` **0** · `acceptance state` **0** ·
`acceptance must persist` **0** · `acceptance need not persist` **0**.

⭐⭐ **The retention obligation exists but is NOT phrased in retention vocabulary — it is phrased as
AUDITABILITY.** ⚠️ A vocabulary search alone would have returned **0** and concluded, wrongly, that
acceptance history is unwitnessed. **Counts were never treated as evidence, and all six positive
minority hits were read in full.**

---

# 1. The witnesses

| # | witness | status |
|---|---|---|
| ⭐⭐⭐ **W1** | `step-008:1132` **A9** — `Historical acceptance must remain auditable.` **boxed** | ⭐ **`[EMP]` — a corpus INVARIANT** |
| ⭐⭐⭐ **W2** | `step-008 §15` *"Acceptance is a domain event"*; `AssertionAccepted`; *"This creates an auditable transition."* | ⭐ **`[EMP]`** — but ⚠️ the 9-field payload is *"the event **might** contain"* ⇒ **`[STIPULATED]`** |
| ⭐⭐⭐ **W3** | `step-008 §24` — `K_t \xrightarrow{AssertionAccepted} K_{t+1}`, *"But the underlying assertion does not change truth value."* | ⭐ **`[EMP]` — acceptance IS a state transition** |
| ⭐⭐⭐⭐ **W4** | `theory-part-04:116` — `K \neq \text{set of currently accepted propositions}`, **boxed**; instead `K = CurrentEpistemicState + HistoricalContext + Justification + Provenance + Governance` | ⭐⭐ **`[EMP]` — decisive for the `K2` subject** |
| ⭐⭐ **W5** | `complete-mathematical-model §6` — *"The assertion set should **not** mean 'all currently accepted assertions'"* | ⭐ **`[EMP]` — an independent SECOND denial, and a corpus SELF-CORRECTION of the earlier `𝒜_t` reading** |
| ⭐⭐⭐ **W6** | `step-008 §10` — `Ω_A = (SupportStatus, AcceptanceStatus, CommitmentStatus, ContestStatus)`, `Ω_A ∈ 𝒪`; `ω : Ω_A × Event × Policy ⇀ Ω_A` **boxed** | ⚠️ **`[STIPULATED]` — §10 opens *"I recommend"*** |
| ⭐⭐ **W7** | `step-008 §16` — `NotAccepted \neq Rejected` and `Unresolved \neq Rejected`, both **boxed** | ⭐ **`[EMP]` — acceptance is NOT Boolean** |
| ⭐ **W8** | `step-008 §11` — `Accepted(P)=True` **while** `Contested(P)=True` | ⭐ **`[EMP]` — acceptance ⊥ contest** |
| ⭐ **W9** | `step-008 §12` — `ρ_A = AcceptancePolicy` (`N_ind ≥ 2`, `Authority ≥ A_min`, `Age ≤ T_max`, `ActiveConflict = False`, human approval); `TV-F-018-019`: *"all explicitly policy **examples, not laws**"* | ⭐ **`[EMP]`** |
| ⭐ **W10** | `STEP-VERIFY-001-010:81` — **`KERNEL₅`** = `{Evidence, Proposition, Provenance, Lineage, Dependency, Context, TemporalValidity, Polarity, AssessmentBasis}` vs **`POLICY`** = `{Aggregation, Inference, Thresholds, ProbabilityModels, BeliefModels, DecisionRules}`; `Assessment ≠ Acceptance ≠ Truth` | ⭐⭐ **`[EMP]` — acceptance is NOT in that corpus's own kernel list** |
| **W11** | `step-008 §28` — `Supported ⇏ Accepted` · `Accepted ⇏ Committed` · `Accepted ⇏ Truth` · `NotAccepted ⇏ False` · *"Acceptance is policy-governed"* | ⭐ **`[EMP]`** |
| ⚠️ **W12** | `step-025w §25W.17` *"Event sourcing fits naturally"* — `AssertionAccepted` among 11 immutable events | ⛔ **`[ARCH]` — an ARCHITECTURE claim; see §16** |
| **W13** | `step-197`, `STEP-VERIFY-186-205` — `P(S_t) ⊆ P(S_{t+1})`, hedged *"for information that the domain requires"* | `[CORROBORATION]` — the conservation shape |

⭐ **`AssertionAccepted` occurs in 5 admissible files** *(step-008, step-025w, `STEP-TRACE-B4`,
`TV-F-018-019`, `10-ddd-verification-plan`)* — **not a one-off.**

# 2. What "currently" modifies — of the five options, **`D`**

$$\boxed{\begin{array}{c}\mathbf{D} \textbf{ — acceptance is BOTH a historical event/transition AND a current predicate, and the corpus states both.}\\[4pt] \boxed{\textbf{⛔ Not } A \textbf{ (W1/W2/W3 make it an auditable transition) · ⛔ not } B \textbf{ (W4/W5 deny the stored-accepted-set reading) · ⛔ not } C \textbf{ alone · ⛔ not } E \textbf{ (A9 is explicit).}}\end{array}}$$

⭐ *"Currently"* modifies the **evaluation index**, not the object: `Accepted(P)` is asked **of the
present state under the present `ρ`**, which is why `NotAccepted` at `t₂` does not erase
`AcceptedAt(a,t₁)`.

# 3. The five objects, separated

| | object | corpus status | persistence | cell |
|---|---|---|---|---|
| **`A1`** | acceptance **event** `Accept(a,t,actor,warrant,…)` | ⭐ **`[EMP]`** — §15 *"Acceptance is a domain event"*; payload `[STIPULATED]` | ⭐ **required to remain AUDITABLE (A9)** | ⭐ **`K3b`** *(transition warrant)* + **`K3a`** |
| **`A2`** | acceptance **status** `Status(a,t)` | ⚠️ **`[STIPULATED]`** — `Ω_A`, *"I recommend"* | if a stored field, its prior values are retained | ⭐ **`K2`** *(current)* + **`K3a`** *(prior)* |
| **`A3`** | current **predicate** `Accepted_t(a)` | ⭐ **`[EMP]`** — 25 `Accepted(` uses | ⭐⭐ **NONE of its own — evaluated, not stored** | ⛔ **none** |
| **`A4`** | historical **fact** `AcceptedAt(a,t)` | ⭐⭐ **`[EMP]` via A9** | ⭐⭐ **YES — auditability** | ⭐ **`K4a-i`** + **`K3a`** + **`K3b`** |
| **`A5`** | acceptance **transition** `τ_accept : S→S'` | ⭐⭐ **`[EMP]`** — §24 `K_t → K_{t+1}` | ⭐ **YES** | ⭐ **`K3a`** *(pairs)* + **`K3b`** *(warrant)* |
| — | acceptance **warrant** | ⚠️ **`[UNWITNESSED]` as a term** (`acceptance warrant` **0**); `DecisionBasis` is *"might contain"* | — | ⭐ **`K3b`** if present |

⭐⭐ **`A3` is the only one with no persistence obligation of its own — and it is the one `P-29`'s
closing sentence generalised from.**

# 4. `Persist` vs `Compute` — the six tests kept separate

| # | test | answer |
|---|---|---|
| 1 | is current acceptance **computable**? | ⭐ **YES** — `ρ_A`'s conditions are checkable predicates over state |
| 2 | is current acceptance itself **stored**? | ⚠️ **`[STIPULATED]` only** — `Ω_A`'s `AcceptanceStatus` slot, hedged *"I recommend"* |
| 3 | is **historical** acceptance retained? | ⭐⭐⭐ **YES — A9, boxed** |
| 4 | is an acceptance **transition** retained? | ⭐⭐ **YES — §24 + "auditable transition"** |
| 5 | is acceptance **recoverable** from `K1`–`K11`? | ⭐⭐ **YES** — §13 |
| 6 | is acceptance **independently required** as information? | ⭐⭐⭐ **NO** — §13/§14 |

$$\boxed{A_t = g(S_t,\rho_t) \ \not\Rightarrow\ A_t \textbf{ is unpersisted} \qquad A_t \textbf{ computable} \ \not\Rightarrow\ A_t \textbf{ has no HISTORICAL obligation}}$$

⭐ **Both invalid implications are refused, as the commission required. Test 3's answer is what breaks
the shortcut.**

# 5. The temporal model

$$\boxed{\begin{array}{lll}\mathit{Accepted}(S_0,\rho_0) = \textbf{false} & \mathit{Accepted}(S_1,\rho_0) = \textbf{true} & \textbf{⇒ the ASSERTION's status changed}\\ \mathit{Accepted}(S_1,\rho_0) = \textbf{true} & \mathit{Accepted}(S_1,\rho_1) = \textbf{false} & \textbf{⇒ ⭐ NOTHING epistemically persistent changed } (P\text{-}27\ F7)\end{array}}$$

⭐⭐ **W3 names precisely what does NOT change:** *"the underlying assertion does not change truth
value."* ⇒ **the accepted object is not the primitive that changed.** ⭐ What changed is a **relation
between the assertion and the governing policy**, recorded as a transition.

# 6. The four counterexample pairs

| | pair | result |
|---|---|---|
| ⭐ **A** | `S1 = S2`, `ρ1 ≠ ρ2` | ⭐⭐ **GOVERNANCE EVALUATION, not epistemic persistence** — ⭐ **verified, not assumed:** W3 supplies the independent reason *(truth value unchanged)*, so this no longer rests on `P-27` `F7` alone |
| ⭐⭐ **B** | `a` accepted at `t₁` vs `a` introduced already accepted | ⭐⭐⭐ **A9 REQUIRES distinguishing them** — `H2` has no auditable acceptance; `K3a`'s `(prior→successor)` pair exists in `H1` and not in `H2` |
| ⭐⭐⭐ **C** | `Accept,Reject,Accept` vs `Accept` | ⭐⭐ **DISTINGUISHED — by `K3a`.** `K3a` retains prior **values**; the intermediate `Rejected` value is a prior value, and `P-16` established `K3a` retains **pairs**, so the ordering survives. ⚠️ **Not "K4a-i covers it" — `K4a-i` retains the CLAIM, which is identical in both histories** |
| ⭐ **D** | `Assessment(a)=X` with `Accepted(a)` true vs false | ⭐⭐ **`Supported ⇏ Accepted` (W11) + `ρ_A` (W9)** — the distinguishing variable is the **policy evaluation**, not the assessment. ⭐ **`Assessment ≠ Acceptance ≠ Truth` is never collapsed** |

⭐⭐ **Pair C is the load-bearing one, and it lands on `K3a`, not `K4a-i` — correcting `P-29`'s own
mapping of historical acceptance.**

# 7. ⭐⭐⭐ `K2` SUBJECT AUDIT — the corpus refuses the identification outright

$$\boxed{\begin{array}{c}\mathbf{[EMP]}\quad \boxed{K \neq \text{set of currently accepted propositions}} \qquad \textbf{and, independently} \qquad \textit{"the assertion set should NOT mean 'all currently accepted assertions'"}\\[6pt] \boxed{K = \mathit{CurrentEpistemicState} + \mathit{HistoricalContext} + \mathit{Justification} + \mathit{Provenance} + \mathit{Governance}} \qquad \mathit{Current}(K) = \textbf{"the current epistemic INTERPRETATION"}\end{array}}$$

| candidate subject | verdict |
|---|---|
| `accepted assertion` · `status` | ⭐⭐⭐ **`[REFUTED]` — W4 and W5 deny it twice** |
| `whole record` · `epistemic state` | ⭐ too broad — `K` has **five** parts, only one of which is current |
| ⭐ **current field/relation values of the item** | ⭐⭐ **`[EMP]`-supported** — `Current(K)` is an **interpretation** over `K`, and `P-08 §4.3` / `P-24` fixed `K2` as **item-scoped** |

$$\boxed{\begin{array}{c}\mathbf{K2\ VERDICT:}\ K2 \textbf{ preserves the CURRENT FIELD AND RELATION VALUES OF AN IDENTIFIED ITEM —}\\ \textbf{the state FROM WHICH } \mathit{Accepted}(a) \textbf{ is evaluated. ⛔ It does NOT preserve } \mathit{Accepted}(a).\end{array}}$$

⭐⭐ **This is stronger than `P-29`'s anticipated qualification:** `P-29` expected *"computed, therefore
not what `K2` retains."* ⭐⭐⭐ **The corpus gives a direct denial instead — the identification is
refused in the corpus's own words, so the conclusion never needs the "computed ⇒ not persisted"
inference the commission correctly flagged as invalid.**

⭐ **A capability clarification, not a capability change.** `|K| = 11`.

# 8. ⭐⭐ `K4a-i` AUDIT — the mapping is CORRECTED

> *"Does 'every claim ever made' entail 'every acceptance ever made'?"* — ⭐⭐⭐ **NO.**

| history | claim retained | acceptance history |
|---|---|---|
| **`H1`** claim, never accepted | ⭐ `K4a-i` ✅ | ⭐⭐ **no acceptance transition exists** — nothing to retain |
| **`H2`** claim, accepted | ✅ | ⭐ **`K3a`** pair + **`K3b`** warrant |
| **`H3`** claim, accepted, withdrawn | ✅ | ⭐ **`K4b-i`** withdrawn ground + `K3a` |
| **`H4`** claim, rejected, later accepted | ⭐ **identical `K4a-i` to `H2`** | ⭐⭐⭐ **only `K3a` separates them** |
| **`H5`** claim, accepted, superseded | ✅ | ⭐ **`K4a-ii`** supersedes + `K3a` |

$$\boxed{\begin{array}{c}\mathbf{K4a\text{-}i\ VERDICT:}\ \textbf{historical acceptance is NOT preserved by } K4a\text{-}i.\\ \boxed{\textbf{It is a PROPERTY OF HISTORICAL TRANSITIONS, carried by } \mathbf{K3a}\ (\textbf{prior status values, as pairs}) \textbf{ and } \mathbf{K3b}\ (\textbf{the transition's warrant}).}\\[4pt] \textbf{⭐ } K4a\text{-}i \textbf{ retains the CLAIM; } H2 \textbf{ and } H4 \textbf{ have the SAME claim and DIFFERENT acceptance histories.}\end{array}}$$

⭐⭐ **`P-29` mapped historical acceptance to `K4a-i`. That mapping is `[QUALIFIED]` — the correct
cells are `K3a` + `K3b`.** ⚠️ **Two of my own prior artifacts corrected by this audit, both from
corpus evidence rather than reinterpretation.**

# 9. Acceptance vs currentness — the table, with unsupported cells left EMPTY

| `Current(a)` | `Accepted(a)` | possible? | corpus basis |
|:--:|:--:|:--:|---|
| yes | yes | ⭐ ✅ | the ordinary case; `Ω_A` example tuples |
| yes | no | ⭐⭐ ✅ | **`Supported ⇏ Accepted`** + **`Unresolved ≠ Rejected`** — a current, unresolved assertion |
| **no** | **yes** | ⭐⭐⭐ **only as `AcceptedAt(a,t)`, NOT as `Accepted_now(a)`** | ⚠️ `Accepted(P)` is defined **currently**, so a non-current item is not *currently* accepted — **the cell is legitimate at the HISTORICAL level and empty at the CURRENT level** |
| no | no | ✅ | retired / superseded items |

$$\boxed{\begin{array}{c}\mathit{Accepted}(a) \neq \mathit{Current}(a) \quad\textbf{and}\quad \mathit{Accepted}(a) \neq \mathit{Current}(a) \wedge \mathit{Accepted}(a)\\ \boxed{\textbf{⭐ Row 3 is exactly where the two LEVELS separate — and it is the reason } D \textbf{ is the answer to §2.}}\end{array}}$$

⭐ **No cell was filled by intuition; row 3 is reported as level-split rather than as true or false.**
⭐⭐ **And acceptance is at least THREE-valued** *(`Accepted` / `Unresolved`=`NotAccepted` / `Rejected`,
W7)*, so the table is a projection, not the state space.

# 10. Acceptance vs authorization — **independent**

| case | corpus-compatible? |
|---|---|
| **A** authorized action, no assertion | ⭐ ✅ *(`P-28` item 3; `P-29` §9)* |
| **B** assertion, no authorization | ⭐⭐ ✅ **`G-67` establishes by NAMING with no warrant** |
| **C** authorized → assertion → acceptance | ✅ |
| ⭐ **D** authorized → assertion → **rejection** | ⭐⭐ ✅ **`NotAccepted ≠ Rejected` (W7) makes rejection a distinct outcome, not the absence of acceptance** |

$$\boxed{\textbf{A, B and D are ALL corpus-compatible } \Rightarrow \textbf{ ⭐ acceptance is NOT the epistemic consequence of authorization.}}$$

# 11. ⭐⭐ Acceptance ⇒ warrant? — **`[OPEN]`, and it splits acceptance into two regimes**

| evidence | direction |
|---|---|
| `ρ_A` requires conditions; `DecisionBasis` in the payload; A7 *"acceptance is policy-governed"* | ⭐ **for** |
| ⭐⭐ **`G-67` establishes by NAMING with no warrant** *(`B-6`'s structural `n/a`)*; `acceptance warrant` **0** | ⭐⭐ **against** |

$$\boxed{\begin{array}{c}\mathit{Accepted}(a) \Rightarrow \mathit{Warrant}(a) \ \textbf{ is } \mathbf{[OPEN]}.\\ \boxed{\textbf{⭐⭐ The corpus supports } \mathbf{warranted\ acceptance}\ (\rho_A\textbf{-governed}) \textbf{ AND } \mathbf{establishment\ by\ naming}\ (G\text{-}67) \textbf{ as DISTINCT regimes.}}\end{array}}$$

⭐⭐ **Consequence for `K3b`, stated and not overreached:** `K3b` retains a warrant **when a transition
has one**. ⛔ **It does not follow that every acceptance has one** — and `P-19`'s restatement of `K5`
already covers the honest case: *the estate must not claim checkable grounding it does not have.*

# 12. Policy-relativity — evaluation, not state

$$\boxed{\mathit{Accepted}(a;\ \mathit{GovernanceContext},\ S) \quad\textbf{— } \rho \textbf{ enters as a GOVERNANCE EVALUATION over epistemic state, not as epistemic state.}}$$

⭐⭐ **Three independent supports, so this no longer rests on `P-27`/`P-28` interpretation:** W3
*"the underlying assertion does not change truth value"* · W10 puts `DecisionRules` and `Thresholds` in
**`POLICY`**, not `KERNEL₅` · W9's conditions are *"policy **examples, not laws**"*.

⚠️ **Recorded tension, NOT resolved:** W4 lists **`Governance`** as a component of `K`, while `P-28`
placed governance in a **separate estate**. ⭐ **`P-28` is frozen and is not reopened here; the tension
is carried as `[OPEN]`.**

# 13. State-sufficiency test — **no independence pair exists**

| | |
|---|---|
| **goal** | `S_K(H1) = S_K(H2)` with `AcceptanceHistory(H1) ≠ AcceptanceHistory(H2)` |
| ⭐⭐⭐ **result** | 🔴 **IMPOSSIBLE** while satisfying `K1`–`K11` |
| **exactly which cell prevents it** | ⭐⭐ **`K3a`.** Acceptance status is a **value**; `K3a` retains **prior values as `(prior → successor)` pairs** *(`P-16`)*. Any two histories differing in acceptance history differ in that pair set — Pair **B** *(pair present vs absent)* and Pair **C** *(pair sequence differs)* both demonstrate it |
| ⭐ **and `K3b`** | retains each transition's **warrant**, which is what makes A9's *auditability* satisfiable rather than merely the bare status |

⛔ **Not asserted as "`K4a-i` covers it" — §8 showed `K4a-i` does NOT.**

# 14. Information decomposition

$$\boxed{I_A = I_A^{current} \;\cup\; I_A^{historical} \;\cup\; I_A^{warrant} \;\cup\; I_A^{policy} \;\cup\; I_A^{currentness}}$$

| component | cell |
|---|---|
| `I_A^current` — the state acceptance is evaluated **from** | ⭐ **`K2`** *(§7's narrowed subject)* |
| `I_A^historical` — prior acceptance values, ordered | ⭐⭐ **`K3a`** |
| `I_A^warrant` — the transition's basis | ⭐ **`K3b`** *(+ `K5`'s honesty condition)* |
| `I_A^policy` — `PolicyId`/`PolicyVersion` | ⭐⭐ **`K3b` content** *(`P-29` verdict B — independently corroborated here)* |
| `I_A^currentness` — which item state is current | ⭐ **`K2`** *(item-scoped, `P-24`)* |
| **the claim itself** | **`K4a-i`** |

$$\boxed{\textbf{⭐⭐⭐ Every component maps to an EXISTING cell. Acceptance introduces NO irreducible information requirement.}}$$

# 15. DDD test — no aggregate

| form | corpus status |
|---|---|
| ⭐ **`Accept` / `AssertionAccepted` as domain EVENT** | ⭐⭐ **`[EMP]`** — §15's heading is literally *"Acceptance is a domain event"* |
| `Accepted(·)` as **predicate / domain-service result** | ⭐ **`[EMP]`** |
| `AcceptanceStatus` as **state property** | ⚠️ **`[STIPULATED]`** — *"I recommend"* |
| `ρ_A` as **policy evaluation** | ⭐ **`[EMP]`** |
| `Acceptance` as **entity or aggregate** | ⭐⭐⭐ **`[UNWITNESSED]`** |
| `Acceptance` as **value object** | **`[UNWITNESSED]`** |

⛔ **No aggregate is created because "Acceptance" is a noun.** ⭐ **The one form the corpus states
outright is EVENT — and an event is a transition record, which `K3a`/`K3b` already carry.**

# 16. ⭐⭐ The event-sourcing trap — both inferences REFUSED

| inference | verdict |
|---|---|
| *"acceptance changes ⇒ an acceptance event must be persisted"* | ⛔ **INVALID.** ⭐ **The obligation comes from A9, not from the fact of change.** W12's *"Event sourcing fits naturally"* is an **`[ARCH]`** claim about *our architecture* — ⛔ **`P-19`'s rule applies: theory is never inferred from implementation existence** |
| *"acceptance is computed ⇒ no acceptance history exists"* | ⛔ **INVALID — and REFUTED by A9.** ⭐⭐ **This is the exact inference `P-29`'s closing sentence relied on** |

⭐ **How the corpus treats historical transitions** *(per `P-21`–`P-23`)*: as **state changes with
retained prior values and warrants** — `K3a`/`K3b`. ⭐⭐ **`AssertionAccepted` is a *description* of
such a transition, not an independent persistence primitive.**

# 17. Statistical framing — analytical only

$$A = f(X;\rho_A) \qquad \textbf{with } \rho_A \textbf{'s thresholds } N_{ind}\!\ge\!2,\ \mathit{Authority}\!\ge\!A_{min},\ \mathit{Age}\!\le\!T_{max}$$

⭐ Acceptance is analogous to a **decision rule with thresholds**, ⛔ **not** an estimand and ⛔ **not**
a measurement result. ⭐⭐ **W10 corroborates from the corpus's own vocabulary: `Thresholds` and
`DecisionRules` are listed under `POLICY`.**

> *If the classification rule changes while `X` is unchanged, has the observation changed?* — **No.**
> ⚠️ **Analytical only; not used as evidence.** ⭐ **The corpus supplies the same conclusion
> independently through W3.**

# 18. Falsification search — all three directions

| direction | result |
|---|---|
| ⭐⭐ **acceptance IS persisted** | ⭐ **found — but NOT in retention vocabulary.** `acceptance must persist` 0 · `acceptance history` 0 · `acceptance retained` 0 · `acceptance event` 0 · `acceptance timestamp` 0 · `accepted status` 0 — ⭐⭐⭐ **yet A9 says *"historical acceptance must remain auditable"*, and §15 says *"acceptance is a domain event"*.** ⚠️ **A pure vocabulary search would have concluded `[UNWITNESSED]` and been WRONG** |
| **acceptance is COMPUTED** | ⭐ `Accepted(` **25** · `currently accepted` **16** · `accepted under` 7 · `acceptance predicate` 2 — ⚠️ but `computed acceptance` 0 · `derive acceptance` 0 · `evaluate acceptance` 0: **the computed reading is carried by NOTATION and USE, never asserted in prose** |
| **acceptance is NOT persistence-relevant** | ⭐⭐ **`acceptance need not persist` 0 · `accepted status not stored` 0 — ZERO, and zero is not positive evidence** |

⭐⭐⭐ **The methodological result: this audit's decisive witness was reachable only by CONCEPT search
(`auditable`), not by vocabulary search (`retained`). Recorded, because six earlier audits in this
series relied on vocabulary absence.**

# 19. ⭐⭐⭐ `P-29`'s strongest claim, attacked

> `P-29`: *"Acceptance is a current predicate, not a stored historical fact."*

$$\boxed{\begin{array}{c}\mathbf{[QUALIFIED].}\ \textbf{The FIRST half stands and is STRENGTHENED } (K \neq \text{currently-accepted set}).\\ \textbf{⭐⭐ The SECOND half is TOO STRONG: A9 requires historical acceptance to remain auditable, and §15/§24 make it a retained transition.}\\[4pt] \boxed{\textbf{Current acceptance = computed/interpreted } \wedge \textbf{ historical acceptance = persisted via } K3a + K3b. \textbf{ ⭐ Perfectly coherent, and both attested.}}\end{array}}$$

⭐⭐ **Does this damage `P-29`'s verdict?** ⛔ **No — it CORROBORATES it.** `P-29` concluded the
policy-version reference attaches to **transitions**; W2's payload carries **`PolicyId` +
`PolicyVersion`** on the `AssertionAccepted` **transition**. ⭐⭐⭐ **`P-29`'s verdict B now rests on a
second, independent witness that `P-29` itself never found — while `P-29`'s closing sentence and its
`K4a-i` mapping are both qualified.**

⭐ **The commission's stated prior (`B`) was correct, and my closing sentence overreached its
evidence.**

# 20. Kernel change test — **NOT TRIGGERED**

| condition | status |
|---|---|
| 1 acceptance introduces an independently required capability | ⭐ 🔴 **FAILS** — §14: every component maps to an existing cell |
| 2 not already covered by `K1`–`K11` | ⭐ 🔴 **FAILS** — `K2`, `K3a`, `K3b`, `K4a-i` |
| 3 an information-theoretic independence counterexample | ⭐⭐ 🔴 **FAILS** — §13: no such pair; **`K3a`** prevents it |
| 4 corpus-grounded | ✅ *(A9 is real)* |
| 5 inside the epistemic boundary | ⚠️ **partly** — the *evaluation* is governance-side |

$$\boxed{\begin{array}{c}\textbf{Conditions 1, 2 and 3 fail. ⛔ NO NEW CELL.}\\ \boxed{|K| = 11 \quad\textbf{· SEMANTIC INTERPRETATION QUALIFIED (}K2\textbf{'s subject narrowed; historical acceptance re-mapped from } K4a\text{-}i \textbf{ to } K3a+K3b\textbf{)}}\end{array}}$$

---

# 21. `P-30` VERDICT

$$\boxed{\mathbf{B} \textbf{ — CURRENT acceptance is computed; HISTORICAL acceptance is PERSISTED through existing transition/history obligations.}}$$

⛔ **Not `A`** — A9 *"historical acceptance must remain auditable"* is a boxed invariant, and `A` was
**not** chosen from *"currently"* · ⛔ **not `C`** — conditions 1–3 of the kernel test fail · ⛔ **not
`D`** — the senses are **stratified, not incompatible** · ⛔ **not `E`** — A9, §15 and §24 are explicit.

## Acceptance object table

| object | corpus status | persistence status | existing K-cell |
|---|---|---|---|
| acceptance **event** | ⭐ **`[EMP]`** *(payload `[STIPULATED]`)* | ⭐ **auditable (A9)** | ⭐ **`K3b`** + `K3a` |
| acceptance **transition** | ⭐⭐ **`[EMP]`** *(§24)* | ⭐ **retained** | ⭐⭐ **`K3a`** *(pairs)* + **`K3b`** |
| acceptance **status** | ⚠️ **`[STIPULATED]`** *(`Ω_A`)* | prior values retained | **`K2`** + **`K3a`** |
| **current predicate** | ⭐ **`[EMP]`** | ⭐⭐ **NONE — evaluated** | ⛔ **none** |
| **historical fact** | ⭐⭐ **`[EMP]` via A9** | ⭐⭐ **YES** | ⭐⭐ **`K3a`** + **`K3b`** |
| acceptance **warrant** | ⚠️ **`[OPEN]`** *(term `[UNWITNESSED]`)* | when present | **`K3b`** |

## `K2` subject verdict
$$\boxed{K2 \textbf{ preserves the CURRENT FIELD AND RELATION VALUES OF AN IDENTIFIED ITEM — the state from which } \mathit{Accepted}(a) \textbf{ is EVALUATED, never } \mathit{Accepted}(a) \textbf{ itself.}}$$
⭐ **Grounds: `K ≠ set of currently accepted propositions` (boxed) · *"the assertion set should not
mean 'all currently accepted assertions'"* · `Current(K)` = *"the current epistemic
**interpretation**"* · item scope from `P-08 §4.3` / `P-24`.**

## `K4a-i` verdict
$$\boxed{\textbf{Historical acceptance is ⭐ A PROPERTY OF HISTORICAL TRANSITIONS — carried by } \mathbf{K3a} \textbf{ and } \mathbf{K3b}\textbf{, ⛔ NOT by } K4a\text{-}i.}$$
⭐ **Exact mapping:** `K4a-i` retains the **claim**, identical across `H2` *(accepted)* and `H4`
*(rejected then accepted)*; only **`K3a`'s prior-value pairs** separate them, and **`K3b`** supplies
the warrant A9's auditability needs. ⭐⭐ **`P-29`'s `K4a-i` mapping is `[QUALIFIED]`.**

## Current vs historical separation
$$\boxed{\begin{array}{ll}\mathit{Accepted}_{now}(a) & \textbf{computed / interpreted — NO persistence obligation of its own}\\ \mathit{Accepted}_{at\ t}(a) & \textbf{⭐ PERSISTED — A9, via } K3a + K3b\\ \mathit{Assessment}(a) & \textbf{DISTINCT — } \mathit{Assessment} \neq \mathit{Acceptance} \neq \mathit{Truth}\textbf{; } \mathit{Supported} \nRightarrow \mathit{Accepted}\\ \mathit{Current}(a) & \textbf{DISTINCT — item currentness } (K2)\textbf{; } \mathit{Accepted} \neq \mathit{Current}\\ \mathit{Authorized}(\mathit{action}) & \textbf{INDEPENDENT — governance; A, B and D all corpus-compatible}\\ \mathit{Warrant}(W) & \textbf{⚠️ } \mathit{Accepted} \Rightarrow \mathit{Warrant} \textbf{ is } \mathbf{[OPEN]}\textbf{ — two regimes}\end{array}}$$
⛔ **None collapsed.**

## Independence test
$$\boxed{\textbf{NO pair exists with } S_K(H_1)=S_K(H_2) \textbf{ while the corpus-required acceptance property differs. ⭐ The distinguishing cell is } \mathbf{K3a} \textbf{ — prior values retained as } (\textit{prior}\to\textit{successor}) \textbf{ pairs.}}$$

## Kernel status
$$\boxed{|K| = 11 \textbf{ — unchanged. No cell added, removed, split or merged. Two SEMANTIC interpretations qualified.}}$$

## Status register
**`[EMP]`** ⭐ **A9** *"historical acceptance must remain auditable"* · §15 *"acceptance is a domain
event"* + *"creates an auditable transition"* · §24 `K_t → K_{t+1}` · ⭐ `K ≠ set of currently accepted
propositions` · *"the assertion set should not mean 'all currently accepted assertions'"* ·
`Current(K)` = current epistemic **interpretation** · `K = CurrentEpistemicState + HistoricalContext +
Justification + Provenance + Governance` · `NotAccepted ≠ Rejected` · `Unresolved ≠ Rejected` ·
`Accepted ∧ Contested` possible · `Supported ⇏ Accepted` · `Accepted ⇏ Committed` · `Accepted ⇏ Truth`
· `NotAccepted ⇏ False` · *"acceptance is policy-governed"* · `ρ_A` with example conditions · `KERNEL₅`
excludes acceptance while `POLICY` contains `DecisionRules`/`Thresholds` · `AssertionAccepted` in **5**
admissible files.
**`[DERIVED]`** ⭐ **`K2`'s narrowed subject** · **historical acceptance sits in `K3a`+`K3b`** ·
acceptance introduces **no irreducible information** · acceptance ⊥ authorization · `ρ` is a
governance **evaluation** over epistemic state.
**`[CORROBORATION]`** ⭐⭐ **`P-29` verdict B** — the `AssertionAccepted` payload carries
`PolicyId`/`PolicyVersion` on a **transition**, an independent second witness.
**`[STIPULATED]`** `Ω_A` and `AcceptanceStatus` *("I recommend")* · the 9-field payload *("might
contain")* · `ω : Ω_A × Event × Policy ⇀ Ω_A`.
**`[QUALIFIED]`** ⭐⭐ **`P-29`'s closing sentence** *(second half too strong)* · ⭐ **`P-29`'s `K4a-i`
mapping** of historical acceptance.
**`[REFUTED]`** ⭐ *`K2` preserves `Accepted(a)`* · *`K` is the currently-accepted set* · *acceptance
is the epistemic consequence of authorization* · *"computed ⇒ no history"* · *"changes ⇒ an event must
be persisted"*.
**`[ARCH]`** `step-025w` *"event sourcing fits naturally"* — ⛔ **not used as theory**.
**`[UNWITNESSED]`** `Acceptance` as **entity / aggregate / value object** · `acceptance warrant` as a
term · `acceptance state` · `acceptance timestamp` · *acceptance need not persist*.
**`[OPEN]`** ⭐ **`Accepted(a) ⇒ Warrant(a)`** — two regimes *(`ρ_A`-governed vs `G-67` naming)* · ⚠️
**`Governance` as a component of `K` (W4) vs `P-28`'s two-estate boundary — carried, `P-28` NOT
reopened** · whether `Ω_A` is one field or four · **`W3`'s conditional** *(`P-29`)* · `Ind_ρ`'s five
other `I(e_i,e_j)` slots · `K4b-ii`'s corroboration arm · register deletion · *"estate"*'s two
referents · **well-foundedness / terminality / acyclicity — DEFERRED, not reopened**.

## ⭐ ONE next unresolved question

$$\boxed{\begin{array}{c}\textbf{Does the PARTIALITY of the status-transition function impose a persistence obligation?}\\[4pt] \boxed{\begin{array}{l}\textbf{The corpus writes } \omega : \Omega_A \times \mathit{Event} \times \mathit{Policy} \rightharpoonup \Omega_A \textbf{ with } \rightharpoonup \textbf{ — a PARTIAL function.}\\ \textbf{⭐ Partiality means some } (\textit{status},\textit{event},\textit{policy}) \textbf{ triples are UNDEFINED: transitions that were ATTEMPTED AND REFUSED.}\\ \textbf{⭐⭐ Must the estate retain that a transition was refused — or is a refusal a non-event that leaves no trace?}\\ \textbf{None of } K1\text{–}K11 \textbf{ was derived from a REFUSED transition; every cell assumes a transition OCCURRED.}\end{array}}\end{array}}$$

```
B — CURRENT ACCEPTANCE IS COMPUTED; HISTORICAL ACCEPTANCE IS PERSISTED THROUGH EXISTING
TRANSITION/HISTORY OBLIGATIONS. KERNEL REMAINS 11 CELLS. NO K12.

THE COMMISSION'S PRIOR WAS RIGHT AND MY OWN P-29 CLOSING SENTENCE OVERREACHED. Four corpus statements
settle the two-level structure:
  step-008 §28 A9   "Historical acceptance must remain auditable."          (a BOXED INVARIANT)
  step-008 §15      "Acceptance is a domain event" ... "creates an auditable transition"
  step-008 §24      K_t --AssertionAccepted--> K_{t+1}
  theory-part-04    "K != set of currently accepted propositions"           (BOXED)

SO BOTH HALVES ARE ATTESTED: current acceptance is NOT the knowledge state — the corpus denies that
identification TWICE, in theory-part-04 and again in the complete-model's own self-correction of the
A_t reading — while historical acceptance MUST REMAIN AUDITABLE. P-29's sentence "acceptance is a
current predicate, not a stored historical fact" is [QUALIFIED]: first half strengthened, second half
too strong.

METHODOLOGICALLY THE MOST IMPORTANT RESULT OF THIS AUDIT: the decisive witness was unreachable by
vocabulary search. `acceptance history` 0, `acceptance retained` 0, `acceptance event` 0, `acceptance
timestamp` 0, `accepted status` 0, `acceptance must persist` 0 — all ZERO across 1,859 admissible
files. The obligation is phrased as AUDITABILITY, not as retention. A pure vocabulary search would
have returned [UNWITNESSED] and been WRONG. Six earlier audits in this series leaned on vocabulary
absence; that is now a recorded methodological limit.

P-29's VERDICT IS CORROBORATED BY A WITNESS P-29 NEVER FOUND: the AssertionAccepted payload carries
PolicyId and PolicyVersion — a policy-version reference ON A TRANSITION, exactly where P-29 placed it.
The artifact that qualified P-29's closing sentence independently strengthened P-29's verdict.

AND P-29's K4a-i MAPPING IS CORRECTED. "Every claim ever made" does NOT entail "every acceptance ever
made": a claim ACCEPTED and a claim REJECTED-THEN-ACCEPTED have the SAME K4a-i content. Only K3a's
(prior -> successor) VALUE PAIRS separate them, with K3b supplying the warrant A9's auditability needs.
Historical acceptance is a PROPERTY OF HISTORICAL TRANSITIONS, not of retained claims.

K2's SUBJECT, NARROWED FROM THE CORPUS RATHER THAN FROM AN INFERENCE: K2 preserves the CURRENT FIELD
AND RELATION VALUES OF AN IDENTIFIED ITEM — the state from which Accepted(a) is EVALUATED, never
Accepted(a) itself. This never needed the invalid "computed therefore not persisted" step the
commission rightly flagged: the corpus refuses the identification in its own words, and defines
Current(K) as "the current epistemic INTERPRETATION."

ACCEPTANCE IS NOT BOOLEAN — NotAccepted != Rejected and Unresolved != Rejected, both boxed. It is at
least three-valued, so the Current x Accepted table is a projection, and its third row (not current,
accepted) is legitimate ONLY at the historical level. That row is exactly where the two levels split.
No table cell was filled by intuition.

BOTH EVENT-SOURCING INFERENCES REFUSED: "acceptance changes therefore an event must be persisted" is
invalid — the obligation comes from A9, not from change; and step-025w's "event sourcing fits
naturally" is an [ARCH] claim about our architecture, never theory. "Acceptance is computed therefore
no history exists" is invalid AND refuted by A9 — and it is the exact inference P-29 relied on.

NO NEW CELL: conditions 1, 2 and 3 of the kernel test all fail. Every information component maps to an
existing cell, and the independence pair is IMPOSSIBLE — K3a prevents it. No Acceptance aggregate was
created merely because "Acceptance" is a noun; the only form the corpus states outright is EVENT, and
an event is a transition record K3a/K3b already carry.

ONE TENSION CARRIED, NOT RESOLVED: theory-part-04 lists Governance as a COMPONENT of K, while P-28
placed governance in a SEPARATE ESTATE. P-28 is frozen and was not reopened.

NEXT QUESTION (one): DOES THE PARTIALITY OF THE STATUS-TRANSITION FUNCTION IMPOSE A PERSISTENCE
  OBLIGATION? The corpus writes omega : Omega_A x Event x Policy --PARTIAL--> Omega_A. Partiality means
  some (status, event, policy) triples are UNDEFINED — transitions ATTEMPTED AND REFUSED. Must the
  estate retain that a transition was refused, or is refusal a non-event leaving no trace? None of
  K1-K11 was derived from a refused transition; every cell assumes a transition OCCURRED.

NO ARCHITECTURE — NO SCHEMA v3 — NO IMPLEMENTATION — NO ACCEPTANCE AGGREGATE — POLICY PERSISTENCE NOT
REOPENED — 3MC AND LANE M UNTOUCHED — WELL-FOUNDEDNESS STILL DEFERRED.
```
