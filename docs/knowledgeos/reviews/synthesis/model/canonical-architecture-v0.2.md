# Canonical Architecture — KnowledgeOS Synthesis Model **v0.2**

> ✅ **STATUS: AUTHORIZED (HPA RULING — GN-19, 2026-08-28).** The HPA explicitly ruled:
> **ACCEPT F-1 · ACCEPT F-2 WITH SPECIFIC RESOLUTION · ACCEPT F-3 · ACCEPT F-4** — authorizing
> application of **exactly R-1…R-4** to v0.1 and nothing else. This document is now the
> **current authorized canonical model**, superseding v0.1 (retained unmodified as the
> pre-falsification record). Lifecycle note: this file was first written as a PREPARED-UNRATIFIED
> draft ahead of the ruling (a recorded governance error — see governance-notes, GN-19 history);
> it acquired authority only with this ruling. Per the ruling: **3C is NOT authorized by GN-19;
> Brainstorming Archaeology, Book Architecture and Book remain locked.**

**Derivation:** v0.1 (`canonical-architecture.md`) + the four repairs **ruled ACCEPT under GN-19
(HPA, 2026-08-28)** — nothing else changed; the ruling authorizes exactly R-1…R-4. Full change ledger in §8. Status per GN-10 unchanged: *architecturally coherent and
substantially evidence-supported; formal synthesis and implementation conformance remain to be
established.* Working thesis per GN-11 unchanged.

---

## 1 · Canonical concepts — v0.1 table carried forward, with four **ruled** amendments (GN-19)

All 18 v0.1 concepts stand as written, except:

**AcceptancePolicy** *(amended by R-1)* — determines what may be admitted as accepted knowledge
(≠ what evidence supports) — **and is itself a governed, versioned object**: any change to an
in-force policy is a *decision* routed through `DC + BC_Governance`. Grade: policy content READ (008);
its governance REQUIRED-BY-COHERENCE with corpus-adjacent support (121.47 versioning).

**Zero / Z_t** *(amended by R-2)* — canonical signature **`Zero(K, EC)`** where `EC = η(G, IdealState)`
is the total derivation of requirements from the Knower-owned goal and sufficiency threshold.
*Historical signature* `Zero(K,G,EC)` (025d) retained in lineage; see R-2 for the resolution basis.

**Epistemic statuses** *(amended by R-3)* — the **epistemic ladder is three-valued**:
`Candidate → Supported → Accepted`. **`Committed` is a decision-boundary status** attached to an
Accepted item by authority. A6 (⟦C⟧ *"authority determines commitment, not evidential truth"*) is now
the **explicit boundary-crossing rule**, not an intra-ladder step.

**Status transitions** *(amended by R-4)* — the ladder is a **covering relation**: each status is
reachable only from its immediate predecessor (`Candidate⋖Supported⋖Accepted`, and
`Accepted ⋖ Committed` across the boundary under A6). Skipping is formally excluded.

## 2 · Non-collapse distinctions — unchanged (seven + the 042 triple)

## 3 · Canonical flow — one wiring change (R-1)

As v0.1, plus the stratification loop:

```
  PolicyChangeProposal ──► DECISION under DC ──► BC_Governance approval ──► Policy vN → vN+1
        (a policy is knowledge content AT REST inside K_t;
         it GOVERNS admission only in its approved, versioned, in-force form)
```

⟦INT⟧ This severs F-1's self-reference: `Policy-as-content ∈ K_t` (admissible like any claim) is
distinguished from `Policy-in-force (versioned)` (the governor of admission). Self-modification of
the in-force policy without governed approval is excluded by construction.

## 4 · Canonical invariants — v0.1's I-1…I-10 unchanged, plus:

| # | Invariant | Grade |
|---|---|---|
| **I-11** *(new, R-1)* | **No in-force policy — AcceptancePolicy and constitution included — changes without a governed, versioned approval decision** | REQUIRED-BY-COHERENCE; corpus-adjacent (042 governance invariant + 121.47) |
| **I-12** *(new, R-4)* | **Status transitions follow the covering relation; no skipping** | REQUIRED-BY-COHERENCE (one axiom) |

I-1's 3B-identified hole (unowned AcceptancePolicy) is **closed by I-11**: Knower ownership now
reaches Determination through the governed policy chain.

## 5–7 · Mathematical objects, DDD candidates, non-claims — as v0.1, with:

- `Zero(K,EC)` replaces `Zero(K,G,EC)` in the object inventory (lineage note retained).
- G's **only** direct consumer in the canonical model is now **Proposal** (`025g`, evidence-derived);
  all requirement content reaches Zero through `EC = η(G, IdealState)`.
- Non-claims list gains: *the η-totality assumption is a synthesis decision, revisitable during 3C / Brainstorming
  Archaeology if evidence establishes a genuine residual role for G inside Zero (per the GN-19 ruling).*

---

## 8 · CHANGE LEDGER v0.1 → v0.2 — **authority: GN-19 (HPA ruling, 2026-08-28)**

| # | Finding | HPA ruling (GN-19, 2026-08-28) | Change applied | Epistemic grade of the change |
|---|---|---|---|---|
| **R-1** | F-1 self-referential admission | **ACCEPTED** (governed/versioned, **not** ad-hoc Knower predicate) | policy-as-content vs policy-in-force split; §3 stratification loop; **I-11** | REQUIRED-BY-COHERENCE, corpus-adjacent (⟦C⟧ 121.47 *"the constitution therefore needs Version"*; 042 `ProductionChange → RequiredApproval`) |
| **R-2** | F-2 G-residual in `Zero(K,G,EC)` | **ACCEPTED WITH SPECIFIC RESOLUTION** (ruling adopts `Zero(K, EC)`, `EC = η(G, IdealState)`; historical `Zero(K,G,EC)` retained as lineage) | **Resolution chosen: simplify to `Zero(K, EC)`, `EC = η(G, IdealState)` total.** Evidence basis: ⟦C⟧ 025d *"Zero cannot be computed from the goal alone"* gives EC the requirement content; **no corpus evidence establishes any G-residual inside Zero**; G's evidenced direct consumer is Proposal (025g). Declaring a residual would have been invention; simplification is the evidence-conservative resolution | SYNTHESIS DECISION (REQUIRED-BY-COHERENCE); per the ruling, the η-totality assumption is **revisitable during 3C / Brainstorming Archaeology** if evidence establishes a genuine residual role for G inside Zero |
| **R-3** | F-3 `Committed` boundary-typing | **ACCEPTED** | epistemic ladder = 3 statuses; `Committed` re-typed decision-boundary; A6 = explicit crossing rule | REQUIRED-BY-COHERENCE; conservative w.r.t. 008 (A6 already stated the difference) |
| **R-4** | F-4 no-skip axiom | **ACCEPTED** | covering-relation axiom; **I-12** | REQUIRED-BY-COHERENCE (one axiom) |

**Nothing else was modified.** The ruling authorizes exactly R-1…R-4; no other architectural change
is authorized by it. v0.1 retained unedited (banner only) as the pre-falsification record. Per GN-14
discipline: every change above carries its grade; none is presented as corpus-stated.

**Next gate (GN-20):** 3C repository conformance — **a separate gate, NOT authorized by GN-19** —
then Brainstorming Archaeology → Final Architecture → Book Architecture → Book (all locked).
