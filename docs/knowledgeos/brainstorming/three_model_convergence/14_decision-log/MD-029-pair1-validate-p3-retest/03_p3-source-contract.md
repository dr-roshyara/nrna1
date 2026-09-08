# C1 P-3 — Exact Source-Stated Contract (re-verified directly against seq 0157)

## Correction to this reconstruction's own prior characterization (recorded, not edited into frozen text)

MD-023/024 and Phase 4's own concept register described P-3's test as *"a many-to-many evidence-
sharing stress test."* **Direct re-reading of seq 0157 in full finds no such scenario anywhere in the
document.** The actual method: a **pairwise transactional-atomicity argument** — each of the six
proposed properties (Identity, Evidence References, Justification, Epistemic State, Confidence,
History) tested pairwise against every other, asking whether semantic relatedness/traceability implies
transactional co-location necessity. **Every pair returns "WEAK" or "MODERATE" atomicity requirement,
never "STRONG"** — the falsification conclusion (*"the six parts do NOT require one aggregate"*) is
verified, directly, from source; only the earlier method-description is corrected here.

## P-3's own final classification table (seq 0157 §12, quoted directly)

| Concept | Classification | Owner |
|---|---|---|
| Identity | ENTITY (Core) | Kernel |
| Claim Content | ENTITY (Core) | Kernel |
| Evidence References | RELATIONSHIP (Core) | Kernel |
| Justification History | RELATIONSHIP (Core) | Kernel |
| Epistemic State | DERIVED VALUE OBJECT | Projection |
| **Confidence** | **ASSESSMENT** | **Governance Context / Kernel Assignment** |
| Full Event History | EVENT STREAM | Audit Context |
| Evidence Content | ENTITY | Evidence Context |
| Evidence Validity | ASSESSMENT | Evidence Context / Governance |
| Evidence Provenance | RELATIONSHIP | Evidence Context |
| Justification Reasoning | RELATIONSHIP | Interpretation Context |

## The replacement hypothesis (explicitly still HYPOTHESIS, not established, per seq 0157's own §13)

*"The smallest consistency boundary is: Identity + Evidence References + Justification History."*
Epistemic State, Confidence, full History, Evidence Content, and Justification Reasoning are all
explicitly stated as **not requiring co-location** with this core.

## Admissible transitions / lifecycle (seq 0157 §8)

`SUPERSEDED`, `CONTESTED`, `RECONCILED`, `WITHDRAWN`, `INSUFFICIENT_EVIDENCE` — each classified as a
derived state (+ relationship, for most; `WITHDRAWN` alone has no relationship component).

## What P-3 does NOT specify (per seq 0157's own §14, "STOP — EVIDENCE INSUFFICIENT")

Content mutability (amendment vs. new claim), candidate structure, constitutional rule structure,
evidence-sufficiency evaluation, confidence-assignment mechanism, contradiction detection, and the
Kernel↔Constitution/Kernel↔Aggregate relationships are **all explicitly left open by the source
itself** — seq 0157's own final section states outright: *"Manufacturing answers would be premature."*
