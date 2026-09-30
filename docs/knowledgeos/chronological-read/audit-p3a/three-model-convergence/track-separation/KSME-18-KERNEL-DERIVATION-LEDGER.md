---
source_track: TRACK-A-PHASE-MEASURE (reuse of prior BC-02.14-18-R1 findings)
input_artifacts: [KSME-18-KERNEL-DERIVATION-INVENTORY]
derived_from: [BC-02.14 through BC-02.18-R1]
cross_track_dependency: none in this ledger (Track-B entries excluded — see KSME-18-KERNEL-EQUIVALENCE-AUDIT.md)
---

# KSME-18 — Kernel Derivation Ledger

Full field detail for the highest-value candidates; compact summary for the rest. Classification applies
the Derivation-First vocabulary (`SOURCE-DERIVED`/`CORPUS-DERIVABLE`/`DERIVED-BY-RECONSTRUCTION`/
`UNDERIVED`/`CONSTRUCTION`/`HYPOTHESIS`) plus the completeness tier (`K-DERIVED-COMPLETE`/`K-DERIVED-
PARTIAL`/`K-DERIVABLE`/`K-HYPOTHESIS`/`K-CONSTRUCTION`).

## `KO-008` — canonical-construction `𝒦=(K,H)`

- **Definition**: `𝒦=(K,H)`, `K=(D_t,𝒜,ℛ,Σ_c,E_L)` (`S1783`/`S1777`, `verification/canonical-construction/`,
  2026-08-30 — MD-043-admitted Track-A cluster).
- **Premises**: a cited `NEEDS` table, each entry traced to a named corpus rule/step.
- **Derivation**: a removal test — each of 14 forced operations checked against the state components.
- **Sufficiency**: `PARTIAL` — necessity of components shown; representation (stored vs. derived for
  `Σ_c`,`E_L`) explicitly undecided.
- **Minimality**: cardinality-minimal *relative to the cited `NEEDS` table*, not proven minimal absolutely.
- **Executable?** No — the removal test consumes the definition, not the state as runtime data.
- **Classification**: `K-DERIVED-PARTIAL`. Derivation tier: `CORPUS-DERIVABLE` (premises present, assembled
  from a cited table, not independently re-verified against primary sources by this ledger).

## `KO-009a` — `(𝒜,ℛ)` / `KO-009b` — ratified 8-primitive `K_t`

- **Definition**: `(𝒜,ℛ)`, `Assertion` expandable to `(id,P,e,c,t,Π)`; ratified `K_t={Entity,State,Event,
  Observation,Proposition,Relation,Policy,Action}` (`step-049`/`C-022`/`FA-4`).
- **Derivation**: `t285_reconcile.py`'s `T-A`–`T-E` test suite — **the one executed, independently
  re-run result in this whole ledger** (KSME-11/13A/14 all independently re-ran this file, byte-identical).
- **Sufficiency**: `π_K:K_t→(𝒜,ℛ)` well-defined, total, but **`T1` proves insufficiency** — `(𝒜,ℛ)` cannot
  carry `Action`/`Event`/`Policy`.
- **Minimality**: not claimed for `(𝒜,ℛ)`; `K_t` (8-primitive) is `RATIFIED` as vocabulary, not proven
  minimal as a carrier (no concrete carrier exists — this investigation's own KSME-11 finding).
- **Executable?** Yes, `t285_reconcile.py`, `EXECUTED`, independently re-verified 3+ times.
- **Classification**: `K-DERIVED-PARTIAL` (the projection `π_K` is `K-DERIVED-COMPLETE` as a *negative*
  result — proven insufficient, which is itself a complete, valid derivation). Derivation tier:
  `SOURCE-DERIVED` (both objects and the negative sufficiency result are directly source-established and
  independently executed).

## `KO-010` — `kernel-reduction` operator set

- **Definition**: `C0⊆Operators`, `|C0|=13`; `Reach:2^{Operators}→2^{Capabilities}`.
- **Sufficiency**: `ESTABLISHED` *within the tested `V0`–`V12` representations* — the one candidate with
  an actual positive, bounded sufficiency result in this whole ledger.
- **Minimality**: cardinality-minimal *within the tested family* — a real, bounded, positive minimality
  result, not absolute.
- **Executable?** Yes, `Reach(S)` fixpoint, `EXECUTED`.
- **Explicit self-disclaimer**: `research/kernel-reduction/README.md`: "not KnowledgeOS architecture...
  do not import from production code" — the ONE candidate in the entire registry with an explicit,
  source-declared non-canonical status.
- **Classification**: `K-DERIVED-COMPLETE` *for its own declared, bounded scope* (25 capabilities, 16
  scenarios) — but that scope is explicitly `DIFFERENT-SCOPE`/task-specific relative to KnowledgeOS's
  historical `K_t` family, not a candidate for the same object. Derivation tier: `SOURCE-DERIVED`.

## `KO-011` — LANE-B `K_t=Γ(E_t,Q,C,EC)`

- **Definition**: real, executable, source-defined `K_t=Γ(E_t,Q,C,EC)`, `research/knowledgeos-sim/kos/`.
- **Sufficiency/minimality**: not a KnowledgeOS-scoped claim — this package's own factivity-impossibility
  theorem (10,000-trial confirmed) shows `Knows→True` and total-attributing `Γ` are jointly
  unsatisfiable — a real, rigorous, **negative** result about this specific formalization.
- **Executable?** Yes — "the ONE fully computable, source-defined K-state mapping found in this entire
  investigation" (`BC-02.15`'s own words).
- **Classification**: `K-DERIVED-COMPLETE` for its own LANE-B-scoped object, **but confirmed `UNWITNESSED`
  to every Track-A K-object** (`BC-02.16`: "independent/coincidental naming reuse of `K_t`, not lineage").
  Never merged into Track-A per the `LANE-B` tag (`KSME-14-ADMISSIBILITY-DECISION.md`).

## `ABK-1` — resolved as NOT ONE OBJECT

- **Definition**: 2 mutually incompatible constructions in the same ~2-hour window — a graph-based
  "Attributed Bipartite Knowledge Representation" and an FDE-bilattice "Annotated Bilattice Kernel" —
  neither citing Rule 258, `K_min^4`, `K_t^11`, `MinKer`, or `KR-REP-REDUCTION`.
- **Provenance**: the cluster's own internal walkback found directly — one document boxes "`ABK-1` is
  unique minimal Kernel... [RATIFIED]", 3 files later self-corrects to "[PROPOSED]... NOT closed as the
  KnowledgeOS Kernel", "Theory v1.3: NOT READY".
- **Classification**: `K-HYPOTHESIS`, explicitly self-downgraded by the corpus's own later material.
  Structurally identical to the `182xxx`/Step-282 overclaim-then-correction pattern already found
  independently multiple times in this investigation.

## Compact summary — remaining candidates

| ID | Classification | Derivation tier | Note |
|---|---|---|---|
| `KO-001`–`KO-006` | `K-HYPOTHESIS` or `K-DERIVED-PARTIAL` (weakest: `KO-005`/`006` `NO CONSUMING COMPUTATION FOUND`) | `UNDERIVED` for full structure | `KO-005` internally CONTRADICTED (canonical vs. hedged-candidate framing within the same source cluster) |
| `KO-007a-f` (S2055's 6 hypotheses) | `K-HYPOTHESIS`, unadjudicated | `UNDERIVED` for 5 of 6; Model C `K-DERIVED-COMPLETE` (as the negative-sufficiency result, folded into `KO-009a` above) | |
| `KO-012` (`𝕂`, Knowledge Space) | `K-HYPOTHESIS` | `UNDERIVED` | Name only, no elaboration |
| `MinKer(c_KOS)` | `K-HYPOTHESIS` | `UNDERIVED` | Blocked on 3 incompatible equivalence relations |
| 13-capability theory | `K-DERIVED-PARTIAL` | `DERIVED-BY-RECONSTRUCTION` | Proven only as a conditional theorem schema |
| `Φ:Π_t→K_t` | `K-HYPOTHESIS` | `UNDERIVED` | Independently rediscovered by KSME-11/12 as `G-109` |

## Missing premises, explicitly recorded, never silently filled

- `K=(A,R,Σ,E_L)`'s attribution to "Step 272" traces one link further back than any located file —
  **`R | missing premise: the actual "Step 272" file, unlocated`** (`BC-02.16`'s own finding).
- `KO-006`'s minimality claim requires a closed operation/observation universe per Rule 258's own
  criterion — **`R | missing premise: 𝒪/𝒯 enumeration against the 8 ratified primitives`** (already the
  central finding of KSME-11/12/13A, now cross-confirmed from this independent BC-02.x lineage).
