# 06 — Normative Decision Register

**Mandate Part F.** *"Only after performing all of the above may you decide whether D-1, D-2 and D-3
genuinely require a human normative decision. If any can be derived, derive it."*

---

## 1. Disposition of the three

| | Decision | Second-order disposition | Where derived |
|---|---|---|---|
| **D-1** | What is the mandatory operation set `𝒪`? | **DERIVED — RETIRED** | `01` |
| **D-2** | Is authority exogenous? | **DERIVED — RETIRED** | `02` |
| **D-3** | Is conditional determination in scope? | **DERIVED — RETIRED** | `03` |

$$\boxed{\textbf{OPEN / NORMATIVE nodes in the canonical dependency graph: } 0 \text{ of } 29}$$

### D-1 — derived

- `𝒪` **is** enumerated: 15 operations (§256.2 nine + §259.7 six). `CORPUS EVIDENCE`
- `𝒪` **is** classified into five classes (§259.7), and only class 1 enters the congruence test
  (§259.8). `DERIVATION`
- History-sensitive **queries** are class 4 and do not refute state sufficiency (§257.32–33).
  `DERIVATION` — *this reverses the first-order EXP-3 inference.*
- `K=(𝒜,ℛ)` is congruent for **every** class-1 operation under **both** dependency variants over a
  208-state domain. `EXECUTION EVIDENCE`
- The open remainder of §256.32 **does not change the answer**, so it is not load-bearing.
  `EXECUTION EVIDENCE`

**A choice that cannot change the answer is not a decision that needs making.**

### D-2 — derived

- §187.28–29 stipulates exogeneity. `CORPUS EVIDENCE`
- The running system implements it at **100 % coverage**: 132/132 grants carry `humanActRef`;
  `registeredBy: governance` on 132/132; **0** typed human-act objects inside; the resolver
  *"states the requirement; it cannot perform it"* and fails closed. `IMPLEMENTATION OBSERVATION`
- Theory and implementation **agree**. The first-order "contradiction" (G-38) is **withdrawn**.

**A stipulation implemented at 100 % coverage with fail-closed enforcement is an implementation
observation, not an open choice.**

### D-3 — derived

- `Determination` occurs **1 165 times in 142 files**. `CORPUS EVIDENCE`
- §157.22 gives it an **aggregate root** (`DeterminationId · Proposition · Method ·
  EvidenceReferences · Result · EpistemicStatus · Actor · ContextSnapshot`), an invariant, a
  `DRAFT→PROPOSED→FINALIZED` lifecycle, immutability after finalization, and domain events.
  `CORPUS EVIDENCE`
- §165.10 **refutes** the embed-in-assertion model: `D₁=f(K,E,C₁)` and `D₂=f(K,E,C₂)` can both be
  valid, so `D ∉ K`. `DERIVATION`
- The first-order recommendation (Model C) is a **strictly weaker subset** of §157.22.

**The corpus chose a model, twice, with reasons. It was lost in a band transition, not left open.**

---

## 2. Genuinely normative items that remain

**Zero at the level of theory closure.**

Two items are *normative in origin* but are **already settled and implemented**, and are recorded
here so that a future session does not reopen them by mistake:

| ID | Settled normative commitment | Status | Reopening cost |
|---|---|---|---|
| **N-1** | **Authority is exogenous; the kernel enforces, never originates** (§187.29) | `CLOSED/NORMATIVE` — implemented, 132/132 | Reopening reinstates the 7-node cycle (`05` §2) and invalidates the fail-closed resolver |
| **N-2** | **Minimality is relative to `𝒯`, not ontological** (§254 `Minimality(K\|𝒯)`) | `CLOSED/NORMATIVE` — and §259.16 refuses the stronger claim on principle | Reopening requires a universal-operation-set claim the corpus explicitly declines |

**Neither is a question. Both are answers already given, and the second-order evidence supports
both.**

---

## 3. Deployment parameters — decisions, but not *this* decision

Seven items (`04` §4) are **intentional parameters**. They will need answers **per deployment**, and
answering them is a product act, not a theory act. Recording them prevents them being miscounted as
theory gaps again:

| Parameter | Who declares it | Consequence of leaving it open |
|---|---|---|
| `𝒯_class-1` for this deployment | product | `K*` is not computable until declared — but `K=(𝒜,ℛ)` is congruent for the whole candidate set, so nothing is blocked |
| `𝒥_mandatory` (the invariant set) | product | **the live one** — SO-EXP-02/04: this determines state adequacy, and only 6 invariants were locatable |
| merge conflict-resolution rule | product | convergence is a property of the rule; §025l's claim is contentless without it |
| `V_D` per dimension | domain | value membership decidability |
| probability regime (if any) | domain | none — probability is external by design |
| the logic layer (negation, conditionals, quantification) | product | `Determination`'s **rule** stays untyped |
| retention policy for `H` | product | audit questions answerable or not |

**`𝒥_mandatory` is the one that matters**, and it is the second-order pass's main new finding: the
corpus states a congruence criterion (§259.15) and states invariants (§256.9, §265.11, §265.12) and
never composes them.

---

## 4. If a decision must nevertheless be put to a human

Only one item comes close, and it is **not** D-1/D-2/D-3. It is stated here in the mandate's format
so it is available if the reader disagrees with §1.

### **N-3 (candidate) — Should `Determination` be reinstated into the canonical model?**

**Exact question.** §157.22 specifies a `Determination` aggregate. Steps 258–271 reduce the model to
`K=(𝒜,ℛ)` and never mention it. Should the canonical theory carry `Determination`, or is the
reduction to `K` deliberate?

**Why the corpus cannot answer it.** No terminal step says *"Determination is out of scope"* and none
says *"retained externally"*. It is absent without disposition (DS-1).

**Why mathematics cannot answer it.** §165.10 proves `D ∉ K`. It cannot say whether `D` is *in the
theory* at all.

**Why implementation cannot answer it.** **Zero** `Determination` instances exist. The similarly named
PublicDigit `Determination`/`EvidenceSet` classes are in the Adjudication context — a different
domain (`11` PL-8).

**Options.** (A) reinstate §157.22 verbatim as an external aggregate · (B) record it as deliberately
deferred · (C) leave the silence.

**Consequences.** A restores explanation and the founding conditional problem's *carrier* (though not
its *rule*, DS-2). B costs one sentence and stops the loss recurring. C is what has happened three
times already — with `Regime`, with the repaired measure-theory model, and now with `Determination`.

**RECOMMENDATION** *(labelled as recommendation, not fact)*: **B, then A** — record the disposition
now so the loss is arrested, and reinstate when `Policy`/`Rule` are typed, since `Determination`
without a typed `Method` reproduces DS-2.

**What is invariant across all options.** All 16 surviving first-order results, the `K` congruence
result, and the entire `CLOSED` half of the dependency graph.

---

## 5. Register summary

| Class | Count |
|---|---:|
| Genuinely open normative decisions blocking theory closure | **0** |
| Settled normative commitments (do not reopen) | 2 |
| Deployment parameters (product acts, not theory acts) | 7 |
| Candidate decisions offered for disagreement | 1 (N-3) |
| **D-1 / D-2 / D-3** | **all three DERIVED and RETIRED** |

---

**Next:** `07-SECOND-ORDER-VERDICT.md`.
