---
artifact: IDENTITY-ROUNDTRIP-AUDIT
mandate: 20260830_1158 §1–§16
date: 2026-08-30
status: **DELIVERED — STOP CONDITION §16 TRIGGERED**
authority: verifier session (adversarial, independent)
evidence_class: B (four executed programs; real artifacts read from production code) + A (formal)
headline: |
  The round-trip PASSES 3/3 AND THE RESULT REFUTES THE PREMISE.
  GovernanceLineageNode is not a knowledge state. It is a LINEAGE NODE.
  The implementation implements L, not K. Mapping K onto it was a category error — mine.
---

# Identity → Round-Trip → Kernel Reconstruction

## 0. §17 — I tried to break it, and it broke

**The mandate's rule: *"Do not try to finish the theory. Try to BREAK it."*** The round-trip experiment
was designed to confirm the proposed `Item`. **It refuted the premise of the experiment instead.**

---

## 1. §1 — Frozen state before any change

| Class | Content |
|---|---|
| **CORPUS ESTABLISHES** | typed provenance graph (230.13) · `Validation : 𝕂×X → Assessment` (232.4) · `𝒯_G` not closed under `∘` (232.19) · bitemporality (185) · `TamperEvidence not Blockchain` (073.30) |
| **VERIFIER FORMALLY DERIVED** | `I* = {Provenance}` across 9 phases · `K/T/Invariants` circular, 4 cycles · two competing kernel lineages, 27/36 pairs · equality must be a family |
| **ACTUALLY EXECUTED** | 57 tests / 159 assertions (2 suites, exit 0) · `K₀ → T → K₁` computed · 10-attack refutation · this round-trip |
| **OBSERVED IN REAL CODE** | `GovernanceLineageNode(decisionId, integrityHash, decidedAt)` · `GovernanceLineageEdge(fromNodeId, toNodeId, edgeReason)` · `GovernanceLineageGraph` keyed by `decisionId` · `epistemic`/`KnowledgeState` in **0** files |
| **PROPOSAL ONLY** | `Item = (id,q,s,e,c,t,u,integrity)` |
| **UNDERDETERMINED** | epistemic-status vocabulary (11 competing, none designated) |

---

## 2. §3 — What exactly is identified? **The implementation answers.**

```php
public function addNode(GovernanceLineageNode $node): void {
    $this->nodes[$node->decisionId] = $node;      // identity IS the map key
}
public function findNode(string $decisionId): ?GovernanceLineageNode
```

**Of the mandate's eight candidates, the implementation identifies (E) A DECISION** — and identity is
**the key of the node map**, not an attribute alongside others.

Three consequences, read off the code:

1. **Identity determines replacement.** Two nodes with the same `decisionId` **overwrite**. There is no
   version dimension on the node.
2. **There is no supersession in the graph.** `addNode` overwrites; supersession, if present, lives in
   `edgeReason` — a free string.
3. **Identity is technical, not semantic.** Two independently created nodes representing the same decision
   would get different `decisionId`s and be distinct nodes. **The corpus's `Similarity ⇏ Identity` (195.7)
   is respected by accident, not by design.**

---

## 3. §6/§7 — The round-trip (EXECUTED, three artifacts)

Real instances taken from `GovernanceLineageGraphTest.php`.

```
[simple]              decision-1 / hash-1 / 2026-01-01T10:00:00Z   ROUND-TRIP PASSES
[in-transition]       node-A     / hash-A / 2026-01-01T10:00:00Z   ROUND-TRIP PASSES
[successor+integrity] node-B     / hash-B / 2026-01-02T10:00:00Z   ROUND-TRIP PASSES

round-trips passing: 3/3
```

### Field-by-field classification (§6 requires this explicitly)

| Implementation | Theory | Classification | Why |
|---|---|---|---|
| `decisionId` | `id` | **EXACT** | the map key in `addNode()` |
| `integrityHash` | `integrity` | **EXACT** | *only because I added `integrity` to the proposal* |
| `decidedAt` | `t.known_from` | **LOSSY** | impl has **one** timestamp; theory `t` is bitemporal → **3 of 4 slots empty** |
| — | `q` proposition | **THEORY-ONLY** | the node carries no proposition |
| — | `s` status | **THEORY-ONLY** | no epistemic status |
| — | `e` evidence | **THEORY-ONLY** | no evidence reference |
| — | `c` context | **THEORY-ONLY** | no context |
| — | `u` uncertainty | **THEORY-ONLY** | no uncertainty |

### **The round-trip passes VACUOUSLY — executed proof**

```
impl → theory → impl        : True   (lossless)
theory → impl → theory      : True   (lossless ONLY because 5 of 8 fields were already empty)

RICH item round-trip        : False
fields destroyed            : ['q', 's', 'e', 'c', 'u']
```

**It succeeds only because five of eight theory fields are `None` and are silently dropped.** Give the
`Item` real content and the round-trip **fails, destroying five of eight fields.**

### The edge is worse

```
impl edge : (fromNodeId, toNodeId, edgeReason='successor')
theory 230.13 requires e = (T, A, E, C, τ):
   T    edgeReason — a STRING, not a transformation
   A    ABSENT
   E    ABSENT
   C    ABSENT
   τ    ABSENT on the edge (only on nodes)
```

---

## 4. **THE PRINCIPAL FINDING — a category error, and it is mine**

> ### `GovernanceLineageNode` is not a knowledge state. It is a **lineage node**.
>
> It carries identity, integrity and a timestamp — **exactly the three things a provenance-graph node
> needs**, and none of the five things a knowledge state needs.
>
> **The implementation implements `L` (§230.13's typed provenance graph), NOT `K`.**

**This was my error, not the corpus's.** In `BLOCKER-ANALYSIS-AND-EXECUTABLE-KERNEL.md` §8 I mapped the
real node against `K` and concluded *"my own model is refuted."* **The correct conclusion is that I mapped
it against the wrong object.** Recorded as a verifier correction.

**And it is consistent with every other independent result:**

| Result | Method |
|---|---|
| `I* = {Provenance}` — the only invariant in all nine phases | executed, 182 sources |
| `provenance` 29 files, `lineage` 36 files, 47 tests passing | executed |
| `KnowledgeState` / `epistemic` — **0 production files** | executed |
| The real artifact is a **lineage** node, not a state | **this round-trip** |

> **Four independent methods now agree: the corpus's implemented, historically-universal, empirically-tested
> structure is `L`. `K` has no historical universality, no implementation, and no empirical anchor.**

---

## 5. §5 — Where does integrity belong? **Not in `K`.**

Evidence:
- **Implementation:** `integrityHash` sits on the **lineage node**, never on any knowledge object.
- **Corpus, step-073:** `h = H(a)`, `σ = Sign_sk(H(a))`, Merkle `H_root`, and boxed
  *"the architectural requirement is `TamperEvidence` not `Blockchain`"* (073.30).
- **Step 230.13:** lineage edges carry metadata; integrity is not among the five.

> **VERDICT: integrity is a DERIVED commitment over a serialized representation, recorded in LINEAGE.**
> `h = Hash(Serialize(·))` is preferable to a primitive component — the mandate's own suggested form (§5),
> and the evidence supports it.

**This refutes my own proposal to add `integrity` to the `Item`.** It belongs on the lineage node, where the
implementation already puts it.

---

## 6. §10 — Second minimality test (EXECUTED)

| Component | Breaks | Corpus-required? |
|---|---|---|
| `id` | supersession, dedup, replay, merge | **YES** — 218.21, 232 |
| `q` | supersession, contradiction, merge | **YES** — 232.10 |
| `s` | unknown-vs-missing | **YES** — AFR-10 |
| `e` | provenance link | **YES** — `I*` |
| `c` | contradiction, context scoping | **YES** — 230.4/230.9 |
| `t` | supersession, replay, contradiction | **YES** — 185 |
| `u` | uncertainty representation | **YES** — 199.19 |
| `integrity` | tamper evidence | **YES** — 073.30 — **but belongs in lineage, not here (§5)** |

**All eight are necessary for at least one corpus-required capability, so the tuple is not redundant.**
**Necessity is not sufficiency** — and the round-trip shows a real artifact populates **3 of 8**, so the
other five remain **unevidenced by any implementation**.

*(Only `deduplication` is weakly required — implied, never formally stated.)*

---

## 7. §11 — Corrected kernel boundary

Reclassified on the evidence:

| Object | Correct category | Evidence |
|---|---|---|
| `K` knowledge state | **State** — but unevidenced in code | 0 production files |
| `L` lineage | **History** — *implemented and tested* | 65 files, 47 tests |
| `integrity` | **derived function over History** | on the lineage node (§5) |
| `id` | **identity of a History node** | the map key |
| `C`, `E`, `A`, `P` | foundational primitives | dependency analysis |
| `T` | **Transformation** | 8/9 kernels |
| `Validation` | **Assessment**, not transformation | 232.4 |

> **The empirical evidence supports `(L, T, C, E, A, P)` — a HISTORY-centred kernel — better than
> `(K, C, T, E, A)`, a STATE-centred one.** Recorded as **`VERIFIER OBSERVATION`**, not a proposal:
> deciding it requires evidence about whether a knowledge state exists anywhere in the system, which I do
> not have.

---

## 8. §15 — Final decision matrix

| Question | Verdict | Evidence |
|---|---|---|
| Is Knowledge formally defined? | **NO** | never defined in 240 steps |
| Is `K` formally defined? | **NO** | §230.49 admits it; 7 competing candidates |
| Is identity formally defined? | **PARTIAL** | **in code, yes** (`decisionId` = map key); in theory, **no** |
| Is equality formally defined? | **PARTIAL** | 3 relations defined *by me*, executed; corpus: none |
| Is semantic equivalence defined? | **NO** | executed counterexample: re-ingested evidence id |
| Is uncertainty formally defined? | **NO** | 199.19 says a scalar is insufficient; no replacement given |
| Is evidence formally defined? | **PARTIAL** | foundational primitive; `E` double-bound (inside/beside `K`) |
| Is provenance formally defined? | **YES** | 230.12–13 + **implemented + tested** |
| Is lineage formally defined? | **YES** | same; `L = History(T)`, **fails only at `t=0`** |
| Is transition formally defined? | **PARTIAL** | rule well-formed; `⊨`, `Req`, `G` have no algorithms |
| Is governance formally defined? | **PARTIAL** | `G(·) ∈ {0,1}` typed; not computable |
| Is validation formally defined? | **YES** | `𝕂×X → Assessment` (232.4); `Assessment` untyped |
| Is integrity formally defined? | **PARTIAL** | in code yes; ontological placement resolved **here** (§5), not by the corpus |
| Is the model computable? | **YES, UNDER CONDITIONS** | executed kernel; needs a designated status vocab |
| Is it executable? | **YES** | `K₀ → T → K₁` computed |
| Tested against real KnowledgeOS? | **PARTIAL** | 57 tests executed; one structure confirmed |
| Does it round-trip real artifacts? | **YES, VACUOUSLY** | 3/3 pass; **rich item FAILS, 5 fields destroyed** |
| Is the UL stable? | **NO** | 11 status vocabularies, 4 `E`-scales, 0 crosswalks |
| Is minimality proven? | **NO** | necessity shown for 8 components; **sufficiency not shown** |
| Is the theory complete? | **NO** | — |

---

## 9. §14 — Ranked remaining problems

**BLOCKER 1 — what is `K`, and does it exist at all?**
Four independent methods find `L` implemented and `K` absent. **Before defining `K`, the theory must
establish that a knowledge state exists as a distinct object rather than as a projection of lineage.**
*This reframes Blocker 1 from "define `K`" to "justify `K`."*

**BLOCKER 2 — epistemic-status vocabulary.** `UNDERDETERMINED BY CORPUS`; a normative decision. **§16 STOP.**

**GAP** — `Assessment` untyped; `⊨` and `Req` without semantics; the bitemporal↔single-timestamp mismatch.

**DESIGN CHOICE** — state-centred `(K,C,T,E,A)` vs history-centred `(L,T,C,E,A,P)`. **Both mathematically
valid on current evidence.** §16 STOP: *"two candidate definitions remain equally defensible."*

**DOCUMENTATION** — the ~25 registries, the four `E`-scales, the two kernel lineages.

**IMPLEMENTATION GAP** — theory requires `A`, `E`, `C` on lineage edges; the implementation supplies none.

**EMPIRICAL GAP** — `q`, `s`, `e`, `c`, `u` have no implementation evidence whatsoever.

---

## 10. §16 — STOP CONDITIONS TRIGGERED

Three of the seven listed conditions have occurred:

1. **"The proposed Item cannot round-trip a real artifact."** — It round-trips only vacuously; a populated
   item loses five of eight fields.
2. **"Status semantics remain materially contradictory."** — Eleven vocabularies, none designated.
3. **"A real artifact exposes another missing primitive."** — The real node exposes that the theory has **no
   lineage-node type at all**, distinct from a knowledge item.

**And a fourth, from the previous mandate: two candidate kernels (state-centred vs history-centred) are now
equally defensible on the evidence — a normative/architectural decision, not a mathematical one.**

**I am not patching around these.**

### What evidence or derivation is needed next

1. **Does a knowledge state exist in the system?** Search for any production object carrying a proposition
   with an epistemic status. **If none exists, `K` is a theory-only construct and the kernel should be
   history-centred.** *(One grep; I have not run it as a decision, only as inventory — `epistemic` = 0 files
   is suggestive but not conclusive, since the concept could be named otherwise.)*
2. **Designate one status vocabulary.** Normative. Requires the authors.
3. **Introduce a distinct lineage-node type** in the theory, separate from the knowledge item — the
   implementation already has one and the theory does not.

---

**STOPPED per §16. No vocabulary chosen, no kernel selected, no proposal promoted. One verifier error
corrected in public (§4).**
