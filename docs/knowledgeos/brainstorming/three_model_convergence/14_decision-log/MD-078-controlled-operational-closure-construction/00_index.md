# MD-078 — Concept-Family Reconstruction Through Time: `Det_r`/`EvalReq`/`Sat`/`Γ` and Co-Evolving Objects

## Purpose and redirect

User's mission (superseding, mid-turn, an initially-authorized bounded construction phase before any
construction work was performed — no construction artifact exists from that earlier authorization):
**"Never conclude that a concept is undefined or semantically incomplete merely because one document
contains an incomplete definition."** Reconstruct the evolution through time of `Det_r`, `EvalReq`,
`Sat`, `Sat_c`, `Γ`, `EC_t`, `Req`, `r`, `standard`, `Acceptance`, `Eval`, `Eval_c`, `Δ_t`, `Zero`,
`Determination`, `Decision` — across the full corpus, not just the mathematical lane — before deciding
whether MD-076/077's `Det_r`/`EvalReq` gap is genuine or already resolved elsewhere. Explicit
prohibition: do not invent any new `Det_r`/`EvalReq`/`Γ`/`Sat`, do not canonicalize, do not proceed to
F3↔F4 bridging, until this reconstruction is complete.

## Method

Governing rule honored: **reconstruct first → reconcile later → canonicalize last.** No construction
performed. MD-067–077 reused as frozen baseline, never reopened or rewritten.

Evidence gathered via: (1) direct primary-source verification of the T21 "Theory-00-21" 21-part rewrite
(`mathematical_ideas_that_can_be_implemented/20260906-*theory-part-*`, all 21 parts plus the two worked-
example files, every part read in full — 5 by the main process directly, 16 by three parallel extraction
agents, 1 (Part 6) already fully read in the prior turn); (2) a targeted cross-lane grep-and-read sweep
across `brainstorming/kernel/`, `brainstorming/verification/`, `brainstorming/synthesis/`,
`reviews/kernel/`, `reviews/` (excl. `reviews/synthesis/`, never opened), top-level `verification/`, and
top-level `research/`; (3) direct verification reads of the two most consequential discovered documents
(`phase_measure_theory/20260827-162545_step-023-...md`, the genuine birth site of `Sat`/`EpistemicContract`
as named concepts, and `research/knowledgeos-sim/kos/inquiry.py`, a genuinely executable `Sat`/`Gap`/
`Zero`/`Adequate` implementation). `docs/knowledgeos/theory-extraction/` never accessed at any point.

## Central discoveries, disclosed first

1. **A concurrent session has independently produced a near-identical birth census for this exact
   object family** (`brainstorming/verification/gap-discovery/concept-family-birth-census/
   01-BIRTH-CENSUS-DET-R-FAMILY.md`, dated 2026-09-10 — today), operating under a self-imposed firewall
   *against* `three_model_convergence/` (this reconstruction's own home directory) — the mirror image of
   this reconstruction's own standing firewall against `theory-extraction/`. Its own stated blocker
   ("a ruling on whether this reconstruction may read `three_model_convergence/14_decision-log/` for
   this concept family — everything else in the mission is downstream of it") is exactly the question
   this phase is positioned to answer, since this phase's own mission explicitly authorizes reading
   `verification/`. See `03_cross-lane-transfer-and-negative-history-register.md`.
2. **`Det_r` and `EvalReq` are independently confirmed, by two separate methodologies from two separate
   sessions, to have exactly one genuine occurrence in the entire corpus** (Part VI §6.18,
   2026-09-06 00:39:47) — the concurrent census's own "97%/90% firewalled" figures, once corrected for
   a confirmed substring false-positive (`EvalRequirement` mistaken for `EvalReq` in a step-025e
   citation), resolve to: **the "occurrences elsewhere" are overwhelmingly this reconstruction's own
   MD-067–077 analysis text, not primary corpus content.** MD-076's Terminal Classification C stands,
   now with independent cross-session corroboration rather than single-lane evidence.
3. **`Sat(K,r_i)` was never given a computation rule even at its own genuine birth** — traced directly
   to `phase_measure_theory/step-023` (2026-08-27 16:25:45, five days before T5), where §10
   "Requirement satisfaction" defines `Sat(K,r_i)` only as "the degree/status to which knowledge K
   satisfies requirement `r_i`," then immediately uses `Sat(K,r_i)=Satisfied` as a stipulated,
   externally-supplied boolean. **The fiat-stipulation pattern MD-070 found in T22's worked example is
   not a later regression from T21's more sophisticated apparatus — it is the theory's original,
   unbroken, 10-day-persistent pattern**, and `Det_r`/`EvalReq` (T21, Part VI) is the single, isolated,
   same-session-abandoned attempt to replace it with something computed.
4. **Foundational symbols proliferate into mutually incompatible definitions across the same 21-part
   rewrite, at severity far beyond anything MD-067–077 characterized.** `r` denotes at least six
   structurally distinct objects (requirement, relation-type, two different relation-instance tuples,
   two different inference-rule tuples) even within Parts IV/VII/IX/X alone. `Γ` receives at least four
   mutually inconsistent formal structures across Parts II/VIII/X/21. `Zero(·,·)` takes a different
   second-argument type and arity in nearly every Part (`EC`, `CC`, `AC`, `PC`, `RC`, `RCog`, 0–3 args).
   `Det(·)` and `Decision`/`Dec`/`D` each show comparable drift. See
   `01_theory-object-registry-and-definition-evolution-registry.md`.
5. **One genuinely strong positive finding**: `research/knowledgeos-sim/kos12/satc_spec.py` +
   `evalc.py` supply a complete, adversarially-tested, three-valued (`⊤/⊥/U`) executable implementation
   of `Eval_c(K_t,r,Γ_t)→EVal_c` and `Sat_c:=value∘Eval_c`, with real test-run results
   (`satc_phaseA_spec.json`/`phaseB_adversarial.json`/`phaseC_zero.json`), self-labeled "not canonical."
   Separately, `research/knowledgeos-sim/kos/inquiry.py` supplies a complete, genuinely computable
   `Sat(K,r,E)` (kind-dispatched over five requirement kinds against a dict-based `K`), with `Gap`/
   `Zero`/`Adequate` all correctly derived and citing `[DEF-20]`/`[DEF-21]`/`[DEF-22]` — the exact same
   citation IDs MD-067 tracked for T5. **Neither is the same object as T21's `Det_r`/`EvalReq`/
   `Sat(K,r,Γ)`** (different signatures, different representations, explicitly disclosed as candidate/
   research code) — classified `RELATED OBJECT, CONSTRUCTED CANDIDATE`, not `SAME OBJECT`.

## Terminal classification, per object (not forced to a single family-wide verdict)

See `04_co-evolution-map-and-terminal-classification.md` for the full per-object table with evidence.
Summary: `Det_r`/`EvalReq`'s own body — **D (genuine corpus gap)**, now doubly corroborated. `r`/`Γ` —
**E (object identity unresolved)**, the most severe finding of this phase. `EC`/`EC_t` — **C (competing
complete definitions)**, at least six-to-eight mutually distinct formulations across the corpus's full
history, none reconciled. `Req`'s own shape, `Determination⇏Decision`'s separation principle, and
`Δ/Zero`'s aggregation logic given `Sat` values — **A (complete)**, multiply and independently proven.
`Sat_c`/`Eval_c` and the `kos/inquiry.py` `Sat` — **B (complete through multiple sources)**, for a
*related*, not identical, construction.

## Artifact map

- `00_index.md` — this file.
- `01_theory-object-registry-and-definition-evolution-registry.md`
- `02_theorystate-timeline-and-transformation-ledger.md`
- `03_cross-lane-transfer-and-negative-history-register.md`
- `04_co-evolution-map-and-terminal-classification.md`
- `05_smallest-remaining-gap-backlog-and-closure.md`

## What this phase does NOT do

Does not invent any new `Det_r`/`EvalReq`/`Γ`/`Sat` body. Does not canonicalize any competing
definition. Does not perform F3↔F4 bridging. Does not modify any frozen artifact (MD-024–077). Does not
modify the concurrent session's own `01-BIRTH-CENSUS-DET-R-FAMILY.md` (corrections recorded forward,
in this phase's own artifacts, per this reconstruction's own standing discipline for another party's
work). Does not access `theory-extraction/`.
