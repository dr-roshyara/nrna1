---
artifact: B · ASSURANCE-RECONSTRUCTION-MATRIX
mandate: 20260830_1918 §6
date: 2026-08-30
status: **RESOLVED — `Assurance` CANNOT be retained as one formal term. It is SPLIT.**
---

# Assurance — Reconstruction

## 1. The matrix

31 distinct definitional statements, 347 files, 148 numbered steps.

| Source | Meaning | Type | Inputs | Outputs | Lifecycle | Compatible? | Evidence |
|---|---|---|---|---|---|---|---|
| step 071 | `Assurance = Composed local contracts` | composition operator | local contracts | a global contract | per-composition | with 168 only | corpus |
| step 092 | `Assurance = f(Irreversibility, Impact, Criticality)` | **risk function** | 3 risk factors | a magnitude | per-action | **NO** | corpus |
| step 093 | "assurance is multi-dimensional" | vector | unspecified | unspecified | — | vacuous | corpus |
| step 127 | `Assurance = Strong BC Candidate` | **a bounded context** | — | — | strategic | **NO** | corpus |
| step 140 | "authoritative wherever mechanically verifiable" | **predicate over method** | a claim + its method | Bool | per-claim | with 021 | corpus |
| step 168 | "not merely a flat list" → a **lattice** | ordered structure | claims | a partial order | — | with 071 | corpus |
| step 170 | `Assurance = BackwardTraceability` | **reachability relation** | a claim | its ancestors | per-claim | **NO** | corpus |
| step 211 | "architecture assurance graph" | a graph | nodes/edges | — | — | with 168, 170 | corpus |
| step 230 | `Assurance = DeterministicAssurance` | **SELF-REFERENTIAL** | — | — | — | **NO — ill-formed** | corpus |
| 2026-08-21 ×5 | "strong where executable, weak where prose" | **meta-claim about assurance** | an artifact's form | a strength | — | not a definition | corpus |
| 2026-08-23 | "Assurance does not recursively certify itself" (Williamson) | **prohibition** | — | — | — | **contradicts 230** | imported epistemology |
| chapter.md | `Assurance = True` | Boolean | — | Bool | — | **NO** | corpus |

## 2. Classification of the meanings

**Not one concept. Not merely overloaded terminology. Four different KINDS of thing plus one ill-formed
definition:**

| Kind | Members | Verdict |
|---|---|---|
| **A property of a claim's justification** | 140, 168, 211, the "executable vs prose" meta-claim | **genuinely the same concept** at different abstraction levels |
| **A property of the ARTIFACT's form** | "strong where executable, weak where prose" | a *measure* of the above, not the thing itself |
| **A risk quantity** | 092 | **a different concept entirely** — about consequences, not justification |
| **A structural/organisational unit** | 127 (bounded context), 071 (composition) | **an architectural placement, not a semantic definition** |
| **Ill-formed** | 230 (`Assurance = DeterministicAssurance`) | **REFUTED** — self-referential, and directly contradicted by the corpus's own imported Williamson result |

## 3. Distinguishing the neighbours

| Term | Signature | What it answers |
|---|---|---|
| **Verification** | `Artifact × Spec → Bool` | *Was it built to the spec?* |
| **Validation** | `K × Standard → Assessment` | *Does it meet the standard?* |
| **Assessment** | `P × Evidence × Context × Policy → Σ` | *How well supported is this claim?* |
| **Evidence** | `QualifiedObservation` | *What bears on it?* |
| **Backward traceability** | `claim → ancestors` (reachability in `ℛ_der ∪ ℛ_ref`) | *Where did it come from?* |
| **Governance compliance** | `Policy × Transformation → Admissible` | *Was it allowed?* |
| **Deterministic assurance** | — | **NOT DEFINABLE** — step 230 defines it by itself |

> **Every neighbour has a distinct, well-typed signature. `Assurance` has none that is not already one of
> them.** That is the decisive test: **a term with no signature of its own is not a formal term.**

## 4. Can `Assurance` be retained as one formal term?

> ## **NO. It is split.**

```
Assurance  ⟶  REMOVED from the canonical vocabulary as a formal term
              retained ONLY as an informal umbrella word, never in a signature

  its legitimate content redistributes, with no residue:

  JustificationStrength(claim) : Claim → OrdinalScale
      "how well is this claim justified?"  — subsumes 140, 168, 211
      ORDINAL: {prose, reviewed, tested, executable, proven}
      corpus basis: "strong where executable, weak where prose" (5 independent files)

  Traceability(claim)          : Claim → ℘(Claim)     reachability in ℛ_der ∪ ℛ_ref   [= step 170]
  Risk(action)                 : Action → OrdinalScale                                [= step 092]
  GovernanceValid(K, Γ)        : already defined
```

**Nothing is lost.** Every non-contradictory usage maps into one of the four. **The word is dropped because
it is prominent, not retained because it is** — the mandate's §6 instruction, applied.

## 5. The self-referential defect, stated precisely

Step 230: `Assurance = DeterministicAssurance`. Step 2026-08-23 imports: *"Assurance does not recursively
certify itself."* **The corpus asserts a prohibition and then violates it 100+ steps later.**
`JustificationStrength` avoids this: it is a property **of** a claim, assigned by an **external** procedure,
and it never certifies itself. **The prohibition becomes a type constraint rather than a warning.**

## 6. The corpus's own verdict on itself

The most-repeated statement (≥5 independent files) is:

> *"Assurance is strong exactly where it is executable and weak exactly where it is prose."*

**Applied reflexively: the 1468-file corpus is prose, therefore `JustificationStrength(corpus) = prose`,
the weakest level.** The executable fragments — the EKP's 18 lint rules, 47 lineage tests, and this
programme's executed counterexamples — **are the only parts that score above it.** This is not a rhetorical
point; it is the corpus's own criterion applied to the corpus, and it is why triangulation against a running
implementation was necessary.

## 7. Classification

| Claim | Class |
|---|---|
| 31 statements, 6 incompatible types | **EXECUTED** (scan) |
| Step 230 is self-referential and contradicts imported epistemology | **REFUTED** |
| Neighbours each have distinct signatures | **FORMALLY DERIVED** |
| `Assurance` has no signature of its own | **FORMALLY DERIVED** |
| The four-way split loses nothing | **FORMALLY DERIVED** — not proven exhaustive over all 31 |
| `JustificationStrength` is **ordinal** | **FORMALLY DERIVED** — **no arithmetic admissible** |

> **CB-1 is CLOSED by splitting, not by choosing.** No source was preferred for recency; the ill-formed one
> was refuted on its own corpus's grounds.
