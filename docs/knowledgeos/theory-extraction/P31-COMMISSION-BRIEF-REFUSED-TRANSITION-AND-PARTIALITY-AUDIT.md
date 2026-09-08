# `P-31` — COMMISSION BRIEF · Refused Transition, Partiality & Non-Event Persistence Audit

**Role:** Senior Statistician · Mathematician · DDD Architect · Principal Knowledge Engineer
**Date:** 2026-09-08 · **Lane:** T · **After:** `P-30` — Acceptance State, Predicate & Transition Semantics Audit
**Status:** BRIEF — authored by Lane T from `P-30`'s evidence. Not an audit result. Nothing is decided here.

> ⛔ **SUPERSEDED, and NOT the brief that was executed.** The commissioner issued a **revised `P-31`
> commission** correcting a real error in §1 of this document: it wrote *"partiality … means some triples
> are undefined. Something was attempted and did not execute"*, which slides from
> `(s,e,p) ∉ Dom(ω)` to *an attempt occurred and was refused* — an unjustified step, and the exact
> conflation the `R1`–`R6` taxonomy below was meant to test. The revision puts **domain semantics before
> persistence**. The executed audit is
> [`49-P31-PARTIAL-TRANSITION-DOMAIN-REFUSAL-AND-NON-EVENT-PERSISTENCE-AUDIT.md`](./49-P31-PARTIAL-TRANSITION-DOMAIN-REFUSAL-AND-NON-EVENT-PERSISTENCE-AUDIT.md),
> which **confirmed the correction by measurement**: the corpus glosses `⇀` twice as *validity*, and the
> attempt reading has **zero** support. This file is retained as the historical record of the superseded
> brief — it is not repaired.

---

## SINGLE QUESTION

> **Does a REFUSED (attempted-but-not-executed) transition create a persistence obligation — and if so,
> is it an epistemic obligation, a governance obligation, or neither?**

`P-30` surfaced the datum that forces this question. The corpus writes the acceptance-status transition
function as

```text
ω : Ω_A × Event × Policy ⇀ Ω_A          (step-008 §27, boxed)
```

with **`⇀` — a PARTIAL function.** Partiality is not decoration: it means some
`(status, event, policy)` triples are **undefined**. Something was attempted and did not execute.

**Every one of `K1`–`K11` was derived from a transition that OCCURRED.** Not one cell was derived from a
transition that was refused. That is a genuine hole in the derivation history, and it is the first such
hole found in eleven audits.

---

# 0. HARD BOUNDARY

**Freeze:** `P-08` · `P-18`–`P-30` · the 11-cell kernel · Schema v2 · 3MC · Lane M · all prior artifacts.

**Do NOT:**

* create Schema v3 · modify architecture · implement anything
* add `K12` — or add/split/merge/re-scope any cell — without an explicit independence proof
* design a `Rejection`, `Refusal`, `Attempt` or `Violation` aggregate
* infer theory from implementation existence *(the `P-19` rule)*
* treat notation as corpus evidence *(the `⇀` glyph is a PROMPT for the question, never its answer)*
* reopen policy persistence · policy-version persistence · provenance-chain questions
* reopen `P-30`'s acceptance verdict
* investigate well-foundedness, terminality or acyclicity
* let the DDD word "event" decide whether a non-event is recorded

**This is a semantic and persistence audit only.**

---

# 1. ⚠️ THE TRAP THIS BRIEF EXISTS TO PREVENT

Three invalid inferences are available, and all three are attractive:

| ⛔ invalid | why |
|---|---|
| *"`ω` is partial, therefore refusals must be persisted"* | ⭐ **partiality is a statement about the FUNCTION's domain, not about the estate's obligations.** An undefined triple may simply mean *"this cannot happen"* — not *"this happened and was rejected"* |
| *"a refusal changes nothing, therefore nothing is retained"* | ⭐ this is the **same shortcut `P-30` refuted** in the form *"computed ⇒ not persisted"*. A refusal can leave an obligation without changing epistemic state |
| *"`δ(K,o) = Reject(r)` exists in the corpus, therefore rejection is a kernel obligation"* | ⭐⭐ **`CR-3` records `Reject(r)` as an OPEN DECISION** — *"total, or partial with a `Reject` codomain"* is precisely undecided. **Consuming an open decision as evidence is forbidden** |

**Establish which reading the corpus supports. Do not select the one that makes the kernel tidier.**

---

# 2. DISTINGUISH SIX OBJECTS BEFORE SEARCHING

Partiality has at least six distinct causes, and they carry different obligations. Model them apart:

| | object | meaning |
|---|---|---|
| **`R1`** | **impossible** transition | the triple is meaningless — no attempt could occur |
| **`R2`** | **inadmissible** transition | well-formed, but policy forbids it |
| **`R3`** | **refused** transition | attempted, evaluated, denied |
| **`R4`** | **failed** transition | attempted, permitted, did not complete |
| **`R5`** | **rejection as an outcome** | executed successfully, and its result is `Rejected` |
| **`R6`** | **undefined** transition | the model has simply not specified it *(a specification gap, not a runtime fact)* |

⭐ **`R5` is NOT a refusal — it is a successful transition whose value is `Rejected`.** `P-30` established
`NotAccepted ≠ Rejected` and `Unresolved ≠ Rejected`; do not let those three collapse into `R3`.

⭐⭐ **`R6` is the most likely reading of `⇀` and the least interesting one.** Test it FIRST — if the
partiality is a specification gap, the question dissolves and the correct answer is `E`.

For each: corpus-attested? · derived? · merely analytical? · persistence-relevant? · which estate?

---

# 3. SEARCH — CONCEPT FIRST, VOCABULARY SECOND

⭐⭐⭐ **`P-30`'s methodological finding is binding on this audit.** Its decisive witness (`A9`) was
**unreachable** by vocabulary search: `acceptance history`, `acceptance retained`, `acceptance event`
and `acceptance timestamp` all returned **0** while the obligation existed, phrased as **auditability**.

**Therefore: search the CONCEPT before concluding from the VOCABULARY.** Vocabulary:

```text
refused · refusal · rejected transition · denied · disallowed · inadmissible
not permitted · forbidden · blocked transition · illegal transition
attempted · attempt · failed transition · aborted · rolled back
partial function · undefined · undefined transition · ⇀ · rightharpoonup
Reject( · Reject(r) · rejection · guard · precondition · admissible
violation · violated · invariant violation · constraint violation
audit · auditable · audit trail · log · logged · recorded
```

Concept-level, which is where the answer will be if it exists:

```text
auditability of denied actions
retention of attempts
evidence that a rule bit
demonstrating a policy was enforced
proving something did NOT happen
```

⭐ **Zero is never positive evidence.** Every zero must be reported with the **pattern** and the
**scope** it measured, per `CORPUS-SEARCH-RULE.md`. Every decisive hit must be read in full.
**Counts are never evidence.**

Classify: `[EMP]` · `[DERIVED]` · `[CORROBORATION]` · `[STIPULATED]` · `[ARCH]` · `[OPEN]` ·
`[REFUTED]` · `[QUALIFIED]` · `[UNWITNESSED]`.

---

# 4. THE PARTIALITY AUDIT

Read `step-008 §27` in full and determine, from the text and NOT from the glyph:

1. Is `⇀` **deliberate** or **notational habit**? *(Check whether other transition functions in the
   same corpus use `→` — `δ`, `T(K,o,π,α)`, `Φ`, `ω`. A single partial arrow among total ones is
   evidence; a corpus that writes `⇀` everywhere is evidence of style.)*
2. Does the corpus anywhere **say** what happens on an undefined triple?
3. Does `Ω_A`'s partiality concern `R1`, `R2`, `R3` or `R6`?
4. ⚠️ **`Ω_A` is `[STIPULATED]` — *"I recommend"*.** A partiality inside a stipulated object **cannot
   yield an `[EMP]` obligation.** State this constraint explicitly and honour it.

⭐ **If `⇀` turns out to be notational, say so and stop at verdict `E`.** That is a real result, and it
protects the kernel from a cell derived from a glyph.

---

# 5. THE DECISIVE LOGICAL TEST

$$\textbf{Does a refused transition change the epistemic state?}$$

Construct:

```text
H1:  S_0 → (transition τ executed)  → S_1
H2:  S_0 → (transition τ REFUSED)   → S_0
H3:  S_0 → (τ refused, then τ' executed) → S_1
H4:  S_0 → (τ' executed directly)        → S_1
```

Then answer separately — do not merge:

1. Is `S_K(H2) = S_K(H1's starting state)`? *(i.e. does refusal leave epistemic state untouched?)*
2. ⭐⭐ **Is `S_K(H3) = S_K(H4)`?** — **this is the independence pair.** If they must differ, refusal
   carries information. If they may be identical, it does not.
3. Which of `K1`–`K11`, if any, distinguishes `H3` from `H4`?
4. Is the distinguishing information **epistemic** or **governance**?

⭐ **`P-30` established `K3a` retains `(prior → successor)` PAIRS.** A refusal produces **no pair** —
`prior = successor`. Determine rigorously whether that is an **absence of information** or a
**self-pair** the estate must record. **Do not assume; `P-16` corrected exactly this kind of slip once
already.**

---

# 6. THE ESTATE TEST

`P-28` fixed the two-estate boundary; `P-30` carried forward an **unresolved tension** *(`theory-part-04`
lists `Governance` as a component of `K`)*. **Do not reopen it** — but place the refusal:

| candidate | consequence |
|---|---|
| refusal is **governance** *(policy enforcement evidence)* | ⛔ no epistemic obligation · nothing for the kernel |
| refusal is **epistemic** *(the estate's own history)* | ⭐ an obligation, and §7 must locate or prove it |
| refusal is **neither** *(a runtime non-event)* | ⭐ verdict `D` |

⭐⭐ **Ask the sharpest form:** *does the epistemic estate need to know that something was refused, or
only that the current state is what it is?* `P-19`'s restatement of `K5` is the model — *the estate must
not claim checkable grounding it does not have* — and it says nothing about attempts.

---

# 7. KERNEL LOCATION OR INDEPENDENCE PROOF

If a refusal obligation exists, locate it **exactly**, in the `P-30` manner *(name the cell and show
what information it carries — never "`K3b` covers it")*:

```text
K1 · K2 · K3a · K3b · K4a-i · K4a-ii · K4b-i · K4b-ii · K4c-i · K4c-ii · K5
```

⭐ **`K4b-i` (withdrawn ground) and `K4b-ii` (invalidated-by) are the nearest candidates.** Test them
seriously — a withdrawal is a transition that removed standing; a refusal is a transition that never
granted it. **Determine whether that difference is a difference in information.**

Otherwise run the full six-condition new-cell test and produce an **explicit independence proof**:

```text
S_K(H3) = S_K(H4)  while the corpus-required refusal property differs
```

**No cell without that pair. Conditions 1–6 all stated, each with its verdict.**

---

# 8. THE STATISTICAL / DDD SECTIONS

**Statistical *(analytical only — never evidence)*:** a refusal is analogous to a **rejected
hypothesis**, a **filtered observation**, a **censored datum**, or a **guard evaluation**. ⭐ Ask the one
question that matters: *does a dataset that silently drops inadmissible records differ informationally
from one that records the drop?* — then **explicitly refuse to use the answer as corpus evidence.**

**DDD:** determine whether the corpus supports refusal as **command rejection**, **domain event**,
**invariant violation**, **guard**, **specification**, or **nothing**. ⭐ **Distinguish `Reject` (a
command outcome) from `Rejected` (a status value) from `RejectionRecorded` (an event).** If the corpus
establishes none, say **`[UNWITNESSED]`** — and ⛔ **create no aggregate because "Refusal" is a noun.**

---

# 9. ATTACK `P-30`'S OWN CLAIM

`P-30` asserted:

> *"None of `K1`–`K11` was derived from a refused transition; every cell assumes a transition occurred."*

**Treat this as a hypothesis and try to falsify it.** Re-read `P-08` §5.3's *honest downgrade*, `P-19`'s
`K5` restatement, `P-21`'s withdrawal-with-retention and `K4b-i`. ⭐ **If any cell already encodes a
non-occurrence, `P-30`'s sentence is `[QUALIFIED]` and the question narrows sharply.** Report that
outcome as readily as the opposite.

---

# 10. REQUIRED FINAL VERDICT

Choose **exactly one**:

* **`A`** — refusal creates **no** persistence obligation; `⇀` is a specification gap *(`R6`)*
* **`B`** — refusal creates a **governance** obligation only; nothing epistemic
* **`C`** — refusal creates an **epistemic** obligation already carried by an existing cell *(name it and show the information)*
* **`D`** — refusal creates an **independently required** epistemic capability *(independence proof mandatory)*
* **`E`** — the corpus does not determine it; `⇀`'s partiality is **unwitnessed as an obligation**

⛔ **Do not choose `A` merely because refusal leaves current state unchanged** *(that is the invalid
inference of §1)*. ⛔ **Do not choose `D` because a refusal register would be useful.**

Then deliver, in the `P-30` format:

* the **six-object table** *(`R1`–`R6`: corpus status · persistence status · estate · cell)*
* the **partiality verdict** — deliberate or notational, with the comparative arrow evidence
* the **independence result** — the `H3`/`H4` pair, or the exact cell that prevents it
* **`K3a`'s self-pair question** answered explicitly
* the **`P-30` §9 hypothesis** — confirmed or `[QUALIFIED]`
* **kernel status** — `|K| = 11` unless an independent capability is **proven**
* the **status register**, all nine classes
* **carried opens** from `P-27`–`P-30`, including `Accepted ⇒ Warrant` and the `Governance`-in-`K`
  tension — ⛔ **and NOT the deferred three**

---

# STOPPING CONDITION

Stop as soon as one of these is established:

1. `⇀` is notational or a specification gap ⇒ **`E`** *(or `A`)*
2. refusal is governance-side ⇒ **`B`**
3. refusal is epistemic and an existing cell carries it ⇒ **`C`**, with the information shown
4. refusal is epistemic, independent, and the `H3`/`H4` pair exists ⇒ **`D`**, with the proof

⛔ **Do not continue into architecture. Do not design a refusal register. Do not create a cell without
the independence pair.**

---

# FINAL LINE

End with **exactly ONE** next unresolved question, arising from evidence discovered in `P-31`.
**No list.**

---

## Why this is the right `P-31`

`P-30` closed with the first structural hole in eleven audits: **every kernel cell was derived from a
transition that happened.** The corpus's own `⇀` says some transitions do not.

⭐ **But the honest prior is `A` or `E`, not `D`.** `Ω_A` is `[STIPULATED]` *("I recommend")*, `CR-3`
leaves `Reject(r)` an **open decision**, and a partial arrow is weak evidence for a persistence
obligation. **The value of this audit is mostly in closing the hole cleanly — including by finding that
there is nothing behind it.**

⭐⭐ **The one thing that would change that:** if the corpus requires **evidence that a policy was
enforced** — the shape of `P-30`'s `A9`, which was phrased as *auditability* and would have been missed
by any vocabulary search. **That is why §3 mandates concept search first.**
