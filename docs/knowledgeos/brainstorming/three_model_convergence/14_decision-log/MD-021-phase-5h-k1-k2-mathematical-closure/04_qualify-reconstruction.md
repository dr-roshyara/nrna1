# Phase 5H — `Qualify` Reconstruction

## Two independent primary sources, triangulated (neither previously read in Phase 5F/5G)

**Source 1 — seq 0630 §49.76 (`step-049`)**: a defining equation, `Evidence = QualifiedObservation`.
No algorithm; a naming/typing statement only.

**Source 2 — seq 0795 §170.4 (`step-170`, "The End-to-End KnowledgeOS Proof Chain")**: the transition
`O → E` (Observation → Evidence) is gated by:

$$g_1: CaptureAndQualify(O)$$

with named "potential conditions": *source identified · timestamp available · integrity preserved ·
context known · provenance recorded*. The same document gives explicit formal tuples:

$$O = \langle source, time, value, context \rangle \qquad E = \langle O, provenance, integrity,
classification, scope \rangle \qquad E \supset O \text{ (information-structurally)}$$

and an explicit warning: *"Not every observation should become evidence... `Reported ≠ Verified`."*

## Reconstructed specification

| Field | Value | Evidence level |
|---|---|---|
| Name | `Qualify` (D285-6) / `CaptureAndQualify` (seq 0795) — **two slightly different names for what
  this phase treats as the same conceptual function**, given both share input type (Observation) and
  output type (Evidence) | 1 for each naming, 3 for treating them as the same function (an inference, disclosed) |
| Type signature | $Qualify: Observation \rightarrow Evidence$ | 1, triangulated across 2 independent documents |
| Domain | Observation, $O = \langle source,time,value,context\rangle$ | 1 (seq 0795) |
| Codomain | Evidence, $E = \langle O,provenance,integrity,classification,scope\rangle$ | 1 (seq 0795) |
| Preconditions | Named as a checklist only (5 items, seq 0795) — **not formalized as a logical predicate** | 1 for the checklist's existence; 2 for treating it as a formal precondition set |
| Postconditions | `NOT EVIDENCED` | — |
| Algorithm/body | **NOT EVIDENCED anywhere** — confirmed by D285-6's own explicit statement ("`Qualify` has no body in the corpus, one undefined hit, Step 170") and independently re-confirmed by this phase's own reading of seq 0795 itself, which supplies only the checklist, not a computable procedure | 1 (the absence is itself directly stated by the corpus) |
| Relation to Observation | Domain | 1 |
| Relation to Evidence | Codomain | 1 |
| Relation to Proposition | `NOT EVIDENCED` — D285-6's own unpacking places `Proposition` and `Observation` as siblings inside `Assertion`, not as inputs/outputs of `Qualify` itself | — |
| Relation to Policy | D285-6's own type signature for the K-1→K-2 projection cites `Qualify: Observation × Policy → Evidence` (a 2-argument version) — **this differs from seq 0795's own 1-argument `CaptureAndQualify(O)`** | Disclosed as a further, third variance (beyond the Assertion-unpacking one, `07`) — **not reconciled here** |
| Normative or hypothetical | **Hypothetical/conceptual** — seq 0795 §170.6 itself frames qualification as a quality bar ("becomes stronger evidence only after appropriate qualification"), not a ratified, governance-approved function | 2 |

## Computability status (per the authorization's §7 — do not equate "not implemented" with "not computable")

Testing the required five-way distinction:

1. Formally defined and computable — **NO**.
2. Formally defined but not implemented — **NO** (this would require a complete formal definition,
   e.g. a precise predicate/procedure, which does not exist — only a named checklist).
3. Partially defined — **YES, this is the correct classification** — the type signature, domain, and
   codomain are formally stated; the checklist names *categories* of preconditions without formalizing
   them as predicates; no procedure body exists.
4. Only conceptually described — **partially overlaps with (3)**; the distinction matters less here
   since even the "partial" formalization (typed signature) is genuine, not merely conceptual.
5. Undefined — **NO** (too strong; a type signature is a real, if partial, definition).

**Verdict: `Qualify` is partially defined (type-level) and has no algorithmic body — this is
architecturally distinct from "defined but not implemented."** A "defined but not implemented"
function could, in principle, be implemented immediately given its specification; `Qualify` cannot,
because its own preconditions are named as a checklist, not as a formal predicate a program could
evaluate. **This is a sharper, more precise statement than Phase 5F/5G's own "definable, not
computable," which did not distinguish these cases.**

## Verdict

**RECONSTRUCTABLE at the type-signature level, well-evidenced and triangulated across two independent
documents (a meaningful strengthening of Phase 5F/5G's own treatment, which cited only D285-6's own
brief mention).** **NOT EVIDENCED at the algorithmic level, confirmed rather than merely unfound** —
this is a genuine SOURCE-RESEARCH GAP (`11`), not a reconstruction failure. A **third inconsistency**
is disclosed (D285-6's 2-argument signature vs. seq 0795's 1-argument signature) — not resolved, named
explicitly as an open question (`13`).
