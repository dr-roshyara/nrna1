# Boundary — Theory Extraction ↔ `three_model_convergence`

**2026-09-07.** One page, so the boundary is checkable rather than remembered.

---

## The rule

$$\boxed{\begin{array}{lcl}\textbf{3MC asks} & : & \textit{what does the corpus SAY, and how did it get there?}\\ \textbf{Extraction asks} & : & \textit{what THEORY is present in what it says?}\end{array}}$$

**Extraction consumes 3MC as evidence. 3MC never consumes Extraction.** The dependency is one-way,
and a cycle between them would make each the other's authority.

## Question-by-question

| question | answered by |
|---|:--:|
| what concepts occur in the corpus? | **3MC** |
| where did a concept first appear? how did it evolve? | **3MC** |
| which of the three models does a document belong to? | **3MC** |
| what does document `nnnn` claim? | **3MC** |
| where do the three models converge / diverge? | **3MC** |
| which documents cite which? | **3MC** |
| **is `K` one concept or ten?** | **Extraction** |
| **which definitions of a concept exist, and how do they relate?** | **Extraction** |
| **is this a definition, a proposal, or an implementation fact?** | **Extraction** |
| **does theory element `X` have code?** | **Extraction** |
| **what does the theory demand that the corpus lacks?** | **Extraction** |
| **is this a principle or a hypothesis?** | **Extraction** |
| which definition is canonical? | **Governance** |
| what is the carrier? (`OQ-1`) | **Governance** |
| how is it built? | **Implementation** |

## The distinction that generates most of the boundary

The same sentence produces **three different records in three different places**:

| sentence | record type | layer |
|---|---|---|
| *"file 0518 writes `K(t)=(E,C,I,A,P,R,U,B,M,S)`"* | corpus occurrence | **3MC** |
| *"`K` has a ten-component definition, `D-08`, relationship to `D-01`: `unresolved_equivalence`"* | **theory element** | **Extraction** |
| *"`K(t)=(E,C,I,A,P,R,U,B,M,S)` is the KnowledgeOS knowledge state"* | **a decision** | **Governance** |
| *"`class K` exists in `kos_kernel.py`"* | implementation fact | **Extraction** (`Implementation:`) |

⚠️ **Conflating rows 1 and 2 makes the archaeology the theory. Conflating 2 and 3 makes extraction an
adjudication. Both have happened in this estate and both are recorded.**

## Read-only matrix

| | `brainstorming/` | `three_model_convergence/` | `theory-extraction/` | `governance/` |
|---|:--:|:--:|:--:|:--:|
| **3MC** | read | **write** | read | read |
| **Extraction** | read | read | **write** | read |
| **Governance** | read | read | read | **write** |
| **this verification lane** | read | read | read *(supplies evidence)* | read |

**`12_canonical-theory/` is writable by none of them** until its own guard conditions are met.

## Where the artifacts sit

```
docs/knowledgeos/
├── brainstorming/                     ← SOURCES, read-only to everyone
│   ├── three_model_convergence/       ← ARCHAEOLOGY  (owns its own registers)
│   └── verification/gap-discovery/    ← this lane: evidence for Extraction
├── theory-extraction/                 ← EXTRACTION  (this project)
├── research/                          ← experiments + 3 code estates
├── governance/                        ← ADJUDICATION acts
└── reviews/                           ← reviews of all of the above
```

## Two consequences worth stating

1. **`dimension-registry.md` is not the KnowledgeOS concept registry.** It is a **corpus-occurrence**
   record and it is authoritative *as that*. Extraction builds its own concept records and **cites**
   it — the registry-extension proposal is therefore re-addressed, not withdrawn.

2. **A concept absent from 3MC is not absent from the theory, and vice versa.** 3MC's reading pass is
   **1 224 / 2 376 ≈ 52 %** complete. `Yajña`, `Śraddhā` and `Kṣamā` are absent from its concept
   section and present in the corpus — **which is a finding about coverage, not about the theory.**
