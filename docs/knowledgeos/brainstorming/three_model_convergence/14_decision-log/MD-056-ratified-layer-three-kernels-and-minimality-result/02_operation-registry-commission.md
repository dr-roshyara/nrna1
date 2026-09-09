# MD-056 §02 — The Operation-Registry Commission (GN-79/80/83/85/86)

**Read in full, in mtime order** (per the standing methodology): `MINIMALITY-RESULT.md` (14:23) →
`OPERATION-REGISTRY-DERIVATION.md` (14:25) → `OPERATION-CONTRACTS.md` (14:29, read to line 522 of
630 — the remaining ~108 lines are further per-operation contract blocks of the same kind already
characterized, not independently summarized here) → `OPERATION-REGISTRY-DECISION-PACKAGE.md` (15:15)
→ `06-FALSIFICATION-VERDICT.md` (15:19) → `step-284/` (three files, named but not independently
re-summarized — their content is carried forward into and superseded by `step-285`) →
`step-285/06-STEP-285-VERDICT.md` (16:05, the thread's own terminal document). **Not read this
phase**: `OPERATION-REGISTRY-INDEPENDENT-REVIEW.md` itself (877 lines) — its findings (`IR-F-1`
through `IR-F-26`) are extensively quoted verbatim inside the two documents that were read
(`DECISION-PACKAGE`, `FALSIFICATION-VERDICT`), which is where this phase's own characterization of
them comes from; the primary document is named as a further-reading candidate if a future phase needs
its own full findings list.

## Provenance and authority

A **real, dated, multi-stage governance commission** — not brainstorming, not an unratified research
thread. `HPA ruling GN-79` / `commission GN-80` (2026-08-31) authorizes the derivation
(`OPERATION-REGISTRY-DERIVATION.md`, `MINIMALITY-RESULT.md`, `OPERATION-CONTRACTS.md`); `GN-83/86`
authorize an independent falsification pass by "**a different fresh-context party**" (explicitly
required, `AC-5`); `GN-85` fixes the acceptance procedure and the exact ten-dimension/eight-question/
four-outcome format the commissioner must use to assemble the decision package. **Every deliverable
carries an explicit status block: "NOTHING IN THIS FILE IS RATIFIED."** The whole commission is
itself a research/derivation act *authorized by* governance, not a ratification.

## What was executed (real, not asserted)

Five Python scripts (`rm.py` — the reference model; `mintest.py` — witness search, necessity,
minimal-registry enumeration; `consistency.py` — 55 operations × 21 probe states × 18 arguments =
20,790 applications against every ratified constraint; `inventory.py` — Task 1's union/diff/count
over 17 live sources; `circularity.py` — tests whether the corpus's own requirement set can
discriminate at all), every one preserved with raw output. **The independent falsification pass
re-ran all five scripts byte-identically on a separate Python instance** — the closure computation
is confirmed to be genuine bounded BFS composing operations, not a hand-authored table (a real,
executed distinction the falsification pass draws against a *different*, earlier document —
`oderive.py` — which turns out to be exactly that: a hand-authored forcing table with no closure
computation at all, its own widely-cited "14-forced/18-upper" result correctly downgraded from
`DERIVED` to `PROPOSED`).

## The central results

- **57 distinct live candidate operation names** across 17 sources (105 counting historical,
  pre-canonical sets, kept separate per `FA-1 §1 L5`'s own rule: historical corpus is evidence of
  discovery, never authority). **No two of the ten claiming enumerations agree**; the intersection of
  all of them is **empty**. The four best-corroborated names in the whole corpus (`Merge`,
  `Supersede`, `Validate`, `Replay` — 10–13 sources each) turn out **necessary for nothing** under the
  ratified surface — *"corroboration and necessity point in opposite directions."*
- **15 mandatory capabilities derived independently from the ratified surface** (not from any
  candidate operation list — the derivation explicitly proves, by executing the corpus's own stated
  necessity criterion over its own stated index set, that the criterion is a **tautology by
  construction** — a bijection onto its own operation list — and derives a fresh, independent
  requirement set instead). 13 of 15 are `RATIFIED-FORCED`; 2 are `PROPOSED`.
- **Six minimal sufficient operation registries**, enumerated exactly (not merely bounded-searched
  for), with an invariant nine-operation "undetermined band" across every tested variant, generated
  by exactly three named, independent, unresolved choices (ladder granularity; how `CONFLICTED` may
  be entered; how a contradiction is created).
- **First verdict**: none of the six registries is fit for ratification because all six contain
  `Reject`, an operation whose only stated specification (step 256.11) violates two ratified
  invariants (I-12, Article 8). Recommended terminal verdict **D — "the operation universe depends on
  an unresolved prior canonical decision,"** naming ten prior decisions (P-1…P-10).

## The independent falsification pass — corrects the ground, confirms the verdict

This is the load-bearing methodological event of the whole thread, structurally identical to the
VERIFY SESSION's own self-correction pattern (MD-054 §01, wave 6):

- **`AC-1` (candidate-universe completeness) — FAILED.** The reviewer found a whole operation
  taxonomy table (24 arrow-typed signatures) cited by no key, ~19 of its names in neither list;
  three further step files (282/283/284) exist beyond the derivation's own stated ceiling. **The
  candidate universe was not actually closed.**
- **`IR-F-1`: `Reject` does not "violate a specification" — it has no specification to violate.**
  Step 256.11 says "**Possible** semantics," names a non-ratified status, and the reference model's
  own `Reject` implementation carries no source-status guard at all. **The derivation's own stated
  ground for verdict D (inconsistency) is falsified.**
- **`IR-F-2`: the `Replay` operation is a fake** — it sets a flag and the goal test reads that same
  flag, the *identical defect* the derivation itself used to correctly discredit an earlier
  withdrawn witness elsewhere in the same corpus (the MD-054 thread's own `Σ ⊥ Γ` tautology finding).
  **Self-inflicted, and explicitly named as the strongest single reason not to accept the package
  as-is.**
- **`IR-F-4`: the "I-12 bypass" the derivation reported as a discovery about the ratified model is
  actually a hardcoded, undeclared modelling choice** in the reference script (`rm.py:371`), not a
  property of the ratified architecture.
- **`IR-F-5`: Constitution Article 8.3 ("Resolution SHALL be forward-only") was never consulted** —
  zero hits across every deliverable and script — in a derivation whose central open question is
  exactly what a governed resolution's return state should be. Flagged **"materially decisive and
  unexamined."**
- **Net result**: the terminal verdict **D is independently re-derived by the falsification pass on
  entirely different grounds** (underdetermination — an unclosed universe plus an untyped `Reject` —
  rather than inconsistency) — **convergence on the verdict, divergence on the reasoning**, explicitly
  stated as such rather than papered over.

## The ten-dimension verdict (`06-FALSIFICATION-VERDICT.md`)

Completeness FAIL · Minimality PARTIAL · Uniqueness FAIL (and the count itself OPEN) · Canonical
selectability BLOCKED · Σ/Q_t independence PARTIAL · Invariant compatibility PARTIAL (one OPEN) ·
Authority/evidence compatibility PARTIAL · Replayability FAIL · Rejection semantics NOT TESTABLE ·
Governance status NOT ESTABLISHED. **Formal acceptance-gate conclusion: "THE OPERATION REGISTRY IS
NOT VERIFIED,"** and explicitly, **"No second falsification pass can change this determination"** for
the completeness dimension specifically, because completeness is a property of the candidate universe
itself, not of how thoroughly it was tested.

## The terminal document: `step-285/06-STEP-285-VERDICT.md`

The true end of this chronological thread (the last file by mtime, 16:05, explicitly self-labeled
*"Subsumes Step-284 deliverable 06"*). Assesses **five separate completeness dimensions** —
Derivation, Definition, Architecture, Governance, Implementation-readiness — each graded
independently (mirroring the VERIFY SESSION thread's own eight-separate-senses discipline, MD-054
§02). Central findings:

- **Derivation completeness: substantial and real**, but not uniform — qualification has no
  derivation at all, and results exist that were computed over hand-authored tables masquerading as
  closure computations.
- **Definition completeness: weakest of the five** — ≥26 mutually distinct knowledge-state forms,
  ≥12 Σ forms, three rival Evidence definitions, `Q_t` binding three incompatible objects.
- **Architectural completeness**: the governed surface is *"small, coherent, and consists almost
  entirely of constraints"* — present: eight primitives (named, not typed), the invariants, the
  ladder, the DC interlock; **absent: every construction those constraints presuppose** — operations,
  transformations, identity, equality, lineage, replay, Σ, `Q_t`, the Evidence object.
- **Governance completeness**: *"Exactly one construct carries a real governance act: Policy."*
  Everything else has no act, against a background of ~200 unlinked "HPA Ruling"/"RATIFIED"/
  "ACCEPTED" citations across the research artifacts with no ledger counterpart. **The Constitution's
  own status is found contradictory across three records** — placing the ground under several
  constitutional articles themselves in question (`B-01`).
- **Implementation readiness, stated as a single sentence**: a state over the eight ratified
  primitives, carrying the invariants as constraints — *"Nothing that changes that state. No legal
  operation and no legal transformation is canonically defined... `commit` executes as the identity
  function."* (This precisely echoes the VERIFY SESSION thread's own independent finding, MD-054 §02,
  that its `δ`'s commit case collapses to the identity function — two separate research programmes,
  different formalisms, identical structural finding.)
- **Formal `§17 HARD STOP`**: five of the mandate's six stop conditions are met (unresolved
  Constitution status; a discovered primary-text contradiction; a construct with materially different
  meanings across lanes; operation membership requiring a governance decision; a transformation
  contract that would require inventing semantics). **"Therefore this step stops here and repairs
  none of them."** No book edit, no architecture edit, no ratification, no candidate selection.
  **`V.5 OPERATIONS — NOT ESTABLISHED · V.6 TRANSFORMATIONS — NOT ESTABLISHED.`**

## A precise, named dependency chain to implementability (the thread's own §13)

`B-01 (Constitution status) → P-11a/b/c (operation universe) → P-1 (Reject's typing) → identity +
equality → closed invariant register + typed rejection semantics → operation contracts →
transformation contracts → implementation specification → implementation → empirical certification.`
**"No stage may be merged."** This is, to date, the single most precise, ordered, and evidence-
grounded roadmap this reconstruction has encountered for what would actually need to happen before
any KnowledgeOS kernel-adjacent construct could be implemented — offered here as a citation, not
adopted as a plan.
