# KnowledgeOS Information Transformation Theory — **Index and Reading Order**

**Document 00 of 15** · **Date:** 2026-09-04
**Status of the whole set:** **RESEARCH SYNTHESIS.** Not Theory v1.3. Not a ratification.
**Theory v1.2 remains FROZEN · the kernel remains NOT SELECTED · nothing here is adjudicated.**

---

## 0. What this set is, and what it is not

This is the theory that the experiments actually established, written down completely and
separately, one subject per document.

It is **not** a proposal for v1.3, and it must not be read as one. The governance order is
`EXPERIMENT → AUDIT → ADJUDICATION → THEORY v1.3`, and this set sits at the end of AUDIT.
Its purpose is to make adjudication *possible* by putting every claim, its status, and its
scope in one place.

> **Every mathematical statement in this set carries its epistemic status**, per
> `docs/knowledgeos/governance/EPISTEMIC-STATUS-VOCABULARY.md` (ADOPTED 2026-09-04). The tags
> are `[DEF] [THM] [COR] [PRP] [EXP] [CONJ] [NEG] [OPEN] [REC] [DEFECT]`. `[PROP]` is retired
> as ambiguous. **An untagged mathematical statement in this set is a defect.**

---

## 1. The single most important sentence

> ### `[EXP]` We have experimentally separated three things that the theory previously mixed: **information elimination**, **preservation of a business inquiry**, and **successful realization by a decoder**.

$$
\text{Eliminability} \;\neq\; \text{Preservation} \;\neq\; \text{Realization}
$$

This is the result. It is **not** "we discovered a new algebra" — the algebra experiments were
largely negative, and that is reported as such. It is `[EXP]`, **not** `[THM]`: there is no
proof that the three *must* differ in general, only demonstration that they *do* differ in
every system tested.

---

## 2. The central architecture

$$
D \;\xrightarrow{\;T\;}\; R
\qquad\qquad
Q : D \to Y
\qquad\qquad
\Pi : R \to O
$$

```
                    Transformation  T
                            │
                            ▼
                     Representation  R
                            │
        ┌───────────────────┼───────────────────┐
        ▼                   ▼                   ▼
      Zero               Adequacy           Realization
   Π(T(D)) =            Ĥ(Q│T(D)) = 0       O(T(D)) = Q(D)
   Π(T(E_S(D)))
        │                   │                   │
        ▼                   ▼                   ▼
  Can I remove it?   Is the meaning       Can the consumer
                     still there?         actually recover it?
```

**Three questions, three predicates, three answers. They do not imply one another.**

---

## 3. Reading order

| # | Document | Subject |
|---|---|---|
| **00** | *this document* | index, the central result, reading order |
| **01** | `…_theory-01-primitives-and-signatures.md` | `D`, `T`, `R`, `Q`, `Π`, `O`, `E_S`, `C`, the type discipline |
| **02** | `…_theory-02-the-three-question-separation.md` | the separation itself — the load-bearing result |
| **03** | `…_theory-03-zero-definition-and-algebra.md` | `Zero`: definition, and the algebra that **failed** |
| **03a** | `…_theory-03a-the-concept-of-zero.md` | **what `Zero` *is*** — and the four notions it is mistaken for |
| **04** | `…_theory-04-adequacy-information-theory-and-dpi.md` | `Ĥ(Q\|R)`, the DPI **theorem**, its corollary, estimator hygiene |
| **05** | `…_theory-05-realization-and-the-decoder.md` | `O(R) = Q(D)`, why adequacy does not give it |
| **06** | `…_theory-06-representation-reduction-boundary.md` | the `R5 → R4` boundary and its scope bound |
| **07** | `…_theory-07-the-bridge-programme-negative-result.md` | `Zero` does not predict preservation — three experiments |
| **08** | `…_theory-08-contradiction-and-evaluation-structure.md` | contradiction, evaluation semantics, composition, factivity |
| **09** | `…_theory-09-frozen-and-refuted-register.md` | **negative knowledge** — what we know is false |
| **10** | `…_theory-10-experimental-methodology-and-falsification.md` | how these results were made trustworthy |
| **11** | `…_theory-11-statistical-inference-standards.md` | stratification, effect floors, replication, power |
| **12** | `…_theory-12-ddd-architecture-of-the-kernel.md` | bounded contexts, ownership, ports — the DDD model |
| **13** | `…_theory-13-open-questions-and-forward-programme.md` | what is open, and what would close it |
| **14** | `…_theory-14-focus-inquiry-and-the-information-boundary.md` | **Restriction ≠ Inquiry** — the corrected research model, `P1`–`P3`, `FR-004`, and the **standing position** on Zoom-in / Zoom-out (§8a) |

---

## 4. The scope bound, stated once and inherited by every document

Every empirical result in this set was produced on **small synthetic carriers**:

$$
r = (\text{value},\ \text{source},\ \text{tag},\ \text{timestamp}), \qquad |D| \in \{4,5,6\}
$$

with inquiries such as $Q = (\arg\max_{\text{source}},\ |\{\text{tags}\}|)$ and
$Q = (\text{decile}(\text{total}),\ \arg\max_{\text{source}})$.

> `[OPEN]` **Nothing here has been shown to transfer to documents, claims, entities, relations,
> provenance, contradictions, temporal states or evidence.** That transfer is the open research
> problem, not a formality. Every `[EXP]` below is bounded by *this carrier, this `Q`, this
> chain, this contract, this value domain*.

**The canonical illustration of the discipline:** we may not say *"three significant digits is
the universal preservation limit."* We may say *"for this carrier, this `Q`, this chain, this
contract and this value domain, rounding to three significant digits crossed the observed
preservation boundary."*

---

## 5. The honest ledger

**Recounted 2026-09-06** across all 16 documents. ⚠️ **These are TAG OCCURRENCES, counted
mechanically — not distinct statements.** A statement cited in three documents contributes three.

| tag | occurrences | |
|---|---|---|
| `[EXP]` | **129** | empirical results, every one scope-bound |
| `[NEG]` | **118** | **refuted or withdrawn** |
| `[REC]` | 40 | recommendations — **no truth claim** |
| `[OPEN]` | 34 | |
| `[DEF]` | 24 | stipulated |
| `[CONJ]` | 13 | each with a stated refutation condition |
| `[DEFECT]` | 11 | recorded, contained |
| `[THM]` | 8 | ⚠️ **ONE distinct theorem** — the data-processing inequality, *cited*, not ours — referenced 8 times |
| `[COR]` | 6 | its corollary and consequences |
| `[PRP]` | 3 | |
| `[CORPUS]` / `[DESIGN]` | 1 / 1 | |

> ### **The `[NEG]` column is 118 occurrences against 129 `[EXP]`.**
> Nearly one refutation for every empirical result. **Document 09 exists because negative knowledge
> is knowledge, and a research programme that cannot show its refutations has not been doing
> research.**

`[REC]` **The ratio is the point, not the totals.** A set where `[EXP]` outran `[NEG]` by an order
of magnitude would be a set that had stopped testing its own hypotheses.

---

## 6. What would make this Theory v1.3

Not more experiments. **Adjudication**, plus at minimum:

1. A **carrier** decision (document 01 §5 — currently `[OPEN]`, and no carrier is declared).
2. A resolution of `DECISION-02` (document 08).
3. A second independent adopter for `FR-003` (document 09) per `ES-006.1`.
4. An explicit statement of which `[EXP]` results the theory is willing to *depend* on given
   their scope bounds — a governance act, not a research act.
