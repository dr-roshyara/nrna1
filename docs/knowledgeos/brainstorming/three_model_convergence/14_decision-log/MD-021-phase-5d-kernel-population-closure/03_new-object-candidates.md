# Phase 5D — New Object Candidates (not in the frozen 13-object register)

Every candidate found in the P1 census (§`01`) that proposes a distinct Kernel object not already
covered by Phase 5C's 13 entries. Each carries the 19-attribute taxonomy where evidenced,
`NOT EVIDENCED` elsewhere, and an evidence level. **None of these is promoted to canonical status.
None is merged with an existing object without a specific equivalence test (`04`).**

## NEW-OBJ-01 — K1-K8 (eight named Kernel capacities)

- **Source**: seq 0144, `engineering_knowledgeos`.
- **Representation**: an enumerated list of eight named capacities with individual rationale each,
  plus an explicit Kernel-exclusion list. Not a mathematical tuple.
- **Object type**: enumerated-capacity list (per Phase-4's own object-category taxonomy).
- **Testedness**: `PROPOSED → UNTESTED` at the time of writing (digest text: "still
  unexecuted/unadopted... updates the WATCH MARKER").
- **Note**: already named in Phase 4's own concept register (per this session's prior finding) but
  never given its own Phase-5C object entry — this closes that gap descriptively, without modifying
  Phase 5C.
- **Evidence level**: 2 (machine-observable, digest-based); not raw-source spot-checked this phase.
- **Equivalence to any of the 13**: `UNRESOLVED` (no comparison performed).

## NEW-OBJ-02 — DeepSeek's four Kernel hypotheses (Admission Boundary / Epistemic Decision Core / Knowledge Consistency Boundary / Constitutional Transition Engine)

- **Source**: seq 0150, `engineering_knowledgeos`.
- **Representation**: four named, competing hypotheses, one ("Admission Boundary") marked
  best-supported at the time; no shared tuple.
- **Object type**: governance/philosophical hypothesis set, not a formal tuple.
- **Testedness**: `PROPOSED → UNTESTED` (four-way, mutually exclusive at proposal time).
- **Relationship to KERNEL-OBJ-04**: **distinct** — Phase 5C's register cites seq 0150 as
  KERNEL-OBJ-04's origin, but seq 0150's own content (these four hypotheses) does not describe a
  "six-part aggregate invariant." See the attribution finding in `04`.
- **Evidence level**: 2.

## NEW-OBJ-03 — `K_OS=(A,T,P,E,I,S,X,R)`

- **Source**: seq 0654, `engineering_knowledgeos`, "Step 70."
- **Representation**: an explicit 8-tuple (Artifact, Epistemic Type, Provenance, Event, Invariant, ...
  — full component list per the source's own definition).
- **Object type**: formal mathematical/architectural tuple.
- **Testedness**: `TESTED` — the source itself states this kernel was "confirmed via direct
  primitive-by-primitive comparison" to overlap only 3 of 8 elements with another (unnamed in the
  digest) kernel — i.e., a genuine executed comparison, not a bare proposal.
- **Minimality**: no formal minimality proposition found in the digest excerpt (word "kernel"
  appears; no stated representation/admissible-transformation/comparison-set criterion) —
  `NOT EVIDENCED` at this phase's inspection depth.
- **Evidence level**: **partially raised to 1** — raw-source spot-check (see `09`) confirms the tuple
  notation `(A,T,P,E,I,S,X,R)` appears verbatim in the source (lines 30, 550 of
  `phase_measure_theory/20260828-120623_step-070-the-minimal-knowledgeos-kernel.md`), and the file
  independently walks each of the 8 named primitives (Artifact, Epistemic Type, Provenance, Event,
  Invariant, State, Transformation, Policy). **The specific "confirmed via direct comparison to
  overlap only 3 of 8" claim was NOT located within this file's own 1902 lines** — that framing comes
  from the governed per-file synthesis record, not from raw text directly inspected this phase, and is
  therefore left at evidence level 2 (not upgraded to 1) pending a wider search this phase did not
  perform.
- **Equivalence to any of the 13, or to NEW-OBJ-05/-06**: `UNRESOLVED`.

## NEW-OBJ-04 — Governance ratification event, "GN-31"

- **Source**: seq 0764, `engineering_knowledgeos`, "HPA RULING."
- **Representation**: not a Kernel *object* itself — a formal governance-process event ("THE FINAL
  ARCHITECTURE IS HEREBY RATIFIED — v0.2 Formally Ratified"), naming seven "D-FA" determinations and
  the "open by decision, not by neglect" principle.
- **Object type**: governance artifact / ratification event, not a mathematical or architectural
  object in its own right.
- **Disposition**: `OBJECT_VERIFICATION` (per `01`) — recorded here because it is the **only formal
  ratification event found in the entire P1 population**, and its relationship to any of the 13
  registered objects, or to NEW-OBJ-03/-05/-06 (which cluster in the same seq-range, 0654–0867), is
  **not established** — `UNRESOLVED`, not assumed to ratify any specific tuple.
- **Evidence level**: 2.

## NEW-OBJ-05 — Reduced Candidate Kernel `K=(K,C,T,E,A)` ("Minimal Architectural Kernel")

- **Source**: seq 0856, `engineering_knowledgeos`, "Step 230," explicitly labeled "MAJOR CAPSTONE
  SYNTHESIS."
- **Representation**: an explicit 5-tuple with a stated lineage function `L=History(T)` and a
  "derived-properties table" (`SI=f(K,C,T)`, `BP=f(C,T)`, `EI=f(K,E)`, `GA=f(A,T)`, `TL=f(T_{1:n})`,
  per the digest excerpt).
- **Object type**: formal mathematical/architectural tuple, with an explicit derived-properties
  apparatus — the most formally elaborate candidate found in this census.
- **Minimality — priority case for `05`'s word-vs-claim test**: the source's own title uses "Minimal
  Architectural Kernel" and "Reduced Candidate Kernel" — this is the strongest minimality-claim
  candidate found in P1 outside the already-examined 13. Full word-vs-claim adjudication is deferred
  to `05` (this file only registers the object and flags it as priority).
- **Evidence level**: 2; raw-source spot-check performed (see `09`).
- **Equivalence to any of the 13**: `UNRESOLVED`.

## NEW-OBJ-06 — Heterogeneous five-element kernel `𝔎_5=(G,σ,θ,λ,π)`

- **Source**: seq 0867 (near-duplicate at 0868), `meta_research`/`engineering_knowledgeos`, "Step
  241."
- **Representation**: an explicit 5-tuple, with the source's own "heterogeneous-kernel finding"
  stated as a *structural observation about* this tuple (i.e., the source itself flags the tuple's
  elements as not-all-the-same-kind-of-thing) — this self-flagged heterogeneity is itself evidence
  against a premature equivalence claim with NEW-OBJ-05 or any 5-tuple in the 13-object register.
- **Object type**: formal tuple, explicitly self-diagnosed as structurally heterogeneous.
- **Testedness**: `PROPOSED → UNTESTED` (a "finding" about structure, not an executed equivalence
  test against another kernel).
- **Evidence level**: 2.
- **Equivalence to NEW-OBJ-05 (`K=(K,C,T,E,A)`)**: both are 5-tuples produced in adjacent `seq`
  neighborhoods (0856, 0867) — **exactly the kind of surface similarity (same arity, adjacent
  sequence) this reconstruction's own discipline forbids treating as equivalence without a
  demonstrated mapping.** `UNRESOLVED`, deferred to `04`.

## Documents supporting but not separately registering a new object

- **seq 0377 `S_Kernel=(D,E,S,T,U)`, seq 0378/0380/0392 (refinements)**: a named 5-tuple family:
  flagged in `01` as `OBJECT_SOURCE` but **not** given its own NEW-OBJ number here, because its
  relationship to the already-registered KERNEL-OBJ-01/-02 K-1 family is plausible but unverified —
  recording it as a seventh "new" object without checking whether it is simply an earlier draft of
  K-1 would risk inflating the object count. **Left as an open coverage question for a future phase**,
  named explicitly rather than silently absorbed either way.
- **seq 0636** (field-level type shapes for "the entire Kernel"): an elaboration, not an independent
  object — most plausibly an implementation-level refinement of one of the tuples above (candidates:
  NEW-OBJ-03, NEW-OBJ-05, or the K-1 family), but which one is **not determined** by this phase.
- **seq 2260 `K=(K_t,Ω_K,ℐ)`, seq 2293 `𝔎_t=(...)` nine-component**: both arise from the Gītā-lens
  thread and both overlap territory Model A's own Phase-1 register (four independently proposed
  Kernel schemas, per Phase 3's §F) already covers — **not registered as new C1/C2-family objects
  here**, since their primary lineage is the Gītā-lens thread, and re-litigating Model A's own
  register is out of Phase 5D's scope. Flagged in `08` as a classification/homonym note only.

## Summary

**6 new object candidates registered** (NEW-OBJ-01 through -06), **1 attribution-precision finding**
(KERNEL-OBJ-04), **1 open coverage question** (`S_Kernel=(D,E,S,T,U)`'s relationship to K-1),
**0 candidates promoted to canonical or merged with an existing object.**
