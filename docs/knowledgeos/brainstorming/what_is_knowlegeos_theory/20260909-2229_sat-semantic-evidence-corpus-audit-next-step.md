The uploaded research framework confirms the next step very clearly: **do not define `Sat` yet. Search for the evidence that could force its definition.** The framework explicitly identifies corpus search as Step 1. 

## The immediate next step

Run a **targeted corpus audit for worked satisfaction judgments**:

$$
\boxed{
(K_t,r)\longrightarrow Sat(K_t,r)=1
\quad\text{or}\quad
(K_t,r)\longrightarrow Sat(K_t,r)=0
}
$$

with enough surrounding material to establish **why** the judgment is 1 or 0. 

### Search these first

1. **KR-CONTR-FDE-2026-09** — 14 scenarios
2. **Zero Lens specification** — 8 non-collapse invariants
3. **Cross-frame evaluation** — C6 and C7
4. **Gap Theory v1.0** — 10 gap classes
5. **FDE experiment** — four-valued semantics
6. Then the rest of the corpus. 

---

# What the researcher should extract

For every candidate example, create exactly this record:

| Field                | Question                               |
| -------------------- | -------------------------------------- |
| \(K_t\)              | What is the knowledge state?           |
| \(r\)                | What exactly is the requirement?       |
| `Sat`                | Is satisfaction explicitly stated?     |
| Value                | 0 or 1?                                |
| Relevant information | What part of \(K_t\) matters?          |
| Criterion            | Why is it satisfied/not satisfied?     |
| Source               | Where exactly?                         |
| Epistemic status     | Direct / implicit / analogy / invented |

The most important field is:

$$
\boxed{\text{Why?}}
$$

We are looking for the actual semantic boundary.

---

# Then classify the evidence

The framework gives us a very good discipline:

### Level A — Direct worked example

Something explicitly establishes:

$$
(K_t,r)\rightarrow Sat(K_t,r)=1
$$

or 0.

This is the strongest evidence. 

### Level B — Implicit satisfaction

The text clearly treats a requirement as satisfied but never writes `Sat`.

Useful, but insufficient by itself to canonically define the predicate.

### Level C — Analogy

A similar satisfaction mechanism appears elsewhere.

This can generate a hypothesis, but cannot establish F4 semantics.

### Level D — Invented bridge

We ourselves decide:

> “This probably means component membership.”

Reject it as corpus evidence.

---

# Then—and only then—generate hypotheses

Suppose the audit finds examples suggesting:

$$
Sat(K,r)=1
\iff
C(K,r).
$$

We formulate:

$$
H_i:\quad
\forall(K,r)\in D_{\text{corpus}},
\quad
Sat(K,r)=1
\iff C_i(K,r).
$$

Then test it against **every** worked example.

The framework explicitly requires that a candidate reproduce all examples; one genuine counterexample falsifies the hypothesis. 

---

# Do not start with H1–H6

This is an important improvement I would make to the framework.

The six candidate hypotheses currently listed—

* evidence-based,
* determination-based,
* truth-based,
* threshold-based,
* validity-based,
* component-membership—

should **not become the search categories**.

Otherwise we risk confirmation bias.

Instead:

$$
\boxed{
\text{Corpus evidence first}
\rightarrow
\text{extract actual mechanism}
\rightarrow
\text{hypothesis afterward}
}
$$

The framework itself says the purpose is to recover the boundary **without introducing `Accept_r`, component projections, or a canonical \(K_t\)** unless evidence forces them. 

So the researcher should be allowed to discover:

$$
H_7,H_8,\ldots
$$

or conclude:

$$
\boxed{\text{No single corpus-grounded decision boundary exists.}}
$$

That negative result would be completely legitimate.

---

# The decision tree

I would make the next research mission this:

```text
                 CORPUS SEARCH
                      │
                      ▼
          Find worked (K,r,Sat) cases
                      │
             ┌────────┴────────┐
             │                 │
           FOUND             NONE
             │                 │
             ▼                 ▼
       Extract semantics   Search broader
             │                 │
             ▼                 ▼
       Candidate rule       Still none?
             │                 │
             ▼                 ▼
       Test all cases       IDENTIFIABILITY
             │               GAP
       ┌─────┴─────┐
       │           │
    survives    counterexample
       │           │
       ▼           ▼
   Candidate     FALSIFIED
       │
       ▼
 Independent validation
       │
       ▼
   Semantic boundary
       │
       ▼
       Sat
       │
       ▼
      Δ_t
       │
       ▼
      Zero
```

---

## What I would tell Claude/Codex to do now

Give it a **research-only mission**, not an implementation mission:

> **Audit the corpus for direct and implicit worked instances of `(K_t, r) → Sat(K_t,r)` judgments. Do not define Sat, do not introduce Accept_r, do not assume Σ/V7, do not construct a semantic adapter, and do not canonicalize. For every candidate, extract K_t, r, satisfaction value, the information in K_t that appears relevant, the stated acceptance/satisfaction rationale, exact source location, and epistemic status. Distinguish direct worked evidence from implicit evidence, analogy, and invented bridge. If no worked examples exist, report that as a negative result.**

That is the **next action**.

And the success criterion is **not** “we produced a formula for `Sat`.”

The success criterion is:

$$
\boxed{
\text{We found enough corpus evidence to justify a decision boundary—or proved that the corpus does not contain one.}
}
$$

Only after that should we touch \(\Delta_t\), Zero, F4 equivalence, or F3↔F4. The framework explicitly places those downstream of the recovered `Sat`. 
