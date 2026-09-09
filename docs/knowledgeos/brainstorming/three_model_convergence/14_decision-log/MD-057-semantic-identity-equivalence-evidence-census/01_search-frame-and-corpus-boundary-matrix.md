# MD-057 §01 — Search Frame and Corpus-Boundary Matrix

## Pass 1 — broad keyword census (equivalence-family English terms + LaTeX-ish tokens)

`KW='equivalen|≡sem|≡_sem|bisimulat|observational.equivalen|behavio.ral.equivalen|trace.equivalen|
refinement|substitutab|satisf|semantic.satisf|capability.preserv|invariant.preserv|
functional.equivalen|structural.equivalen|canonicali[sz]|irreducib|semantic.order|capability.order|
kernel.identity|kernel.membership|kernel.realiz|equivalence.class|MinKer|K_adm|K_sat|Obs\(|Beh\(|
Trace\(|simulation'`, `*.md` files, hit = at least one match anywhere in the file.

| Directory | total `.md` | hits (broad) | disposition |
|---|---:|---:|---|
| `docs/knowledgeos/brainstorming/kernel/` | 172 | 108 | SEARCHED — high hit rate, mostly common-word noise (see Pass 2) |
| `docs/knowledgeos/brainstorming/verification/` | 349 | 206 | SEARCHED — 71 top-level admitted (MD-054) + 9 `spec/` admitted (MD-052); remainder (~269 files, mostly `gap-discovery/`, `findings/`, `reports/`, `handoff/`, `canonical-construction/`) NOT individually read — see §3 |
| `docs/knowledgeos/brainstorming/synthesis/` | 4 | 1 | SEARCHED-NO-RELEVANT-EVIDENCE (1 hit is a generic word match) |
| `docs/knowledgeos/reviews/kernel/` | 106 | 26 | SEARCHED — 3 files already admitted (MD-042/043/044); remainder NOT read this phase |
| `docs/knowledgeos/reviews/synthesis/` | 285 | 81 | SEARCHED — 20 files already admitted (MD-056); 1 new file read this phase (`02_TEN_BLOCKER_STATUS_MATRIX.md`, see below); remainder (~264 files) NOT read |
| `verification/` (nrna1 top-level) | 50 | 23 | SEARCHED-NO NEW READ — already fully characterized under prior MDs (Zero-algebra track); not reopened |
| `research/` (nrna1 top-level) | 4 | 3 | SEARCHED-NO NEW READ — same as above |
| `docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_implemented/` | 143 | 129 (broad); 47 (high-precision, Pass 2) | SEARCHED — extensive prior coverage under MD-044–050 (the `KR-KERNEL-MINIMALITY-2026-09` chain, 10 files) and MD-051 (F3 narrative reconstruction); the Pass-2 47-file high-precision hit list mostly re-identifies files already characterized under those MDs (the 2026-09-02 closure-package series, the 2026-09-04 semantic-equivalence-freeze series) — NOT re-read individually this phase; no new file opened here |

## Pass 2 — high-precision MinKer-specific tokens

`HP='MinKer|K_adm|K_sat|Obs_c|Beh_c|Tr_K\(|⪯_cap|≡_sem|≡sem'` (deliberately narrow — the tokens this
reconstruction's own MinKer chain (MD-043–050) actually uses, to separate genuine discovery signal
from broad-English-word noise).

| Directory | hits (high-precision) | new (not already covered by a prior MD) |
|---|---:|---|
| `brainstorming/kernel/` | 0 | — |
| `brainstorming/verification/` | 7, all in `gap-discovery/gap-update-2026-09-02/` | **YES — genuinely new, none of the 12 files in this subdirectory were read by MD-052 or MD-054** (MD-054 read `gap-discovery/17-INDEPENDENT-GAP-DISCOVERY-VERDICT.md`, `01-THEORY-EVOLUTION-MAP.md`, `12-IDENTITY-EQUALITY-GAP.md`, `oq1-decision-brief/README.md` — a disjoint file set from this 12-file, later-dated subdirectory) |
| `brainstorming/synthesis/` | 0 | — |
| `reviews/kernel/` | 0 | — |
| `reviews/synthesis/` | 1 — `analysis/sync-intake/02_TEN_BLOCKER_STATUS_MATRIX.md` | YES — not among MD-056's 20 admitted files |
| `verification/` (top) | 0 | — |
| `research/` (top) | 0 | — |
| `mathematical_ideas_that_can_be_implemented/` | 47 | mostly already-covered filenames (see Pass 1 row) |

**A third search, targeted directly at the file-family the `gap-update-2026-09-02` package itself
cites (`261.*`, the step-25x/26x apparatus)**, found `docs/knowledgeos/brainstorming/
phase_measure_theory/knowledgeos_kernel/research/` — a 40+ file sub-programme (`step-285` through
`step-291`, a `D285-*` decision series, `R1`–`R5` registers, `E1-E7-EQUALITY-INVESTIGATION.md`) that
neither Pass 1 nor Pass 2's own directory list covered, because it sits inside
`brainstorming/phase_measure_theory/` — a directory MD-054 already characterized (its top-level
`step-` files, "Steps 1–236," verdict "a completed theory? NO") but whose `knowledgeos_kernel/
research/` **subdirectory** (steps 285–291, later-dated, a distinct D-series) was not itself opened
by MD-054 or any prior MD. **This is the single most consequential new discovery this phase makes**
— see `02_candidate-evidence-and-classification.md`.

## Directories NOT searched this phase, named explicitly

- `docs/knowledgeos/theory-extraction/` — **FIREWALLED**, absolute standing prohibition, honored.
- Lane-T's own `K3`/`Ω`/`T-K1`/`T-K2` material — **EXCLUDED BY GOVERNANCE DECISION** (the standing
  firewall), honored.
- MD-050's own executable-admissibility question — **not reopened** (deferred, the user's own
  separate open decision).
- The remaining ~269 files of `brainstorming/verification/` beyond the 80 already admitted plus the
  12 read this phase — **NOT SEARCHED beyond keyword screening**, disclosed, not silently dropped.
- The remaining ~264 files of `reviews/synthesis/` beyond the 20 admitted (MD-056) plus the 1 read
  this phase — **NOT SEARCHED beyond keyword screening**.
- `step-291/11_VNEXT-CLOSURE-AUDIT.md` (253 lines, self-marked "NOT FROZEN") — **located, not read
  in full this phase** — SEARCHED-FOUND, disclosed as an open item, not a silent omission.
- The remaining ~35 files of `phase_measure_theory/knowledgeos_kernel/research/` beyond the two
  REFINED-STEP files read in full (see §02) — **NOT SEARCHED beyond directory listing and targeted
  grep for `SameId`/`≡_H`/`≡_P`**, disclosed.

## Statistical caveat, stated per the census's own discipline

A keyword hit is a discovery point, not evidence of a result. The Pass-1 broad regex's own high hit
rates (e.g. 206/349 in `brainstorming/verification/`) are consistent with common-word false positives
("satisf-", "equivalen-" appear liberally in ordinary prose) and were not treated as a finished
census — Pass 2's narrower token set, followed by a targeted citation-chase from the one genuinely
new finding, is what actually located this phase's evidence.
