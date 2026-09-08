# Phase 5F — K-1 Primary-Source Reconstruction

## The three-document chain (all raw-source read in full this phase)

### 1. seq 0630 — `phase_measure_theory/20260828-104146_step-049-formal-model-reduction-...md` (the true origin)

This document (not itself inside the `knowledgeos_kernel/` subdirectory — it sits one level up in
`phase_measure_theory/`, confirming D285-1's own citation "step-049" points outside the P2 population
Phase 5E censused) performs an explicit, step-by-step reduction. An **earlier, narrower** "Core
primitives" distinction appears at §49.2 (`Entity, State, Event, Observation` — 4 items) alongside five
other named structure-families (Epistemic/Semantic/Relational/Governance/Operational/Evolution). By
§49.29 ("We therefore have a much smaller foundation"), the document arrives at its **final, 8-item
boxed result**:

> `Entity + State + Event + Observation + Proposition + Relation + Policy + Action.`

§49.30 gives the **formal mathematical kernel**:

$$\mathcal{K} = (E, S, T, O, P, R, \Pi, A)$$

with $E$=entities, $S$=states, $T$=temporal/event structure, $O$=observations, $P$=propositions,
$R$=typed relations, $\Pi$=policies, $A$=actions. §49.31 supplies **explicit derived-structure
definitions** (not present in either D285-1 or D285-7, and not previously captured by this
reconstruction at this level of formal detail):

$$Evidence \subseteq O \times Context \qquad Claim \subseteq P \qquad Prov \subseteq R \qquad
Cause \subseteq R \qquad Identity: E \rightarrow ID \qquad L: K_t \rightarrow K_{t+1} \qquad
D: (K,\Pi) \rightarrow A$$

§49.32–49.34 discuss computability (finite representability) and a graph representation
(`Entity —hasState→ State`, `—observedBy→ Observation`, `—participates→ Event → Proposition →
{supports,causes,contradicts} → Decision`).

**Note on the earlier §49.2 "Core primitives" (4 items) vs. the final §49.29 result (8 items)**: this
is **not a contradiction** — it is the document's own visible reasoning process (a step-numbered
derivation, consistent with this corpus's habitual practice of showing intermediate positions before a
final one). The 8-item list is the document's own **final, boxed** conclusion; the 4-item list is an
earlier working distinction subsumed by it. Recorded explicitly so a future reader does not mistake
the 4-item list for a rival, unreconciled formulation.

### 2. seq 1006 — D285-1 (already read in full, Phase 5E; restated here as K-1's authority record)

Cites step-049 and "C-022 in `claim-registry`" as K-1's source; records authority as **RATIFIED — FA-4
D-FA-6 "qualified naming"; "50 attack classes, no counterexample," COMPUTATIONALLY TESTED**.
`claim-registry` itself was not found as a standalone file in the `phase_measure_theory/` or `kernel/`
scope searched this phase — it is either a distributed/abstract registry concept or sits outside the
searched scope; **this is disclosed as an open provenance gap, not silently filled**.

### 3. seq 1008 — D285-7 (already read in full, Phase 5E; restated here)

Reuses the identical label and primitive list ("K-1 `K_t`, 8 primitives (ratified)") without claiming
any transformation from D285-1's own K-1 — the two documents are sequential parts (D285-1, D285-7) of
one HPA-mandated package, dated the same day (2026-08-31), using the same template ("7-section D285-x
template (HPA review, 2026-08-31)").

## K-1's reconstructed attribute set (19-attribute taxonomy, `NOT EVIDENCED` where warranted)

| Attribute | Value | Evidence level |
|---|---|---|
| Representation | 8-tuple `𝒦=(E,S,T,O,P,R,Π,A)` | 1 (seq 0630 §49.30) |
| State space | Not itself a single named carrier; `E`,`S`,`T`,`O`,`P`,`R` jointly constitute it | 1 |
| Elements | Entity, State, Event, Observation, Proposition, Relation, Policy, Action | 1 |
| Operations | `NOT EVIDENCED` at the primitive level in any of the three documents — D285-7 explicitly states "`𝒪` never enumerated against the ratified 8 primitives... new finding, and the real reason `𝒪_core` cannot close" | 1 (an explicit absence-finding, not a gap in this reconstruction's own search) |
| Invariants | `I-1…I-12`, "ratified" (D285-7); content of I-1..I-12 not itself read this phase | 2 |
| Constraints | `NOT EVIDENCED` beyond the invariant reference above | — |
| Domain/codomain | `NOT EVIDENCED` for the primitives as a whole; derived-structure functions (§49.31) do specify domain/codomain (`Identity: E→ID`, `L: K_t→K_{t+1}`, `D: (K,Π)→A`) | 1 for the derived functions |
| Admissibility | `NOT EVIDENCED` | — |
| Equivalence relation | `NOT EVIDENCED` for K-1 itself; D285-6 supplies one for the K-1↔K-2 projection (see `05`) | — |
| Representation variants | None claimed within this chain | — |
| Testedness | **RATIFIED, computationally tested** ("50 attack classes, no counterexample") | 1 |
| Minimality claim | Not claimed as minimal in any of the three documents; D285-1/D285-7 discuss minimality only for K-2/K-6, not K-1 | 1 (absence confirmed) |
| Authority | FA-4, D-FA-6, C-022/`claim-registry`, step-049 | 1 |
| Source seq | 0630 (origin), 1006 (ratification record), 1008 (consequence trace) | 1 |
| Provenance chain | 0630 → 1006 → 1008, same package, same day | 1 |
| Object type | Formal mathematical tuple, per the six-level ladder's own object-typing discipline | 1 |
| Relationship to other objects | See `03`/`05`/`09` | — |
| Context | `phase_measure_theory/knowledgeos_kernel/` D285-x research package | 1 |
| Status | LIVE — currently the only member of the K-1..K-7 family carrying `RATIFIED` status | 1 |

## What this reconstruction does NOT do with this evidence

Does not treat "computationally tested" and "ratified" as evidence of semantic identity with any
other object (K-2, K-1-A, or any Model-A/B kernel candidate) — those are separate claims, tested
separately in `03`/`05`/`09`.
