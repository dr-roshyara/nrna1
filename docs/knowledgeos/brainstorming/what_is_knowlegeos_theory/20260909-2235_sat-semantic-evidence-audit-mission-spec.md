Yes. This is the correct research protocol.

The immediate objective should now be **one narrowly scoped corpus audit**, not another theoretical discussion and not an implementation experiment.

### Give Claude/Codex this exact mission

```text
MISSION: SAT-SEMANTIC-EVIDENCE-AUDIT-v1

Objective
--------
Determine whether the corpus contains sufficient evidence to recover
the semantic decision boundary of Sat(K_t, r).

Research question
-----------------
Given K_t and r, what corpus-grounded conditions are necessary and
sufficient for Sat(K_t, r) = 1?

STRICT NON-DESIGN RULE
----------------------
Do NOT define Sat.
Do NOT implement Sat.
Do NOT introduce or assume:
  - Accept_r
  - Σ = (A,S,R,V,C)
  - V7 as the canonical K_t model
  - component projections
  - component-membership semantics
  - thresholds
  - semantic adapters
  - new predicates or bridges

Do NOT canonicalize or reconcile competing models.

SEARCH TARGETS
--------------
Search the corpus for:

  (K_t, r) -> Sat(K_t,r) = 1
  (K_t, r) -> Sat(K_t,r) = 0

Also search for implicit cases where the corpus clearly says that
a requirement is satisfied/unsatisfied without explicitly writing Sat.

Prioritize:
  1. KR-CONTR-FDE-2026-09
  2. Zero Lens specification
  3. Cross-frame evaluation C6/C7
  4. Gap Theory v1.0
  5. FDE experiment
  6. Other corpus material containing:
       Sat
       satisfied
       satisfaction
       adequate
       requirement
       standard
       acceptance
       gap

FOR EACH CANDIDATE CASE EXTRACT
-------------------------------
1. Exact source/file
2. Exact location
3. K_t, if explicitly recoverable
4. r, if explicitly recoverable
5. Explicit satisfaction value, if present
6. The statement/rationale establishing satisfaction
7. What information in K_t appears relevant
8. Whether the relation is direct or inferred
9. Epistemic classification

CLASSIFICATION
--------------
A = Direct Worked Example
    Explicit K_t + r + satisfaction judgment + rationale.

B = Implicit Satisfaction
    Satisfaction is clearly established but Sat is not explicitly used.

C = Analogy/Parallel
    Similar semantics elsewhere, but not evidence for this Sat.

D = Invented Bridge
    Requires a modelling assumption not established by the corpus.

Only A can directly establish the semantic decision boundary.
B can support it.
C may generate hypotheses.
D must NOT be used as evidence.

CRITICAL NEGATIVE SEARCH
------------------------
Explicitly report:
- number of A cases
- number of B cases
- number of C cases
- number of D cases
- documents searched
- documents not searched
- cases where K_t or r cannot be reconstructed
- cases where satisfaction is asserted but its reason is unclear

HYPOTHESIS GENERATION
---------------------
Do NOT begin by testing H1-H6.

First extract the actual mechanisms found in the corpus.

Only after extraction formulate candidate hypotheses H_i.

For each hypothesis state:

  H_i:
    Sat(K_t,r) = 1 iff C_i(K_t,r)

Then test every A/B case.

FALSIFICATION
-------------
A genuine counterexample falsifies the candidate.

Do not modify corpus evidence to preserve a hypothesis.

FINAL VERDICT
-------------
Return exactly one of:

1. RECOVERABLE
   Corpus contains sufficient evidence for a defensible Sat boundary.

2. PARTIALLY RECOVERABLE
   Corpus establishes some conditions but not a complete boundary.

3. UNDERDETERMINED
   Corpus establishes the role of Sat but does not determine its
   semantic decision boundary.

4. CONTRADICTORY
   Corpus contains incompatible satisfaction semantics that prevent
   one boundary from being recovered.

5. INSUFFICIENT SEARCH
   Search coverage is not yet sufficient for a conclusion.

IMPORTANT
---------
The desired result is NOT a formula.

The desired result is an evidence-backed answer to:

  "Does the corpus determine what Sat(K_t,r) means operationally?"

If the answer is NO, preserve that negative result.
Do not design a replacement semantics.
```

### What I expect the output to look like

The most valuable artifact is a table like:

| Case  | \(K_t\)       | \(r\)         | Sat | Why?               | Evidence class |
| ----- | ------------- | ------------- | --: | ------------------ | -------------- |
| C-001 | reconstructed | reconstructed |   1 | explicit rationale | A              |
| C-002 | partial       | explicit      |   0 | explicit rationale | A              |
| C-003 | explicit      | explicit      |   — | implied satisfied  | B              |
| C-004 | —             | —             |   — | analogy only       | C              |

**Do not let Claude jump from this table directly to a formula.**

First we inspect whether the cases actually share a semantic mechanism.

---

## The decision point afterward

There are only three interesting outcomes:

### Outcome A — We recover the boundary

Then:

$$
\text{Corpus}
\Rightarrow
C(K_t,r)
\Rightarrow
Sat(K_t,r)
$$

and we proceed to the experiment.

### Outcome B — We recover only part

Then we document:

$$
Sat = Sat_{\text{known}} + Sat_{\text{unknown}}
$$

conceptually, without inventing the unknown part.

That means `Sat` remains **PARTIALLY RECOVERABLE**.

### Outcome C — Nothing determines it

Then the result is scientifically valuable:

$$
\boxed{
\text{The corpus establishes the role of }Sat
\text{ but underdetermines its operational semantics.}
}
$$

At that point we can legitimately open a **design track** for a candidate `Sat`, explicitly separated from historical reconstruction.

---

### One correction to the previous plan

I would **not run a semantic experiment immediately after finding one plausible hypothesis**.

The stronger sequence is:

$$
\boxed{
\text{Corpus}
\rightarrow
\text{Cases}
\rightarrow
\text{Semantic pattern}
\rightarrow
\text{Hypothesis}
\rightarrow
\text{Falsification}
\rightarrow
\text{Independent validation}
\rightarrow
\text{Experiment}
}
$$

That protects the theory from turning an attractive computational construction into a falsely “recovered” historical semantics.

Your uploaded framework already establishes this boundary: the current artifact is explicitly **RESEARCH FRAMEWORK — Not Implementation**, and its purpose is recovery without introducing `Accept_r`, projections, or a canonical \(K_t\) representation unless evidence forces them. 

**So: run this audit now. Do not modify the theory yet.**
