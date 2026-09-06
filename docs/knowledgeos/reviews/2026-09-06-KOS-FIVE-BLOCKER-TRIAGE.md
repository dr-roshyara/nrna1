# Triage of the five blocking items — derivable · governance · or genuinely unresolved

**Date:** 2026-09-06 · **Analysis only. No new definition search. Nothing frozen, nothing ratified.**
**Method:** inspect the **underlying artifacts behind each edge**, not the dependency summary.
**Amends:** `…-SURVIVING-BLOCKER-DEPENDENCY-ANALYSIS.md` §4 · `…-GAP-OCORE-NECESSITY-PROOF.md` §9.

> **Scope note, recorded at the owner's own instance:** the `D-1A → D-1B → D-1C` structure was a
> **proposal**, and the dependency analysis **does not establish it as the required next step.**
> This triage does not assume it. §1 shows why it is now partly moot.

---

## 1. ⭐ `𝒪_core` — **the necessity test ALREADY EXISTS in the corpus**

`readiness/03-OPERATION-TRANSFORMATION-READINESS.md` §7, verbatim:

$$o \text{ is primitive} \iff \exists\, r \in R_{\text{mandatory}} : r \notin \mathrm{Closure}(\mathcal T_{-o})$$

> **"Step 277 states it. `GN-75`: *the necessity test has never been run by anyone.*
> And it cannot be run yet — `R_mandatory` is the invariant register, which is `G-67`, never enumerated."**

### Three consequences

**(a)** `[NEG]` **The apparatus does not need inventing.** `Adeq_K` / `𝔐_K` / `𝒦_min` was a
**reconstruction of something the corpus already has** — and the corpus's version is **stated over
`R_mandatory` (the invariant register), not over a capability basis.** That is why `𝒦_min` had to be
stipulated: it was filling a slot the corpus fills differently.

**(b)** **Candidate registries exist and are inventoried** — `R1`–`R6`, with provenance, derivation
method, executability and governance status. `GN-84`, four statements, never merged:

| question | answer |
|---|---|
| does a minimal registry exist under the tested criterion? | **YES** |
| is it **unique**? | **NO — six found** |
| can one be **selected** from current evidence? | **NO** |
| is it **ratified**? | **NO** |

$$\boxed{\text{“minimal under the chosen computational criterion”} \;\neq\; \text{“minimal canonical operation set of KnowledgeOS”}}$$
> *"The first has been shown non-unique. The second has **not been demonstrated at all**. They may never be substituted for one another."*

**(c)** **Two operations are unreconciled on executed evidence** — `Split` is **LOSSY on `ℛ`**
(`Split;Merge` does not recover relations) and `LinkEvidence` would **mutate** an assertion,
contradicting immutability.

### Classification

> **`𝒪_core` is NOT, in the first instance, a governance act. It is a BLOCKED DERIVATION** — the test
> exists, the candidates exist, and it cannot run because `R_mandatory` = `ℐ` is unestablished.
> **A governance act is needed only if the test leaves more than one survivor.**

---

## 2. ⚠️ My dependency ordering was BACKWARDS — and there is a cycle

My §4 placed `ℐ` **downstream** of `𝒪_core`. The evidence says the opposite:

$$\mathcal I \;(= R_{\text{mandatory}}) \;\longrightarrow\; \text{operation-necessity test} \;\longrightarrow\; \mathcal O_{core}$$

`[NEG]` **Correction accepted against my own analysis.** And the document says the dependency was
missed by both lanes:

> *"The operation-necessity test is blocked on the same missing object as `Sufficient(K,𝒪,ℐ)`: `ℐ`.
> **That is a dependency neither lane has recorded.**"*

### Composing it with what was already known gives a **cycle on the critical path**

```
   𝒪  ──▶ 𝒯 ──▶ δ ──▶ ℐ ──▶ operation-necessity test ──▶ 𝒪
   │                    ▲
   │  invariants are stated as P(K) ⇒ P(δ(K,o))  ─┘   (ℐ needs δ)
   └─ δ's semantics need a fixed operation family    (δ needs 𝒪)
```

$$\boxed{\mathcal O \to \mathcal T \to \delta \to \mathcal I \to \text{necessity test} \to \mathcal O}$$

> ### `[EXP]` **This is the second cycle found on the kernel's critical path** — the first being `≡ ⇄ ≈ ⇄ congruence`. **A cycle cannot be resolved by ordering the work.** It is broken by an entry point: a **stipulated** `ℐ`, a **stipulated** candidate `𝒪`, or a **stipulated** `δ` postcondition — and *which* entry point is chosen is a genuine act.

**⚠️ I am not asserting this cycle as established corpus fact.** Each edge is corpus; **their
composition into a cycle is my derivation**, and it is offered as such.

---

## 3. The five, triaged

| # | item | derivable from existing theory? | governance act required? | genuinely unresolved? |
|---|---|---|---|---|
| **1** | **`𝒪_core`** | **the TEST is derivable and already written** (Step 277); **cannot run** — needs `ℐ` | **only if >1 survivor** | 🟠 **blocked derivation**, not an open question |
| **2** | **`Π ∈ ≡ ?`** | **conditionally YES** — `258.31`: *decided by whether a mandatory op observes provenance* ⇒ derivable once `𝒪` fixed | **YES, separately** — `Canonicalize(Π,≡)` survives the theorem | 🟢 **two different acts, both identified** |
| **3** | **`ℐ` (0 of 7)** | **blocked** — by `Admissible` (undecidable) and `δ` (no commit case) | no | 🔴 **genuinely unresolved — and it is UPSTREAM (§2)** |
| **4** | **`δ` commit case** | **cause is IDENTIFIED and executed** — `Γ` is derived and not a component of `K`, *so `δ` has nowhere to write* | **a small MODELLING decision** — make `Γ` a component, or relocate the write | 🟡 **decision + specification, not open research** |
| **5** | **`Reject ↔ I-12 ↔ Article 8`** | **NO — and cannot be** | **YES — unavoidably** | 🔴 **genuine governance act** |

### Why item 5 cannot be derived

> *"**`Reject` is the one element classified CONTRADICTORY rather than absent** — a ratified-required
> state reachable only by an operation that **breaches two ratified rules**."*

`[EXP]` **Both sides are ratified.** No derivation may overturn a ratified artifact, so the
contradiction is resolvable **only** by an authority that can amend one of them. **This is the one
item on the list that is unambiguously and necessarily a governance act** — and the handoff says it
is **independent and actionable now**.

---

## 4. What actually gates what

```
Admissible (undecidable)  ──┐
δ commit case ──────────────┼──▶  ℐ  ──▶ necessity test ──▶ 𝒪_core ──▶ 𝒯 ──▶ δ ──▶ kernel
                            │                                             ▲
                            └─────────────── cycle ───────────────────────┘

INDEPENDENT, unavoidably governance, actionable now:   Reject ↔ I-12 ↔ Article 8
DERIVABLE ONCE 𝒪 IS FIXED (+ a separate naming act):   Π ∈ ≡ ?
```

`[REC]` **The nearest thing to a true root is `Admissible`**, not `𝒪_core` — it blocks `ℐ`, which
blocks the necessity test, which blocks `𝒪_core`. **I did not verify `Admissible`'s undecidability
myself**; it is carried from the 2026-09-02 delta (*"`Admissible` undecidable — `Assurance` refuted
as definable"*) and **is the single most load-bearing unverified claim in this triage.**

---

## 5. What changes, and what does not

| | |
|---|---|
| **`𝒪_core` is the root** | ⚠️ **weakened.** It is the root *of the step-289 graph*; the necessity-test dependency puts `ℐ` upstream of it, and that edge is in neither lane's graph |
| **`𝒪_core` needs a new necessity apparatus** | ❌ **withdrawn** — Step 277's criterion already exists and is stated over `ℐ`, not over capabilities |
| **`𝒦_min` is stipulated** | ✅ **stands** — and §1(a) now explains *why* it had to be: it was filling a slot the corpus fills with `R_mandatory` |
| **`D-1A` as the next step** | ❌ **not established** — by the owner's own correction and by §1 |
| **`Reject` is independent and actionable** | ✅ **stands, and strengthens** — it is the only unavoidable governance act of the five |
| **NOT READY** | ✅ **unchanged** |

---

## 6. What I have and have not read

**Not everything.** The corpus is **3 225 files · 1 643 190 lines**; exhaustive reading was never
performed and was not claimed. Reading has been **blocker-driven**: for this triage,
`03-OPERATION-TRANSFORMATION-READINESS` §7, `07-MINIMUM-IMPLEMENTABLE` §§1–5,
`step-289/01-dependency-graph`, `10-GOVERNANCE-HANDOFF`, `D285-1`, `REFINED-STEP-285`,
`08-FINDINGS…Q-SERIES`, `09-GAP-UPDATE-FROM-CAVELL`, and the `gap-update-2026-09-02` package.

`[REC]` **Two claims in this triage rest on evidence I have NOT independently verified:**
`Admissible`'s undecidability (§4) and the enumeration of `GN-77`'s nine capabilities. **Both are
load-bearing. Neither should be treated as established by this document.**

**Theory v1.2 FROZEN · kernel NOT SELECTED · `𝒪_core` NOT FROZEN · `𝒦_min` NOT VALIDATED · no code.**
