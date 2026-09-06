# 12 — Identity and Equality

**Mandate §17 (mandatory).** Executed evidence: `exec/exp_identity.py`
(transcript: `exec/OUT-identity.txt`).

---

## 1. The six identities the mandate requires

| Identity | Corpus definition | Class |
|---|---|---|
| **Assertion identity** | the `id` field of `A = (id, P, e, c, t, Π)`. **How `id` is assigned is never specified.** Step 266 §266.11 marks identity *generation* `🟢 canonical identity data` and identity *resolution* `🟡/🔴 external evidence/registry`. | partially `CORPUS ESTABLISHES` |
| **Evidence identity** | **absent.** `e` is a set of ids; no equality on evidence items. Step `20260827-140919` asks the question by title and the terminal model does not answer it. | `UNRESOLVED` |
| **Knowledge-state identity** | **four competing candidates**, none ruled on. §2 below. | `UNRESOLVED` |
| **Transformation identity** | **absent.** Step 266 §266.14 requires *deterministic replay ⟹ operation versioning*; no operation identity or version scheme exists. | `UNRESOLVED` |
| **Policy identity** | **absent.** Policy is class C; no identity, no versioning. | `UNRESOLVED` |
| **Version identity** | `schema_version` and `version` exist in the **EKP implementation** and nowhere in the theory. | `IMPLEMENTED` only |

**IE-1 (`UNRESOLVED`, CRITICAL).** Four of six required identities do not exist. Step 266 §266.14
states the dependency — *deterministic replay requires operation versioning* — and no transformation
identity is ever defined, so **`replay` cannot be guaranteed deterministic across any change to the
operation set.** This directly undercuts `TG-4`: the reference kernel replays deterministically only
because its four operations are frozen in one file.

---

## 2. EXECUTED: `A ∈ K?` has four different answers

Four equalities, each sourced from a different part of the corpus:
`structural` (Step 261), `content-only` (the abstraction Step 255 attacks), `content+status`
(the Q6-era reading), `identity-only` (id-based membership).

```
K = { a1 : Nexus.version=3.69  <vendor/api@10>  σ=Supported }

probe                                       structural  content-only  content+status  identity-only
a_forum  (same prop, other source/status)   False       True          False           False
a_vend2  (same prop+prov+status, NEW id)    False       True          True            False
a_vendor (the identical object)             True        True          True            True

the four equalities DISAGREE on 2 of 3 probes
```

**IE-2 (`EXECUTED`, CRITICAL).** `assertion ∈ K` is not one predicate. It is four, and the corpus
rules on none of them. **Every theorem that quantifies over membership is underdetermined.**

The second probe is the sharp one: `a_vend2` has the *same proposition, same provenance, same status*
and a *different id*. Is it in `K`? Content-based equality says yes; identity-based says no. The
difference is exactly whether **the same fact asserted twice by the same source is one thing or two** —
and that question has real consequences for evidence counting (`07` MT-4's non-idempotence),
for merge, and for `single_authoritative` in the EKP.

---

## 3. EXECUTED: `K₁ = K₂?` partitions the same states differently

Five states, differing in source, status, and the presence of a derivation edge:

```
structural      -> 5 classes: every state distinct
content-only    -> 2 classes: {S1,S2,S3} | {S4,S5}
content+status  -> 3 classes: {S1,S3} | {S2} | {S4,S5}
identity-only   -> 5 classes: every state distinct
```

**IE-3 (`EXECUTED`, CRITICAL).** The same five states fall into **2, 3, 5 or 5** equivalence classes.
`K₁ = K₂` has no truth value in the theory as it stands. The mandate requires it to be *computable*;
it is not yet *well-defined*.

Note that `content-only` and `content+status` both merge `S4` and `S5` — states differing only in
whether a `derives` relation exists. `exec/exp_congruence.py` EXP-2 showed that difference is
observable by `withdraw()`. So **two of the four candidate equalities are demonstrably not
congruences**, and Step 260 §260.9 requires the equality to be a congruence.

That leaves `structural` and `identity-only`. Both give 5 classes here — but they differ on the
membership probe `a_vend2` (§2), so they are not the same relation either.

---

## 4. Why this cannot be fixed by choosing one

The natural response is: *pick structural equality and move on.* It does not work, for a reason the
corpus itself supplies.

Step 260 §260.9 requires:

$$H_1 \equiv H_2 \;\Longrightarrow\; T(H_1,c) \equiv T(H_2,c) \quad \text{for all } T \in \mathcal T$$

and §260.15:

$$\boxed{\text{Decidability of KnowledgeOS semantic equivalence} = \text{UNRESOLVED}}$$

**IE-4 (`DERIVED`, CRITICAL) — this session's sharpening.** The problem is worse than undecidability,
and Step 260 supplies the premises without drawing the conclusion:

1. `≡` is defined by universal quantification over `𝒯` (§260.9).
2. `𝒯` is not enumerated — Step 266's operation registry is `🟡 complete typed registry` missing,
   and policy/authority/assessment are `🔴`.
3. Therefore **the quantifier ranges over an open collection.**

A relation defined by `∀T ∈ 𝒯` where `𝒯` has no extension is **not yet a relation**. It is not
undecidable; it is *unconstructed*. And by §260.16 (*"membership requires the equivalence test"*),
everything downstream inherits it:

```
𝒯 (open)  ⟹  ≡ (unconstructed)  ⟹  K* = ℋ/≡ (no quotient)
                                 ⟹  A ∈ K   (no predicate)
                                 ⟹  K₁ = K₂ (no truth value)
                                 ⟹  Step 259 sufficiency theorem (no content)
                                 ⟹  Step 266 minimality 🔴 (correctly marked)
```

`exec/exp_congruence.py` EXP-3 demonstrates the head of that chain empirically: **the sufficiency
answer flips when one corpus-plausible operation is added to `𝒪`.**

**This is the single deepest gap this session found.** It is not a missing definition; it is a
missing *domain of quantification*, and five separate results depend on it.

---

## 5. Hash identity

Step 260 §260.17 gets this right and deserves recording:

$$\boxed{\text{Hash identity} \neq \text{semantic identity}}$$

Two representations may hash differently while denoting the same class; a collision-resistant hash is
not a proof of equality. **`CORPUS ESTABLISHES`.**

This matters empirically: `GovernanceLineageNode` carries an `integrityHash`, and Step 267 §267.10
lists `integrityHash` as `IMPLEMENTED, but theory slot unresolved`. §260.17 explains why the slot is
unresolved — an integrity hash is a **tamper-detection** device, not an identity criterion. Those are
different jobs, and the corpus is right not to conflate them.

---

## 6. The counterexamples the mandate requires, run

| Counterexample | Result |
|---|---|
| same content, different provenance | distinguished by `explain`; `content-only` is not a congruence (EXP-1) |
| same content, different policy | **untestable** — Policy has no identity (§1), so "different policy" is not expressible |
| same id, different version | **untestable** — no version identity in the theory (§1); the EKP has `version`, the theory does not |
| same state, different history | **indistinguishable in `K`**; distinguished by a history predicate (EXP-3, `11` PL-6) |
| same assertions, different relations | distinguished by `withdraw`; merged by `content-only` and `content+status` (EXP-2, §3) |

**IE-5 (`UNRESOLVED`, HIGH).** Two of the mandate's five required counterexamples **cannot be
constructed**, because the objects they quantify over (Policy identity, version identity) do not
exist in the theory. That is itself the finding: the absence is not a gap in the test, it is a gap in
the theory.

---

## 7. Findings

| ID | Finding | Class | Severity |
|---|---|---|---|
| **IE-1** | Four of the six required identities (evidence, knowledge-state, transformation, policy) do not exist. Step 266 states that deterministic replay requires operation versioning; no operation identity exists. | `UNRESOLVED` | **CRITICAL** |
| **IE-2** | `A ∈ K` is four different predicates; they disagree on 2 of 3 probes. Every membership-quantified theorem is underdetermined. | `EXECUTED` | **CRITICAL** |
| **IE-3** | `K₁ = K₂` partitions five states into 2/3/5/5 classes under four corpus-sourced equalities. Two of the four are demonstrably not congruences. | `EXECUTED` | **CRITICAL** |
| **IE-4** | `≡` is defined by `∀T ∈ 𝒯` over an **unenumerated** `𝒯`. It is not undecidable — it is unconstructed. `K*`, membership, state equality, the Step 259 sufficiency theorem and Step 266 minimality all inherit this. | `DERIVED` | **CRITICAL** |
| **IE-5** | Two of the mandate's five required counterexamples cannot be constructed because Policy identity and version identity do not exist. | `UNRESOLVED` | HIGH |
| **IE-6** | *Positive:* `hash identity ≠ semantic identity` is correctly established and correctly separates integrity from identity. | `CORPUS ESTABLISHES` | — |

---

**Next:** `13-IMPLEMENTATION-REALITY-CHECK.md`.
