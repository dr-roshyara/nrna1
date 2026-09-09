# MD-052 Phase A — Provenance and Boundary

## Population, chronology, git history

`git log --diff-filter=A -- docs/knowledgeos/brainstorming/verification/spec/` → **one commit only**:
`70fee73c8`, 2026-09-06 08:02, the same bulk-import commit already characterized by MD-042/MD-043 as
the largest commit in this repository's history. Its own message: *"multiple, competing and
superseded formulations coexist deliberately, and nothing here is canonical"* — names `verification/`
generically ("gap-discovery, the readiness matrices, the multiplicity register and the conflict
records") without specifically calling out `spec/`. This is not, by itself, an inclusion or exclusion
signal — the message operates at a coarser resolution than this subdirectory.

File mtimes (`ls -la --time-style=full-iso`) are genuine and pre-date the commit by a week: the
`spec/` cluster spans 2026-08-29 14:36 through 2026-08-30 09:27. **The wider `verification/`
directory contains material running later still** — `THRESHOLD-AUDIT-subdirs-and-loose-files.md` and
`STEP-VERIFY-*` files run through 2026-08-30 09:27, and `00-INDEX.md`'s own artifact table names
several files (`V2-dependency-graph.md`, `V3-verification-priority-matrix.md`,
`G-theory-gap-register.md`, `reports/`) not yet located or opened. **Population is therefore
established only for the specific 9 files read (K0, A4, A5, A7, A8, A9, A10, AM, 00-INDEX), not for
the whole `spec/` directory or the whole `verification/` tree.**

## Authorship / provenance claims — checked, not assumed

`00-INDEX.md` self-identifies as run by **"VERIFY SESSION,"** citing a numbered mandate/directive
series (mandate 1459, directive 1514, 1518, 1549 — none of which appear anywhere in this
reconstruction's own `00_control/protocol.md` or MD-021 apparatus). This is a **named, self-described
programme**, not an anonymous artifact — but its own organizational standing (who "VERIFY SESSION"
is, what authority the cited mandates carry) is **not established in this corpus** by anything read
this phase, exactly the same "LEGITIMATE AUTHORITY NOT ESTABLISHED" posture this reconstruction
already holds for the K-1/K-2 governance track (MD-022).

## The five distinctions, kept un-conflated (per explicit instruction)

| Notion | Status for this cluster |
|---|---|
| Previously unseen by this reconstruction | **TRUE** — confirmed; no prior MD in this window opened `verification/spec/`. |
| Separate research programme | **TRUE, self-declared** — "VERIFY SESSION" names itself distinctly from `three_model_convergence`'s own protocol.md apparatus, with its own mandate numbering. |
| Separate provenance lineage | **FALSE, or at minimum NOT ESTABLISHED** — see the finding below: K0 directly consumes and critiques a file (`step-049`) from `phase_measure_theory/`, the *same* raw "step" track this reconstruction's own F1 (frozen K-1) is built from. The two are not lineage-independent. |
| Independent research | **NOT SUPPORTED BY THE EVIDENCE FOUND** — see below. |
| Independent replication | **NOT APPLICABLE / NOT ESTABLISHED** — K0 does not claim to replicate a prior result under separate methodology from scratch; it explicitly builds on and critiques a specific named predecessor artifact one day its senior. |

## The central provenance finding: K0's own "049 8-primitive set" is step-049, and step-049 is F1's own object

A targeted, bounded search (not an open-ended sweep) located
`docs/knowledgeos/brainstorming/phase_measure_theory/20260828-104146_step-049-formal-model-reduction-
consistency-checking-primitive-identification-and-computability.md` (dated 2026-08-28, **one day
before K0**). Its own §49.75, "Candidate primitive set," reads verbatim:

> "I would currently freeze the following as the **candidate mathematical kernel**:
> `𝒫 = {Entity, State, Event, Observation, Proposition, Relation, Policy, Action}`."

This is an **exact, ordered match** to K0's own §1 citation of "049 8-primitive set
{Entity,State,Event,Observation,Proposition,Relation,Policy,Action}." **`SOURCE-STATED`, confirmed
by direct quotation of both documents.**

Separately, this reconstruction's own frozen record (MD-021 Phase 5N, `model-boundary-decisions.md`)
already establishes: *"the M₄₉/K_t/K-1 identity"* was reconstructed as *"an explicit, disclosed
inference (E3/E4)"* — and D-FA-4 (seq 0764) *"explicitly classifies the 8-primitive kernel candidate
('M₄₉') as an 'L2 candidate'... 'Membership at the object level remains open (OQ-2).'"* The label
**"M₄₉" is, on the evidence available, the same object as "step-049"** — the numbering convention
matches exactly, and no competing referent for "M₄₉" was found in the frozen record. **This is a
`FORMALLY-DERIVED` (from this reconstruction's own Phase 5N text plus the newly-located step-049 file
— not from K0 itself) finding, not a `SOURCE-STATED` one**: no single document read this phase states
"M₄₉ = step-049" in those words; the identification rests on matching the numbering pattern and the
exact primitive-set content, both independently verifiable, but the equation itself is this phase's
own inference.

**Consequence, stated carefully**: if "049 8-primitive set" (K0) and "M₄₉" (Phase 5N / F1) are the
same object — which the evidence above supports but does not, by itself, formally prove beyond the
numbering/content match — then **K0's critique of the 049-tuple is a critique of the same candidate
object this reconstruction's own F1 already carries, independently arrived at as N2/"L2
candidate"/OQ-2-open**. This does *not* mean K0 and F1's own governance track are the same
*programme* — they plainly are not (different directories, different mandate apparatus, different
methodology: formal/deductive proof vs. governance-ratification archaeology) — but it does mean they
are **not lineage-independent at the object level**: both consume the same upstream artifact
(`step-049`, `phase_measure_theory/`).

## What this means for "independence"

K0's own exclusion of the 049-tuple ("most of its members do no deductive work") reads, on this
evidence, as **a next-day, cross-track critique of a specific named predecessor's proposal**, not as
an independently-arrived-at negative result reached without knowledge of step-049. This is squarely
the case the user's own distinction was designed to catch: **previously unseen by this
reconstruction ≠ independent research.** K0 is real, is substantive, and is not previously seen — but
it is not evidence of two unrelated research efforts converging on the same negative finding; it is
one finding building directly on, and revising, another, one day apart, inside a related step-numbered
lineage.

## Relationship to the MD-043 unresolved zone

Confirmed: `verification/spec/` sits inside the ~445-file zone MD-043 left explicitly
PLAUSIBLE/UNRESOLVED (only the `step-272/280/281/282/handoff/witnesses/canonical-construction/
consolidation` cluster was resolved there, on the strength of a *different* shared-commit/filename
match than the one found here). **This phase's own finding neither resolves nor narrows that larger
zone** — it establishes the provenance of nine specific files, not of `verification/` as a whole.
