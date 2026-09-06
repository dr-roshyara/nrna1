# 08 — Evidence Gap

**Mandate §13:** *"What makes an observation evidence rather than merely an observation?"*

---

## 1. The corpus answers the question

It does, and well. Closure-04 (2026-08-27 13:17):

$$\boxed{Evidence(O, P, C, R)}$$

> Observation `O` is evidence for proposition `P`, given context `C` and evaluation rule `R`.

with the four-level chain

$$Source \rightarrow Observation \rightarrow EvidenceRelation \rightarrow EpistemicAssessment$$

and four separations: `Evidence ≠ Observation`, `≠ Source`, `≠ Support`, `≠ Truth`.

**The qualification rule is: relevance to a named proposition under an explicit rule.**

The argument is a real one, not an assertion. *"The server returned 3.69"* is evidence for
`Nexus.version = 3.69`; it is not evidence for `Nexus is secure`; it is certainly not evidence for
`Nexus will remain secure for five years`. Hence `Evidence(O,P₁) ≠ Evidence(O,P₂)` and evidence is
relational, not a property a document possesses.

**EG-1 (`CORPUS ESTABLISHES`).** The mandate's §13 question has an answer, and this session accepts
it. This is one of the three strongest results in the corpus.

---

## 2. And then the answer is never connected to its own blocker

Step 266 §266.2, three days and 240 steps later, classifies:

> **Class C — not computable as currently defined:** … `Relevant(A)` …

and §266.37:

> *"`Relevant(A)` is a syntactically valid mathematical predicate. But unless `Relevant` has a
> decision procedure, it remains semantically under-specified."*

**EG-2 (`DERIVED`, HIGH).** The qualification rule is **well-formed and non-computable**. Its
load-bearing predicate — relevance under a rule — is in the class the corpus itself marks as having
no decision procedure.

The two results never meet. Closure-04 declares evidence closed; Step 266 declares relevance open;
**no document in the corpus observes that the first depends on the second.** Searched: Steps 266,
267, 269, 270, and the six terminal artefacts. Zero cross-references.

This is a *connection* gap rather than a *content* gap, and it is the most common failure mode this
session found across the whole corpus: correct local results that are never composed.

---

## 3. Each of the mandate's six required definitions

| Required | Corpus state | Class |
|---|---|---|
| **observation** | primitive; `(source, method, time)`; Step 253 §253.6 rates it a strong irreducible candidate | `CORPUS ESTABLISHES` |
| **evidence** | the 4-place *relation* `Evidence(O,P,C,R)`, not an object | `CORPUS ESTABLISHES` |
| **qualification** | relevance to `P` under `R` in `C` | `CORPUS ESTABLISHES` (rule) / `UNRESOLVED` (procedure) |
| **evidence identity** | **absent.** Step 025i/`20260827-140919` asks "what does it mean for two evidence items to be the same?"; the terminal model reduces `e` to a bare set of ids with no equality | `UNRESOLVED` |
| **evidence provenance** | `Π` on the assertion, not on the evidence. `e` is a set of **ids**, so evidence provenance is *outside* `K` | `UNRESOLVED` |
| **evidence strength** | a number in `[0,1]` with no scale type, no empirical relational structure and no probability model (`07-MEASUREMENT-THEORY-GAP.md` MT-4/MT-5) | `REFUTED` as measurement |
| **evidence validity** | not defined. `Stale`/`Invalid` appear in `025d`'s nine-status set and are dropped from the four-arm summary | `UNRESOLVED` |

**EG-3 (`UNRESOLVED`, HIGH) — the terminal model demotes evidence to opaque identifiers.**
In `A = (id, P, e, c, t, Π)`, `e` is a set of evidence identifiers. Nothing in `K = (𝒜, ℛ)` holds an
evidence **object**. Therefore, from `K` alone, none of these is answerable:

- are two evidence items the same item? (identity)
- where did this evidence come from? (provenance — `Π` is the *assertion's*, not the evidence's)
- is it still valid? (validity)
- is it independent of that other item? (dependence — the whole subject of `025c-2`, `025c-3`)

The corpus built an evidence algebra across **five separate steps** (`025c`, `025c-1`, `025c-2`,
`025c-3`, `025n`) and the terminal state retains none of its inputs. The algebra has nothing to
operate on.

**EG-4 (`EXECUTED`, MEDIUM).** `025c` and `025n` are both titled *"Evidence Aggregation Algebra"* and
are not byte-identical (`00-CORPUS-INVENTORY.md` INV-2). Two algebras, one name, no reconciliation.

---

## 4. What execution says about the evidence algebra

Only one part of the evidence work has ever been executed, and not by the corpus:
`reviews/synthesis/analysis/mathematical-tests/exp01_recheck.py`, from the Claude review thread.
This session ran it. Its verdict:

> *"The published negative verdict ('no simple scalar operator is sufficient as the epistemic
> foundation') does NOT follow from the 7-column matrix alone (SATURATING passes all seven columns).
> It DOES follow from the matrix PLUS criterion E (no scalar can retain (S⁺,S⁻)) PLUS the
> pre-operator dependency/duplicate work PLUS the calibration caution … the negative conclusion is
> CONFIRMED and is in fact provable."*

**EG-5 (`EXECUTED`).** The corpus's central negative result about evidence aggregation — *no scalar
suffices* — **survives independent execution**, though not for the reason the corpus's own matrix
gave. The reason that works is the one that matters: **a scalar cannot retain the pair
`(support, refutation)`**, which is exactly `06-SIGMA-GAP-ANALYSIS.md`'s evidence axis needing four
values rather than one number.

Two independent routes to the same conclusion: evidence standing is **at least two-dimensional**
(support and refutation), plus a sufficiency judgement relative to a bar (SG-4). A single strength
number cannot carry it, and `AggregateSupport = Σs/(1+log n)` (MT-4) tries to.

---

## 5. The observation→evidence transition, drawn with its blockers

```
   SOURCE            OBSERVATION              EVIDENCE RELATION          ASSESSMENT
  (typed,           (typed, has              Evidence(O,P,C,R)          σ over ≥5 axes
   identified)       provenance)                    │                        │
      │                   │                         │                        │
      ✔ EKP:              ✔ EKP:                    ✘ Relevant: class C      ✘ no axis in
      owner,              code_refs,                ✘ C: untyped (ON-4)         the running
      authority           test_refs                 ✘ R: policy, class C        system (SG-7)
                                                    │
                                             ✘ evidence identity: undefined
                                             ✘ evidence provenance: outside K
                                             ✘ evidence validity: undefined
```

Two of the four stages are realized in the running system; the two that carry the *epistemic* work
are blocked at every input.

---

## 6. Findings

| ID | Finding | Class | Severity |
|---|---|---|---|
| **EG-1** | The mandate's §13 question **is answered**: `Evidence(O,P,C,R)` — relevance to a proposition under an explicit rule — with a sound supporting argument. | `CORPUS ESTABLISHES` | — |
| **EG-2** | The rule's load-bearing predicate `Relevant` is class C (no decision procedure). Closure-04 and Step 266 never reference each other. The qualification rule is well-formed and non-computable. | `DERIVED` | HIGH |
| **EG-3** | The terminal `K` reduces evidence to opaque ids: evidence identity, provenance, validity and independence are all unanswerable from `K`. The five-step evidence algebra has no inputs left to operate on. | `UNRESOLVED` | **CRITICAL** |
| **EG-4** | `025c` and `025n` are two non-identical documents with the same title, "Evidence Aggregation Algebra". | `EXECUTED` | MEDIUM |
| **EG-5** | The corpus's *"no scalar suffices"* result survives independent execution — for a different reason than its own matrix gave. Evidence standing is at least two-dimensional `(S⁺, S⁻)` plus sufficiency. | `EXECUTED` | — |
| **EG-6** | `C` (context) — a parameter of the qualification rule — has no type anywhere, so `Evidence(O,P,C₁,R) = Evidence(O,P,C₂,R)?` has no answer. (Same defect as `03-FOUNDATIONAL-ONTOLOGY.md` ON-4.) | `UNRESOLVED` | HIGH |

---

**Next:** `09-TRANSFORMATION-GAP.md`.
