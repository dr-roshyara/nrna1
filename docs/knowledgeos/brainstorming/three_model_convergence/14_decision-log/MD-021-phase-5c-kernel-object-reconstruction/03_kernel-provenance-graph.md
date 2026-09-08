# Phase 5C — Kernel Provenance Graph

## Document → object mapping (the 13-object register's own source documents)

| Document (seq) | Object(s) | Role |
|---|---|---|
| 0080 | KERNEL-OBJ-13 | sole source |
| 0150 | KERNEL-OBJ-04 | origin |
| 0157 | KERNEL-OBJ-04 | falsification |
| 0167 | KERNEL-OBJ-01, KERNEL-OBJ-03 | content origin (same underlying content, two register entries for two distinct aspects: the ADR artifact itself, and the K-1 content it supplies) |
| 0196 | KERNEL-OBJ-01 | label application |
| 0216 | KERNEL-OBJ-01 | rejection/decomposition |
| 0377 | KERNEL-OBJ-05 | origin |
| 0504 | KERNEL-OBJ-05, KERNEL-OBJ-06 | reconnection / origin |
| 0858 | KERNEL-OBJ-09 | sole source |
| 1006 | KERNEL-OBJ-10 | sole source (K-2/K-3/K-6/K-7 not individually resolved) |
| 1008 | KERNEL-OBJ-02 (= KERNEL-OBJ-11) | sole source |
| 2260 | KERNEL-OBJ-07 | sole source |
| 2293 | KERNEL-OBJ-08 | sole source |
| 2330 | KERNEL-OBJ-12 | sole source (already fully verified in Phase 4) |

**This mapping covers 14 of the 116 documents in the Kernel-marker population — the disclosed,
non-exhaustive subset this phase actually resolved to objects, per `01_kernel-population-and-
method.md`'s own scope statement.** The remaining ~102 documents are not individually mapped in this
phase.

## Object → object relationships (evidenced only; `POSSIBLE_RELATIONSHIP`/`UNRESOLVED` where the
## evidence does not rise to a named relationship type)

| From | Relationship | To | Evidence level |
|---|---|---|---|
| KERNEL-OBJ-03 (ADR-KOS-KERNEL-001) | `ORIGINATES` (content) | KERNEL-OBJ-01 (K-1) | Level 1 (raw-source confirmed: "KnowledgeAggregate"/"ConflictRecord" present in 0167's raw text) |
| KERNEL-OBJ-01 | `REJECTS` (literal implementation) | itself, at seq 0216 | Level 1 (raw-source confirmed: "do not implement... literally") |
| KERNEL-OBJ-04 (proposed, 0150) | `FALSIFIES` | KERNEL-OBJ-04's own atomicity claim, at seq 0157 | Level 1 (raw-source confirmed) |
| KERNEL-OBJ-05 | `REFINES`/reconnection | KERNEL-OBJ-06 | Level 3 (governed per-file record; not repeated verbatim in raw prose per Phase-4 spot-check) |
| KERNEL-OBJ-01 | `POSSIBLE_RELATIONSHIP` (homonym candidate) | KERNEL-OBJ-02 | `UNRESOLVED` — Level 4 research interpretation only (structural dissimilarity observed, no connecting document) |
| KERNEL-OBJ-07 | `POSSIBLE_RELATIONSHIP` | KERNEL-OBJ-08 | `UNRESOLVED` — same subdirectory/thread, chronologically adjacent, no explicit connecting statement found |
| KERNEL-OBJ-12 | `POSSIBLE_RELATIONSHIP` (self-declared) | "file 2322/2323's C1 kernel candidates" (not individually resolved to a register object in this phase) | Level 1 for the self-declaration itself; `UNRESOLVED` for whether it was ever carried out |
| KERNEL-OBJ-13 | *(no relationship found)* | any other object in this register | `UNRESOLVED` — no connecting document found |
| KERNEL-OBJ-09 | *(no relationship found)* | any other object in this register | `UNRESOLVED` — no connecting document found |
| KERNEL-OBJ-10/-11 (K-2/K-3/K-6/K-7) | *(not individually resolved)* | any register object | out of this phase's own scope |

## The two directory families — restated per the authorization's own required framing

Per Phase 5B, and restated exactly as the authorization requires (not overstated into
"independence"): **`NO CROSS-FAMILY DERIVATION EVIDENCED IN THE INVESTIGATED RELATION TYPES.`**
Specifically, no `REFINES`/`DERIVES_FROM`/`SUPERSEDES`/`ORIGINATES` edge was found in this phase's own
13-object graph crossing between the `kernel/`-family objects (KERNEL-OBJ-01, -03, -04, -13) and the
`phase_measure_theory/`-family objects (KERNEL-OBJ-02/-11, -05, -06, -07, -08, -09, -10, -12). This
phase additionally searched (per the authorization's §9) for `EXPLICITLY_REFERENCES`,
`REPRODUCES`, `VERIFIES`, `REJECTS`, `EXPLICITLY_DISTINGUISHES_FROM`, and shared-invariant
relationships crossing the two families — **none was found** beyond the two already-recorded
`POSSIBLE_RELATIONSHIP` entries above (KERNEL-OBJ-01↔02, self-declared by KERNEL-OBJ-12 only). This
remains the correct, narrower state: **no cross-family derivation evidenced in the relation types
investigated** — not a claim that the two families are proven independent.
