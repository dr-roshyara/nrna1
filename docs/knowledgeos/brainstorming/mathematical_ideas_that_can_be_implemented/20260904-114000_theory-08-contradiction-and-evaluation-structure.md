# Theory 08 — **Contradiction, Evaluation Structure, Composition, Factivity**

**Document 08 of 15** · 2026-09-04
**Sources:** `KR-CONTR-MODELS`, `KR-CONTR-EVAL`, `KR-CONTR-FDE`, `KR-COMP`, `KR-COMP-SEP`,
`DECISION-01`, `DECISION-02`, the factivity adjudication brief

> This is the **other lane** of the programme. It is governed separately
> (`KR-ZERO ⊥ KR-REP-REDUCTION`) and its results are independent of documents 03–07.

---

## 1. Contradiction does not require four-valued logic

`[NEG]` **"KnowledgeOS requires four-valued logic" is NOT supported.**

`[EXP]` The tested contradiction requirements **do not force a fourth flat value**. Flat
candidates A/B/C fail the **same seven** required distinctions, and their common failure lies in
the **unknown/boundary family — not in contradiction at all.**

> ### `[EXP]` The problem that looked like a contradiction problem is a **boundary/unknown** problem.

`[EXP]` Candidate D — a **structured** evaluation — separates **all** tested required
distinctions.

`[EXP]` **A structured reason/boundary component is indispensable *within the tested candidate
representation language*.** *(Wording corrected by measurement, not by rewording: the original
claim "reason is the sole indispensable field" was too strong.)*

`[EXP]` **Minimum flat cardinality depends on the declared required-distinction set.** There is
no context-free answer to "how many truth values do we need"; the question is ill-posed without
the distinction set.

`[PRP]` $\mathrm{Contr} \neq \mathrm{Satisfied}$ is a required candidate invariant.

`[EXP]` **Candidate C can map contradiction to `Satisfied` while passing `I1`–`I5`** — which is
precisely why the invariant above must be stated separately rather than assumed to follow from
the interface tests.

---

## 2. Evaluation is structural, not cardinal

`[EXP]` `KR-CONTR-EVAL`: the requirement is **structured evaluation**, and the structural
requirement grew the state space from $\chi = 3$ to $\chi = 21$ — but the finding is *not*
"we need 21 values." It is that **cardinality is the wrong axis**. Adding flat values does not
buy the distinctions; adding structure does.

`[NEG]` **The reason-expressivity objection is answered, not dodged:** a trivial
$\mathrm{Reason}(x) = x$ would make "reason is indispensable" vacuous. It was tested — `reason`
takes 10 values in the tested language, and the finding survives factorization.

---

## 3. Composition — three rules entered, one was eliminated twice

`KR-COMP` established that composition criteria select **a frame qualifier, not a rule**, and
that $\varphi \supseteq \{\text{time}, \text{context}\}$.

`KR-COMP-SEP` then went looking for a separating witness:

> ### `[EXP]` The **commissioned** witness shape does not separate the three rules. A different shape does — and the commissioned shape **eliminates** a rule outright instead.

| witness | effect |
|---|---|
| `W1` (commissioned shape) | **eliminates `last-wins`** — a `C1` failure on all six surviving triples |
| `W2` (the actual separator) | separates `majority` from `intraframe-only` |
| `C6` order-invariance | **eliminates `last-wins` a second time, independently** |

`[EXP]` **Adding internal conflict to a witness DESTROYS the separation rather than creating
it.** The two surviving rules differ only in what they do when **no** frame conflicts
internally — exactly the case the commissioned shape excludes by construction.
**The separating witness must have NO internal conflict.**

`[NEG]` **`last-wins` reports `W1` as `negative-support`, not as conflict** — it reads only the
last frame and discards the internally-contradictory frame entirely.

`[NEG]` **`last-wins` is not well-defined on the evidence set** — its output depends on
enumeration order. A composition rule must be a function of the evidence **set**, not of its
listing.

**Net: `last-wins` eliminated twice over, independently. `majority` and `intraframe-only`
survive.**

---

## 4. Factivity — `R1`, and what it does and does not settle

**DECIDED:** $K_t \to A_t$ (AttributedState). `Knows → True` is **external and factive**;
**Verification is a separate concern** and is not folded into the knowledge operator.

`[EXP]` The repair was necessary: an earlier evaluator returned `T` for uninterpreted /
theory-incomplete states, which was a defect and unfair to the competing candidate. The
strengthened evaluator carries an explicit `evaluator_exists` flag.

---

## 5. The two standing decisions

### `DECISION-01` — **RATIFIED**

> **Semantic evaluation must be invariant under non-evidential variation.**

`C6` and `C7` are instances.

> ⚠️ `[NEG]` **The rank-convention result is NOT a further instance** (document 06 §5). That
> reading required an invertible recoding; the audit refuted invertibility with 21 369 tie
> violations. The claim was withdrawn — mine *and* the review's replacement wording.

### `DECISION-02` — **OPEN, and blocking**

> **Is $\varphi$ a semantic evaluation frame, or an evidence partition?**

`[OPEN]`. It blocks the composition rule and the $\delta$ transition. **Nothing downstream of it
may be settled by inference from the experiments** — the experiments constrain it, they do not
decide it. This is the single largest open governance item in the lane.

---

## 6. Register

| Statement | Status |
|---|---|
| KnowledgeOS requires four-valued logic | **`[NEG]`** |
| The tested requirements force a fourth flat value | **`[NEG]`** |
| The common failure of flat candidates is in contradiction | **`[NEG]`** — it is in the **unknown/boundary** family |
| Structured evaluation separates all tested distinctions | `[EXP]` |
| A structured reason/boundary component is indispensable in the tested language | `[EXP]` |
| Minimum flat cardinality is context-free | **`[NEG]`** — depends on the distinction set |
| $\mathrm{Contr} \neq \mathrm{Satisfied}$ | `[PRP]` — required candidate invariant |
| The commissioned witness separates the three rules | **`[NEG]`** |
| `last-wins` is admissible | **`[NEG]`** — eliminated twice, independently |
| A composition rule is a function of the evidence set | `[PRP]` — the `C6` criterion |
| $\varphi \supseteq \{\text{time}, \text{context}\}$ | `[EXP]` |
| $K_t \to A_t$; `Knows → True` external/factive; Verification separate | **DECIDED** |
| Semantic evaluation invariant under non-evidential variation | **`DECISION-01` RATIFIED** |
| The rank-convention result instantiates `DECISION-01` | **`[NEG]`** — withdrawn |
| Is $\varphi$ a frame or a partition? | **`DECISION-02` — `[OPEN]`, blocking** |
