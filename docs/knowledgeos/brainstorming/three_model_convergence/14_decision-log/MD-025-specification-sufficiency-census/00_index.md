# MD-025 — Specification Sufficiency and Missing-Structure Census

**Status: COMPLETE.** Executed under separate, explicit authorization (2026-09-08), with two
corrections to MD-024's own wording applied throughout (MD-024's frozen text not edited, corrections
recorded here only): "not defined by the source" / "not testable" replaces "fails by default" for
unspecified operators; the `ConflictRecord` finding is corrected to "an adequacy constraint has been
identified, cross-model functional correspondence not established." **Not Stage 07. Not Phase 5O.**

## Central finding, stated up front (full evidence in `03`)

**A rigorous, fully-specified operator-contract apparatus for C0's own 13 operators plus `Qualify`
exists — with a formal atom vocabulary, explicit Input/Output conventions, state effects, and a
per-operator "corpus support" rating — but it does not live inside `docs/knowledgeos/brainstorming/`,
the corpus root this entire reconstruction has operated under since Phase 0 (MD-010/MD-011). It lives
in `docs/knowledgeos/research/kernel-reduction/`, a sibling directory this reconstruction has never
read, classified, or brought into scope (0 rows reference it anywhere in `classification-register.tsv`).
Critically, this is not merely "somewhere in the wider filesystem" — M0030 itself (an admissible,
`b`-tagged file at the center of Model B's own evidence base) directly and explicitly specifies this
exact directory as its own required output location** (`docs/knowledgeos/research/kernel-reduction/`,
cited by path at two points in M0030's own raw text). **The missing specification MD-024 found absent
is not absent from the broader corpus — it is outside the admissible search frame this reconstruction
has used throughout, and an admissible source directly points to where it lives.**

A parallel, equally rich finding applies to `S^epi`'s own `Context` argument: a formal type-dependency
table in `docs/knowledgeos/research/theory-v1.1-simulation/C-type-system.md` (also outside the corpus
root, also never classified) defines `EpistemicStandard`/`S^epi`'s own domain explicitly, including a
separately-typed `Context` (`C`).

**No such external elaboration was found for C1's `ConflictRecord` or C2's `Θ`** — both remain
genuinely unspecified beyond their own names, wherever checked.

## Frozen inputs

`00_control/protocol.md`; complete MD-023, MD-024; Stage 06; `02_model-a_gita/`,
`03_model-b_mathematical/`, `04_model-c_kernel-ddd/`, `05_cross-model/`, Phase 6. Phase 5A–5N, the
handover, and MD-022 are an external frozen boundary, not touched. The P-series (`docs/knowledgeos/
theory-extraction/`) is explicitly not consulted, per the user's own standing instruction that it is a
separate, independently-run verification track.

## What "admissible" means in this document, stated precisely

**Admissible B evidence** = the 151 independent `b`-tagged records inside `mathematical_ideas_that_
can_be_implemented/`, exactly as Phase 2's own authorization defined it — unchanged by this study.
**Corpus-wide** = anything under `docs/knowledgeos/` generally. This study reports findings from
*outside* the admissible frame precisely and explicitly, and never treats them as if they were inside
it. No admissible-evidence population was expanded by this study. Bringing `docs/knowledgeos/research/`
into scope would itself be a corpus-boundary decision requiring its own separate authorization,
analogous to MD-010/MD-011 — this study recommends that decision be considered, but does not make it.

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01_target-structure-register.md` | The 5 target structures and their required properties. |
| `02_evidence-population-and-search-frame.md` | The admissible B population (162 files) and the layered search methodology. |
| `03_model-b_operator-contract-census.md` | The central finding — `docs/knowledgeos/research/kernel-reduction/04-operator-contracts.md`. |
| `04-sepi-contract-census.md` | `S^epi`'s Context finding, from `theory-v1.1-simulation/`. |
| `05_c1-conflictrecord-census.md` | Negative census result. |
| `06_c2-transition-census.md` | Negative census result. |
| `07_model-a-context-census.md` | Whether A's own evidence supplies `S^epi`'s missing `C`. |
| `08_cross-source-conflict-register.md` | None found. |
| `09_provenance-ledger.md` | Full source-to-claim traceability. |
| `10_specification-sufficiency-matrix.md` | The required master matrix. |
| `11_adversarial-falsification.md` | Falsifiers for every finding. |
| `12_resolution-status.md` | Answers to the 13 required decision-tree questions. |
| `13_verification-and-completion-report.md` | Verification suite; final report. |

## What this study does NOT do

Does not import `docs/knowledgeos/research/` content as Model B's own admissible evidence. Does not
retroactively "complete" MD-024's composition tests using this newly-found material. Does not decide
whether to expand the corpus root. Does not enter Stage 07. Does not select a model, canonicalize
anything, or reopen the K-1/K-2 governance track. Does not consult the P-series.
