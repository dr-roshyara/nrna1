# 07 — The Minimum Implementable KnowledgeOS

Answers mandate **§15**. Computed, not preferred: `exec/minimum_implementable.py`.

**Method.** A reference kernel must be able to (1) hold a knowledge state, (2) transition it legally,
(3) reject illegally, (4) replay from history. Take the transitive dependency closure of those four
capabilities over the construct graph. **Everything outside the closure is excluded by a dependency
fact, not by judgement.**

---

## 1. The answer, and it is conditional

$$\boxed{\textbf{15 or 18 constructs — and which one is undetermined}}$$

| Reading of the invariant register `ℐ` | Kernel requires | Unblocked | Blocked |
|---|---:|---:|---:|
| **structural invariants only** | **15** | 3 | 12 |
| **structural + epistemic invariants** | **18** | 3 | 15 |

The three that move: **Σ · Evidence · Policy.**

**Nothing in the corpus settles which reading is correct, because `ℐ` has never been enumerated
(G-67).** So §15's question is **not currently answerable to better than ±3 constructs**, and the
uncertainty is not about preference — it is about one missing object.

**`BLOCKED — REQUIRES DERIVATION.`**

---

## 2. The 15 required under either reading

| Construct | Why the kernel needs it | Status |
|---|---|---|
| **Proposition** | content of an assertion | `D` — two rival types (D-6) |
| **Identity** | `𝒜` is a set; membership needs identity | `D?` — TG-06 |
| **Equality** | postconditions are undecidable without it (C-5 #3) | `D` — 4 notions |
| **Provenance** | field of Assertion; `t=0`-safe | **unblocked** |
| **RelationType** | `ℛ` is typed | **unblocked** |
| **Assertion** | element of `𝒜` | **unblocked** |
| **Relation** | `ℛ`; carries derivation, contradiction, supersession | `D` — D-5 |
| **K** | the state | `A` — two rival `K` |
| **History** | replay folds over it | `I` |
| **InvariantReg (`ℐ`)** | legality of a transition | `D` — **G-67** |
| **𝒪_core** | which operations exist | `O` — not frozen |
| **Operations** | the transition alphabet | `O` — 0 canonical |
| **Transformation (`δ`)** | the transition itself | `T` — no body |
| **Rejection** | *reject illegally* is a kernel capability | `T` — **CONTRADICTORY** |
| **Replay** | *replay from history* is a kernel capability | `T` |

---

## 3. Justified exclusions — each is a dependency fact

The mandate asks whether the minimum requires each of a named list. **It does not**, and here is why
for each:

| Construct | Required? | Dependency reason |
|---|---|---|
| **Evidence** | **conditional** | reachable only via `Σ` → `ℐ`. In iff `ℐ` carries epistemic invariants |
| **Σ** | **conditional** | same |
| **Policy** | **conditional** | same. *(Note: the only ratified construct is conditional-out of the kernel)* |
| **Q_t** | **NO** | nothing in the closure depends on it. It is required by *Missingness*, which no kernel capability requires |
| **Missingness** | **NO** | reachable only from `Q_t` + `Σ` |
| **Determination** | **NO** | reachable only from `Assessment` + `Authority`; nothing in the closure depends on it |
| **Lineage** | **NO** | derived from `ℛ` — *available free*, but not required |
| **Authority** | **NO** | required by `Authorization` and `Γ`, neither of which a kernel capability needs |
| **Authorization** | **NO** | as above |
| **Assessment / Qualification / Measurement** | **NO** | reachable only downstream of `Evidence`/`Σ` |
| **Orphan** | **NO** | derived from `ℛ` — free, not required |

> **The research programme produced 25 constructs. A reference kernel requires 15 to 18 of them.
> Between 7 and 10 are not on the kernel's critical path at all** — including `Q_t` and `Missingness`,
> which absorbed Steps 280–281 entirely, and `Determination`, which was the founding question.

**This is not a criticism of that work.** `Q_t` closed a real defect in `Σ`, and `Σ` is conditional-in.
It is a statement about **what must be specified before a kernel can be built**, which is the question
asked.

---

## 4. The uncomfortable observation

Of the 15 constructs required under **both** readings:

- **3 are unblocked** (Provenance, RelationType, Assertion)
- **1 is ratified** — and it is `Policy`, which is **conditional-out** of the kernel
- **0 of the required 15 carry a governance act**

$$\boxed{\text{The one construct with governance force is not required by the kernel. None of the constructs the kernel requires has one.}}$$

That is the implementation-readiness problem stated at its sharpest, and it is an **architecture and
governance** problem, not a theory one.

---

## 5. What could be built today, legitimately

A **structural sub-kernel**: `Assertion`, `Provenance`, `RelationType`, plus `Lineage` and `Orphan`
free from `ℛ`. That is:

- store identified, provenanced assertions
- typed relations between them
- lineage traversal (`O(n+m)`)
- orphan detection

**And it already exists** — that is the EKP, at `knowledge-lint` green over 37 documents with
`GovernanceLineageGraph` (4 tests) and `orphan_document`.

> **The existing implementation is exactly the unblocked subset.** Nothing more can be added without
> passing Blocker 1.
