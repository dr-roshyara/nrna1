# `P-29` — Effective Policy Reference Necessity Audit

**2026-09-08 · Lane T.** After [`46` `P-28`](./46-P28-POLICY-RHO-PERSISTENCE-AND-HISTORICAL-RE-EVALUATION-AUDIT.md).

> **Single question:** *must an epistemic assertion retain a resolvable reference to the effective policy
> version under which it was accepted?*
>
> ⛔ **`P-08`, `P-18`–`P-28`, the 11-cell kernel, Schema v2, 3MC and Lane M FROZEN · no Schema v3 · no
> architecture · no implementation · no policy infrastructure · no canonical theory · no `K12` · no
> silent repair · governance persistence is never treated as epistemic persistence · well-foundedness /
> terminality / acyclicity remain DEFERRED.**

# 0. ⭐⭐⭐ Two corpus sentences settle it — and one corrects the SUBJECT of `P-28`'s hypothesis

$$\boxed{\begin{array}{l}\mathbf{[EMP]}\ \textit{step\_278}\text{:777} \quad \textbf{"A historical } \mathbf{TRANSITION} \textbf{ must reference the policy version used at execution."}\\[6pt] \mathbf{[EMP]}\ \textit{STEP-TRACE-B4}\text{:178} \quad \boxed{\mathit{Assessment} \neq \mathit{Acceptance}} \;\cdot\; \boxed{\mathit{Accepted}(P) = \textbf{"currently accepted under policy } \rho\textbf{"}}\end{array}}$$

⭐⭐ **`TRANSITION`, not `assertion`.** ⭐ And `K3b` is **transition-relative** (`P-23`). ⇒ the requirement
is real **and lands on an existing cell.**

⭐⭐⭐ **And `Accepted(P)` is *"CURRENTLY accepted under policy `ρ`"*** — acceptance is a **current,
policy-relative predicate**, not a historical fact carrying its own policy reference.

**Measured absences, all zero:** `assertion … policy version` **0** · `policy attribution` **0** ·
`must record (the) policy` **0** · `record (the) policy version` **0** · `retained with (the)
assertion` **0** · `policy version (is) required` **0** · `policy reference (is) optional` **0**.

---

# 1. The strongest witnesses

| # | witness | status |
|---|---|---|
| ⭐⭐⭐ **W1** | `step_278:777` — *"A historical **transition** must reference the policy version used at execution."* | ⭐ **`[EMP]` — the ONLY "must reference" sentence in the admissible corpus (2 files, one duplicated)** |
| ⭐⭐⭐ **W2** | `STEP-TRACE-B4:178` — **`Assessment ≠ Acceptance`** and **`Accepted(P) = "currently accepted under policy ρ"`** | ⭐ **`[EMP]`, boxed by the corpus** |
| ⭐⭐ **W3** | `step_248` *(transformation congruence and **state sufficiency** test)* — `\| Policy information \| 🟡 **Required if future transformations depend on governing policy** \|` | ⭐ **`[EMP]` — a CONDITIONAL requirement** |
| **W4** | `step_278:1932` — *"Historical policy reference."* *(a listed item)* | `[CORROBORATION]` |
| **W5** | `step_007:1311`, `step_286:513` — *"`ρ` = governing policy"*, *"`P_t` = governing policy"* | `[EMP]` — notation |
| **W6** | constitutional DSL: `\| POLICY \| A governing policy \| "Data Privacy Policy v2" \|` | ⭐ `[EMP]` — **policies are named AND versioned** |
| **W7** | `external to K` **13 files** · `external to epistemic` **2** | `[EMP]` — `P-28`'s boundary |

⭐ **The `A`–`E` test of §1:** **(A)** authorization requires an effective version — `[EMP]`, `P-28`
item 9 · ⭐⭐ **(C′) a historical TRANSITION must reference the version — `[EMP]`, W1** · **(E)** policy
remains available in governance — `[EMP]`, `P-28` item 4 · ⭐⭐⭐ **(B) every accepted ASSERTION requires
one — `[UNWITNESSED]`** · **(D)** historical acceptance must remain resolvable **to the version** —
⚠️ **`[DERIVED]` only via W1, and only for transitions.**

# 2–3. The relation table

| relation | status | estate | persistence obligation |
|---|---|---|---|
| **`AuthorizedUnder(action, ρv)`** | ⭐ **`[EMP]`** — `P-28` item 9 | **governance** | ⭐ **governance-side** |
| **`AcceptedUnder(a, ρv)`** | ⭐⭐ **`[EMP]` — but as *"CURRENTLY accepted under `ρ`"*** | **epistemic** | ⭐⭐⭐ **NONE — it is a CURRENT predicate, `K2`-shaped, evaluated not stored** |
| ⭐⭐⭐ **`WarrantedUnder(W, ρv)`** | ⭐⭐ **`[EMP]` via W1, at TRANSITION level** | **epistemic** | ⭐⭐ **YES — and it is `K3b`** |
| ⭐ **`WarrantReferences(W, ρv)`** | ⭐⭐ **`[EMP]`** — W1 says *"must **reference**"* | **cross-boundary** | ⭐ **`K3b` CONTENT** |
| ⭐⭐⭐ **`AssertionReferences(a, ρv)`** | ⭐⭐⭐ **`[UNWITNESSED]`** | — | ⛔ **NONE** |

$$\boxed{\begin{array}{c}\textbf{The corpus bridges authorization to TRANSITIONS, never to ASSERTIONS.}\\ \boxed{\textbf{⇒ The implication §19 warned about — } \textit{authorization has a version} \Rightarrow \textit{assertion has a version} \textbf{ — is REFUTED BY MEASUREMENT.}}\end{array}}$$

# 4–5. The core necessity test

| history | content |
|---|---|
| **`H1`** | `ρ₁` effective · `a` accepted · warrant `W` retained · policy history retained · **`W` references `ρ₁`** |
| **`H4`** | same epistemic state · policy history retained · ⭐ **no policy reference from `W`** |

| step | finding |
|---|---|
| are all 11 cells identical between `H1` and `H4`? | ⭐⭐⭐ 🔴 **NO — `K3b`'s CONTENT differs**, because W1 makes the reference part of the transition's retained warrant |
| ⇒ is `H4` non-conformant for **epistemic** persistence? | ⭐⭐ ✅ **YES — by W1**, and the failing obligation is **`K3b`'s**, not a missing one |
| ⇒ or merely dependent on governance-side lookup? | ⭐ 🔴 **no** — W1 puts *"must reference"* on the **transition**, i.e. inside the epistemic estate |

$$\boxed{\begin{array}{c}\textbf{The independence pair CANNOT be constructed with all eleven cells identical.}\\ \boxed{\textbf{⇒ Condition 4 of the new-cell test FAILS. ⛔ NO } K12.}\end{array}}$$

⭐ **`H3` *(governance can still determine `ρ₁` was effective)*:** ⭐⭐ **that rescues RESOLVABILITY but not
CONFORMANCE** — W1 requires the reference to be **on the transition**, so governance lookup is
sufficient for *answering the question* and insufficient for *satisfying the rule*. ⚠️ **Two different
things, and the corpus asks for the second.**

# 6. The resolution table

| requirement | governance | epistemic `K` | `K1`–`K11` |
|---|:--:|:--:|---|
| **`R1`** policy exists | ⭐ ✅ | 🔴 | — |
| **`R2`** policy retained | ⭐ ✅ *(`P-28` item 4)* | 🔴 | — |
| **`R3`** policy was effective | ⭐ ✅ *(time-bounded)* | 🔴 | — |
| **`R4`** authorization checkable | ⭐ ✅ *(item 9)* | 🔴 | — |
| **`R5`** assertion acceptance retained | 🔴 | ⭐ ✅ | ⭐ **`K2`** *(current)* · **`K4a-i`** *(the claim)* |
| **`R6`** warrant checkable | ⚠️ **shared** | ⭐ ✅ | ⭐ **`K3b`** + **`K5`** |
| ⭐⭐ **`R7`** the policy governing **acceptance** resolvable | ⭐ ✅ **via `R2`+`R3`** | ⚠️ **only for TRANSITIONS, via W1** | ⭐⭐ **`K3b` content** |

⭐⭐⭐ **`R7` does NOT follow from `R1`–`R6`, and the corpus only supplies it for transitions.** ⭐ **For
assertions it is `[UNWITNESSED]` — the very inference the commission forbade.**

# 7. ⭐⭐ `K3b` audit — which of the four readings?

| reading | verdict |
|---|---|
| 1 retention of **warrant content** | ⭐ **`[EMP]`** — `P-08` §16 |
| 2 retention of **every external object** referenced | ⭐ **`[REFUTED]`** — `P-08` §5.3 permits an **honest downgrade** instead of retaining the object |
| ⭐ 3 retention of **resolvable references** to external objects | ⭐⭐ **`[EMP]` FOR THE POLICY VERSION SPECIFICALLY** — W1's *"must reference"* |
| 4 retention of **sufficient information to check** the warrant | ⭐ **`[DERIVED]`** — `K5`'s territory *(`P-19`'s restatement)* |

$$\boxed{\begin{array}{c}\textbf{Reading 1 is } K3b\textbf{'s general form; reading 3 is what W1 ADDS, and only for the POLICY VERSION.}\\ \boxed{\textbf{⭐ So it is REQUIRED warrant content, not merely PERMITTED — and that answers §7's question directly.}}\end{array}}$$

⭐⭐ **And the referent stays in governance:** the warrant holds a **`PolicyVersionId`-shaped reference**;
`P-28` item 4 keeps the **version itself** retained governance-side. **A cross-boundary reference, not
an absorbed object.**

# 8. ⭐⭐⭐ The `P-13` analogy, attacked — and `P-28`'s hypothesis survives with its SUBJECT corrected

**The falsification case:** `W = "accepted because condition `C` held"`, where `ρ` defines `C` but `W`
never names `ρ`. **If governance can still establish which version defined `C`, does `K3b` fail?**

$$\boxed{\begin{array}{c}\textbf{⭐ YES — because W1 does not ask for RESOLVABILITY, it asks for a REFERENCE: } \textit{"must reference the policy version used at execution."}\\[4pt] \boxed{\textbf{⇒ The analogy is not needed. W1 is a DIRECT requirement, and the } P\text{-}13 \textbf{ pattern merely shows the SHAPE is admissible.}}\end{array}}$$

⭐⭐ **So `P-28`'s hypothesis is CONFIRMED as to `K3b`, and CORRECTED as to subject:** it said *"a
policy-version reference is `K3b` content"* while framing the question around **assertions**. ⭐⭐⭐
**The corpus attaches it to TRANSITIONS.** ⚠️ **`[QUALIFIED]`, not refuted.**

⛔ **And the general inference — *"a warrant must retain a resolvable reference to every external thing
relevant to its validity"* — is NOT established.** Reading 2 is refuted; only the **policy version** is
witnessed.

# 9. Authorization ≠ epistemic acceptance

| case | corpus-compatible? |
|---|---|
| **`A`** an authorized action producing **no** assertion | ⭐ ✅ **yes** — `P-28` item 3: policy change is *itself* a governed operation, and it asserts nothing epistemic |
| **`B`** an assertion with **no** governance authorization event | ⭐ ✅ **yes** — `G-67` **establishes by NAMING with no warrant at all** (`B-6`'s structural `n/a`) |
| **`C`** an assertion accepted after an authorized act | ✅ possible |
| **`D`** an assertion retained after the authorizing policy changes | ⭐ ✅ **and `P-28` item 4 keeps `ρ₁` retrievable** |

$$\boxed{\textbf{⭐ } A \textbf{ AND } B \textbf{ are both corpus-compatible } \Rightarrow \textbf{ authorization CANNOT automatically generate an epistemic policy-reference obligation.}}$$

⭐⭐ **`G-67` is the decisive one: an establishment with NO warrant is `[EMP]`. If assertions needed
policy references, `G-67` would be non-conformant — and it is not.**

# 10. Historical fact vs current validity — answered verbatim

$$\boxed{\mathbf{[EMP]}\quad \mathit{Accepted}(P) = \textbf{"CURRENTLY accepted under policy } \rho\textbf{"} \qquad \mathit{Assessment} \neq \mathit{Acceptance}}$$

| does the corpus require retention of… | |
|---|---|
| **historical acceptance** | ⭐ ✅ **`K4a-i`** — every claim ever made |
| **historical policy** | ⭐ ✅ **governance** — item 4 |
| **current validity** | ⭐⭐ **`K2`** — and W2 shows it is **evaluated under the current `ρ`** |
| ⭐ **re-evaluation** | ⭐⭐⭐ **`[UNWITNESSED]` — NOT upgraded** |
| **historical policy attribution** | ⚠️ **for TRANSITIONS only** — W1 |

⭐ **`Accepted(P)` being *"currently"* qualified is the strongest single reason no assertion-level
reference is required: acceptance is not a stored historical fact, it is a present predicate.**

# 11–12. Measurement and state/parameter separation
⚠️ **Analytical only; not used as evidence.** `A = f(X;ρ)`: `ρ` behaves as a **decision rule /
governance parameter / external oracle** *(`P-28` §15)*. ⭐ **And reproducibility does not require
storing the instrument inside the observation — an independently versioned external object suffices,
which is exactly what item 4 provides.**

$$\boxed{\begin{array}{ll}\textbf{state persistence} & S_t \;\longrightarrow\; K1\text{–}K11\\ \textbf{parameter persistence} & \rho_t \;\longrightarrow\; \textbf{governance } (P\text{-}28)\\ ⭐\ \textbf{historical relation} & \textbf{which } \rho_t \textbf{ governed a TRANSITION} \;\longrightarrow\; \mathbf{K3b\ content\ (W1)}\\ \textbf{replay contract} & \mathit{Replay}(S_0,H);\ \rho \textbf{ supplied externally } (P\text{-}28\ \S8)\end{array}}$$

⭐ **And per the commission's caution: `ρ`'s absence from the replay signature is evidence about the
corpus's replay ABSTRACTION — ⛔ not proof that policy could never enter a replay contract.** ⚠️
**Stated explicitly, as required.**

# 13. Adversarial pairs

| | pair | result |
|---|---|---|
| **`Q-A`** same `a`, different governing policies | ⭐ **`K2`** differs *(acceptance is policy-relative and current)* |
| ⭐⭐ **`Q-B`** same warrant, reference present vs absent | ⭐⭐⭐ **epistemic persistence DOES distinguish them — `K3b` content, W1** |
| **`Q-C`** policy history retained, direct reference absent | ⭐ **non-conformant by W1** — governance resolvability does **not** substitute |
| **`Q-D`** policy history retained, reference lost | ⭐ **epistemic (`K3b`) failure** |
| **`Q-E`** reference retained, policy history lost | ⭐ **GOVERNANCE failure** *(item 4)* — a **dangling** reference |
| **`Q-F`** policy changes, assertion unchanged | ⭐ **epistemic state UNCHANGED** — `P-27` `F7` |
| **`Q-G`** policy changes and the assessment flips | ⭐⭐ **a GOVERNANCE transition with an assessment consequence** — ⛔ **not an epistemic state transition** |
| ⭐ **`Q-H`** same historical assertion, current policy differs | ⭐⭐ **the assertion does NOT change** — `K4a-i` retains it; only `Accepted(P)` re-evaluates |

⭐ **`Q-D`/`Q-E` split the failure cleanly across the two estates — which is the sharpest confirmation
of `P-28`'s boundary.**

# 14. Minimal new-cell test — **NOT TRIGGERED**

| condition | status |
|---|---|
| 1 a policy-version reference is corpus-required | ⭐ ✅ **`[EMP]`, W1 — for TRANSITIONS** |
| 2 the requirement concerns **epistemic** persistence | ⭐ ✅ **yes — a transition is epistemic** |
| ⭐⭐⭐ **3 `K1`–`K11` cannot already preserve it** | ⭐⭐⭐ 🔴 **FAILS — `K3b` retains transition warrants, and the reference is warrant content** |
| 4 an identical-state pair differing on the property | ⭐ 🔴 **FAILS — §4: `K3b`'s content differs** |
| 5–6 | ⛔ **not reached** |

$$\boxed{\textbf{Conditions 3 AND 4 both fail. ⛔ NO } K12 \textbf{ — and it is not added merely because it would make historical authorization easier to explain.}}$$

# 15. DDD bounded-context test

| object | context |
|---|---|
| `Policy` · `PolicyVersion` · `EffectivePeriod` · `Authority` · `Authorization` · `RuleRegistry` | ⭐ **GOVERNANCE** — `P-28` item 6 |
| `Assertion` · `Evidence` · `Warrant` · `Acceptance` · `History` | ⭐ **EPISTEMIC** |
| ⭐⭐ **the policy-version reference on a transition** | ⭐⭐⭐ **a CROSS-CONTEXT REFERENCE** |

$$\boxed{\begin{array}{c}\textbf{A cross-context reference does NOT make the referent part of the epistemic aggregate.}\\ \boxed{\textbf{⭐ The minimal epistemic model needs a REFERENCE it can hold as warrant content — NOT a } \mathit{PolicyVersion} \textbf{ object.}}\end{array}}$$

# 16. Provenance test

| is policy… | verdict |
|---|---|
| **source** · **provenance** | ⭐ **`[REFUTED]` / `[UNWITNESSED]`** — `P-25` found provenance's targets are knowledge, evidence, assertions, transitions, artifacts; ⛔ **policy is never named a source** |
| ⭐ **governing rule** | ⭐⭐ **`[EMP]`** — *"`ρ` = governing policy"*, ×3 witnesses |
| **authority** | ⭐ **`[EMP]`** — item 9's authorization |
| **context** · **external condition** | ⭐ **`[EMP]`** — the *policy oracle* |

⭐ **Recorded explicitly, as §16 requires: the corpus does NOT establish policy as provenance.**

# 17. Falsification search — both directions, every hit inspected

| direction | result |
|---|---|
| ⭐ **AGAINST a mandatory epistemic reference** | `policy reference optional` **0** · `without policy reference` **0** · `warrant without policy` **0** · `governance resolves` **0** · `policy not part of` **0** · ⚠️ `external to K` **13** — ⭐ **checked: they bound POLICY's estate, and say nothing about references** |
| ⭐⭐ **FOR one** | ⭐⭐⭐ **`must reference (the) policy` = 2 files (one a duplicate) — and BOTH are W1, about a TRANSITION** · `policy version required` **0** · `record the policy version` **0** · `retained with the assertion` **0** · `policy attribution mandatory` **0** |

⭐ **The positive direction rests on ONE sentence, and that sentence says *transition*. ⛔ Counts were
never treated as evidence, and the single decisive hit was read in full.**

---

# 18. `P-29` VERDICT

$$\boxed{\mathbf{B} \textbf{ — REQUIRED, BUT ALREADY COVERED BY } K3b \textbf{ — with the SUBJECT corrected from ASSERTION to TRANSITION.}}$$

⛔ **Not `A`** *(the requirement is stated: W1's *"must reference"*)* · ⛔ **not `C`** *(conditions 3 and 4
of the new-cell test both fail)* · ⛔ **not `D`** *(the requirement lands on a **transition**, which is
epistemic-side, not governance-only)* · ⛔ **not `E`** *(W1 and W2 are explicit)*. ⭐ **And `B` was not
preselected — §8 attacked it and it survived with a corrected subject.**

## Independence result

$$\boxed{\textbf{NO admissible pair exists with } S_K(H_1) = S_K(H_2) \textbf{ while the policy-reference property differs — because the reference IS } K3b \textbf{ content.}}$$

## Kernel status
$$\boxed{|K| = 11 \textbf{ — unchanged. No cell added, removed, split, merged or re-scoped.}}$$

## Status register
**`[EMP]`** W1 *"a historical **transition** must reference the policy version used at execution"* ·
W2 **`Assessment ≠ Acceptance`** and **`Accepted(P) = "currently accepted under policy ρ"`** ·
W3's 🟡 **conditional** *"policy information required if future transformations depend on governing
policy"* · policies named and versioned *(DSL)* · `external to K` 13 files · `G-67` establishes with
**no warrant**.
**`[DERIVED]`** ⭐ **the reference is REQUIRED `K3b` content, not merely permitted** · **`R7` does not
follow from `R1`–`R6`** · **acceptance is a CURRENT predicate, so no assertion-level reference is
needed** · **a cross-context reference does not absorb the referent**.
**`[QUALIFIED]`** ⭐⭐ **`P-28`'s hypothesis** — confirmed as to `K3b`, **subject corrected** from
assertion to transition.
**`[REFUTED]`** `K3b` reading 2 *(retain every external object)* · policy as **provenance** ·
*authorization ⇒ assertion-level reference*.
**`[UNWITNESSED]`** ⭐⭐⭐ **`AssertionReferences(a, ρv)`** · `policy attribution` · **re-evaluation after
policy change** *(carried from `P-28`, NOT upgraded)*.
**`[OPEN]`** ⭐ **W3's conditional** — *when* do future transformations depend on governing policy? ·
`Ind_ρ`'s five other `I(e_i,e_j)` slots · **well-foundedness / terminality / acyclicity (deferred)** ·
`K4b-ii`'s corroboration arm · register deletion · *"estate"*'s two referents.

## ⭐ ONE next unresolved question

$$\boxed{\begin{array}{c}\textbf{Is ACCEPTANCE itself a persisted state or a computed predicate?}\\[4pt] \boxed{\begin{array}{l}\textbf{W2 gives } \mathit{Accepted}(P) = \textit{"CURRENTLY accepted under policy } \rho\textit{"} \textbf{ and boxes } \mathit{Assessment} \neq \mathit{Acceptance}\textbf{.}\\ \textbf{⭐ If acceptance is COMPUTED, then what } K2 \textbf{ retains is not } \textit{"accepted"} \textbf{ but the underlying state —}\\ \textbf{which would qualify } K2\textbf{'s own subject, the one cell } P\text{-}24 \textbf{ already had to restore once.}\end{array}}\end{array}}$$

---

# On the commission
⭐ **No disagreement with its logic; its expectation of `A`-or-`B` was right, and `B` is the answer.**
⚠️ **One framing note:** its verdict options `A`–`E` were phrased around **assertions and warrants**,
while the corpus's single *"must reference"* sentence attaches to a **transition**. ⭐⭐ **Its own §19
stratification anticipated exactly that gap — separating `L4` authorization, `L5` acceptance and `L6`
warrant — so the answer was reachable only because the stratification was demanded first.**

```
B — REQUIRED, BUT ALREADY COVERED BY K3b, WITH THE SUBJECT CORRECTED FROM ASSERTION TO TRANSITION.
KERNEL REMAINS 11 CELLS. NO K12.

TWO CORPUS SENTENCES SETTLE IT:
  step_278:777  "A historical TRANSITION must reference the policy version used at execution."
  STEP-TRACE-B4:178  Assessment != Acceptance   and   Accepted(P) = "CURRENTLY accepted under policy rho"

TRANSITION, NOT ASSERTION. And K3b is transition-relative (P-23), so the requirement is real AND lands
on an existing cell. `must reference (the) policy` returns exactly 2 files — one a duplicate of the
other — and BOTH are that same sentence, about a transition. Meanwhile every assertion-level formulation
returns ZERO: `assertion ... policy version` 0, `policy attribution` 0, `must record the policy` 0,
`record the policy version` 0, `retained with the assertion` 0. So AssertionReferences(a,rho_v) is
[UNWITNESSED], and the implication the commission warned about — authorization has a version, therefore
an assertion has one — is REFUTED BY MEASUREMENT.

ACCEPTANCE IS A CURRENT PREDICATE. Accepted(P) is "CURRENTLY accepted under policy rho", and the corpus
BOXES Assessment != Acceptance. That is the strongest single reason no assertion-level reference is
needed: acceptance is not a stored historical fact but a present, policy-relative evaluation.

TWO CORPUS-COMPATIBLE CASES BREAK THE BRIDGE INDEPENDENTLY: an authorized action producing NO assertion
(policy change is itself a governed operation), and an assertion with NO authorization event — G-67
establishes by NAMING with no warrant at all. If assertions needed policy references, G-67 would be
non-conformant. It is not.

THE P-13 ANALOGY WAS ATTACKED AND TURNED OUT UNNECESSARY: W1 does not ask for RESOLVABILITY, it asks for
a REFERENCE. So the general inference "a warrant must retain a resolvable reference to every external
thing relevant to its validity" is NOT established — K3b reading 2 is REFUTED, and only the policy
version is witnessed. P-28's hypothesis is therefore CONFIRMED as to K3b and [QUALIFIED] as to subject.

THE NEW-CELL TEST FAILS TWICE — at condition 3 (K3b already retains transition warrants, and the
reference is warrant content) and condition 4 (no identical-state pair can be constructed, since K3b's
content is exactly what differs). No K12 was added to make historical authorization easier to explain.

THE TWO ESTATES SPLIT CLEANLY under adversarial pairs: reference lost with policy history retained is an
EPISTEMIC (K3b) failure; reference retained with policy history lost is a GOVERNANCE failure leaving a
DANGLING reference. A cross-context reference does not absorb the referent — the epistemic model needs a
reference it can hold as warrant content, not a PolicyVersion object.

RECORDED AS §16 REQUIRES: the corpus does NOT establish policy as provenance. It is a GOVERNING RULE, an
AUTHORITY, and an external ORACLE — never a source.
AND AS THE COMMISSION REQUIRED: rho's absence from the replay signature is evidence about the corpus's
replay ABSTRACTION, not proof that policy could never enter a replay contract.

NEXT QUESTION (one): IS ACCEPTANCE ITSELF A PERSISTED STATE OR A COMPUTED PREDICATE? If Accepted(P) is
  computed and policy-relative, then what K2 retains is not "accepted" but the underlying state — which
  would qualify K2's own subject, the one cell P-24 already had to restore once.

NO ARCHITECTURE — NO SCHEMA v3 — NO IMPLEMENTATION — NO POLICY INFRASTRUCTURE — NO CANONICAL THEORY —
3MC AND LANE M UNTOUCHED — WELL-FOUNDEDNESS STILL DEFERRED.
```
