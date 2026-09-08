# `P-28` — Policy `ρ` Persistence & Historical Re-evaluation Audit

**2026-09-08 · Lane T.** After [`45` `P-27`](./45-P27-TG02-INDEPENDENCE-ADMISSIBILITY-VS-PERSISTENCE-AUDIT.md).

> **Single question:** *is `ρ` subject to a corpus-grounded persistence obligation — and if so, is it
> covered by `K1`–`K11` or does it require an independent capability?*
>
> ⛔ **`P-08`, `P-18`–`P-27`, the 11-cell kernel, Schema v2, 3MC and Lane M all FROZEN · no Schema v3 ·
> no architecture · no implementation · no canonical theory · no policy-design proposal · no cell
> changed without an independence proof · well-foundedness / terminality / acyclicity still DEFERRED ·
> no silent repair · counts admissibility-filtered.**

# 0. ⭐⭐⭐ The corpus answers this directly

`[EMP]` **`phase_measure_theory/20260830-221415_step_278_policy-authority-integration-and-executable-semantics.md`**, supervisory list items 3–6 and 9, verbatim:

> **3.** *"Policy change is itself a **governed operation**."*
> ⭐⭐⭐ **4.** *"Policies are **versioned, time-bounded, immutable once active, and HISTORICALLY RETAINED**."*
> ⭐⭐⭐ **5.** *"**Governance policy is EXTERNAL to epistemic `K`**, but **knowledge about policy can legitimately be represented inside `K`**."*
> ⭐⭐ **6.** *"Governance is modeled using **concrete DDD bounded contexts and aggregates**."*
> ⭐ **9.** *"Governance changes may be eventually consistent, but authorization must use a **well-defined effective policy version**."*

and its corrected position:

> ⭐⭐ *"**Policy is external to the epistemic state when functioning as governance policy, but propositions concerning policy are legitimate knowledge objects.**"*

$$\boxed{\begin{array}{c}\textbf{Policy persistence IS required } \mathbf{[EMP]} \textbf{ — } \textit{"historically retained"} \textbf{ — and it is EXTERNAL to epistemic } K\textbf{,}\\ \textbf{with its OWN bounded contexts and aggregates.}\\[6pt] \boxed{\textbf{⇒ OUTCOME } \mathbf{D}\textbf{: a SEPARATE GOVERNANCE PERSISTENCE ESTATE. ⛔ NOT } K12.}\end{array}}$$

⚠️ **And step 271 guards the boundary from the other side:** *"This is **not** a declaration that Policy
is external to KnowledgeOS **architecture**"* — external to epistemic **`K`**, ⛔ **not to the system.**

---

# 1. ρ reconstruction — admissible counts

| term | files | | term | files |
|---|:--:|---|---|:--:|
| ⭐ **`policy change`** | **59** | | `rule registry` | 8 |
| ⭐ **`policy version`** | **47** | | `policy authority` | 7 |
| `decision rule` | 35 | | `effective time` | 6 |
| ⭐ **`policy history`** | **11** | | `acceptance policy` | 5 · `aggregation policy` 4 |
| `policy-parametric` | 5 · `policy oracle` **3** | | `policy snapshot` | ⭐ **1** |

⭐ **`replay` 328 · `reproducib*` 241 · `Replay(` 83** — reproducibility is a **corpus-scale** concern,
`[EMP]`. ⚠️ **And the commission's rule is honoured: that alone is NOT taken as persistence evidence —
§8 tests what replay's *signature* actually requires.**

# 2. The six objects, kept apart

| | object | corpus status |
|---|---|---|
| **`P1`** policy **definition** | the rule itself | ⭐ **`[EMP]`** — *"the required conditions are a policy oracle"* |
| ⭐ **`P2`** policy **version** | `ρ_v1`, `ρ_v2` | ⭐⭐ **`[EMP]` — *"versioned"*, and *"authorization must use a well-defined effective policy version"*** |
| **`P3`** policy **applicability** | operative for a context/time | ⭐ **`[EMP]` — *"time-bounded"*, `effective time` 6 files** |
| **`P4`** policy **application event** | that `ρ` was applied | ⚠️ **`[DERIVED]`** — implied by *"policy change is a governed operation"*; ⛔ no verbatim rule |
| **`P5`** **assessment result** | `Ind_ρ(e_i,e_j) = Independent` | ⭐ **`[EMP]`** — `P-27`; a **value object** |
| **`P6`** **historical warrant** | the grounds a prior act was accepted on | ⭐ **`[EMP]`** — `K3b`'s subject |

⛔ **`P5`'s existence is not taken to imply `P1`–`P4` must persist, and `P1`'s use in a warrant is not
taken to imply `P5` must persist.**

# 3. Functional dependency, and the historical form

`[EMP]` **step 271** offers the typed form directly: **`T(K, o, π, α)`** — *"If Policy is external but
typed…"* — with policy `π` and authority `α` as **arguments**, and the caution that *"Policy and
Assessment should currently be treated as **semantic interfaces**, not prematurely fixed internal
mathematical structures."*

$$\boxed{A_t = f(S_t,\ C_t,\ \rho_t) \qquad\Rightarrow\qquad A_{\text{then}} \textbf{ is NOT reproducible from } S_{\text{then}} \textbf{ alone.}}$$

⭐ **And the corpus states the precondition explicitly:** *"`δ` is deterministic **under the same
policy/version/context**"* — ⭐⭐ **so determinism is RELATIVIZED to policy, and the relativization is
acknowledged rather than hidden.**

# 4. The central counterfactual — answered by item 4

| | `H1` | `H2` |
|---|---|---|
| at `t₁` | `ρ = ρ₁`, `S = S₁`, `A₁ = f(S₁,ρ₁)` | same |
| at `t₂` | `ρ → ρ₂`, **`ρ₁` retained** | `ρ₁` **not retained** |

$$\boxed{\begin{array}{c}\textbf{The corpus makes } H2 \textbf{ NON-CONFORMANT: } \textit{"Policies are ... IMMUTABLE ONCE ACTIVE, and HISTORICALLY RETAINED."}\\ \boxed{\textbf{⇒ } A_{\text{then}} \textbf{ REMAINS knowable — but by the GOVERNANCE estate, which retains } \rho_1\textbf{, not by the epistemic kernel.}}\end{array}}$$

# 5. `A` / `B` / `C` — which requirement holds?

| | case | verdict |
|---|---|---|
| **`A`** current assessment only | ⭐ **`[REFUTED]`** — *"historically retained"* and *"immutable once active"* exceed it |
| ⭐ **`B`** historical **reproducibility** | ⭐⭐ **`[EMP]`** — retention + immutability + versioning is exactly what replay-under-`ρ₁` needs |
| ⭐ **`C`** historical **warrantability** | ⭐⭐ **`[EMP]`, and STRONGER** — item 9: *"**authorization** must use a well-defined **effective** policy version"*, i.e. the **authorizing** version must be determinate |

⭐ **`B` and `C` are not collapsed: `B` concerns replayability of a result, `C` concerns the
determinacy of the version that authorized an act. The corpus attests both, and `C` is an
authorization requirement.**

# 6. What is `ρ`? — the DDD test, answered by the corpus

| candidate | verdict |
|---|---|
| a value · a configuration | ⭐ **`[REFUTED]`** — *"policy change is itself a **governed operation**"* |
| ⭐ a **governance artifact** | ⭐⭐⭐ **`[EMP]` — *"Governance is modeled using concrete DDD bounded contexts and aggregates"*** |
| ⭐ a **versioned specification** | ⭐⭐ **`[EMP]` — *"versioned, time-bounded, immutable once active"*** |
| an external **oracle** | ⭐ **`[EMP]`** — *"the required conditions are a policy oracle — deliberate"* |
| ⭐ a **semantic interface** | ⭐⭐ **`[EMP]`** — step 271, verbatim |
| an assertion **inside `K`** | ⚠️ **only *knowledge ABOUT policy*** — item 5 |
| a domain entity **of the epistemic estate** | ⭐⭐⭐ **`[REFUTED]` — *"external to epistemic `K`"*** |

$$\boxed{\begin{array}{ll}\textbf{Policy is part of the epistemic estate?} & \mathbf{[REFUTED]}\\ \textbf{Policy governs the epistemic estate from outside?} & \mathbf{[EMP]}\\ \textbf{Policy belongs to another persistence/governance estate?} & \mathbf{[EMP]} \textbf{ — its own bounded contexts and aggregates}\end{array}}$$

# 7. Policy change ≠ epistemic change

| # | question | answer |
|---|---|---|
| 1 | is policy change an **epistemic** event? | ⭐ **NO** — it is external to `K` |
| 2 | a **governance** event? | ⭐⭐ **YES — `[EMP]`, *"itself a governed operation"*** |
| 3 | merely configuration? | ⭐ **`[REFUTED]`** — a *governed* operation is not configuration |
| 4 | historical policy **attribution** required? | ⭐ **`[EMP]` via item 9's *effective version*** |
| 5 | must previous assessments be **re-evaluated**? | ⭐⭐ **`[UNWITNESSED]`** — retention is required; **re-evaluation is not stated** |
| 6 | must **both** old and new policy be preserved? | ⭐ **`[EMP]`** — *"immutable once active, and historically retained"* implies accumulation |

⭐ **Row 5 matters: the corpus requires the past to remain KNOWABLE, not the past to be REDONE.**

# 8. ⭐⭐⭐ Replay audit — the signature test

**Measured over all admissible occurrences of `Replay(`:**

| form | count |
|---|:--:|
| **`Replay(H)`** | **34** |
| **`Replay(K_0, H_t)`** | **15** |
| `Replay(K_0, H)` · `Replay(H_t)` | 9 · 9 |
| `Replay(K₀,H)` · `Replay(A,t)` | 5 · 5 |
| `Replay(S_0,H_t)` · `Replay(K_t,EC_G)` · `Replay(K_0,H,t)` · `Replay(I,p)` · `Replay(H,K_0)` | 4 · 3 · 3 · 3 · 3 |

$$\boxed{\begin{array}{c}\textbf{POLICY APPEARS IN NO REPLAY SIGNATURE. Every form is over an initial state and a history.}\\[4pt] \boxed{\textbf{⇒ } \mathit{Replay}(K_0,H) \textbf{, NOT } \mathit{Replay}(K_0,H,\rho). \textbf{ Policy is a PRECONDITION of determinism, not an argument.}}\end{array}}$$

⭐ **And the precondition is stated where determinism is claimed** — *"`δ` is deterministic under the
same policy/version/context"* — ⭐⭐ **so replay assumes the governance estate supplies the version; it
does not carry it.** Of the six candidates in §8's list, the corpus supports **(3) policy version** and
**(5) policy reference** — via item 9's *effective version* — and ⛔ **not a policy snapshot inside `K`**
*(`policy snapshot` = **1** file)*.

# 9. Information-theoretic necessity — and which of the four readings applies

$$I_H \not\subseteq I_K \quad\textbf{— the historical policy is NOT in the epistemic kernel.}$$

| the four readings, kept apart | verdict |
|---|---|
| **information deficit** *(necessary information absent)* | ⭐ **`[REFUTED]`** — it is **not absent**; it is **elsewhere and retained** |
| ⭐⭐ **governance separation** *(belongs to another governed estate)* | ⭐⭐⭐ **`[EMP]` — THIS** |
| **policy externality** *(a deliberate oracle)* | ⭐ **`[EMP]`, and consistent with the above** |
| **result irrelevance** *(no reproducibility requirement)* | ⭐ **`[REFUTED]`** — `replay` 328 files |

⭐⭐ **`I_H ⊄ I_K` is therefore NOT a kernel defect.** It is the **boundary working as the corpus
designed it.**

# 10. Adversarial pairs

| | pair | does the corpus distinguish? | persistence-relevant to `K1`–`K11`? |
|---|---|---|---|
| **`P-A`** same evidence, different **current** policy | ⭐ **yes** — different assessment | 🔴 **no** — `P-27` `F7`, a policy event |
| ⭐ **`P-B`** historical policy **retained vs lost** | ⭐⭐ **YES — and *lost* is NON-CONFORMANT** *(item 4)* | ⭐ **no — the conformance is GOVERNANCE's** |
| **`P-C`** version `v1` vs `v2` | ⭐ **yes — *"versioned"*** | 🔴 no |
| **`P-D`** same text, different **effective time** | ⭐ **yes — *"time-bounded"*** | 🔴 no |
| ⭐ **`P-E`** same result, different governing policy | ⭐ **yes** — item 9's *effective version* individuates the authorization | ⚠️ **only if the reference sits in a warrant — §13** |
| **`P-F`** result flips after policy change, state unchanged | ⭐ **yes** | ⭐ 🔴 **no — `P-27` `F7` settled this** |
| ⭐⭐ **`P-G`** historical warrant **references a policy version**, retained vs removed | ⭐⭐⭐ **YES** | ⭐⭐ **THIS is the only epistemic-side candidate — §13** |

# 11. Falsification search — both directions

| direction | finding |
|---|---|
| ⭐ **policy NOT required to persist** | `policy (need not\|not) (be) (stored\|persisted)` **0** · `current policy only` **0** · `supplied at evaluation` **0** · ⚠️ `policy is external` / `external policy` **5 / 5 — checked, and they say external to epistemic `K`, NOT "need not persist"** |
| ⭐⭐ **policy REQUIRED to persist** | ⭐⭐⭐ **item 4 verbatim: *"versioned, time-bounded, immutable once active, and historically retained"*** · `policy version` 47 · `policy change` 59 · `policy history` 11 · `effective time` 6 |

⭐ **Every hit was checked semantically; counts alone are not treated as evidence.** ⭐⭐ **And the
externality hits do NOT support non-persistence — that was the trap, and it is avoided.**

# 12. ⭐⭐ The attack on `P-27`'s statement

`P-27` §4 said: *"a policy-relative judgement cannot safely be persisted as fact, because the policy may
change."*

| interpretation | verdict |
|---|---|
| **1** the result must **never** be persisted | ⭐ **`[REFUTED]`** |
| **2** may be persisted, but **qualified** by its policy | ⭐ **`[EMP]`-compatible** |
| ⭐⭐⭐ **3** may be persisted **as historical fact** — *"Under `ρ₁`, the assessment was Independent"* | ⭐⭐⭐ **THE CORPUS-SUPPORTED READING — because policies are versioned and historically retained, so `ρ₁` remains determinate** |
| **4** must be recomputable, history irrelevant | ⭐ **`[REFUTED]`** — item 4 |

$$\boxed{\begin{array}{c}\mathbf{P\text{-}27\textbf{'s statement is } [QUALIFIED].}\\ \textbf{True of an UNQUALIFIED result; FALSE of a result qualified by its governing policy version —}\\ \textbf{and item 4's retention is precisely what makes the qualified form safe.}\end{array}}$$

# 13. ⭐⭐ Warrant audit — the one epistemic-side candidate

`W = W(S, ρ, action)`. **Can `W₁` remain checkable if `ρ` changes and `ρ₁` is not retained?**

| scenario | outcome |
|---|---|
| `ρ₁` retained | ⭐ **checkable — and item 4 makes this the conformant case** |
| only `ρ₂` retained | ⚠️ **`W₁` becomes mis-evaluable** — ⛔ **but this state is NON-CONFORMANT for governance, not a kernel gap** |
| neither retained | same |

⭐⭐ **Does the epistemic kernel already carry the policy REFERENCE?** ⭐⭐⭐ **`K3b` retains the transition
warrant, and `P-13` established that a warrant's CONTENT may denote an external thing** *(there, a
corpus state; here, a policy version)*. ⇒ **a policy-version reference is `K3b` CONTENT** — the exact
shape `P-13` settled, and **no new cell.**

⛔ **And no cell is created because a warrant became inconvenient to evaluate.**

# 14. State vs parameter

$$\boxed{\begin{array}{c}S \textbf{ and } \rho \textbf{ are DISTINCT — step 271's } T(K,o,\pi,\alpha) \textbf{ types them separately, and item 5 places } \rho \textbf{ OUTSIDE } K.\\[6pt] \boxed{\begin{array}{l}\textbf{⇒ The 11-cell kernel's semantic target is } \mathbf{EPISTEMIC\text{-}STATE\ PERSISTENCE}\textbf{, NOT complete reproducibility persistence.}\\ \textbf{⭐ That target is not silently changed here — it is now EXPLICIT for the first time.}\end{array}}\end{array}}$$

# 15. Measurement-theoretic audit
⚠️ **Used to formulate tests, never as evidence.** `A = f(X; ρ)`: a change in `ρ` reclassifies without
changing `X`.

| is `ρ`… | verdict |
|---|---|
| part of the **measurement instrument** | ⚠️ **analogically yes** — item 7: *"policy evaluation receives a statistical/measurement model where quantitative predicates are involved"* |
| part of the **estimand definition** | ⭐ **plausible** — `Ind_ρ`'s codomain is policy-relative |
| a decision **threshold** | ⚠️ partly |
| ⭐ a **governance rule** | ⭐⭐ **`[EMP]` — and this is the corpus's own classification** |
| an external **oracle** | ⭐ **`[EMP]`** |

⭐ **Measurement-theoretic reproducibility would want `ρ` preserved — and the corpus agrees, but places
that duty in governance.** ⛔ **The analogy is not used as persistence evidence.**

# 16. ⭐⭐⭐ The two-estate boundary

$$\boxed{\begin{array}{ll}\textbf{EPISTEMIC ESTATE} & \textbf{evidence · assertions · relations · warrants · history} \;\longrightarrow\; K1\text{–}K11\\[4pt] \textbf{GOVERNANCE ESTATE} & \textbf{policies · versions · authority · effective dates · rule registries}\\ & \longrightarrow \textbf{its own DDD bounded contexts and aggregates } \mathbf{[EMP]}\end{array}}$$

> *Does the Lane-T 11-cell kernel claim responsibility for both?* ⭐⭐⭐ **NO — and the corpus says so
> before Lane T could get it wrong: item 5.**

⭐ **So a missing policy-history capability does NOT enlarge the epistemic kernel.** ⛔ **`K12` would have
been a boundary violation, not a discovery.**

# 17. Minimal new-cell test — **NOT TRIGGERED**

| condition | status |
|---|---|
| 1 policy history must persist | ⭐ ✅ **`[EMP]`** |
| ⭐⭐ **2 the requirement belongs INSIDE the modeled persistence boundary** | ⭐⭐⭐ 🔴 **FAILS — item 5 places it outside epistemic `K`** |
| 3–6 | ⛔ **not reached** |

$$\boxed{\textbf{Condition 2 fails } \Rightarrow \textbf{ ⛔ NO CELL DEFINED. And the failing condition is the BOUNDARY one, which is the informative outcome.}}$$

---

# 18. Final classification

$$\boxed{\mathbf{D} \textbf{ — SEPARATE GOVERNANCE PERSISTENCE. Policy MUST persist; it belongs to a distinct estate, NOT the epistemic kernel.}}$$

⛔ **Not `A`** *(the corpus requires retention — item 4)* · ⛔ **not `B`** *(policy itself is not covered by
`K1`–`K11`; only **propositions about** policy and **references** in warrants are)* · ⛔ **not `C`**
*(condition 2 fails — it is not inside this boundary)* · ⛔ **not `E`** *(the corpus is explicit in five
numbered items)*. ⭐ **And `C` was not chosen merely because `ρ` affects an assessment.**

## Kernel status

$$\boxed{|K| = 11 \textbf{ — unchanged. No cell added, removed, split, merged or re-scoped.}}$$

⭐⭐ **And a semantic clarification is now on the record:** the kernel's target is **epistemic-state
persistence**, not **complete reproducibility persistence** — §14. `I_H ⊄ I_K` is the **boundary**, not
a **defect**.

## Qualified · open · refuted · unwitnessed
**`[QUALIFIED]`** ⭐ **`P-27` §4's *"a policy-relative judgement cannot safely be persisted as fact"*** —
true unqualified, **false when qualified by the governing policy version**, which item 4 makes
determinate.
**`[REFUTED]`** case `A` current-only · `ρ` as configuration · `ρ` as an epistemic-estate entity ·
interpretations 1 and 4 of `P-27` §4 · *externality ⇒ non-persistence*.
**`[UNWITNESSED]`** ⭐ **any requirement to RE-EVALUATE prior assessments after a policy change** *(§7 row
5)* · `P4` policy-application-event as a stated rule · a policy **snapshot inside `K`** *(1 file)*.
**`[OPEN]`** whether the **policy-version reference in a warrant** is `K3b` content or something more
*(§13 — the next question)* · `Ind_ρ`'s five other `I(e_i,e_j)` slots · well-foundedness / terminality /
acyclicity **(still deferred)** · `P-27`'s and `P-26.1`'s carried opens.

## ⭐ ONE next unresolved question

$$\boxed{\begin{array}{c}\textbf{Must an epistemic assertion retain a RESOLVABLE REFERENCE to the effective policy version under which it was accepted?}\\[4pt] \boxed{\begin{array}{l}\textbf{⭐ Item 9 — } \textit{"authorization must use a well-defined EFFECTIVE POLICY VERSION"} \textbf{ — makes this live;}\\ \textbf{§13 suggests it is } K3b \textbf{ CONTENT by the } P\text{-}13 \textbf{ pattern, but that is } \mathbf{[OPEN]} \textbf{ and is the ONLY}\\ \textbf{epistemic-side candidate this audit surfaced.}\end{array}}\end{array}}$$

---

# On the commission itself
⭐ **No disagreement with the prompt's logic — and one observation worth recording:** its §16 anticipated
outcome `D` and warned that *"a missing policy-history capability must not therefore enlarge the
epistemic persistence kernel."* ⭐⭐ **That is exactly what the corpus turned out to say, in item 5, and
condition 2 of §17 is precisely the condition that failed.** ⚠️ **The one place I would flag a residual
risk is §8's instruction not to treat *"needed for reproducibility"* as persistence evidence:
`replay` at 328 files means reproducibility IS a corpus-scale obligation — so the rule was right to
demand the SIGNATURE test rather than the count, and the signature is what decided it.**

```
D — SEPARATE GOVERNANCE PERSISTENCE. POLICY MUST PERSIST, AND IT BELONGS TO A DISTINCT ESTATE.
KERNEL REMAINS 11 CELLS — NO CELL ADDED. K12 WOULD HAVE BEEN A BOUNDARY VIOLATION, NOT A DISCOVERY.

THE CORPUS ANSWERS DIRECTLY, in step_278's supervisory list:
  3. "Policy change is itself a GOVERNED OPERATION."
  4. "Policies are VERSIONED, TIME-BOUNDED, IMMUTABLE ONCE ACTIVE, and HISTORICALLY RETAINED."
  5. "GOVERNANCE POLICY IS EXTERNAL TO EPISTEMIC K, but KNOWLEDGE ABOUT POLICY can legitimately be
     represented inside K."
  6. "Governance is modeled using concrete DDD BOUNDED CONTEXTS AND AGGREGATES."
  9. "authorization must use a well-defined EFFECTIVE POLICY VERSION."
and its corrected position: "Policy is external to the epistemic state when functioning as governance
policy, but propositions concerning policy are legitimate knowledge objects." Step 271 guards the other
side: "This is NOT a declaration that Policy is external to KnowledgeOS ARCHITECTURE" — external to
epistemic K, not to the system.

THE SIGNATURE TEST DECIDED IT, not the counts. Measured across all admissible occurrences: Replay(H) 34,
Replay(K_0,H_t) 15, Replay(K_0,H) 9, Replay(H_t) 9, Replay(A,t) 5, Replay(S_0,H_t) 4, and so on —
POLICY APPEARS IN NO REPLAY SIGNATURE. Replay is Replay(K_0,H), never Replay(K_0,H,rho). Policy is a
PRECONDITION of determinism — "delta is deterministic UNDER THE SAME policy/version/context" — supplied
by the governance estate rather than carried by replay. This is why the commission was right to demand
the signature rather than the 328-file `replay` count.

FOUR READINGS OF I_H NOT SUBSET-OF I_K, kept apart: it is NOT an information deficit (the information is
not absent, it is ELSEWHERE and RETAINED), NOT result-irrelevance (replay is corpus-scale). It is
GOVERNANCE SEPARATION plus deliberate POLICY EXTERNALITY. So I_H not-subset I_K is THE BOUNDARY WORKING
AS DESIGNED, not a kernel defect.

THE FALSIFICATION TRAP AVOIDED: "policy is external" / "external policy" return 5/5 files — and checked
semantically they say external to EPISTEMIC K, never "need not persist". Meanwhile "policy need not be
stored", "current policy only" and "supplied at evaluation" all return ZERO. Counts were never treated
as evidence.

P-27 §4 IS [QUALIFIED]: "a policy-relative judgement cannot safely be persisted as fact" is TRUE of an
UNQUALIFIED result and FALSE of one qualified by its governing policy version — interpretation 3 —
because item 4's versioning and historical retention make rho_1 determinate. Interpretations 1 and 4
are [REFUTED].

THE MINIMAL NEW-CELL TEST FAILS AT CONDITION 2 — the BOUNDARY condition — which is the informative
outcome: policy history must persist (condition 1 passes, [EMP]) but the requirement does not belong
inside the modeled boundary. Conditions 3–6 were never reached.

A SEMANTIC CLARIFICATION NOW ON THE RECORD: the kernel's target is EPISTEMIC-STATE PERSISTENCE, not
COMPLETE REPRODUCIBILITY PERSISTENCE. Step 271's T(K,o,pi,alpha) types S and rho separately and item 5
places rho outside K. That target was never silently changed — it is explicit for the first time here.

UNWITNESSED: any requirement to RE-EVALUATE prior assessments after a policy change. The corpus requires
the past to remain KNOWABLE, not to be REDONE.

NEXT QUESTION (one): MUST AN EPISTEMIC ASSERTION RETAIN A RESOLVABLE REFERENCE TO THE EFFECTIVE POLICY
  VERSION UNDER WHICH IT WAS ACCEPTED? Item 9 makes it live; §13 suggests it is K3b CONTENT by the P-13
  pattern (a warrant's content may denote an external thing), but that is [OPEN] — and it is the ONLY
  epistemic-side candidate this audit surfaced.

NO ARCHITECTURE — NO SCHEMA v3 — NO IMPLEMENTATION — NO CANONICAL THEORY — NO POLICY DESIGN — 3MC AND
LANE M UNTOUCHED — WELL-FOUNDEDNESS STILL DEFERRED.
```
