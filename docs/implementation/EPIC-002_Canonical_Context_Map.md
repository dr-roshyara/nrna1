# EPIC-002 Canonical Context Map

**Kind:** the single authoritative Context Map of the ruled decomposition — authorized by the ARB's Decision Gate ruling (2026-07-25, recorded in `EPIC-002_ARB_Context_Mapping_Strategy_Decision_Request.md` §5). **Authority:** generated; becomes the canonical strategic representation upon ARB acceptance. **State transition (explicit, per ARB refinement):** *Upon ARB acceptance of this Canonical Context Map, the previously approved Candidate Domain Boundaries are ratified as the project's Bounded Contexts for Tactical DDD.* Before that acceptance they remain candidates; the transition happens at acceptance, unmistakably, and nowhere else.
**Ruled decomposition this map represents:** Scenario C (Hybrid) for integrity — custodial AND self-verifying mechanisms coexist, assigned per evidentiary stream — and CB-1/CB-4 kept separate (act-time vs. review-time responsibilities; different reasons to change; recorded reversal condition in the ruling).
**Contents:** contexts, business collaborations, information dependencies, the CB-3-Alt annotation, the Separability constraint, and the hybrid stream-assignment rule. **Deliberately absent (next phase, not yet authorized):** relationship patterns (Partnership/ACL/Shared Kernel/Published Language/Customer-Supplier), APIs, events, aggregates, repositories, services, Tactical DDD.
**Inputs:** the accepted EPIC-002 Strategic DDD artifacts only. No new sources; no new analysis — this map *draws* what the record already states.

---

## 1. The Map

```
                    ┌─────────────────────────────────────────┐
                    │   K2 — SEPARABILITY (cross-cutting      │
                    │   constraint on COL-1, COL-3a/b, COL-4: │
                    │   evidence stays distinguishable from   │
                    │   judgment where feasible; feasibility  │
                    │   bounded by compromise findings)       │
                    └─────────────────────────────────────────┘

 ┌────────────────────┐        COL-2: fixed record            ┌──────────────────────────┐
 │  CONTEMPORANEOUS   │───────────────────────────────────────▶                          │
 │  RECORD-FIXING     │                                       │      ADJUDICATION        │
 │  (was CB-4)        │        COL-4: record as               │      (was CB-3)          │
 │                    │        integrity's object             │                          │
 │  fixes the record  │──────────────┐                        │  resolves contested/     │
 │  at act-time       │              │                        │  incomplete evidence     │
 └────────▲───────────┘              │                        │  into a determination;   │
          │                          ▼                        │  owns D1; never          │
          │ COL-5b (conditional):   ┌────────────────────┐    │  automatic (K1)          │
          │ record-deficiency       │ INTEGRITY (hybrid, │    │                          │
          │ finding                 │ per-stream):       │    │  ┌────────────────────┐  │
          │                         │                    │    │  │ ANNOTATION —       │  │
 ┌────────┴───────────┐             │ CUSTODIAL          │    │  │ authority-validity │  │
 │  COLLECTION &      │  COL-3a/b:  │ INTEGRITY          │───▶│  │ split (CB-3-Alt):  │  │
 │  AGGREGATION       │  integrity  │ (was CB-2)         │    │  │ open internal      │  │
 │  (was CB-1)        │  assurance  │  · custody trail   │    │  │ question, thin     │  │
 │                    │  per stream │                    │    │  │ evidence, non-     │  │
 │  gathers multi-    │────────────▶│ SELF-VERIFYING     │    │  │ blocking           │  │
 │  kind evidence     │             │ INTEGRITY          │    │  └────────────────────┘  │
 │  (P1); subject     │             │ (was CB-2-Alt)     │    └──────────┬───────────────┘
 │  to K4             │             │  · public          │               │
 └────────▲───────────┘             │    verification    │               │
          │                         └────────────────────┘               │
          │                                                              │
          │            COL-1: aggregated evidence body (mandatory)       │
          └──────────────────────────────────────────────────────────────┤
          │                                                              │
          │            COL-5a (conditional): insufficiency finding /     │
          └──────────────────────────────────────────────────────────────┘
                       correction demand (declare-failure)
```

## 2. The Contexts (ratified as Bounded Contexts upon ARB acceptance of this map)

| Context | Was | Purpose | Owns decision | Key constraint |
|---|---|---|---|---|
| **Collection & Aggregation** | CB-1 | Assemble heterogeneous, multi-kind evidence into a usable body | — (none; by evidence, deliberately) | K4 — plurality-in-principle vs. operationalization-in-practice |
| **Contemporaneous Record-Fixing** | CB-4 | Fix the record (or its justification) at act-time, before and independent of review | D2 — is a given retrospective reconstruction a violation or a different artifact? | Act-time responsibility; changes for recording/legal reasons, not acquisition reasons (ruling rationale) |
| **Custodial Integrity** | CB-2 | Maintain an attributable, tamper-evident possession trail for its assigned streams | D3 (custodial streams) | K3 — custody and self-verification are not interchangeable |
| **Self-Verifying Integrity** | CB-2-Alt | Discharge the integrity obligation via public/cryptographic verification for its assigned streams | D3 (self-verifying streams) | K3; plus the documented limit — the stronger "crypto replaces custody entirely" claims were refuted |
| **Adjudication** | CB-3 | Resolve contested/incomplete evidence into an authoritative determination | D1 — automatic vs. discretionary | K1 — never fully automatic; carries the CB-3-Alt annotation |

**The hybrid stream-assignment rule (from the Scenario C ruling):** every evidentiary stream is explicitly assigned to Custodial Integrity, Self-Verifying Integrity, or both (belt-and-suspenders, as real deployments exhibit). No stream may be silently unassigned. *The actual stream assignments are made in later design work, not in this map — the map establishes the rule.*

## 3. Collaborations on the map

| Edge | From → To | What crosses | Status |
|---|---|---|---|
| COL-1 | Collection → Adjudication | Aggregated evidence body | Mandatory; the map's most stable edge |
| COL-2 | Record-Fixing → Adjudication | The contemporaneously-fixed record | Mandatory within P3's (contested) scope |
| COL-3a | Custodial Integrity → Adjudication | Custody attestation for custodial streams | Mandatory per assignment |
| COL-3b | Self-Verifying Integrity → Adjudication | Public-verifiability result for self-verifying streams | Mandatory per assignment |
| COL-4 | Record-Fixing → both Integrity contexts | The fixed record as integrity's object | Mandatory; in self-verifying streams, public commitment at act-time is the fixing (the fusion finding survives as an *edge property*, not a merged boundary — per the keep-separate ruling) |
| COL-5a | Adjudication → Collection | Insufficiency finding / correction demand | Conditional (declare-failure path) |
| COL-5b | Adjudication → Record-Fixing | Record-deficiency finding | Conditional |
| COL-6 | *(annotation only)* | Instrument-validity status | Exists only if the CB-3-Alt split is ever ruled; until then internal to Adjudication |

## 4. What this map explicitly carries as open (unresolved by design, not omission)

- **CB-3-Alt (authority-validity split):** annotation on Adjudication — thin evidence, non-blocking, no separate ruling made.
- **P3's scope:** how far contemporaneity's obligation extends remains genuinely ambiguous in the literature; COL-2's "mandatory within scope" reflects that honestly.
- **P5's tension** is not resolved by the hybrid ruling — it is *housed* by it: the custody-vs-self-verification contest becomes a per-stream assignment question instead of a winner-take-all question. The tension is now an operating decision inside a ruled structure — consistent with the ARB's architectural synthesis of the observed hybrid deployments (the evidence documents such deployments; the choice to structure around them is the board's judgment, per the recorded rationale).
- **Stream assignments themselves:** deferred to later design work, per the rule above.
- **Recorded reversal condition (ruling §5):** Collection/Record-Fixing separateness is revisited only if Record-Fixing proves to have no independent domain policy beyond Collection.

## 5. Traceability

Every context and edge on this map traces to committed record: contexts to `EPIC-002_Bounded_Context_Discovery.md` (as corrected by the family-independence audit) and the Decision Gate ruling; edges to `EPIC-002_Domain_Collaboration_Discovery.md` (COL-1..COL-6); constraints to the Strategic Domain Discovery catalogue (K1-K4); the hybrid rule to the Scenario C ruling; the keep-separate structure to the merger ruling and its recorded rationale. Nothing on this map is new analysis.

---

**Stop condition:** this map is produced under the Decision Gate's YES path. **STOP for ARB acceptance.** Upon acceptance: (1) the five contexts are ratified as Bounded Contexts; (2) the next authorized activity is **Relationship Pattern Selection** over this map's edges. Until acceptance, no relationship patterns, no Tactical DDD, no implementation.

---
*Charter: `EPIC-002_Problem_Statement.md` · Ruling: `EPIC-002_ARB_Context_Mapping_Strategy_Decision_Request.md` §5 · Inputs: the accepted EPIC-002 Strategic DDD artifacts · No new sources consulted.*
