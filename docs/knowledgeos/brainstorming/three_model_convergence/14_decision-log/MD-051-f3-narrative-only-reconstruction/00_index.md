# MD-051 — F3 Narrative-Only Obs/Beh_𝔠 Reconstruction (Admissibility-Corrected Repeat of MD-050)

## Why this phase exists

This phase exists because **MD-050 (committed `6634eab65`) has an admissibility defect**, discovered
during this phase's own preparation. MD-050's `Beh_𝔠(K):=Reach(Ops(K))` construction and its three
computed proofs were built by reading F3's raw **executable** source
(`nrna1/research/kernel-reduction/kr/{atoms,carriers,reach,operators}.py`) directly. A prior,
already-frozen phase — **MD-030** (2026-09-08) — had explicitly classified that entire directory
**"D — ADMISSIBILITY/PROVENANCE BLOCK … No file admitted"** (zero git history, self-declared `[EXP]`/
"nothing here is canonical," and an internal inconsistency between its own `operators.py` and
`variants.py`). No later decision ever lifted that block. Only four **sibling narrative** documents
under the differently-named path `docs/knowledgeos/research/kernel-reduction/` were ever formally
admitted — `03-capability-model.md` and `04-operator-contracts.md` via **MD-028-DQ-1**,
`06-composition-rules.md` via **MD-032**, `12-randomized-results.md` via **MD-035** — each time for a
narrow, stated purpose, never for the executable.

**MD-050's own frozen text is not modified by this phase.** Per the standing MD-021 discipline,
discovered defects are recorded in a new phase's own artifacts, never silently repaired in the
original. The user, presented with three disposition options for MD-050 (retroactively admit /
treat as unauthorized / defer), chose **Defer — decide after seeing the narrative-only
reconstruction**, with one added hard constraint: *this phase's own construction must not use
MD-050's executable-derived results as a premise, comparison target, hint, or reconstruction aid* —
i.e. MD-051 is a **blind** reconstruction from the admitted narrative evidence alone, compared against
MD-050 only as a final, clearly-labeled, post-hoc step.

## What this phase does

Independently reconstructs `Obs`/`Beh_𝔠` for F3 (kernel-reduction C0/C0_plus), using **only** the four
already-admitted narrative files, per the user's own detailed prompt (paraphrased, not restated
verbatim — original message is the authorization record):

> Do NOT assume F3 is sufficiently specified. Executability is not evidence of semantic behaviour.
> Reconstruct `Obs`/`Beh_𝔠` from F3's existing WRITTEN SPECIFICATION and already-admitted evidence
> only — never from executing or reading the executable source. Classify every component
> SOURCE-DEFINED / LOGICALLY DERIVABLE / RECONSTRUCTED / NOT SPECIFIED / HYPOTHETICAL. Apply
> anti-circularity discipline. Give a DDD classification of what F3 "is." End with a required final
> result: A) fully instantiable, B) partially instantiable, C) not instantiable without new modelling.

## Artifact map

- `00_index.md` — this file.
- `01_admissibility-disclosure.md` — the disclosure required before proceeding (this phase's
  required "disagreement," per standing instruction): precisely what MD-050 did, precisely what
  MD-030 ruled, and how far the imprecision spread across MD-042/044/045/049's own prose.
- `02_narrative-only-construction.md` — the actual specification-only `Obs`/`Beh_𝔠` reconstruction,
  built cold from the four admitted files, with full evidence-level tagging and anti-circularity
  checks.
- `03_comparison-and-classification.md` — the required post-hoc comparison against MD-050's
  already-published numbers (performed only after §02 was complete), the DDD classification of what
  F3 "is," and the required final A/B/C result.
- `04_verification-and-completion.md` — verification suite, backlog check, completion statement.

## Scope boundary

Within-F3 only. Does not compare against F1/F5 (still unrepresented). Does not decide MD-050's own
disposition — that decision is explicitly deferred to the user, informed by, not made by, this
phase's own findings. No classification changed. No frozen artifact modified. No executable file
read or executed in this phase.
